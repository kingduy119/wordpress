<?php
if (function_exists('register_sidebar')) {
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
// require_once dirname( __FILE__ ) . '/widgets/abstract/fields-widget.php';
// require_once dirname( __FILE__ ) . '/widgets/post-widget.php';

// if( ! class_exists( 'KDI_Post' ) ):
// final class KDI_Post {
//     public function __construct() {
//         add_action( 'init', array( $this, 'init' ) );
//         add_action( 'widgets_init', array( $this, 'widgets_init' ) );
//         add_action( 'wp_enqueue_scripts', array( $this, 'load_css_script' ) );
//     }

//     public function init() {
//         // remove_theme_support('widgets-block-editor');
//         add_theme_support( 'custom-logo');
//         add_theme_support( 'post-thumbnails' );
    
//         // menus:
//         register_nav_menu('main-menu', __('Main menu') );
    
//         // widget:
//         if (function_exists('register_sidebar')) {
//             register_sidebar(array(
//                 'name'              => 'Page header',
//                 'id'                => 'header',
//                 'before_sidebar'    => '<header id="page-header" class="fixed-top bg-light">',
//                 'after_sidebar'     => '</header>',
//                 'before_widget'     => '<div id="%1$s" class="page-header-widget">',
//                 'after_widget'      => '</div>',
//             ));
    
//             register_sidebar(array(
//                 'name'              => 'Sidebar',
//                 'id'                => 'sidebar',
//                 'before_sidebar'    => '<div id="page-sidebar" class="container">',
//                 'after_sidebar'     => '</div>',
//                 'before_widget'     => '<div id="%1$s" class="sidebar-wg">',
//                 'after_widget'      => '</div>',
//             ));
//             register_sidebar(array(
//                 'name'              => 'Sidebar product',
//                 'id'                => 'sidebar-product',
//                 'before_sidebar'    => '<div id="page-product-sidebar" class="container">',
//                 'after_sidebar'     => '</div>',
//                 'before_widget'     => '<div id="%1$s" class="sidebar-product-wg">',
//                 'after_widget'      => '</div>',
//             ));
//             register_sidebar(array(
//                 'name'              => 'Sidebar product catalog',
//                 'id'                => 'sidebar-product-catalog',
//                 'before_sidebar'    => '<div id="page-product-sidebar-catalog" class="container">',
//                 'after_sidebar'     => '</div>',
//                 'before_widget'     => '<div id="%1$s" class="sidebar-product-wg">',
//                 'after_widget'      => '</div>',
//             ));
    
//             register_sidebar(array(
//                 'name'              => 'Page footer',
//                 'id'                => 'page-footer',
//                 'before_sidebar'    => '<div id="page-footer">',
//                 'after_sidebar'     => '</div>',
//                 'before_widget'     => '<div id="%1$s">',
//                 'after_widget'      => '</div>',
//             ));
//         }
//     }

//     public function widgets_init() {
//         unregister_widget('WP_Widget_Pages');
//         unregister_widget('WP_Widget_Calendar');
//         unregister_widget('WP_Widget_Archives');
//         unregister_widget('WP_Widget_Links');
//         unregister_widget('WP_Widget_Meta');
//         unregister_widget('WP_Widget_Search');
//         unregister_widget('WP_Widget_Categories');
//         unregister_widget('WP_Widget_Recent_Posts');
//         // unregister_widget('WP_Widget_Recent_Comments');
//         unregister_widget('WP_Widget_RSS');
//         unregister_widget('WP_Widget_Tag_Cloud');
//         unregister_widget('WP_Nav_Menu_Widget');
    
//         register_widget('KDI_Widget_Post');
//     }

//     public function load_css_script() {
//         $styles = array(
//             assets( 'css/reset.css' ),
//             assets( 'css/main.css' ),
//             assets( 'css/templates/card-overlay.css' ),
//             assets( 'css/wordpress-custom.css' ),
//             assets( 'lib/bootstrap-5/dist/css/bootstrap.css' ),
//             'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css',
//             'https://fonts.googleapis.com/css2?family=Roboto+Serif:wght@500&display=swap',
//         );
//         foreach( $styles as $key => $link ) {
//             wp_enqueue_style( "kdi-style-{$key}", $link );
//         }

//         $scripts = array(
//             assets('js/main1.js'),
//             assets('lib/bootstrap-5/dist/js/bootstrap.js'),
//         );
//         foreach( $scripts as $key => $link ) {
//             wp_enqueue_script( "kdi-script-{$key}", $link );
//         }

//         global $wp_query;
//         wp_localize_script( 'kdi-script-0', 'kdi_ajax', [
//             'ajax_url'      => admin_url( 'admin-ajax.php' ),
//             'query_vars'    => json_encode( $wp_query->query ),
//         ] );
//     }

    

// }

// new KDI_Post();
// endif;