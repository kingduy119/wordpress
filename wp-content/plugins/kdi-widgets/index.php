<?php
/**
 * Plugin Name: KDI Widgets
 * Plugin URI: 
 * Description: Plugin for KDI
 * Version: 1.0
 * Author: Duy Hoang
 * Author URI:
 * License: later
 */

if( ! defined( 'KDI_WG_DIR_PATH' ) ) {
    define( 'KDI_WG_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

function kdi_get_template_part( $file = '', $args = array() ) {
    extract( $args );
    include KDI_WG_DIR_PATH . $file;
}

// ############################################
require_once plugin_dir_path( __FILE__ ) . 'includes/abstract/class-wg-field.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wg-slider.php';

function wg_load_css() {
    wp_enqueue_style( 'kdi-plugin', plugins_url( 'assets/css/style.css', __FILE__ ) );
    
    wp_enqueue_script( 'kdi-slider', plugins_url( 'assets/js/slider1.js', __FILE__ ), array('jquery'), '1.1' );
    wp_enqueue_script( 'kdi-carousel', plugins_url( 'assets/js/carousel.js', __FILE__ ), '', '1.1' );
    wp_enqueue_script( 'kdi-gallery', plugins_url( 'assets/js/gallery.js', __FILE__ ), '', '1.1' );
}

add_action( 'wp_enqueue_scripts', 'wg_load_css' );
add_action( 'widgets_init', function() {
    register_widget('KDI_WG_Slider');
} );



// #############################################
// Woocommerce Support
// #############################################
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    // Ví dụ thêm nội dung sau tiêu đề sản phẩm
    add_action( 'woocommerce_single_product_summary', 'custom_info_after_title', 6 );
    function custom_info_after_title() {
        echo '<p style="color: #ff6600;">🔥 Ưu đãi đặc biệt hôm nay!</p>';
    }

    // Ví dụ ẩn short description
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

    // Ví dụ thêm một khối nội dung mới ở cuối
    add_action( 'woocommerce_after_single_product_summary', 'custom_extra_section', 15 );
    function custom_extra_section() {
        echo '<div style="border-top: 1px solid #ccc; padding-top: 10px;">';
        echo '<h3>Thông tin vận chuyển</h3>';
        echo '<p>Giao hàng miễn phí toàn quốc trong 3 ngày.</p>';
        echo '</div>';
    }
}

// Custom product details widget
// require_once plugin_dir_path( __FILE__ ) . 'includes/class-wg-product-details.php';
// add_action( 'widgets_init', function() {
//     register_widget('KDI_WG_Product_Details');
// } );

// // Custom product gallery widget
// require_once plugin_dir_path( __FILE__ ) . 'includes/class-wg-product-gallery.php';
// add_action( 'widgets_init', function() {
//     register_widget('KDI_WG_Product_Gallery');
// } );    

// // Custom product carousel widget
// require_once plugin_dir_path( __FILE__ ) . 'includes/class-wg-product-carousel.php';
// add_action( 'widgets_init', function() {
//     register_widget('KDI_WG_Product_Carousel');
// } );