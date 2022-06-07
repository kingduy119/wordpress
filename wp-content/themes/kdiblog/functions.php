<?php



require_once dirname( __FILE__ ) . '/modules/kdi-post.php';
require_once dirname( __FILE__ ) . '/modules/kdi-post-hook.php';
require_once dirname( __FILE__ ) . '/modules/kdi-post-function.php';
require_once dirname( __FILE__ ) . '/modules/kdi-post-shortcode.php';

require_once dirname( __FILE__ ) . '/modules/admin/settings.php';

if( kdi_woo_is_actived() ) {
    require_once dirname( __FILE__ ) . '/modules/kdi-product.php';
    require_once dirname( __FILE__ ) . '/modules/kdi-product-hook.php';
    require_once dirname( __FILE__ ) . '/modules/kdi-product-function.php';
}



add_filter( 'big_image_size_threshold', '__return_false' );