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
    $per_page = min($per_page, 50); // giới hạn

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'fields'         => 'ids', // tối ưu
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

    // Category ID hoặc slug
    $cat = $request->get_param('category');
    if (!is_null($cat)) {
        $args[is_numeric($cat) ? 'cat' : 'category_name'] = sanitize_text_field($cat);
    }

    // Tag ID hoặc slug
    $tag = $request->get_param('tag');
    if (!is_null($tag)) {
        $args[is_numeric($tag) ? 'tag_id' : 'tag'] = sanitize_text_field($tag);
    }

    // Search
    $search = $request->get_param('search');
    if (!is_null($search)) {
        $args['s'] = sanitize_text_field($search);
    }

    // Date query
    $date_query = [];

    $after = $request->get_param('date_after');
    if (!is_null($after)) {
        $date_query['after'] = sanitize_text_field($after);
    }

    $before = $request->get_param('date_before');
    if (!is_null($before)) {
        $date_query['before'] = sanitize_text_field($before);
    }

    if (!empty($date_query)) {
        $args['date_query'] = [$date_query];
    }

    // ================== Caching ==================
    ksort($args); // đảm bảo serialize ổn định
    $cache_key = 'kdi_posts_' . md5(serialize($args));
    $cached = wp_cache_get($cache_key, 'kdi_posts');

    if ($cached !== false) {
        return rest_ensure_response($cached);
    }

    // ================== Query & build data ==================
    $query = new WP_Query($args);
    $post_ids = $query->posts;

    if (empty($post_ids)) {
        return rest_ensure_response([
            'found'        => 0,
            'total_pages'  => 0,
            'current_page' => $paged,
            'posts'        => [],
        ]);
    }

    $posts = array_map(function ($id) {
        return [
            'id'             => $id,
            'title'          => get_the_title($id),
            'slug'           => get_post_field('post_name', $id),
            'excerpt'        => get_the_excerpt($id),
            'content'        => get_post_field('post_content', $id),
            'date'           => get_the_date('', $id),
            'image'          => get_the_post_thumbnail_url($id, 'medium'),
            'featured_media' => get_post_thumbnail_id($id),
            'category'       => wp_get_post_categories($id, ['fields' => 'names']),
            'tags'           => wp_get_post_tags($id, ['fields' => 'names']),
        ];
    }, $post_ids);

    // Gói kết quả kèm thông tin phân trang
    $response = [
        'status' => 'success',
        'found'        => $query->found_posts,
        'total_pages'  => $query->max_num_pages,
        'current_page' => $paged,
        'data'        => $posts,
    ];

    // ================== Save cache ==================
    wp_cache_set($cache_key, $response, 'kdi_posts', 60); // cache 60s

    return rest_ensure_response($response);
}

function kdi_clear_post_list_cache()
{
    wp_cache_flush();
}
add_action('save_post_post', 'kdi_clear_post_list_cache');
add_action('deleted_post', 'kdi_clear_post_list_cache');
add_action('trashed_post', 'kdi_clear_post_list_cache');
