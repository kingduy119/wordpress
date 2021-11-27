<?php get_header() ?>

<!-- Page content-->
<div class="container">
    <div class="row">
        <!-- Blog entries-->
        <div class="col-lg-8">
            <h1 class="my-2 mb-4 page-header">
                Tìm kiếm:
                <small><?php the_search_query(); ?></small>
            </h1>

            <?php
                if( have_posts() ) :
                    while( have_posts() ) : the_post();
                        get_template_part('template-parts/content', get_post_format());
                    endwhile;
                else :
            ?>
                <p>Không có bài viết nào phù hợp với từ khóa: <strong><?php the_search_query(); ?></strong></p>
            <?php endif; ?>

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