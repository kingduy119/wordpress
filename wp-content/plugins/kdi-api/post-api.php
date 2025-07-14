<?php

add_action('rest_api_init', function () {
    // GET /wp-json/kdi/v1/posts
    register_rest_route('kdi/v1', '/posts', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_posts',
        'permission_callback' => '__return_true',
    ]);

    // GET /wp-json/kdi/v1/post/(?P<id>\d+)
    register_rest_route('kdi/v1', '/post/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_post_by_id',
        'permission_callback' => '__return_true',
    ]);

    // GET /wp-json/kdi/v1/post-categories
    register_rest_route('kdi/v1', '/post-categories', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_post_categories',
        'permission_callback' => '__return_true',
    ]);
});

function kdi_get_posts(WP_REST_Request $request) {
    $per_page = (int) $request->get_param('per_page') ?: 10;
    $paged    = (int) $request->get_param('page') ?: 1;

    // Giới hạn tối đa số lượng post trả về để tránh abuse
    $max_per_page = 50;
    if ($per_page > $max_per_page) {
        $per_page = $max_per_page;
    }

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
    ];

    $query = new WP_Query($args);
    $posts = [];

    while ($query->have_posts()) {
        $query->the_post();
        $posts[] = [
            'id'       => get_the_ID(),
            'title'    => get_the_title(),
            'slug'     => get_post_field('post_name', get_the_ID()),
            'excerpt'  => get_the_excerpt(),
            'content'  => get_the_content(),
            'date'     => get_the_date(),
            'image'    => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
            'category' => wp_get_post_categories(get_the_ID(), ['fields' => 'names']),
            'tags'     => wp_get_post_tags(get_the_ID(), ['fields' => 'names']),
        ];
    }

    wp_reset_postdata();
    return rest_ensure_response($posts);
}

function kdi_get_post_by_id(WP_REST_Request $request) {
    $id = (int) $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'post') {
        return new WP_Error('not_found', 'Post not found', ['status' => 404]);
    }

    return [
        'id'       => $post->ID,
        'title'    => $post->post_title,
        'slug'     => $post->post_name,
        'excerpt'  => get_the_excerpt($post),
        'content'  => apply_filters('the_content', $post->post_content),
        'date'     => get_the_date('', $post),
        'image'    => get_the_post_thumbnail_url($post->ID, 'medium'),
        'category' => wp_get_post_categories($post->ID, ['fields' => 'names']),
        'tags'     => wp_get_post_tags($post->ID, ['fields' => 'names']),
    ];
}

function kdi_get_post_categories() {
    $categories = get_categories(['hide_empty' => false]);
    $result = [];

    foreach ($categories as $cat) {
        $result[] = [
            'id'    => $cat->term_id,
            'name'  => $cat->name,
            'slug'  => $cat->slug,
            'count' => $cat->count,
        ];
    }

    return rest_ensure_response($result);
}
