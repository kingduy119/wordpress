<?php

/**
 * Single variation cart button
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action('woocommerce_before_add_to_cart_button'); ?>

	<?php
	// do_action('woocommerce_before_add_to_cart_quantity');

	// woocommerce_quantity_input(
	// 	array(
	// 		'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
	// 		'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
	// 		'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
	// 	)
	// );

	// do_action('woocommerce_after_add_to_cart_quantity');
	?>


	<div class="custom-quantity d-inline-flex align-items-center border rounded overflow-hidden">
		<button type="button" class="btn btn-outline-secondary btn-minus px-3">−</button>
		<input
			type="text"
			name="quantity"
			value="1"
			class="custom-qty-input text-center border-0"
			min="1"
			style="width: 60px;" />
		<button type="button" class="btn btn-outline-secondary btn-plus px-3">+</button>
	</div>

	<button
		type="submit"
		name="add-to-cart"
		value="<?php echo esc_attr($product->get_id()); ?>"
		class="single_add_to_cart_button btn btn-primary"
		style="min-width: 150px; max-width: 250px;">
		<?php echo esc_html($product->single_add_to_cart_text()); ?></button>

	<?php do_action('woocommerce_after_add_to_cart_button'); ?>

	<?php do_action('woocommerce_after_add_to_cart_button'); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const qtyInput = document.querySelector('.custom-qty-input');
			const btnPlus = document.querySelector('.btn-plus');
			const btnMinus = document.querySelector('.btn-minus');

			btnPlus.addEventListener('click', () => {
				let current = parseInt(qtyInput.value) || 1;
				qtyInput.value = current + 1;
			});

			btnMinus.addEventListener('click', () => {
				let current = parseInt(qtyInput.value) || 1;
				if (current > 1) {
					qtyInput.value = current - 1;
				}
			});
		});
	</script>
</div>