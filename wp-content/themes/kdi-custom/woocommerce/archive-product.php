<?php
defined( 'ABSPATH' ) || exit;

get_header();

?>
<div class="container my-5">
  <h1 class="mb-4"><?php woocommerce_page_title(); ?></h1>

  <?php if ( woocommerce_product_loop() ) : ?>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-4">
      <?php while ( have_posts() ) : the_post(); ?>
        <div class="col">
          <?php wc_get_template_part( 'content', 'product' ); ?>
        </div>
      <?php endwhile; ?>
    </div>

    <div class="mt-4">
      <?php woocommerce_pagination(); ?>
    </div>

  <?php else : ?>
    <?php do_action( 'woocommerce_no_products_found' ); ?>
  <?php endif; ?>
</div>

<?php
get_footer();
