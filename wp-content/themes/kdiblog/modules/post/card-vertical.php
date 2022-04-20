<?php
  global $post;
  $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
  
  $categories = get_the_category( $post->ID );
  // $category_link = sprintf('<a href="%1$s">%2$s</a>',
  //   get_term_link( $categories[0]->slug, 'category' ),
  //   sprintf('<span class="badge bg-primary %1$s">%2$s</span>', $categories[0]->slug, $categories[0]->name),
  // );

  $title_link = sprintf('<a class="text-decoration-none text-dark fw-bold" href="%1$s">%2$s</a>', 
    esc_url( get_permalink() ),
    get_the_title()
  );

?>

<div id="post-<?php the_ID(); ?>" class="post card">

  <div class="post-thumbnail image-box">
    <div class="image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>')" ></div>
    <div class="overlay"></div>
    <div class="post-category"><?php //echo $category_link; ?></div>
  </div>


  <div class="card-body">

    <h5 class="post-title card-title"><?php echo $title_link; ?></h5>
    <div class="post-meta d-flex flex-start gap-2">
      <?php
        kdi_post_part( 'loop/author' );
        kdi_post_part( 'loop/date' );
      
      ?>
    </div>
    <p class="post-excerpt card-text" ><?php echo get_the_excerpt($post); ?></p>

  </div> <!-- card-body -->
</div>
