
<?php 
  global $post;
  $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );

  $title_link = sprintf('<a class="text-decoration-none text-dark fw-bold" href="%1$s">%2$s</a>', 
    esc_url( get_permalink() ),
    get_the_title()
  );
?>

<div id="post-<?php the_ID(); ?>" class="post card">
  <div class="row g-0">

    <div class="col-xs-12 col-sm-12 col-md-4">
    
      <a href="<?php the_permalink(); ?>">
        <div class="image-box">
          <div class="image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>')" ></div>
        </div>
      </a>
      
    </div> <!-- col -->

    <div class="col-xs-12 col-sm-12 col-md-8">
        <div class="card-body pt-0">

        <h5 class="post-title card-title"><?php echo $title_link; ?></h5>
        <div class="post-meta d-flex flex-start gap-2">
          <?php
            kdi_post_part( 'loop/author' );
            kdi_post_part( 'loop/date' );
          ?>
        </div>

        <p class="post-excerpt card-text" ><?php echo get_the_excerpt($post); ?></p>
          
        </div> <!-- card-body -->
    </div> <!-- col -->
    
  </div> <!-- row -->
</div>