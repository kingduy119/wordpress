<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
?>

<article class="product-card card h-100">
    <a href="<?php the_permalink(); ?>" class="product-card__link text-decoration-none ratio ratio-1x1">
      <div class="product-card__image-wrapper postiion-relative">
        <?php woocommerce_show_product_loop_sale_flash(); ?>
        <?php  echo $product->get_image( 'medium', [ 'class' => 'product-card__image w-100 h-100 object-fit-cover' ] ); ?>
        </div>
    </a>
    <div class="product-card__body card-body">
      <h5 class="product-card__title card-title fs-5">
        <?php the_title(); ?>
      </h5>

      <?php if ( $product->is_on_sale() ) : ?>
        <span class="product-card__price--regular text-muted text-decoration-line-through me-2 fs-6">
          <?php echo wc_price( $product->get_regular_price() ); ?>
        </span>
        <span class="product-card__price--sale text-danger fw-bold fs-5">
          <?php echo wc_price( $product->get_sale_price() ); ?>
        </span>
      <?php else : ?>
        <span class="product-card__price text-dark fs-5">
          <?php echo wc_price( $product->get_regular_price() ); ?>
        </span>
      <?php endif; ?>
    </div>

</article>
