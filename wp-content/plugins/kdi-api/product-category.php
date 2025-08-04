<?php
// Đăng ký các route khi REST API init
add_action('rest_api_init', function () {

    // GET /wp-json/kdi/v1/categories
    register_rest_route(API_URL, '/product-categories', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_categories',
        'permission_callback' => '__return_true',
    ]);

    // POST /wp-json/kdi/v1/categories
    register_rest_route(API_URL, '/product-categories', [
        'methods'  => 'POST',
        'callback' => 'kdi_create_category',
        'permission_callback' => 'kdi_check_permission',
    ]);

    // PUT /wp-json/kdi/v1/categories/(?P<id>\d+)
    register_rest_route(API_URL, '/product-categories/(?P<id>\d+)', [
        'methods'  => 'PUT',
        'callback' => 'kdi_update_category',
        'permission_callback' => 'kdi_check_permission',
        // 'args' => [
        //     'id' => [
        //         'validate_callback' => 'is_numeric'
        //     ]
        // ]
    ]);

    // DELETE /wp-json/kdi/v1/categories/(?P<id>\d+)
    register_rest_route(API_URL, '/product-categories/(?P<id>\d+)', [
        'methods'  => 'DELETE',
        'callback' => 'kdi_delete_category',
        'permission_callback' => 'kdi_check_permission',
        // 'args' => [
        //     'id' => [
        //         'validate_callback' => 'is_numeric'
        //     ]
        // ]
    ]);
});

// GET
function kdi_get_categories()
{
    $terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
    ]);

    $categories = [];
    foreach ($terms as $term) {
        $categories[] = [
            'id'    => $term->term_id,
            'name'  => $term->name,
            'slug'  => $term->slug,
            'count' => $term->count,
        ];
    }

    return rest_ensure_response($categories);
}

// POST
function kdi_create_category($request)
{
    $name = sanitize_text_field($request->get_param('name'));
    $slug = sanitize_title($request->get_param('slug'));

    $result = wp_insert_term($name, 'product_cat', [
        'slug' => $slug
    ]);

    if (is_wp_error($result)) {
        return new WP_Error('category_create_failed', $result->get_error_message(), ['status' => 400]);
    }

    return rest_ensure_response([
        'message' => 'Category created successfully',
        'term_id' => $result['term_id']
    ]);
}

// PUT
function kdi_update_category($request)
{
    $term_id = (int) $request->get_param('id');
    $name = sanitize_text_field($request->get_param('name'));
    $slug = sanitize_title($request->get_param('slug'));

    $args = [];
    if (!empty($name)) $args['name'] = $name;
    if (!empty($slug)) $args['slug'] = $slug;

    $result = wp_update_term($term_id, 'product_cat', $args);

    if (is_wp_error($result)) {
        return new WP_Error('category_update_failed', $result->get_error_message(), ['status' => 400]);
    }

    return rest_ensure_response([
        'message' => 'Category updated successfully',
        'term_id' => $result['term_id']
    ]);
}

// DELETE
function kdi_delete_category($request)
{
    $term_id = (int) $request->get_param('id');

    $result = wp_delete_term($term_id, 'product_cat');

    if (is_wp_error($result)) {
        return new WP_Error('category_delete_failed', $result->get_error_message(), ['status' => 400]);
    }

    return rest_ensure_response([
        'message' => 'Category deleted successfully',
        'term_id' => $term_id
    ]);
}

// Check quyền (chỉ admin/editor mới được POST/PUT/DELETE)
function kdi_check_permission()
{
    return current_user_can('manage_woocommerce') || current_user_can('edit_products');
}
