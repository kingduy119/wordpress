<?php

add_action('rest_api_init', function () {
    // GET /wp-json/kdi/v1/pages
    register_rest_route(API_URL, '/pages', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_pages',
        'permission_callback' => '__return_true',
    ]);
});

function kdi_get_pages(WP_REST_Request $request)
{
    $per_page = (int) $request->get_param('per_page') ?: 10;
    $paged    = (int) $request->get_param('page') ?: 1;
    $per_page = min($per_page, 50); // giới hạn

    $args = [
        'post_type'      => 'page',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'fields'         => 'ids',
    ];

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

    // Search
    $search = $request->get_param('search');
    if (!is_null($search)) {
        $args['s'] = sanitize_text_field($search);
    }

    // ================== Cache ==================
    ksort($args);
    $cache_key = 'kdi_pages_' . md5(serialize($args));
    $cached = wp_cache_get($cache_key, 'kdi_pages');

    if ($cached !== false) {
        return rest_ensure_response($cached);
    }

    $query = new WP_Query($args);
    $post_ids = $query->posts;

    if (empty($post_ids)) {
        return rest_ensure_response([
            'found'        => 0,
            'total_pages'  => 0,
            'current_page' => $paged,
            'pages'        => [],
        ]);
    }

    $pages = array_map(function ($id) {
        return [
            'id'      => $id,
            'title'   => get_the_title($id),
            'slug'    => get_post_field('post_name', $id),
            'excerpt' => get_the_excerpt($id),
            'content' => get_post_field('post_content', $id),
            'date'    => get_the_date('', $id),
            'image'   => get_the_post_thumbnail_url($id, 'medium'),
        ];
    }, $post_ids);

    $response = [
        'status'       => 'success',
        'found'        => $query->found_posts,
        'total_pages'  => $query->max_num_pages,
        'current_page' => $paged,
        'data'         => $pages,
    ];

    wp_cache_set($cache_key, $response, 'kdi_pages', 60);

    return rest_ensure_response($response);
}


function kdi_clear_page_list_cache()
{
    wp_cache_flush();
}
add_action('save_post_page', 'kdi_clear_page_list_cache');
add_action('deleted_post', 'kdi_clear_page_list_cache');
add_action('trashed_post', 'kdi_clear_page_list_cache');
