<?php

add_action('rest_api_init', function () {
    // POST /wp-json/kdi/v1/posts
    register_rest_route(API_URL, '/posts', [
        'methods' => 'POST',
        'callback' => 'kdi_create_post',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);
});


function kdi_create_post(WP_REST_Request $request)
{
    $params = $request->get_json_params();

    // 1. Kiểm tra quyền
    if (!current_user_can('edit_posts')) {
        return new WP_Error('permission_denied', 'You are not allowed to create posts', ['status' => 403]);
    }

    // 2. Validate: bắt buộc có title hoặc content
    if (empty($params['title']) && empty($params['content'])) {
        return new WP_Error('missing_fields', 'Title or content is required', ['status' => 400]);
    }

    // 3. Validate post_status hợp lệ
    $allowed_statuses = ['publish', 'draft', 'pending'];
    $status = sanitize_key($params['status'] ?? 'publish');
    if (!in_array($status, $allowed_statuses)) {
        $status = 'draft';
    }

    $post_data = [
        'post_title'   => sanitize_text_field($params['title'] ?? ''),
        'post_content' => wp_kses_post($params['content'] ?? ''),
        'post_status'  => $status,
        'post_type'    => sanitize_key($params['post_type'] ?? 'post'),
        'post_author'  => (int) ($params['author'] ?? get_current_user_id()),
    ];

    if (!empty($params['excerpt'])) {
        $post_data['post_excerpt'] = sanitize_text_field($params['excerpt']);
    }

    if (!empty($params['categories'])) {
        $post_data['post_category'] = array_map('intval', $params['categories']);
    }

    if (!empty($params['tags'])) {
        $post_data['tags_input'] = array_map('sanitize_text_field', $params['tags']);
    }

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return new WP_Error('cannot_create', 'Cannot create post', ['status' => 500]);
    }

    // 4. Gán ảnh đại diện
    if (!empty($params['featured_media'])) {
        set_post_thumbnail($post_id, (int) $params['featured_media']);
    }

    // 5. Gán meta fields
    if (!empty($params['meta']) && is_array($params['meta'])) {
        foreach ($params['meta'] as $key => $value) {
            update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
        }
    }

    // 6. Gán custom taxonomy (nếu có)
    if (!empty($params['custom_tax'])) {
        wp_set_object_terms($post_id, array_map('sanitize_text_field', $params['custom_tax']), 'custom_taxonomy');
    }

    // 7. Xoá cache liên quan
    clean_post_cache($post_id);
    wp_cache_delete('all_posts', 'kdi_posts'); // Nếu bạn dùng cache riêng cho danh sách

    // 8. Trả về kết quả
    return rest_ensure_response([
        'status' => 'success',
        'data'   => [
            'id'   => $post_id,
            'link' => get_permalink($post_id),
        ],
    ]);
}
