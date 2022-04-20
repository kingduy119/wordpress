<?php


if( ! function_exists( 'kdi_product_thumbnail' ) ) {
    function kdi_product_thumbnail() {
        kdi_product_part( 'loop/thumbnail' );
    }
}

if( ! function_exists( 'kdi_product_title' ) ) {
    function kdi_product_title() {
        kdi_product_part( 'loop/title' );
    }
}

if( ! function_exists( 'kdi_product_price' ) ) {
    function kdi_product_price() {
        kdi_product_part( 'loop/price' );
    }
}