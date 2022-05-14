<?php
require_once dirname( __FILE__ ) . '/widgets/product-widget.php';
require_once dirname( __FILE__ ) . '/widgets/product-filter-widget.php';

if( ! class_exists( 'KDI_Product' ) ):
class KDI_Product {
    public function __construct() {
        add_action( 'init', array( $this, 'init') );
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
        // add_action( 'wp_enqueue_scripts', array( $this, 'load_css_script' ) );
    }

    public function init() {
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-slider' );
        add_theme_support( 'wc-product-gallery-lightbox' );
    }

    public function widgets_init() {
        unregister_widget( 'WC_Widget_Product_Categories' );
        unregister_widget( 'WC_Widget_Product_Tag_Cloud' );
        unregister_widget( 'WC_Widget_Layered_Nav' );
        unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
        unregister_widget( 'WC_Widget_Price_Filter' );
        unregister_widget( 'WC_Widget_Product_Search' );
        unregister_widget( 'WC_Widget_Top_Rated_Products' );
        unregister_widget( 'WC_Widget_Recent_Reviews' );
        unregister_widget( 'WC_Widget_Recently_Viewed' );
        unregister_widget( 'WC_Widget_Product_Categories' );
        unregister_widget( 'WC_Widget_Products' );
        // unregister_widget( 'WC_Widget_Cart' );

        register_widget('KDI_Product_Widget');
        register_widget('KDI_Product_Filter_Widget');
    }
}
new KDI_Product();
endif;