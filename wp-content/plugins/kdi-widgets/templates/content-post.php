<div class="h-100 border rounded p-2 post-card">
  <a href="<?php the_permalink(); ?>" class="post-thumb">
    <?php if (has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('medium', ['class' => 'img-fluid mb-2']); ?>
    <?php endif; ?>
  </a>

  <h6 class="fw-bold mb-1 post-title">
    <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
      <?php the_title(); ?>
    </a>
  </h6>

  <div class="text-muted mb-2 post-excerpt">
    <?php echo wp_trim_words(get_the_excerpt(), 30, ''); ?>
  </div>
</div>