<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.7.0
 */

use Automattic\WooCommerce\Enums\ProductType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="product_meta">


	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
		<div class="mb-2">
			<strong class="me-2">SKU:</strong>
			<span class="text-muted"><?php echo $product->get_sku() ?: esc_html__( 'N/A', 'woocommerce' ); ?></span>
		</div>
	<?php endif; ?>

	<?php
	$product_cats = wp_get_post_terms( $product->get_id(), 'product_cat' );
	if ( ! empty( $product_cats ) ) : ?>
		<div class="mb-2">
			<strong class="me-2"><?php _e('Category:', 'kdiseadev'); ?></strong>
			<?php foreach ( $product_cats as $cat ) : ?>
				<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="badge bg-primary text-decoration-none me-1">
					<?php echo esc_html( $cat->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php
	$product_tags = wp_get_post_terms( $product->get_id(), 'product_tag' );
	if ( ! empty( $product_tags ) ) : ?>
		<div class="mb-2">
			<strong class="me-2"><?php _e('Tag:', 'kdiseadev'); ?></strong>
			<?php foreach ( $product_tags as $tag ) : ?>
				<a href="<?php echo esc_url( get_term_link( $tag ) ); ?>" class="badge bg-secondary text-decoration-none me-1">
					<?php echo esc_html( $tag->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php
	$product_brands = wp_get_post_terms( $product->get_id(), 'product_brand' );
	if ( ! empty( $product_brands ) ) : ?>
		<div class="mb-2">
			<strong class="me-2"><?php _e('Brand:', 'kdiseadev'); ?></strong>
			<?php foreach ( $product_brands as $brand ) : ?>
				<a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" class="badge bg-warning text-decoration-none me-1">
					<?php echo esc_html( $brand->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>


</div>
