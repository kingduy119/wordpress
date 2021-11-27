<?php
/*
Template Name: Archives
*/
?>
<?php get_header() ?>

<!-- Page content-->
<div class="container">
    <div class="row">
        <!-- Blog entries-->
        <div class="col-lg-8">
            <h1 class="my-2 mb-4 page-header">
                <?php the_title() ?>
            </h1>

            <div id="custom_html-4" class="widget_text card my-4 widget_custom_html">
                <h5 class="card-header">Theo tháng:</h5>
                <div class="card-body">
                    <?php wp_get_archives('type=monthly'); ?>
                </div>
            </div>

            <div id="custom_html-4" class="widget_text card my-4 widget_custom_html">
                <h5 class="card-header">Theo chuyên mục:</h5>
                <div class="card-body">
                    <?php wp_list_categories(); ?>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            <?php get_sidebar() ?>
        </div>
    </div>
    <!-- row -->
</div>
<!-- container -->

<?php get_footer() ?>