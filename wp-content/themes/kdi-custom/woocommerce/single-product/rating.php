<?php
/**
 * Single Product Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

$star_rating = '<div class="star-rating"><span class="stars-fill" style="width:' . ( ( $average / 5 ) * 100 ) . '%">' . esc_html( $average ) . '</span></div>';

if ( $rating_count > 0 ) : ?>

	<div class="single-product__rating">
		<?php echo $star_rating . '<span>(' . $review_count .')</span>'; // Hiển thị đánh giá sao ?>
		
	</div>

<?php endif; ?>
