<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>

	<section class="related-products my-5">
		<h2 class="h4 fw-bold mb-4"><?php esc_html_e( 'Related Products', 'woocommerce' ); ?></h2>
		
		<div class="row g-4">
			<?php foreach ( $related_products as $related_product ) :
				$post_object = get_post( $related_product->get_id() );
				setup_postdata( $GLOBALS['post'] =& $post_object );
			?>
				<div class="col-12 col-sm-6 col-md-4 col-lg-3">
					<?php wc_get_template_part( 'content', 'product' ); ?>
				</div>
			<?php endforeach; ?>
		</div>

	</section>

<?php endif;
wp_reset_postdata();
