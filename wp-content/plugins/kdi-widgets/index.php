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

// #############################################
// 1. Init
// #############################################
if (! defined('KDI_WG_DIR_PATH')) {
    define('KDI_WG_DIR_PATH', plugin_dir_path(__FILE__));
}

function kdi_get_template_part($file = '', $args = array())
{
    extract($args);
    include KDI_WG_DIR_PATH . $file;
}

// #############################################
// 2. Import
// #############################################
require_once plugin_dir_path(__FILE__) . 'includes/abstract/class-wg-field.php';
require_once plugin_dir_path(__FILE__) . 'includes/post-wg.php';
require_once plugin_dir_path(__FILE__) . 'includes/slider-wg.php';

// Chèn Bootstrap CSS & JS từ CDN
if (! function_exists('kdi_product_widget_enqueue_bootstrap')) {
    function is_elementor_preview()
    {
        return isset($_GET['elementor-preview']) || isset($_GET['action']) && $_GET['action'] === 'elementor';
    }

    function kdi_product_widget_enqueue_bootstrap()
    {
        // Load trong frontend hoặc khi preview Elementor
        if (!is_admin() || is_elementor_preview()) {
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

if (! function_exists('wg_load_css')) {
    function wg_load_css()
    {
        wp_enqueue_style('kdi-plugin', plugins_url('assets/css/style.css', __FILE__));

        // wp_enqueue_script('kdi-slider', plugins_url('assets/js/slider1.js', __FILE__), array('jquery'), '1.1');
        // wp_enqueue_script('kdi-carousel', plugins_url('assets/js/carousel.js', __FILE__), '', '1.1');
        // wp_enqueue_script('kdi-gallery', plugins_url('assets/js/gallery.js', __FILE__), '', '1.1');
    }
    add_action('wp_enqueue_scripts', 'wg_load_css');

    function kdi_register_widgets()
    {
        register_widget('KDI_WG_Slider');
        register_widget('KDI_WG_Posts');
    }
    add_action('widgets_init', 'kdi_register_widgets');
}

// #############################################
// 3. Helper
// #############################################
if (! function_exists('kdi_widget_get_template')) {
    function kdi_widget_get_template($template, $args = array())
    {
        $theme_file = locate_template('kdi-widget/' . $template . '.php');

        if ($theme_file) {
            $file = $theme_file;
        } else {
            $file = plugin_dir_path(__FILE__) . 'templates/' . $template . '.php';
        }

        if (! empty($args) && is_array($args)) {
            extract($args);
        }

        include $file;
    }
}


// #############################################
// Woocommerce Support
// #############################################
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    // Ví dụ thêm nội dung sau tiêu đề sản phẩm
    add_action('woocommerce_single_product_summary', 'custom_info_after_title', 6);
    function custom_info_after_title()
    {
        echo '<p style="color: #ff6600;">🔥 Ưu đãi đặc biệt hôm nay!</p>';
    }

    // Ví dụ ẩn short description
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

    // Ví dụ thêm một khối nội dung mới ở cuối
    add_action('woocommerce_after_single_product_summary', 'custom_extra_section', 15);
    function custom_extra_section()
    {
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