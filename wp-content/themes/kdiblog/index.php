
<?php get_header() ?>

<h2>INDE</h2>
<div class="container">
    <div class="row g-1">
        <div class="col-md-12 col-lg-9">
            
            <?php do_action( 'kdi_content' ); ?>

        </div>
        <div class="col-md-12 col-lg-3">

            <?php get_sidebar(); ?>

        </div>
    </div>
</div>

<?php get_footer() ?>