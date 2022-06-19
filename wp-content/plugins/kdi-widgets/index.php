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


