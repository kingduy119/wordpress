<?php
/*
Plugin Name: KDI Products
Description: Widget hiển thị danh sách sản phẩm từ WooCommerce
Version: 1.0
Author: Duy Hoang
*/

// Hàm kiểm tra Elementor preview
function is_elementor_preview() {
    return isset($_GET['elementor-preview']) || isset($_GET['action']) && $_GET['action'] === 'elementor';
}
// Chèn Bootstrap CSS & JS từ CDN
function my_widget_enqueue_bootstrap() {
    // Load trong frontend hoặc khi preview Elementor
    if ( !is_admin() || is_elementor_preview() ) {
        wp_enqueue_style(
            'bootstrap-css',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'
        );
        wp_enqueue_script(
            'bootstrap-js',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            [],
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'my_widget_enqueue_bootstrap');

// if( class_exists( 'WooCommerce' )) {
require_once plugin_dir_path(__FILE__) . 'classes/field-base.php';
require_once plugin_dir_path(__FILE__) . 'classes/product-list.php';

// Đăng ký widget
add_action('widgets_init', function () {
    register_widget('Product_List_WG');
});
// }

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