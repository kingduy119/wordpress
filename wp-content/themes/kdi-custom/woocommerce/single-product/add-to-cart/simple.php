<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
		
		<div class="custom-quantity d-inline-flex align-items-center border rounded overflow-hidden">
			<button type="button" class="btn btn-outline-secondary btn-minus px-3">−</button>
			<input
				type="text"
				name="quantity"
				value="1"
				class="custom-qty-input text-center border-0"
				min="1"
				style="width: 60px;"
			/>
			<button type="button" class="btn btn-outline-secondary btn-plus px-3">+</button>
		</div>

		<?php
		// do_action( 'woocommerce_before_add_to_cart_quantity' );

		// woocommerce_quantity_input(
		// 	array(
		// 		'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
		// 		'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
		// 		'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		// 	)
		// );

		// do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>

		<button 
			type="submit" 
			name="add-to-cart" 
			value="<?php echo esc_attr( $product->get_id() ); ?>" 
			class="single_add_to_cart_button btn btn-primary"
			style="min-width: 150px; max-width: 250px;"
			>
		<?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>


	

	<script>
		document.addEventListener('DOMContentLoaded', function () {
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


<?php endif; ?>
