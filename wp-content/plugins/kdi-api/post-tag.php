<?php

add_action('rest_api_init', function () {
    // ✅ GET all tags - /wp-json/kdi/v1/post-tags
    register_rest_route('kdi/v1', '/post-tags', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_post_tags',
        'permission_callback' => '__return_true',
    ]);

    // ✅ GET single tag - /wp-json/kdi/v1/post-tags/{id}
    register_rest_route('kdi/v1', '/post-tags/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_post_tag',
        'permission_callback' => '__return_true',
    ]);

    // ✅ POST - create tag
    register_rest_route('kdi/v1', '/post-tags', [
        'methods'  => 'POST',
        'callback' => 'kdi_create_post_tag',
        'permission_callback' => function () {
            return current_user_can('manage_categories');
        },
    ]);

    // ✅ PUT - update tag
    register_rest_route('kdi/v1', '/post-tags/(?P<id>\d+)', [
        'methods'  => 'PUT',
        'callback' => 'kdi_update_post_tag',
        'permission_callback' => function () {
            return current_user_can('manage_categories');
        },
    ]);

    // ✅ DELETE tag
    register_rest_route('kdi/v1', '/post-tags/(?P<id>\d+)', [
        'methods'  => 'DELETE',
        'callback' => 'kdi_delete_post_tag',
        'permission_callback' => function () {
            return current_user_can('manage_categories');
        },
    ]);
});

// ==================== Callbacks ====================

// GET all tags
function kdi_get_post_tags()
{
    $tags = get_terms([
        'taxonomy'   => 'post_tag',
        'hide_empty' => false,
    ]);

    $result = [];

    foreach ($tags as $tag) {
        $result[] = [
            'id'    => $tag->term_id,
            'name'  => $tag->name,
            'slug'  => $tag->slug,
            'count' => $tag->count,
        ];
    }

    return rest_ensure_response($result);
}

// GET single tag
function kdi_get_post_tag(WP_REST_Request $request)
{
    $term_id = (int) $request['id'];
    $term = get_term($term_id, 'post_tag');

    if (!$term || is_wp_error($term)) {
        return new WP_Error('not_found', 'Tag not found.', ['status' => 404]);
    }

    return rest_ensure_response([
        'id'          => $term->term_id,
        'name'        => $term->name,
        'slug'        => $term->slug,
        'description' => $term->description,
        'count'       => $term->count,
        'link'        => get_term_link($term),
    ]);
}

// POST - create tag
function kdi_create_post_tag(WP_REST_Request $request)
{
    $params = $request->get_json_params();
    $name   = sanitize_text_field($params['name'] ?? '');
    $slug   = sanitize_title($params['slug'] ?? '');
    $desc   = sanitize_text_field($params['description'] ?? '');

    if (empty($name)) {
        return new WP_Error('empty_name', 'Tag name is required', ['status' => 400]);
    }

    $term = wp_insert_term($name, 'post_tag', [
        'slug'        => $slug,
        'description' => $desc,
    ]);

    if (is_wp_error($term)) {
        return $term;
    }

    $term_data = get_term($term['term_id'], 'post_tag');

    return rest_ensure_response([
        'created' => true,
        'term_id' => $term_data->term_id,
        'name'    => $term_data->name,
        'slug'    => $term_data->slug,
    ]);
}

// PUT - update tag
function kdi_update_post_tag(WP_REST_Request $request)
{
    $term_id = (int) $request['id'];
    $term = get_term($term_id, 'post_tag');

    if (!$term || is_wp_error($term)) {
        return new WP_Error('not_found', 'Tag not found.', ['status' => 404]);
    }

    $params = $request->get_json_params();
    $args   = [];

    if (!empty($params['name'])) {
        $args['name'] = sanitize_text_field($params['name']);
    }

    if (!empty($params['slug'])) {
        $args['slug'] = sanitize_title($params['slug']);
    }

    if (!empty($params['description'])) {
        $args['description'] = sanitize_text_field($params['description']);
    }

    if (empty($args)) {
        return new WP_Error('no_data', 'No data provided to update.', ['status' => 400]);
    }

    $result = wp_update_term($term_id, 'post_tag', $args);

    if (is_wp_error($result)) {
        return new WP_Error('update_failed', $result->get_error_message(), ['status' => 400]);
    }

    $updated_term = get_term($result['term_id'], 'post_tag');

    return rest_ensure_response([
        'updated'     => true,
        'term_id'     => $updated_term->term_id,
        'name'        => $updated_term->name,
        'slug'        => $updated_term->slug,
        'description' => $updated_term->description,
    ]);
}

// DELETE tag
function kdi_delete_post_tag(WP_REST_Request $request)
{
    $term_id = (int) $request['id'];

    $term = get_term($term_id, 'post_tag');
    if (!$term || is_wp_error($term)) {
        return new WP_Error('not_found', 'Tag not found.', ['status' => 404]);
    }

    $result = wp_delete_term($term_id, 'post_tag');
    if (is_wp_error($result) || !$result) {
        return new WP_Error('delete_failed', 'Failed to delete tag.', ['status' => 500]);
    }

    return rest_ensure_response([
        'deleted' => true,
        'term_id' => $term_id,
        'message' => sprintf('Tag "%s" has been deleted.', $term->name),
    ]);
}
