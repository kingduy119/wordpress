<?php

add_action('rest_api_init', function () {
    // GET /wp-json/kdi/v1/post/(?P<id>\d+)
    register_rest_route(API_URL, '/post/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_post_by_id',
        'permission_callback' => '__return_true',
    ]);
});

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



// function kdi_get_post_by_id_old(WP_REST_Request $request)
// {
//     $id = (int) $request['id'];
//     $post = get_post($id);

//     if (!$post || $post->post_type !== 'post') {
//         return new WP_Error('not_found', 'Post not found', ['status' => 404]);
//     }

//     return [
//         'id'       => $post->ID,
//         'title'    => $post->post_title,
//         'slug'     => $post->post_name,
//         'excerpt'  => get_the_excerpt($post),
//         'content'  => apply_filters('the_content', $post->post_content),
//         'date'     => get_the_date('', $post),
//         'image'    => get_the_post_thumbnail_url($post->ID, 'medium'),
//         'category' => wp_get_post_categories($post->ID, ['fields' => 'names']),
//         'tags'     => wp_get_post_tags($post->ID, ['fields' => 'names']),
//     ];
// }
