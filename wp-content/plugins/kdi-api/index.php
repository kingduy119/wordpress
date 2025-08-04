<?php

/**
 * Plugin Name: KDI Custom API
 * Description: REST API endpoints for WooCommerce products and categories
 * Version: 1.0
 * Author: Duy Hoang
 */
define('API_URL', 'kdi/v1');

require_once plugin_dir_path(__FILE__) . 'post.php';
require_once plugin_dir_path(__FILE__) . 'post-list.php';
require_once plugin_dir_path(__FILE__) . 'post-id.php';
require_once plugin_dir_path(__FILE__) . 'post-category.php';
require_once plugin_dir_path(__FILE__) . 'product.php';
require_once plugin_dir_path(__FILE__) . 'product-category.php';

// Allow application passwords to get api access
if (function_exists('wp_is_application_passwords_available')) {
    add_filter('wp_is_application_passwords_available', '__return_true');
}
