<article id="post-<?php the_ID(); ?>" <?php post_class('card h-100'); ?>>
  <div class="card-body">
    <h5 class="card-title"><?php the_title(); ?></h5>
    <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
    <a href="<?php the_permalink(); ?>" class="btn btn-primary">Xem chi tiết</a>
  </div>
</article>
