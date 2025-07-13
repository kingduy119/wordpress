<?php
/*
Plugin Name: KDI Products
Description: Widget hiển thị danh sách sản phẩm từ WooCommerce
Version: 1.0
Author: Duy Hoang
*/


// Chèn Bootstrap CSS & JS từ CDN
if ( ! function_exists('kdi_product_widget_enqueue_bootstrap') ) {
    function is_elementor_preview() {
        return isset($_GET['elementor-preview']) || isset($_GET['action']) && $_GET['action'] === 'elementor';
    }

    function kdi_product_widget_enqueue_bootstrap() {
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
    add_action('wp_enqueue_scripts', 'kdi_product_widget_enqueue_bootstrap');
}


// if( class_exists( 'WooCommerce' )) {
if ( ! class_exists( 'FieldBase_WG' ) ) {
    require_once plugin_dir_path(__FILE__) . 'classes/field-base.php';
}
if ( ! class_exists( 'Product_List_WG' ) ) {
    require_once plugin_dir_path(__FILE__) . 'classes/product-list.php';
}

// Đăng ký widget
add_action('widgets_init', function () {
    register_widget('Product_List_WG');
});
// }

