
<?php 
  global $post;
  $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );

  $categories = get_the_category( $post->ID );
  $category = sprintf('<a href="%1$s">%2$s</a>',
    get_term_link( $categories[0]->slug, 'category' ),
    sprintf('<span class="badge bg-primary %1$s">%2$s</span>', $categories[0]->slug, $categories[0]->name),
  );

  $title_link = sprintf('<a class="text-decoration-none text-light" href="%1$s"><strong>%2$s</strong></a>', 
    esc_url( get_permalink() ),
    get_the_title(),
  );
?>

<div id="post-<?php the_ID(); ?>" class="post card">
  <div class="image-box">
    <div class="image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>')" ></div>
    <div class="overlay"></div>
  </div>

  <div class="card-img-overlay d-flex align-items-end">
    <div class="container">
      <?php echo $category; ?>
      <h5 class="post-title text-hidden-line line-3"><?php echo $title_link; ?></h5>
    </div>
  </div>
</div>