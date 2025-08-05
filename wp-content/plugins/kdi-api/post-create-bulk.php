<?php
add_action('rest_api_init', function () {
    // POST /wp-json/kdi/v1/bulk-create-posts
    register_rest_route('kdi/v1', '/bulk-create-posts', [
        'methods' => 'POST',
        'callback' => 'kdi_bulk_create_posts',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);
});


function kdi_bulk_create_posts(WP_REST_Request $request)
{
    if (!current_user_can('edit_posts')) {
        return new WP_Error('permission_denied', 'You are not allowed to create posts', ['status' => 403]);
    }

    $posts = $request->get_json_params();

    if (!is_array($posts)) {
        return new WP_Error('invalid_data', 'Expected an array of posts', ['status' => 400]);
    }

    $created_posts = [];
    $errors = [];

    foreach ($posts as $index => $data) {
        if (empty($data['title']) && empty($data['content'])) {
            $errors[] = [
                'index' => $index,
                'message' => 'Missing title and content',
                'post_data' => $data,
            ];
            continue;
        }

        $new_post = build_wp_post_array($data);
        $post_id = wp_insert_post($new_post);

        if (!is_wp_error($post_id)) {
            // ✅ Validate featured_media là attachment hợp lệ
            if (!empty($data['featured_media']) && get_post_type($data['featured_media']) === 'attachment') {
                set_post_thumbnail($post_id, (int)$data['featured_media']);
            }

            do_action('kdi_bulk_post_created', $post_id, $data);

            $created_posts[] = $post_id;
        } else {
            // ✅ Ghi log lỗi để debug nếu đang phát triển
            if (WP_DEBUG === true) {
                error_log(print_r($post_id, true));
            }

            $errors[] = [
                'index' => $index,
                'message' => $post_id->get_error_message(),
                'post_data' => $data,
            ];
        }
    }

    return [
        'status' => empty($errors) ? 'success' : 'partial_success',
        'created_post_ids' => $created_posts,
        'errors' => $errors,
    ];
}


function build_wp_post_array(array $data): array
{
    $post = [
        'post_title'   => wp_strip_all_tags($data['title'] ?? ''),
        'post_content' => $data['content'] ?? '',
        'post_status'  => $data['post_status'] ?? 'publish', // ✅ Cho phép truyền post_status
        'post_type'    => $data['post_type'] ?? 'post',
        'post_author'  => get_current_user_id(),
    ];

    $map = [
        'slug'       => 'post_name',
        'excerpt'    => 'post_excerpt',
        'tags'       => 'tags_input',
        'categories' => 'post_category',
        'post_date'  => 'post_date',
    ];

    foreach ($map as $key => $target) {
        if (!empty($data[$key])) {
            $post[$target] = $key === 'slug' ? sanitize_title($data[$key]) : $data[$key];
        }
    }

    // ✅ Hỗ trợ gán custom fields
    if (!empty($data['meta']) && is_array($data['meta'])) {
        $post['meta_input'] = $data['meta'];
    }

    return $post;
}
