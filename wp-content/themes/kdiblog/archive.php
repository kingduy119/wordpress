<?php 
global $wp_query;
get_header(); 
?>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-9">
        <?php

            do_action( 'kdi_loop_content' );
                
            the_posts_pagination( array(
                'type'      => 'list',
                'next_text' => _x( 'Next', 'Next post', 'kdi' ),
                'prev_text' => _x( 'Previous', 'Previous post', 'kdi' ),
            ) );
        ?>
        </div>

        <div class="col-md-12 col-lg-3">
            <?php  get_sidebar(); ?>
        </div>
    </div>
</div>
<!-- .container -->

<?php get_footer() ?>