<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}
?>

<div id="reviews" class="woocommerce-Reviews">
	<div class="woocommerce-Reviews-title mb-4">
		<h4 class="fw-bold">
			<?php
			$count = $product->get_review_count();
			if ( $count && wc_review_ratings_enabled() ) {
				/* translators: 1: reviews count 2: product name */
				printf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ),
					esc_html( $count ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				esc_html_e( 'Reviews', 'woocommerce' );
			}
			?>
		</h4>
	</div>

	<?php if ( have_comments() ) : ?>
		<ol class="list-unstyled">
			<?php
			wp_list_comments( [
				'callback' => 'woocommerce_comments',
				'style'    => 'ol',
			] );
			?>
		</ol>

		<?php
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			?>
			<nav class="woocommerce-pagination">
				<?php paginate_comments_links(); ?>
			</nav>
		<?php endif; ?>

	<?php else : ?>
		<p class="woocommerce-noreviews text-muted"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
	<?php endif; ?>

	<?php if ( wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) || get_option( 'woocommerce_review_rating_verification_required' ) !== 'yes' ) : ?>

		<div id="review_form_wrapper" class="mt-5">
			<div id="review_form">
				<?php
				$commenter = wp_get_current_commenter();

				$comment_form = [
					'title_reply'         => have_comments() ? __( 'Add a review', 'woocommerce' ) : sprintf( __( 'Be the first to review “%s”', 'woocommerce' ), get_the_title() ),
					'title_reply_before'  => '<h5 id="reply-title" class="comment-reply-title mb-4">',
					'title_reply_after'   => '</h5>',
					'comment_notes_after' => '',
					'label_submit'        => __( 'Submit', 'woocommerce' ),
					'class_submit'        => 'btn btn-primary',

					'comment_field' => '
						<div class="mb-3">
							<label for="comment" class="form-label">' . __( 'Your review', 'woocommerce' ) . '</label>
							<textarea id="comment" name="comment" class="form-control" rows="5" required></textarea>
						</div>',

					'fields' => [
						'author' => '
							<div class="mb-3">
								<label for="author" class="form-label">' . __( 'Name', 'woocommerce' ) . ' <span class="text-danger">*</span></label>
								<input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" required />
							</div>',
						'email'  => '
							<div class="mb-3">
								<label for="email" class="form-label">' . __( 'Email', 'woocommerce' ) . ' <span class="text-danger">*</span></label>
								<input id="email" name="email" type="email" class="form-control" value="' . esc_attr( $commenter['comment_author_email'] ) . '" required />
							</div>',
					],
				];

				if ( wc_review_ratings_enabled() ) {
					$comment_form['comment_field'] = '
					<div class="mb-3">
						<label for="comment" class="form-label">' . __( 'Your review', 'woocommerce' ) . '</label>
						<div class="custom-rating-stars">
							<input type="radio" name="rating" id="rating-5" value="5"><label for="rating-5">★</label>
							<input type="radio" name="rating" id="rating-4" value="4"><label for="rating-4">★</label>
							<input type="radio" name="rating" id="rating-3" value="3"><label for="rating-3">★</label>
							<input type="radio" name="rating" id="rating-2" value="2"><label for="rating-2">★</label>
							<input type="radio" name="rating" id="rating-1" value="1"><label for="rating-1">★</label>
						</div>
						<textarea id="comment" name="comment" class="form-control" rows="5" required></textarea>
					</div>';
				}

				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>

	<?php else : ?>
		<p class="woocommerce-verification-required text-muted"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
	<?php endif; ?>
</div>

<style>
	.comment_container {
		display: flex;
		gap: 1rem;
		margin-bottom: 1rem;
	}

	.comment_container .avatar {
		border-radius: 50%
	}

	.comment-text {
		position: relative;
		border: solid 1px #eaeaea;
		width: 100%;
		padding: 1rem;
	}
	
	.star-rating {
		overflow: hidden;
		position: absolute;
		right: 3%;
		height: 1em;
		line-height: 1;
		font-size: 1em;
		width: 5.4em;
		overflow-x: hidden;
		color: #ffc107;
	}
	.star-rating::before {
		content: "★★★★★";
		opacity: 0.25;
		position: absolute;
		left: 0;
		top: 0;
		color: #EAEAEA
	}

	/* Form Review */
	.custom-rating-stars {
		direction: rtl;
		unicode-bidi: bidi-override;
		font-size: 1.5rem;
		display: inline-flex;
	}

	.custom-rating-stars input {
		display: none;
	}

	.custom-rating-stars label {
		color: #ccc;
		cursor: pointer;
		transition: color 0.2s;
	}

	.custom-rating-stars input:checked ~ label,
	.custom-rating-stars label:hover,
	.custom-rating-stars label:hover ~ label {
		color: #ffc107;
	}

	
</style>

<!-- <option value="">' . __( 'Rate&hellip;', 'woocommerce' ) . '</option> -->