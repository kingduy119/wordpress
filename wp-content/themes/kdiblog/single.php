
<?php get_header() ?>

<div class="container">
    <div class="row">
        <div class="col-12 col-lg-9">
            <?php
            /**
             * kdi_get_content - 5
             */
            // do_action( 'kdi_content' );
            get_template_part('modules/contents/content-single')
            ?>

        </div>
        <div class="col-12 col-lg-3">
            <?php get_sidebar(); ?>
            
        </div>
    </div>
</div>

<?php get_footer() ?>
