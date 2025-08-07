<?php

add_action('rest_api_init', function () {
    // DELETE /wp-json/kdi/v1/posts/(?P<id>\d+)
    register_rest_route(API_URL, '/posts/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'kdi_delete_post',
        'permission_callback' => function () {
            return current_user_can('delete_posts');
        },
    ]);
});


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
