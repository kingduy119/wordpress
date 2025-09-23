<?php

add_action('rest_api_init', function () {
    // PUT /wp-json/kdi/v1/posts/(?P<id>\d+)
    register_rest_route(API_URL, '/posts/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'kdi_update_post',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);

    // PUT /wp-json/kdi/v1/bulk-update-posts
    register_rest_route(API_URL, '/bulk-update-posts', [
        'methods'  => 'POST',
        'callback' => 'kdi_bulk_update_posts',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ]);
});


// Update
function kdi_update_post($request)
{
    $params  = $request->get_json_params();
    $post_id = intval($request['id']);

    if (get_post_status($post_id) === false) {
        return new WP_Error('invalid_post', 'Bài viết không tồn tại', ['status' => 404]);
    }

    $post_data = [
        'ID' => $post_id,
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

    // Gán meta fields & ACF fields nếu có
    if (!empty($params['meta']) && is_array($params['meta'])) {
        foreach ($params['meta'] as $key => $value) {
            $key = sanitize_key($key);

            if (strpos($key, 'acf_') === 0 && function_exists('update_field')) {
                // ACF field → dùng update_field
                update_field($key, $value, $post_id);
            } else {
                // Meta field thường
                update_post_meta($post_id, $key, sanitize_text_field($value));
            }
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
