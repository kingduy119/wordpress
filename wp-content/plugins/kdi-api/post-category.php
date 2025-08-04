<?php
add_action('rest_api_init', function () {
    // ✅ GET - /wp-json/kdi/v1/post-categories
    register_rest_route(API_URL, '/post-categories', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_post_categories',
        'permission_callback' => '__return_true',
    ]);

    // ✅ POST - /wp-json/kdi/v1/post-categories
    register_rest_route(API_URL, '/post-categories', [
        'methods'  => 'POST',
        'callback' => 'kdi_create_post_category',
        'permission_callback' => function () {
            return current_user_can('manage_categories');
        },
    ]);

    // ✅ PUT - /wp-json/kdi/v1/post-categories
    register_rest_route(API_URL, '/post-categories/(?P<id>\d+)', [
        'methods'  => 'PUT',
        'callback' => 'kdi_update_post_category',
        'permission_callback' => function () {
            return current_user_can('manage_categories');
        },
    ]);

    // ✅ DELETE - /wp-json/kdi/v1/post-categories
    register_rest_route(API_URL, '/post-categories/(?P<id>\d+)', [
        'methods'  => 'DELETE',
        'callback' => 'kdi_delete_post_category',
        'permission_callback' => function () {
            return current_user_can('manage_categories');
        },
    ]);
});

// ==================== Callback Functions ====================

// GET
function kdi_get_post_categories()
{
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

// POST
function kdi_create_post_category($request)
{
    $params = $request->get_json_params();
    $name = sanitize_text_field($params['name'] ?? '');
    $slug = sanitize_title($params['slug'] ?? '');

    if (empty($name)) {
        return new WP_Error('empty_name', 'Category name is required', ['status' => 400]);
    }

    $term = wp_insert_term($name, 'category', ['slug' => $slug]);

    if (is_wp_error($term)) {
        return $term;
    }

    return rest_ensure_response(['created' => true, 'term_id' => $term['term_id']]);
}

// PUT
function kdi_update_post_category($request)
{
    $term_id = (int)$request['id'];
    $params = $request->get_json_params();
    $args = [];

    if (!empty($params['name'])) {
        $args['name'] = sanitize_text_field($params['name']);
    }

    if (!empty($params['slug'])) {
        $args['slug'] = sanitize_title($params['slug']);
    }

    if (empty($args)) {
        return new WP_Error('no_data', 'No data provided to update', ['status' => 400]);
    }

    $term = wp_update_term($term_id, 'category', $args);

    if (is_wp_error($term)) {
        return $term;
    }

    return rest_ensure_response(['updated' => true, 'term_id' => $term['term_id']]);
}

// DELETE
function kdi_delete_post_category($request)
{
    $term_id = (int)$request['id'];

    $result = wp_delete_term($term_id, 'category');

    if (!$result || is_wp_error($result)) {
        return new WP_Error('delete_failed', 'Failed to delete category', ['status' => 500]);
    }

    return rest_ensure_response(['deleted' => true, 'term_id' => $term_id]);
}
