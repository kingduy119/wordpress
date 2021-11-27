<?php get_header() ?>

<!-- Page content-->
<div class="container">
    <div class="row">
        <!-- Blog entries-->
        <div class="col-lg-8">
            <h1 class="my-2 mb-4 page-header">
                Tác giả: <small><?php the_author() ?></small>
            </h1>

            <?php
                if( have_posts() ) :
                    while( have_posts() ) : the_post();
                        get_template_part('template-parts/content', get_post_format());
                    endwhile;
                endif;
            ?>
            
            <!-- Pagination-->
            <nav aria-label="Pagination">
                <?php mini_blog_pagination() ?>
            </nav>
        </div>

        <div class="col-lg-4">
            <?php get_sidebar() ?>
        </div>
    </div>
</div>

<?php get_footer() ?>