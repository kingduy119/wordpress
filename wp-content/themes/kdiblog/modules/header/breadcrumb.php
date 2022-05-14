
<?php if( is_archive() || is_single() ) : ?>
<div class="container">

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb m-0 py-2">

        <li class="breadcrumb-item"><a href="<?php echo home_url('/'); ?>">Home</a></li>

        <?php 
        $cats = wp_get_post_terms( get_the_ID(), 'category');
        if( $cats ) : foreach($cats as $cat) : 
        ?>
          <li class="breadcrumb-item">
            <a href="<?php echo esc_url( get_term_link( $cat->term_id, 'category' ) ); ?>">
              <?php echo $cat->name; ?>
            </a>
          </li>
        <?php 
        endforeach; endif;

          if( is_single() ) {
            the_title('<li class="breadcrumb-item active" aria-current="page">', '</li>');
          }
        ?>

      </ol>
    </nav>

</div>
<?php endif; ?>