<div class="h-100 border rounded p-2">
  <a href="<?php echo get_permalink($product->get_id()); ?>">
    <?php echo $product->get_image('woocommerce_thumbnail', ['class' => 'img-fluid mb-2']); ?>
  </a>

  <h6 class="fw-bold mb-1">
    <a href="<?php echo get_permalink($product->get_id()); ?>" class="text-decoration-none text-dark">
      <?php echo $product->get_name(); ?>
    </a>
  </h6>

  <div class="text-primary mb-2">
    <?php echo $product->get_price_html(); ?>
  </div>

  <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
     class="btn btn-sm btn-success w-100">
     Thêm vào giỏ
  </a>
</div>




