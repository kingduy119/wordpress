<?php

add_action('rest_api_init', function () {
  // GET /wp-json/kdi/v1/products
  register_rest_route(API_URL, '/products', [
    'methods'  => 'GET',
    'callback' => 'kdi_get_products',
    'permission_callback' => '__return_true',
  ]);

  // GET /wp-json/kdi/v1/product-details/(?P<id>\d+)
  register_rest_route(API_URL, '/product-details/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'kdi_get_product_details',
    'permission_callback' => '__return_true',
  ));
});
// Single Product
function kdi_get_product_details(WP_REST_Request $request)
{
  $id = $request['id'];
  $product = wc_get_product($id);

  if (! $product) {
    return new WP_Error('not_found', 'Product not found', array('status' => 404));
  }

  $response = [
    'id'                => $product->get_id(),
    'name'              => $product->get_name(),
    'slug'              => $product->get_slug(),
    'price'             => $product->get_price(),
    'regular_price'     => $product->get_regular_price(),
    'sale_price'        => $product->get_sale_price(),
    'currency'          => get_woocommerce_currency(),
    'description'       => $product->get_description(),
    'short_description' => $product->get_short_description(),
    'stock_quantity'    => $product->get_stock_quantity(),
    'in_stock'          => $product->is_in_stock(),
    'images'            => [],
    'type'              => $product->get_type(),
    'attributes'        => [],
    'variations'        => [],
  ];

  // Ảnh chính + gallery của sản phẩm cha
  $thumbnail_id = $product->get_image_id();
  if ($thumbnail_id) {
    $response['images'][] = wp_get_attachment_url($thumbnail_id);
  }

  foreach ($product->get_gallery_image_ids() as $img_id) {
    $url = wp_get_attachment_url($img_id);
    if ($url) {
      $response['images'][] = $url;
    }
  }

  // Thuộc tính hiển thị (visible)
  foreach ($product->get_attributes() as $attribute) {
    if (! $attribute->get_visible()) continue;

    $attr_name = wc_attribute_label($attribute->get_name());

    if ($attribute->is_taxonomy()) {
      $terms = wp_get_post_terms($id, $attribute->get_name(), array('fields' => 'names'));
      $response['attributes'][] = [
        'name'    => $attr_name,
        'options' => $terms
      ];
    } else {
      $response['attributes'][] = [
        'name'    => $attr_name,
        'options' => $attribute->get_options()
      ];
    }
  }

  // Biến thể
  if ($product->is_type('variable')) {
    $variations = $product->get_available_variations();
    foreach ($variations as $var) {
      $variation_id = $var['variation_id'];

      // Lấy gallery ảnh riêng của biến thể từ plugin
      $gallery_ids = get_post_meta($variation_id, 'woo_variation_gallery_images', true);
      $gallery_urls = [];

      if (! empty($gallery_ids) && is_array($gallery_ids)) {
        foreach ($gallery_ids as $img_id) {
          $url = wp_get_attachment_url($img_id);
          if ($url) $gallery_urls[] = $url;
        }
      }

      $response['variations'][] = [
        'id'            => $variation_id,
        'price'         => $var['display_price'],
        'regular_price' => $var['display_regular_price'],
        'sale_price'    => $var['display_sale_price'],
        'attributes'    => $var['attributes'],
        'image'         => $var['image']['src'],
        'gallery'       => $gallery_urls, // ✅ ảnh phụ theo biến thể
        'in_stock'      => $var['is_in_stock'],
        'sku'           => $var['sku'],
      ];
    }
  }

  return rest_ensure_response($response);
}

function kdi_get_products(WP_REST_Request $request)
{
  $per_page = (int) $request->get_param('per_page') ?: 10;
  $paged    = (int) $request->get_param('page') ?: 1;
  $max_per_page = 50;
  if ($per_page > $max_per_page) $per_page = $max_per_page;

  $orderby = $request->get_param('orderby') ?: 'date'; // date, price, title
  $order   = strtoupper($request->get_param('order') ?: 'DESC'); // ASC or DESC

  $category = $request->get_param('category');
  $min_price = $request->get_param('min_price');
  $max_price = $request->get_param('max_price');
  $search = $request->get_param('search');

  $args = [
    'post_type'      => 'product',
    'posts_per_page' => $per_page,
    'paged'          => $paged,
    'order'          => $order,
  ];

  // Sắp xếp theo giá
  if ($orderby === 'price') {
    $args['meta_key'] = '_price';
    $args['orderby'] = 'meta_value_num';
  } elseif (in_array($orderby, ['title', 'date'])) {
    $args['orderby'] = $orderby;
  }

  // Lọc theo danh mục
  if ($category) {
    $args['tax_query'] = [[
      'taxonomy' => 'product_cat',
      'field'    => 'slug',
      'terms'    => $category,
    ]];
  }

  // Lọc theo giá
  if ($min_price || $max_price) {
    $meta_query = ['relation' => 'AND'];

    if ($min_price) {
      $meta_query[] = [
        'key'     => '_price',
        'value'   => (float)$min_price,
        'compare' => '>=',
        'type'    => 'NUMERIC',
      ];
    }

    if ($max_price) {
      $meta_query[] = [
        'key'     => '_price',
        'value'   => (float)$max_price,
        'compare' => '<=',
        'type'    => 'NUMERIC',
      ];
    }

    // Kết hợp với meta_query nếu đã có do sắp xếp theo price
    if (!empty($args['meta_query'])) {
      $args['meta_query'] = array_merge($args['meta_query'], $meta_query);
    } else {
      $args['meta_query'] = $meta_query;
    }
  }

  // Tìm kiếm
  if ($search) {
    $args['s'] = sanitize_text_field($search);
  }

  $query = new WP_Query($args);
  $products = [];

  while ($query->have_posts()) {
    $query->the_post();
    $product = wc_get_product(get_the_ID());

    if (!$product) continue;

    $products[] = [
      'id'       => $product->get_id(),
      'name'     => $product->get_name(),
      'slug'     => $product->get_slug(),
      'price'    => $product->get_price(),
      'type' => $product->get_type(),
      'image'    => wp_get_attachment_url($product->get_image_id()),
      'category' => wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']),
      'tags'     => wp_get_post_terms($product->get_id(), 'product_tag', ['fields' => 'names']),
    ];
  }

  wp_reset_postdata();
  return rest_ensure_response($products);
}
