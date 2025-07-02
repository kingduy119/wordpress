<?php

require_once dirname( __FILE__ ) . '/modules/kdi-post.php';
require_once dirname( __FILE__ ) . '/modules/kdi-post-hook.php';
// require_once dirname( __FILE__ ) . '/modules/kdi-post-function.php';
// require_once dirname( __FILE__ ) . '/modules/kdi-post-shortcode.php';

// require_once dirname( __FILE__ ) . '/modules/admin/settings.php';

// if( class_exists( 'WooCommerce' )) {
//     require_once dirname( __FILE__ ) . '/modules/kdi-product.php';
//     require_once dirname( __FILE__ ) . '/modules/kdi-product-hook.php';
//     require_once dirname( __FILE__ ) . '/modules/kdi-product-function.php';
// }



// add_filter( 'big_image_size_threshold', '__return_false' );
// Chèn Bootstrap CSS & JS từ CDN
function my_widget_enqueue_bootstrap() {
    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css',
        array(),
        null
    );

    // Bootstrap Bundle JS (gồm cả Popper)
    wp_enqueue_script(
        'bootstrap-bundle-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js',
        array(),
        null,
        true // load ở footer
    );

    // Font Awesome 6 Free (CDN)
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
        array(),
        '6.5.0'
    );
    
}
add_action('wp_enqueue_scripts', 'my_widget_enqueue_bootstrap');