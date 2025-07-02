<?php
/**
 * Plugin Name: KDI Custom API
 * Description: REST API endpoints for WooCommerce products and categories
 * Version: 1.0
 * Author: Duy Hoang
 */

add_action('rest_api_init', function () {
    // // GET /wp-json/kdi/v1/products
    // register_rest_route('kdi/v1', '/products', [
    //     'methods'  => 'GET',
    //     'callback' => 'kdi_get_products',
    //     'permission_callback' => '__return_true',
    // ]);

    // GET /wp-json/kdi/v1/product/(?P<id>\d+)
    register_rest_route('kdi/v1', '/product/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_product_by_id',
        'permission_callback' => '__return_true',
    ]);

    // GET /wp-json/kdi/v1/categories
    register_rest_route('kdi/v1', '/categories', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_categories',
        'permission_callback' => '__return_true',
    ]);
});



function kdi_get_product_by_id(WP_REST_Request $request) {
    $id = (int) $request['id'];
    $product = wc_get_product($id);

    if (!$product) {
        return new WP_Error('not_found', 'Product not found', ['status' => 404]);
    }

    return [
        'id'          => $product->get_id(),
        'name'        => $product->get_name(),
        'slug'        => $product->get_slug(),
        'description' => $product->get_description(),
        'price'       => $product->get_price(),
        'image'       => wp_get_attachment_url($product->get_image_id()),
    ];
}

function kdi_get_categories() {
    $terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
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