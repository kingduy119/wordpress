<?php get_header() ?>

<!-- Page content-->
<div class="container">
    <div class="row">
        <!-- Blog entries-->
        <div class="col-lg-8">
            <?php
                if( have_posts() ) :
                    while( have_posts() ) : the_post();
                        get_template_part('template-parts/content-single', get_post_format());
                    endwhile;
                endif;
            ?>
        </div>
        
        <div class="col-lg-4">
            <?php get_sidebar() ?>
        </div>
    </div>
    <!-- row -->
</div>
<!-- container -->

<?php get_footer() ?>