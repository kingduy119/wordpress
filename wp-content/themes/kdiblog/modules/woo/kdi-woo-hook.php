<?php


add_action( 'kdi_product_loop', 'kdi_product_loop_thumbnail', 5 );





/**
 * templates:
 */


add_action( 'kdi_template_card_header', 'kdi_product_thumbnail', 10 );

add_action( 'kdi_template_card_content', 'kdi_product_price', 5 );
add_action( 'kdi_template_card_content', 'kdi_product_title', 10 );

