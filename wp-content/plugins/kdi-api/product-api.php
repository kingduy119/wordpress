<?php

add_action('rest_api_init', function () {

    // GET /wp-json/kdi/v1/categories
    register_rest_route('kdi/v1', '/categories', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_categories',
        'permission_callback' => '__return_true',
    ]);

    // GET /wp-json/kdi/v1/product-details/(?P<id>\d+)
    register_rest_route('kdi/v1', '/product-details/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'kdi_get_product_details',
    'permission_callback' => '__return_true',
  ));
});


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


// Single Product
function kdi_get_product_details(WP_REST_Request $request ) {
  $id = $request['id'];
  $product = wc_get_product( $id );

  if ( ! $product ) {
    return new WP_Error( 'not_found', 'Product not found', array( 'status' => 404 ) );
  }

  $response = [
    'id' => $product->get_id(),
    'name' => $product->get_name(),
    'slug' => $product->get_slug(),
    'price' => $product->get_price(),
    'regular_price' => $product->get_regular_price(),
    'sale_price' => $product->get_sale_price(),
    'currency' => get_woocommerce_currency(),
    'description' => $product->get_description(),
    'short_description' => $product->get_short_description(),
    'stock_quantity' => $product->get_stock_quantity(),
    'in_stock' => $product->is_in_stock(),
    'images' => [],
    'type' => $product->get_type(),
    'attributes' => [],
    'variations' => [],
  ];

  // Ảnh chính + gallery
  $thumbnail_id = $product->get_image_id();
  if ( $thumbnail_id ) {
    $response['images'][] = wp_get_attachment_url( $thumbnail_id );
  }

  foreach ( $product->get_gallery_image_ids() as $img_id ) {
    $response['images'][] = wp_get_attachment_url( $img_id );
  }

  // Thuộc tính hiển thị
  foreach ( $product->get_attributes() as $attribute ) {
    if ( ! $attribute->get_visible() ) continue;

    $attr_name = wc_attribute_label( $attribute->get_name() );

    if ( $attribute->is_taxonomy() ) {
      $terms = wp_get_post_terms( $id, $attribute->get_name(), array( 'fields' => 'names' ) );
      $response['attributes'][] = [ 'name' => $attr_name, 'options' => $terms ];
    } else {
      $response['attributes'][] = [ 'name' => $attr_name, 'options' => $attribute->get_options() ];
    }
  }

  // Nếu là sản phẩm biến thể → lấy danh sách biến thể
  if ( $product->is_type( 'variable' ) ) {
    $variations = $product->get_available_variations();
    foreach ( $variations as $var ) {
      $response['variations'][] = [
        'id' => $var['variation_id'],
        'price' => $var['display_price'],
        'regular_price' => $var['display_regular_price'],
        'sale_price' => $var['display_sale_price'],
        'attributes' => $var['attributes'],
        'image' => $var['image']['src'],
        'in_stock' => $var['is_in_stock'],
        'sku' => $var['sku'],
      ];
    }
  }

  return rest_ensure_response( $response );
}
