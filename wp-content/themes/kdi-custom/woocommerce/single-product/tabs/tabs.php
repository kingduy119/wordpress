<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper">
		<ul 
		role="tablist"
		class="nav nav-tabs mt-4" 
		>
		<?php $first = true; ?>
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<li 
				role="presentation" 
				class="nav-item" 
				id="tab-title-<?php echo esc_attr( $key ); ?>"
				>
					<a 
					role="tab" 
					class="nav-link <?php if ( $first ) echo 'active'; ?>"
					href="#tab-<?php echo esc_attr( $key ); ?>" 
					aria-controls="tab-<?php echo esc_attr( $key ); ?>"
					data-bs-toggle="tab"
					>
						<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
					</a>
				</li>
				<?php $first = false; ?>
			<?php endforeach; ?>
		</ul>
		<div class="tab-content p-3 border border-top-0">
			<?php $first = true; ?>
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<div 
					class="tab-pane fade <?php if ( $first ) echo 'show active'; ?>" 
					id="tab-<?php echo esc_attr( $key ); ?>" 
					role="tabpanel" 
					aria-labelledby="tab-<?php echo esc_attr( $key ); ?>-tab"
				>
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
				</div>
				<?php $first = false; ?>
			<?php endforeach; ?>
		</div>
		

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>
