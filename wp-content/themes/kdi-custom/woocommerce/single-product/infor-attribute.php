<?php
global $product;

$attributes = $product->get_attributes();

global $product;

		if ( $product instanceof WC_Product ) {
			/**
			 * Single product add to cart action.
			 *
			 * @since 1.0.0
			 */
      echo $product->get_type();
			do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
		}
