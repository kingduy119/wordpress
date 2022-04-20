
<?php get_header() ?>

<div class="container">
    <div class="row">
        <div class="col-12 col-lg-9">

            <?php do_action( 'kdi_loop_content' ); ?>

        </div>
        <div class="col-12 col-lg-3">

            <?php  get_sidebar(); ?>

        </div>
    </div>
</div>

<?php get_footer() ?>
