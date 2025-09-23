<?php

add_action('rest_api_init', function () {
    // GET /wp-json/kdi/v1/post/(?P<id>\d+)
    register_rest_route(API_URL, '/post/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_post_by_id',
        'permission_callback' => '__return_true',
    ]);

    // GET /wp-json/kdi/v1/posts
    register_rest_route(API_URL, '/posts', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_posts',
        'permission_callback' => '__return_true',
    ]);

    // GET /wp-json/kdi/v2/posts
    // register_rest_route(API_URL_V2, '/posts', [
    //     'methods'  => 'GET',
    //     'callback' => 'kdi_get_posts_v2',
    //     'permission_callback' => '__return_true',
    // ]);
});

// ID
function kdi_get_post_by_id(WP_REST_Request $request)
{
    $id = (int) $request['id'];
    $cache_key = "kdi_post_detail_$id";

    // Lấy từ cache nếu có
    $cached = wp_cache_get($cache_key, 'kdi_posts');
    if ($cached !== false) {
        return rest_ensure_response($cached);
    }

    $post = get_post($id);

    if (
        !$post ||
        $post->post_type !== 'post' ||
        $post->post_status !== 'publish'
    ) {
        return new WP_Error('not_found', 'Post not found', ['status' => 404]);
    }

    // Meta chọn lọc
    $meta_whitelist = ['views', 'reading_time'];
    $meta = [];
    foreach ($meta_whitelist as $key) {
        $meta[$key] = get_post_meta($post->ID, $key, true);
    }

    // Fallback ảnh đại diện nếu không có
    $image = get_the_post_thumbnail_url($post->ID, 'medium') ?: 'https://example.com/default-image.jpg';

    $data = [
        'id'             => $post->ID,
        'title'          => $post->post_title,
        'slug'           => $post->post_name,
        'excerpt'        => get_the_excerpt($post),
        'content'        => apply_filters('the_content', $post->post_content),
        'date'           => get_the_date('', $post),
        'modified'       => get_the_modified_date('', $post),
        'link'           => get_permalink($post),
        'image'          => $image,
        'featured_media' => get_post_thumbnail_id($post->ID),
        'category'       => wp_get_post_categories($post->ID, ['fields' => 'names']),
        'tags'           => wp_get_post_tags($post->ID, ['fields' => 'names']),
        'author'         => [
            'id'     => $post->post_author,
            'name'   => get_the_author_meta('display_name', $post->post_author),
            'avatar' => get_avatar_url($post->post_author),
        ],
        'comment_count'  => (int) $post->comment_count,
        'meta'           => $meta,
    ];

    $response = [
        'status' => 'success',
        'data'   => $data,
    ];

    // Cache trong 10 phút (600 giây)
    wp_cache_set($cache_key, $response, 'kdi_posts', 600);

    return rest_ensure_response($response);
}

// clear cache when post is updated
add_action('save_post', function ($post_id) {
    if (get_post_type($post_id) === 'post') {
        wp_cache_delete("kdi_post_$post_id", 'kdi_posts');
    }
});


// LIST
function kdi_get_posts(WP_REST_Request $request)
{
    $per_page = (int) $request->get_param('per_page') ?: 10;
    $paged    = (int) $request->get_param('page') ?: 1;
    $per_page = min($per_page, 50);

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'fields'         => 'ids',
    ];

    // ================== Param động ==================
    $param_map = [
        'post_status'     => 'sanitize_text_field',
        'post__in'        => fn($v) => array_map('intval', (array) $v),
        'post__not_in'    => fn($v) => array_map('intval', (array) $v),
        'orderby'         => 'sanitize_text_field',
        'order'           => fn($v) => strtoupper($v) === 'ASC' ? 'ASC' : 'DESC',
        'author'          => 'intval',
        'meta_key'        => 'sanitize_text_field',
        'meta_value'      => 'sanitize_text_field',
    ];

    foreach ($param_map as $key => $fn) {
        $val = $request->get_param($key);
        if (!is_null($val)) {
            $args[$key] = $fn($val);
        }
    }

    // Category
    $cat = $request->get_param('category');
    if (!is_null($cat)) {
        $args[is_numeric($cat) ? 'cat' : 'category_name'] = sanitize_text_field($cat);
    }

    // Tag
    $tag = $request->get_param('tag');
    if (!is_null($tag)) {
        $args[is_numeric($tag) ? 'tag_id' : 'tag'] = sanitize_text_field($tag);
    }

    // Search
    $search = $request->get_param('search');
    if (!is_null($search)) {
        $args['s'] = esc_sql($search); // giữ ký tự tìm kiếm hợp lệ
    }

    // Date query
    $date_query = [];
    if ($after = $request->get_param('date_after')) {
        $date_query['after'] = sanitize_text_field($after);
    }
    if ($before = $request->get_param('date_before')) {
        $date_query['before'] = sanitize_text_field($before);
    }
    if (!empty($date_query)) {
        $args['date_query'] = [$date_query];
    }

    // Sticky posts
    $sticky = $request->get_param('sticky');
    if (!is_null($sticky)) {
        $sticky_ids = get_option('sticky_posts');
        if ($sticky) {
            $args['post__in'] = !empty($sticky_ids) ? $sticky_ids : [0];
            $args['ignore_sticky_posts'] = 1;
            // } elseif ($sticky === 'exclude') {
            //     $args['post__not_in'] = !empty($sticky_ids) ? $sticky_ids : [0];
        } else {
            $args['ignore_sticky_posts'] = 0;
        }
    }

    // ================== Cache ==================
    ksort($args);
    $cache_key = 'kdi_posts_v1_' . md5(serialize($args));
    $cached = wp_cache_get($cache_key, 'kdi_posts');
    if ($cached !== false) {
        return rest_ensure_response($cached);
    }

    // ================== Query ==================
    $query = new WP_Query($args);
    $post_ids = $query->posts;

    if (empty($post_ids)) {
        return rest_ensure_response([
            'found'        => 0,
            'total_pages'  => 0,
            'current_page' => $paged,
            'has_more'     => false,
            'data'         => [],
        ]);
    }

    // Preload cache (giảm N+1 queries)
    update_post_caches($post_ids, ['post', 'category', 'tag']);

    $posts = array_map(function ($id) {
        $author_id = (int) get_post_field('post_author', $id);

        // ================== Lấy tất cả ACF field có prefix acf_ ==================
        $raw_meta = get_post_meta($id);
        $acf_fields = [];

        foreach ($raw_meta as $key => $val) {
            if (strpos($key, 'acf_') === 0) {
                // meta_value luôn là mảng -> lấy phần tử đầu
                $acf_fields[$key] = is_array($val) && count($val) === 1 ? maybe_unserialize($val[0]) : $val;
            }
        }

        return array_merge([
            'id'             => $id,
            'title'          => get_the_title($id),
            'slug'           => get_post_field('post_name', $id),
            'excerpt'        => get_the_excerpt($id),
            'content'        => get_post_field('post_content', $id),
            'date'           => get_the_date('', $id),
            'image'          => get_the_post_thumbnail_url($id, 'medium'),
            'featured_media' => get_post_thumbnail_id($id),
            'categories'     => wp_get_post_categories($id, ['fields' => 'names']),
            'tags'           => wp_get_post_tags($id, ['fields' => 'names']),
            'author'         => [
                'id'     => $author_id,
                'name'   => get_the_author_meta('display_name', $author_id),
                'avatar' => get_avatar_url($author_id),
            ],
        ], $acf_fields);
    }, $post_ids);

    $response = [
        'found'        => $query->found_posts,
        'total_pages'  => $query->max_num_pages,
        'current_page' => $paged,
        'has_more'     => $paged < $query->max_num_pages,
        'data'         => $posts,
    ];

    // Save cache (5 phút)
    wp_cache_set($cache_key, $response, 'kdi_posts', 300);

    return rest_ensure_response($response);
}

// ================== Clear cache khi post thay đổi ==================
add_action('save_post', 'kdi_clear_posts_cache');
add_action('deleted_post', 'kdi_clear_posts_cache');
function kdi_clear_posts_cache()
{
    wp_cache_flush_group('kdi_posts');
}

// Helper để clear 1 group cache
if (!function_exists('wp_cache_flush_group')) {
    function wp_cache_flush_group($group)
    {
        global $wp_object_cache;
        if (method_exists($wp_object_cache, 'delete_group')) {
            $wp_object_cache->delete_group($group);
        } else {
            // fallback: flush toàn bộ nếu không hỗ trợ xóa group
            wp_cache_flush();
        }
    }
}



// 
// function kdi_get_posts_v2(WP_REST_Request $request)
// {
//     // ================== Params string ==================
//     $query_string = $_SERVER['QUERY_STRING'] ?? '';
//     $url = rest_url('wp/v2/posts');
//     if (!empty($query_string)) {
//         $url .= '?' . $query_string;
//     }

//     // ================== Cache key ==================
//     $cache_key = 'kdi_posts_v2_' . md5($query_string);
//     $cached = wp_cache_get($cache_key, 'kdi_posts_v2');
//     if ($cached !== false) {
//         return rest_ensure_response($cached);
//     }

//     // ================== Call WP Default API ==================
//     $response = wp_remote_get($url, ['timeout' => 10]);

//     if (is_wp_error($response)) {
//         return new WP_Error(
//             'api_failed',
//             'Không thể gọi API mặc định',
//             ['status' => 500]
//         );
//     }

//     $body    = wp_remote_retrieve_body($response);
//     $data    = json_decode($body, true);
//     $headers = wp_remote_retrieve_headers($response);

//     // ================== Extract pagination ==================
//     $total       = !empty($headers['x-wp-total']) ? intval($headers['x-wp-total']) : 0;
//     $total_pages = !empty($headers['x-wp-totalpages']) ? intval($headers['x-wp-totalpages']) : 0;
//     $paged       = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

//     // ================== Custom Response ==================
//     $custom_response = [
//         'found'        => $total,
//         'total_pages'  => $total_pages,
//         'current_page' => $paged,
//         'has_more'     => $paged < $total_pages,
//         'data'         => $data,
//     ];

//     // ================== Save cache (5 phút) ==================
//     wp_cache_set($cache_key, $custom_response, 'kdi_posts_v2', 300);

//     return rest_ensure_response($custom_response);
// }

// // ================== Clear cache khi post thay đổi ==================
// add_action('save_post', 'kdi_clear_posts_cache_v2');
// add_action('deleted_post', 'kdi_clear_posts_cache_v2');
// function kdi_clear_posts_cache_v2()
// {
//     wp_cache_flush_group('kdi_posts_v2');
// }

// // Helper để clear 1 group cache
// if (!function_exists('wp_cache_flush_group')) {
//     function wp_cache_flush_group($group)
//     {
//         global $wp_object_cache;
//         if (method_exists($wp_object_cache, 'delete_group')) {
//             $wp_object_cache->delete_group($group);
//         } else {
//             // fallback: flush toàn bộ nếu object cache không hỗ trợ xóa theo group
//             wp_cache_flush();
//         }
//     }
// }
