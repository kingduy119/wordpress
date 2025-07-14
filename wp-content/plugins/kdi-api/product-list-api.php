<?php

add_action('rest_api_init', function () {
    // GET /wp-json/kdi/v1/products
    register_rest_route('kdi/v1', '/products', [
        'methods'  => 'GET',
        'callback' => 'kdi_get_products',
        'permission_callback' => '__return_true',
    ]);
    
});

function kdi_get_products(WP_REST_Request $request) {
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
