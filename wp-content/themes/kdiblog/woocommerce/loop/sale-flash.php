<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;
$price_sale = $product->get_sale_price();
    $price_current = intval( $product->get_regular_price() );
    if( $product->is_on_sale() && 0 != $price_current ) {
        $percen =  round( 100 - ( $price_sale / $price_current * 100) );
    }

if ( $product->is_on_sale() ) : 
	// echo '<span class="product--on-sale">' . $percen . '%</span>';
?>
	<span class="product--onsale">
		<span><?php echo esc_html( $percen ); ?>%</span>
		<span>OFF</span>
	</span>
<?php
endif;

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
