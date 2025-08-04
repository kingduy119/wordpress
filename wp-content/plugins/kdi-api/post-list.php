<?php

add_action('rest_api_init', function () {
    // GET /wp-json/kdi/v1/posts
    register_rest_route(API_URL, '/posts', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_posts',
        'permission_callback' => '__return_true',
    ]);
});

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
    wp_cache_flush(); // hoặc cụ thể hơn:
    // wp_cache_delete('kdi_posts_...', 'kdi_posts');
}
add_action('save_post_post', 'kdi_clear_post_list_cache');
add_action('deleted_post', 'kdi_clear_post_list_cache');
add_action('trashed_post', 'kdi_clear_post_list_cache');



// function kdi_get_posts_old(WP_REST_Request $request)
// {
//     $per_page = (int) $request->get_param('per_page') ?: 10;
//     $paged    = (int) $request->get_param('page') ?: 1;

//     // Giới hạn số lượng tránh abuse
//     $per_page = min($per_page, 50);

//     $args = [
//         'post_type'      => 'post',
//         'posts_per_page' => $per_page,
//         'paged'          => $paged,
//     ];

//     // ================== Bộ lọc động ==================

//     // Lọc theo category ID hoặc slug
//     if ($cat = $request->get_param('category')) {
//         if (is_numeric($cat)) {
//             $args['cat'] = intval($cat);
//         } else {
//             $args['category_name'] = sanitize_text_field($cat);
//         }
//     }

//     // Lọc theo tag ID hoặc slug
//     if ($tag = $request->get_param('tag')) {
//         if (is_numeric($tag)) {
//             $args['tag_id'] = intval($tag);
//         } else {
//             $args['tag'] = sanitize_text_field($tag);
//         }
//     }

//     // Lọc theo search (title, content, excerpt)
//     if ($search = $request->get_param('search')) {
//         $args['s'] = sanitize_text_field($search);
//     }

//     // Tác giả
//     if ($author = $request->get_param('author')) {
//         $args['author'] = intval($author);
//     }

//     // Trạng thái bài viết
//     if ($status = $request->get_param('post_status')) {
//         $args['post_status'] = sanitize_text_field($status);
//     }

//     // Danh sách post ID cần lấy
//     if ($ids = $request->get_param('post__in')) {
//         $args['post__in'] = array_map('intval', (array) $ids);
//     }

//     // Danh sách post ID cần loại trừ
//     if ($not_in = $request->get_param('post__not_in')) {
//         $args['post__not_in'] = array_map('intval', (array) $not_in);
//     }

//     // Sắp xếp
//     if ($orderby = $request->get_param('orderby')) {
//         $args['orderby'] = sanitize_text_field($orderby);
//     }

//     if ($order = $request->get_param('order')) {
//         $args['order'] = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
//     }

//     // Meta query đơn giản
//     if ($meta_key = $request->get_param('meta_key')) {
//         $args['meta_key'] = sanitize_text_field($meta_key);
//         if ($meta_value = $request->get_param('meta_value')) {
//             $args['meta_value'] = sanitize_text_field($meta_value);
//         }
//     }

//     // Date query (từ ngày / đến ngày)
//     $date_query = [];

//     if ($after = $request->get_param('date_after')) {
//         $date_query['after'] = sanitize_text_field($after); // định dạng: 'YYYY-MM-DD'
//     }

//     if ($before = $request->get_param('date_before')) {
//         $date_query['before'] = sanitize_text_field($before);
//     }

//     if (!empty($date_query)) {
//         $args['date_query'] = [$date_query];
//     }

//     // ==================================================

//     $query = new WP_Query($args);
//     $posts = [];

//     while ($query->have_posts()) {
//         $query->the_post();
//         $posts[] = [
//             'id'             => get_the_ID(),
//             'title'          => get_the_title(),
//             'slug'           => get_post_field('post_name', get_the_ID()),
//             'excerpt'        => get_the_excerpt(),
//             'content'        => get_the_content(),
//             'date'           => get_the_date(),
//             'image'          => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
//             'featured_media' => get_post_thumbnail_id(get_the_ID()),
//             'category'       => wp_get_post_categories(get_the_ID(), ['fields' => 'names']),
//             'tags'           => wp_get_post_tags(get_the_ID(), ['fields' => 'names']),
//         ];
//     }

//     wp_reset_postdata();

//     return rest_ensure_response($posts);
// }
