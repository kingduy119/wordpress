<?php
// Load parent theme CSS
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
});

// if ( ! function_exists('kandece_child_enqueue_bootstrap')) {
//     // Chèn Bootstrap CSS & JS từ CDN
//     function kandece_child_enqueue_bootstrap() {
//         wp_enqueue_style('kandece-child-style', get_stylesheet_uri());
//         $bootstrap_css = get_template_directory_uri() . '/includes/bootstrap-5.3.7/css/bootstrap.min.css';
//         $bootstrap_js  = get_template_directory_uri() . '/includes/bootstrap-5.3.7/js/bootstrap.bundle.min.js';

//         // Bootstrap CSS
//         wp_enqueue_style(
//             'bootstrap-css',
//             $bootstrap_css,
//             array(),
//             null
//         );

//         // Bootstrap Bundle JS (gồm cả Popper)
//         wp_enqueue_script(
//             'bootstrap-js',
//             $bootstrap_js,
//             array(),
//             null,
//             true // load ở footer
//         );

//         // Font Awesome 6 Free (CDN)
//         wp_enqueue_style(
//             'font-awesome',
//             'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
//             array(),
//             '6.5.0'
//         );
        
//     }
//     add_action('wp_enqueue_scripts', 'my_widget_enqueue_bootstrap');
// }

