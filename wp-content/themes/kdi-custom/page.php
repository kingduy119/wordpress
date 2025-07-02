<?php
get_header();
if (have_posts()) :
    while (have_posts()) : the_post();
?>
    <div class="container">
        <div class="row">
            <div class="col-9">
                <?php the_content(); ?>
            </div>
            <div class="col-3">
                <?php
                // Display the mini cart or sidebar
                if (is_active_sidebar('sidebar-product')) {
                    dynamic_sidebar('sidebar-product');
                }
                ?>
            </div>
        </div>
    </div>
<?php 
    endwhile;
endif;
get_footer(); ?>