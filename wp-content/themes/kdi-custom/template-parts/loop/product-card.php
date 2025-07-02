<div class="card">
    <?php if (has_post_thumbnail()) : ?>
        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
    <?php else : ?>
        <img src="<?php echo wc_placeholder_img_src(); ?>" class="card-img-top" alt="Placeholder">
    <?php endif; ?>
    <div class="card-body">
        <h5 class="card-title"><?php the_title(); ?></h5>
        <p class="card-text">
        <?php echo wp_trim_words(get_the_excerpt(), 20); ?><br>
        <strong><?php echo wc_price(get_post_meta(get_the_ID(), '_price', true)); ?></strong>
        </p>
        <a href="<?php the_permalink(); ?>" class="btn btn-primary">Xem sản phẩm</a>

        <?php
        global $product;
        $product = wc_get_product(get_the_ID());

        if ($product && $product->is_purchasable() && $product->is_in_stock()) {
            wc_get_template('loop/add-to-cart.php');
        }
        ?>
    </div>
</div>