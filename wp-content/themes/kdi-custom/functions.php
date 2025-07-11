<?php
// require_once get_template_directory() . '/includes/class-wp-bootstrap-navwalker.php';
require_once get_template_directory() . '/includes/class-bootstrap-navwalker.php';

if (! function_exists('kdiseadev_theme_setup')) {
    function kdiseadev_theme_setup() {
        add_theme_support( 'custom-logo');
        add_theme_support( 'post-thumbnails' );

    }
}
add_action('after_setup_theme', 'kdiseadev_theme_setup');

if (function_exists('register_sidebar')) {
    register_nav_menus([
        'main-menu' => __('Main Menu', 'your-theme-textdomain'),
    ]);


    register_sidebar(array(
        'name'              => 'Page header',
        'id'                => 'header',
        'before_widget'     => '',
        'after_widget'      => '',
    ));

    register_sidebar(array(
        'name'              => 'Sidebar',
        'id'                => 'sidebar',
        'before_sidebar'    => '<div id="page-sidebar" class="container">',
        'after_sidebar'     => '</div>',
        'before_widget'     => '<div id="%1$s" class="sidebar-wg">',
        'after_widget'      => '</div>',
    ));
    register_sidebar(array(
        'name'              => 'Sidebar product',
        'id'                => 'sidebar-product',
        'before_sidebar'    => '<div id="page-product-sidebar" class="container">',
        'after_sidebar'     => '</div>',
        'before_widget'     => '<div id="%1$s" class="sidebar-product-wg">',
        'after_widget'      => '</div>',
    ));
    register_sidebar(array(
        'name'              => 'Sidebar product catalog',
        'id'                => 'sidebar-product-catalog',
        'before_sidebar'    => '<div id="page-product-sidebar-catalog" class="container">',
        'after_sidebar'     => '</div>',
        'before_widget'     => '<div id="%1$s" class="sidebar-product-wg">',
        'after_widget'      => '</div>',
    ));

    register_sidebar(array(
        'name'              => 'Page footer',
        'id'                => 'page-footer',
        'before_widget'     => '',
        'after_widget'      => '',
    ));
}

// Chèn Bootstrap CSS & JS từ CDN
function my_widget_enqueue_bootstrap() {
    wp_enqueue_style('kdiseadev-style', get_stylesheet_uri());
    $bootstrap_css = get_template_directory_uri() . '/includes/bootstrap-5.3.7/css/bootstrap.min.css';
    $bootstrap_js  = get_template_directory_uri() . '/includes/bootstrap-5.3.7/js/bootstrap.bundle.min.js';

    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap-css',
        $bootstrap_css,
        array(),
        null
    );

    // Bootstrap Bundle JS (gồm cả Popper)
    wp_enqueue_script(
        'bootstrap-js',
        $bootstrap_js,
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


if( class_exists( 'WooCommerce' )) {
    add_theme_support('woocommerce');
}



