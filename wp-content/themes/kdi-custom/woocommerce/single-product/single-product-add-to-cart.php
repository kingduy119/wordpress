<?php
global $product;

$attributes = $product->get_attributes();

global $product;

if ($product instanceof WC_Product) {

	if ("variable" == $product->get_type()) {
		if (! ($product instanceof WC_Product)) {
			return;
		}
		// Enqueue variation scripts.
		// wp_enqueue_script('wc-add-to-cart-variation');
		do_action('woocommerce_variable_add_to_cart');
	} else {
		wc_get_template('single-product/add-to-cart/simple.php');
	}
}
