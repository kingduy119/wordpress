<?php

/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

defined('ABSPATH') || exit;

global $product;

$attribute_keys  = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);

do_action('woocommerce_before_add_to_cart_form'); ?>

<style>
	table {
		display: none;
	}

	.variation-badge {
		display: inline-block;
		margin: 3px;
		cursor: pointer;
		user-select: none;
	}

	.variation-badge span {
		display: inline-block;
		padding: 6px 12px;
		border: 1px solid #ccc;
		border-radius: 20px;
		background-color: #f8f8f8;
		color: #333;
		transition: all 0.2s ease;
	}

	.variation-options .label {
		font-weight: 500;
		text-transform: capitalize;
		margin-bottom: 0.3rem;
		font-size: 0.9rem;
	}

	.variation-badge.active span,
	.variation-badge input[type="radio"]:checked+span {
		border-color: #0071a1;
		background-color: #0071a1;
		color: white;
		font-weight: bold;
		box-shadow: 0 0 0 2px rgba(0, 113, 161, 0.4);
	}
</style>

<form
	class="variations_form cart"
	action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
	method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>"
	data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. 
								?>">
	<?php do_action('woocommerce_before_variations_form'); ?>

	<?php if (empty($available_variations) && false !== $available_variations) : ?>
		<p class="stock out-of-stock"><?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))); ?></p>
	<?php else : ?>
		<!--  -->
		<?php foreach ($attributes as $attribute_name => $options) : ?>
			<div class="variation-options variation-<?php echo esc_attr($attribute_name); ?>">
				<p class="label"><?php echo wc_attribute_label($attribute_name); ?>:</p>

				<?php foreach ($options as $option): ?>
					<?php
					$name = 'attribute_' . sanitize_title($attribute_name);
					$id = $name . '_' . sanitize_title($option);
					$checked = isset($_REQUEST[$name]) && wc_clean(urldecode($_REQUEST[$name])) === $option;
					?>
					<label for="<?php echo esc_attr($id); ?>" class="variation-badge <?php echo $checked ? 'active' : ''; ?>">
						<input
							type="radio"
							name="<?php echo esc_attr($name); ?>"
							value="<?php echo esc_attr($option); ?>"
							id="<?php echo esc_attr($id); ?>"
							hidden />
						<span><?php echo esc_html($option); ?></span>
					</label>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
		<!--  -->
		<table class="variations" cellspacing="0" role="presentation">
			<tbody>
				<?php foreach ($attributes as $attribute_name => $options) : ?>
					<tr>
						<th class="label"><label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"><?php echo wc_attribute_label($attribute_name); // WPCS: XSS ok. 
																												?></label></th>
						<td class="value">
							<?php
							wc_dropdown_variation_attribute_options(
								array(
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
								)
							);
							echo end($attribute_keys) === $attribute_name ? wp_kses_post(apply_filters('woocommerce_reset_variations_link', '<a class="reset_variations" href="#" aria-label="' . esc_attr__('Clear options', 'woocommerce') . '">' . esc_html__('Clear', 'woocommerce') . '</a>')) : '';
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<!--  -->

		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
		<?php do_action('woocommerce_after_variations_table'); ?>

		<div class="single_variation_wrap">
			<?php
			/**
			 * Hook: woocommerce_before_single_variation.
			 */
			do_action('woocommerce_before_single_variation');

			/**
			 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
			 *
			 * @since 2.4.0
			 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
			 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
			 */
			do_action('woocommerce_single_variation');

			/**
			 * Hook: woocommerce_after_single_variation.
			 */
			do_action('woocommerce_after_single_variation');
			?>
		</div>
	<?php endif; ?>

	<?php do_action('woocommerce_after_variations_form'); ?>
</form>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const form = document.querySelector(".variations_form");

		// Tìm tất cả radio inputs
		form.querySelectorAll("input[type=radio][name^='attribute_']").forEach(function(radio) {
			radio.addEventListener("change", function() {
				const name = radio.name;
				const value = radio.value;

				// Cập nhật <select> tương ứng
				const select = form.querySelector(`select[name="${name}"]`);
				if (select) {
					select.value = value;
					select.dispatchEvent(new Event("change", {
						bubbles: true
					}));
				}

				// Active class cho badge
				const badges = form.querySelectorAll(`input[name="${name}"]`);
				badges.forEach(function(input) {
					input.closest("label").classList.remove("active");
				});
				radio.closest("label").classList.add("active");
			});
		});
	});
</script>

<?php
do_action('woocommerce_after_add_to_cart_form');
