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
        'before_sidebar'    => '<header id="page-header" class="fixed-top bg-light">',
        'after_sidebar'     => '</header>',
        'before_widget'     => '<div id="%1$s" class="page-header-widget">',
        'after_widget'      => '</div>',
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
        'before_sidebar'    => '<div id="page-footer">',
        'after_sidebar'     => '</div>',
        'before_widget'     => '<div id="%1$s">',
        'after_widget'      => '</div>',
    ));
}

// Chèn Bootstrap CSS & JS từ CDN
function my_widget_enqueue_bootstrap() {
    wp_enqueue_style('kdiseadev-style', get_stylesheet_uri());

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


if( class_exists( 'WooCommerce' )) {
    add_theme_support('woocommerce');
}



