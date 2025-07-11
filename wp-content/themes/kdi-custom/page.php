<?php
/**
 * Template Name: Page Default
 * Description: A default page template without sidebar.
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
    ?>
    <div class="container">
        <?php the_content(); ?>
    </div>
    <?php
    endwhile;
endif;

get_footer();
?>
