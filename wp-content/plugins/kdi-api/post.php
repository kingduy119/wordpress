<?php

add_action('rest_api_init', function () {

    // // POST /wp-json/kdi/v1/posts
    // register_rest_route(API_URL, '/posts', [
    //     'methods' => 'POST',
    //     'callback' => 'kdi_create_post',
    //     'permission_callback' => function () {
    //         return current_user_can('edit_posts');
    //     },
    // ]);

    // PUT /wp-json/kdi/v1/bulk-update-posts
    register_rest_route(API_URL, '/bulk-update-posts', [
        'methods'  => 'POST',
        'callback' => 'kdi_bulk_update_posts',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ]);

    // GET /wp-json/kdi/v1/posts
    // register_rest_route(API_URL, '/posts', [
    //     'methods'  => 'GET',
    //     'callback' => 'kdi_get_posts',
    //     'permission_callback' => '__return_true',
    // ]);

    // // GET /wp-json/kdi/v1/post/(?P<id>\d+)
    // register_rest_route(API_URL, '/post/(?P<id>\d+)', [
    //     'methods'  => 'GET',
    //     'callback' => 'kdi_get_post_by_id',
    //     'permission_callback' => '__return_true',
    // ]);

    // PUT /wp-json/kdi/v1/posts/(?P<id>\d+)
    register_rest_route(API_URL, '/posts/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'kdi_update_post',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);


    // DELETE /wp-json/kdi/v1/posts/(?P<id>\d+)
    register_rest_route(API_URL, '/posts/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'kdi_delete_post',
        'permission_callback' => function () {
            return current_user_can('delete_posts');
        },
    ]);
});

// function kdi_get_posts(WP_REST_Request $request)
// {
//     $per_page = (int) $request->get_param('per_page') ?: 10;
//     $paged    = (int) $request->get_param('page') ?: 1;

//     // Giới hạn tối đa số lượng post trả về để tránh abuse
//     $max_per_page = 50;
//     if ($per_page > $max_per_page) {
//         $per_page = $max_per_page;
//     }

//     $args = [
//         'post_type'      => 'post',
//         'posts_per_page' => $per_page,
//         'paged'          => $paged,
//     ];

//     $query = new WP_Query($args);
//     $posts = [];

//     while ($query->have_posts()) {
//         $query->the_post();
//         $posts[] = [
//             'id'       => get_the_ID(),
//             'title'    => get_the_title(),
//             'slug'     => get_post_field('post_name', get_the_ID()),
//             'excerpt'  => get_the_excerpt(),
//             'content'  => get_the_content(),
//             'date'     => get_the_date(),
//             'image'    => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
//             'featured_media'  => get_post_thumbnail_id(get_the_ID()),
//             'category' => wp_get_post_categories(get_the_ID(), ['fields' => 'names']),
//             'tags'     => wp_get_post_tags(get_the_ID(), ['fields' => 'names']),
//         ];
//     }

//     wp_reset_postdata();
//     return rest_ensure_response($posts);
// }

// function kdi_get_post_by_id(WP_REST_Request $request)
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

// Create
// function kdi_create_post_old($request)
// {
//     $params = $request->get_json_params();

//     $post_data = [
//         'post_title'   => sanitize_text_field($params['title'] ?? ''),
//         'post_content' => wp_kses_post($params['content'] ?? ''),
//         'post_status'  => $params['status'] ?? 'publish',
//         'post_type'    => $params['post_type'] ?? 'post',
//         'post_author'  => $params['author'] ?? get_current_user_id(),
//     ];

//     if (!empty($params['excerpt'])) {
//         $post_data['post_excerpt'] = sanitize_text_field($params['excerpt']);
//     }

//     if (!empty($params['categories'])) {
//         $post_data['post_category'] = array_map('intval', $params['categories']);
//     }

//     if (!empty($params['tags'])) {
//         $post_data['tags_input'] = array_map('sanitize_text_field', $params['tags']);
//     }

//     $post_id = wp_insert_post($post_data);

//     if (is_wp_error($post_id)) {
//         return new WP_Error('cannot_create', 'Cannot create post', ['status' => 500]);
//     }

//     // Gán ảnh đại diện
//     if (!empty($params['featured_media'])) {
//         set_post_thumbnail($post_id, intval($params['featured_media']));
//     }

//     // Gán meta nếu có
//     if (!empty($params['meta']) && is_array($params['meta'])) {
//         foreach ($params['meta'] as $key => $value) {
//             update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
//         }
//     }

//     return [
//         'id' => $post_id,
//         'link' => get_permalink($post_id),
//     ];
// }

// Update
function kdi_update_post($request)
{
    $params = $request->get_json_params();
    $post_id = intval($request['id']);

    if (get_post_status($post_id) === false) {
        return new WP_Error('invalid_post', 'Bài viết không tồn tại', ['status' => 404]);
    }

    $post_data = [
        'ID'           => $post_id,
    ];

    if (!empty($params['title'])) {
        $post_data['post_title'] = sanitize_text_field($params['title']);
    }

    if (!empty($params['content'])) {
        $post_data['post_content'] = wp_kses_post($params['content']);
    }

    if (!empty($params['status'])) {
        $post_data['post_status'] = sanitize_text_field($params['status']);
    }

    if (!empty($params['excerpt'])) {
        $post_data['post_excerpt'] = sanitize_text_field($params['excerpt']);
    }

    if (!empty($params['author'])) {
        $post_data['post_author'] = intval($params['author']);
    }

    if (!empty($params['categories'])) {
        $post_data['post_category'] = array_map('intval', $params['categories']);
    }

    if (!empty($params['tags'])) {
        $post_data['tags_input'] = array_map('sanitize_text_field', $params['tags']);
    }


    $updated = wp_update_post($post_data, true);

    if (is_wp_error($updated)) {
        return new WP_Error('update_failed', 'Không cập nhật được bài viết', ['status' => 500]);
    }

    // Gán featured image nếu có
    if (!empty($params['featured_media'])) {
        set_post_thumbnail($post_id, intval($params['featured_media']));
    }

    // Gán meta fields nếu có
    if (!empty($params['meta']) && is_array($params['meta'])) {
        foreach ($params['meta'] as $key => $value) {
            update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
        }
    }

    return [
        'updated' => true,
        'id'      => $post_id,
        'link'    => get_permalink($post_id),
    ];
}

// Update bulk
function kdi_bulk_update_posts(WP_REST_Request $request)
{
    $params = $request->get_json_params();

    if (empty($params['post_ids']) || !is_array($params['post_ids'])) {
        return new WP_Error('invalid_params', 'Thiếu danh sách ID bài viết.', ['status' => 400]);
    }

    $post_ids = array_map('intval', $params['post_ids']);
    $success_ids = [];
    $errors = [];

    foreach ($post_ids as $post_id) {
        if (get_post_status($post_id) === false) {
            $errors[] = ['id' => $post_id, 'error' => 'Bài viết không tồn tại'];
            continue;
        }

        $post_data = ['ID' => $post_id];

        if (!empty($params['title'])) {
            $post_data['post_title'] = sanitize_text_field($params['title']);
        }

        if (!empty($params['content'])) {
            $post_data['post_content'] = wp_kses_post($params['content']);
        }

        if (!empty($params['status'])) {
            $post_data['post_status'] = sanitize_text_field($params['status']);
        }

        if (!empty($params['excerpt'])) {
            $post_data['post_excerpt'] = sanitize_text_field($params['excerpt']);
        }

        if (!empty($params['author'])) {
            $post_data['post_author'] = intval($params['author']);
        }

        if (!empty($params['categories'])) {
            $post_data['post_category'] = array_map('intval', $params['categories']);
        }

        if (!empty($params['tags'])) {
            $post_data['tags_input'] = array_map('sanitize_text_field', $params['tags']);
        }

        $updated = wp_update_post($post_data, true);

        if (is_wp_error($updated)) {
            $errors[] = ['id' => $post_id, 'error' => 'Cập nhật thất bại'];
            continue;
        }

        if (!empty($params['featured_media'])) {
            set_post_thumbnail($post_id, intval($params['featured_media']));
        }

        if (!empty($params['meta']) && is_array($params['meta'])) {
            foreach ($params['meta'] as $key => $value) {
                update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
            }
        }

        $success_ids[] = $post_id;
    }

    return [
        'success' => $success_ids,
        'errors'  => $errors,
    ];
}


// Delete
function kdi_delete_post($request)
{
    $post_id = intval($request['id']);

    // Kiểm tra bài viết có tồn tại không
    $post = get_post($post_id);
    if (!$post) {
        return new WP_Error('not_found', 'Bài viết không tồn tại', ['status' => 404]);
    }

    // Kiểm tra quyền
    if (!current_user_can('delete_post', $post_id)) {
        return new WP_Error('unauthorized', 'Bạn không có quyền xóa bài viết này', ['status' => 403]);
    }

    // Xóa bài viết (true = xóa vĩnh viễn, false = đưa vào thùng rác)
    $force = isset($request['force']) ? filter_var($request['force'], FILTER_VALIDATE_BOOLEAN) : true;

    $result = wp_delete_post($post_id, $force);

    if (!$result) {
        return new WP_Error('not_deleted', 'Không thể xóa bài viết', ['status' => 500]);
    }

    return [
        'deleted' => true,
        'force'   => $force,
        'post_id' => $post_id,
    ];
}
