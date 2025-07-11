<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-9">
            <?php get_template_part('template-parts/content-home'); ?>
        </div>

        <div class="col-3 text-bg-warning">
            <?php //wc_get_template('cart/mini-cart.php'); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
