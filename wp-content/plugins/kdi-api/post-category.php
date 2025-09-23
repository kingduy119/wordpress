<?php
add_action('rest_api_init', function () {
    // ✅ GET - /wp-json/kdi/v1/post-categories
    register_rest_route(API_URL, '/post-categories', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_post_categories',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('kdi/v1', '/post-categories/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_post_cat_by_id',
        'permission_callback' => '__return_true', // Hoặc kiểm tra quyền nếu cần
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
        // 'permission_callback' => '__return_true',
        'permission_callback' => function () {
            return current_user_can('manage_categories');
        },
    ]);
});

// ==================== Callback Functions ====================

// GET
function kdi_get_post_categories(WP_REST_Request $request)
{
    // Params & sanitize
    $param_map = [
        'per_page'   => 'intval',
        'page'       => 'intval',
        'order'      => 'sanitize_text_field',
        'orderby'    => 'sanitize_text_field',
        'slug'       => 'sanitize_title',
        'hide_empty' => fn($v) => filter_var($v, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
    ];

    $params = [];
    foreach ($param_map as $key => $sanitize) {
        if ($request->has_param($key)) {
            $params[$key] = is_callable($sanitize) ? $sanitize($request[$key]) : $sanitize($request[$key]);
        }
    }

    $per_page   = isset($params['per_page']) ? min(max($params['per_page'], 1), 50) : 10;
    $paged      = isset($params['page']) ? max($params['page'], 1) : 1;
    $offset     = ($paged - 1) * $per_page;
    $order      = isset($params['order']) && strtoupper($params['order']) === 'ASC' ? 'ASC' : 'DESC';
    $orderby    = $params['orderby'] ?? 'id';
    $slug       = $params['slug'] ?? '';
    $hide_empty = $params['hide_empty'] ?? false;

    // Cache key (versioned)
    $cache_key  = 'kdi_cats_v1_' . md5(serialize([$per_page, $paged, $order, $orderby, $slug, $hide_empty]));
    $cache_group = 'kdi_cats';
    $cached     = wp_cache_get($cache_key, $cache_group);

    if ($cached !== false) {
        return rest_ensure_response($cached);
    }

    // Query
    $args = [
        'taxonomy'   => 'category',
        'number'     => $per_page,
        'offset'     => $offset,
        'hide_empty' => $hide_empty,
        'orderby'    => $orderby,
        'order'      => $order,
    ];
    if ($slug) {
        $args['slug'] = $slug;
    }

    $query = new WP_Term_Query($args);
    $terms = $query->get_terms();

    // Found terms (WP >= 6.0 hỗ trợ $query->found_terms)
    $total = property_exists($query, 'found_terms') && is_numeric($query->found_terms)
        ? (int) $query->found_terms
        : (int) wp_count_terms(['taxonomy' => 'category', 'hide_empty' => $hide_empty]);

    $total_pages = (int) ceil($total / $per_page);

    // Build response
    $data = [];
    foreach ($terms as $term) {
        $link = get_term_link($term);
        if (is_wp_error($link)) {
            $link = '';
        }

        $data[] = [
            'id'          => $term->term_id,
            'name'        => $term->name,
            'slug'        => $term->slug,
            'description' => $term->description,
            'count'       => $term->count,
            'parent'      => $term->parent,
            'taxonomy'    => $term->taxonomy,
            'link'        => $link,
            'rest_url'    => rest_url('wp/v2/categories/' . $term->term_id),
            'rest_base'   => 'categories',
            'type'        => 'term',
            'meta'        => [
                'thumbnail' => get_term_meta($term->term_id, 'thumbnail', true),
            ],
        ];
    }

    $response = [
        'found'        => $total,
        'total_pages'  => $total_pages,
        'current_page' => $paged,
        'has_more'     => $paged < $total_pages,
        'data'         => $data,
    ];

    // Cache 5 phút
    wp_cache_set($cache_key, $response, $cache_group, 300);

    return rest_ensure_response($response);
}


function kdi_get_post_cat_by_id(WP_REST_Request $request)
{
    $term_id = (int) $request['id'];
    $term = get_term($term_id, 'category');

    if (!$term || is_wp_error($term)) {
        return new WP_Error('not_found', 'Category not found.', ['status' => 404]);
    }

    $link = get_term_link($term);
    if (is_wp_error($link)) {
        $link = '';
    }

    return rest_ensure_response([
        'id'          => $term->term_id,
        'name'        => $term->name,
        'slug'        => $term->slug,
        'description' => $term->description,
        'count'       => $term->count,
        'parent'      => $term->parent,
        'taxonomy'    => $term->taxonomy,
        'link'        => $link,
        'rest_url'    => rest_url('wp/v2/categories/' . $term->term_id),
    ]);
}



// POST
function kdi_create_post_category(WP_REST_Request $request)
{
    if (!current_user_can('manage_categories')) {
        return new WP_Error('forbidden', 'You do not have permission to create categories.', ['status' => 403]);
    }

    $params = $request->get_json_params();
    $name   = sanitize_text_field($params['name'] ?? '');
    $slug   = sanitize_title($params['slug'] ?? '');
    $desc   = sanitize_text_field($params['description'] ?? '');
    $parent = isset($params['parent']) ? (int) $params['parent'] : 0;

    if (empty($name)) {
        return new WP_Error('empty_name', 'Category name is required', ['status' => 400]);
    }

    $term = wp_insert_term($name, 'category', [
        'slug'        => $slug,
        'description' => $desc,
        'parent'      => $parent
    ]);

    if (is_wp_error($term)) {
        return $term;
    }

    $term_data = get_term($term['term_id'], 'category');

    return rest_ensure_response([
        'created' => true,
        'term_id' => $term['term_id'],
        'slug'    => $term_data->slug,
        'name'    => $term_data->name,
    ]);
}

// PUT
function kdi_update_post_category(WP_REST_Request $request)
{
    if (!current_user_can('manage_categories')) {
        return new WP_Error('forbidden', 'You do not have permission to update categories.', ['status' => 403]);
    }

    $term_id = (int) $request['id'];

    $term = get_term($term_id, 'category');
    if (!$term || is_wp_error($term)) {
        return new WP_Error('not_found', 'Category not found.', ['status' => 404]);
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

    if (isset($params['parent'])) {
        $args['parent'] = (int) $params['parent'];
    }

    if (empty($args)) {
        return new WP_Error('no_data', 'No data provided to update.', ['status' => 400]);
    }

    // Ngăn không cho category tự làm parent của chính nó
    if (isset($args['parent']) && $args['parent'] === $term_id) {
        return new WP_Error('invalid_parent', 'A category cannot be its own parent.', ['status' => 400]);
    }

    $result = wp_update_term($term_id, 'category', $args);

    if (is_wp_error($result)) {
        return new WP_Error('update_failed', $result->get_error_message(), ['status' => 400]);
    }

    // Lấy lại thông tin term sau khi cập nhật
    $updated_term = get_term($result['term_id'], 'category');

    return rest_ensure_response([
        'updated'     => true,
        'term_id'     => $updated_term->term_id,
        'name'        => $updated_term->name,
        'slug'        => $updated_term->slug,
        'description' => $updated_term->description,
        'parent'      => $updated_term->parent,
    ]);
}


// DELETE
function kdi_delete_post_category(WP_REST_Request $request)
{
    // Kiểm tra quyền xóa chuyên mục
    if (!current_user_can('manage_categories')) {
        return new WP_Error(
            'forbidden',
            'You do not have permission to delete categories.',
            ['status' => 403]
        );
    }

    $term_id = (int) $request['id'];

    // Kiểm tra chuyên mục có tồn tại
    $term = get_term($term_id, 'category');
    if (!$term || is_wp_error($term)) {
        return new WP_Error(
            'not_found',
            'Category not found.',
            ['status' => 404]
        );
    }

    // Tùy chọn: kiểm tra chuyên mục có bài viết không
    $posts_in_category = get_posts([
        'category'       => $term_id,
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ]);

    if (!empty($posts_in_category)) {
        return new WP_Error(
            'category_not_empty',
            'Cannot delete category because it contains posts.',
            ['status' => 400]
        );
    }

    // Xóa chuyên mục
    $result = wp_delete_term($term_id, 'category');
    if (is_wp_error($result) || !$result) {
        return new WP_Error(
            'delete_failed',
            'Failed to delete category.',
            ['status' => 500]
        );
    }

    return rest_ensure_response([
        'deleted' => true,
        'term_id' => $term_id,
        'message' => sprintf('Category "%s" has been deleted.', $term->name),
    ]);
}
