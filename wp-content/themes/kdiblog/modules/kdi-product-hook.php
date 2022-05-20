<?php

/**
 * catalog-product
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

add_action( 'woocommerce_shortcode_before_'. 'products' .'_loop', 'products_custoom_open_shortcode', 20 );
add_action( 'woocommerce_shortcode_after_'. 'products' .'_loop', 'products_custoom_close_shortcode', 20 );
add_action( 'woocommerce_after_shop_loop', 'kdi_wc_pagination', 10 );

/**
 * single-product
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_filter( 'woocommerce_product_tabs', 'woocommerce_default_product_tabs' ); 

add_action( 'kdi_single_product', 'kdi_post_content', 5 );

add_action( 'woocommerce_single_product_summary', 'kdi_wc_title', 5 );
add_action( 'woocommerce_after_single_product_summary', 'kdi_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'kdi_product_related', 20 );
add_filter( 'woocommerce_product_tabs', 'kdi_custom_product_single_tabs' );

/**
 * content-product
 */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
// remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

// add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_price', 6 );
add_action( 'woocommerce_shop_loop_item_title', 'kdi_shop_loop_item_title', 10 );

/**
 * templates/card:
 */
add_action( 'kdi_template_card_header', 'kdi_wc_thumbnail', 10 );

add_action( 'kdi_template_card_content', 'kdi_wc_price', 5 );
add_action( 'kdi_template_card_content', 'kdi_wc_title', 10 );

