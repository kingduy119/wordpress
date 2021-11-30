<?php get_header() ?>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Carousel lastest posts -->
            <?php hd_home_carouse_category('carousel-home-page', 3); ?>
            
            <!-- Nested row for non-featured blog posts-->
            <?php hd_home_page_category_lastest('doi-song', 'Đời Sống', 4); ?>
            <?php hd_home_page_category_lastest('xa-hoi', 'Xã hội', 4); ?>

            </div>

            <div class="col-lg-4">
                <?php get_sidebar() ?>
            </div>
    </div>
    <!-- row -->
</div>
<!-- container -->

<?php get_footer() ?>