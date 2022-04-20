
<?php 
/**
 *
 * Template Name: Fullwidth
 *
 * @package KDI Common
 */

get_header(); ?>

<div class="container">
        
    <?php
    while ( have_posts() ) : the_post();
    
        do_action( 'kdi_page_before' );

        get_template_part('content', 'page');

        do_action( 'kdi_page_after' );

    endwhile; 
    ?>
    
</div>

<?php get_footer(); ?>