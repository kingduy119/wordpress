
<?php get_header() ?>

<div class="container">
    <div class="row g-1">
        <div class="col-md-12 col-lg-3">
            <?php get_sidebar(); ?>
        </div>
        
        <div class="col-md-12 col-lg-9">
            <?php do_action( 'kdi_loop_content' ); ?>
        </div>
    </div>
</div>

<?php get_footer() ?>