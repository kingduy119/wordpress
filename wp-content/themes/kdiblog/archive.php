<?php get_header(); ?>
<div class="container">
    <h2>Archive</h2>
    <div class="row">
        <div class="col-md-9 col-lg-9">

        <div id="page-archive">
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-3 g-2">
                <?php
                    do_action( 'kdi_content' );
                ?>
            </div>
        </div>
        <?php
                
            the_posts_pagination( array(
                'type'      => 'list',
                'next_text' => _x( 'Next', 'Next post', 'kdi' ),
                'prev_text' => _x( 'Previous', 'Previous post', 'kdi' ),
            ) );
            
        ?>
        </div>

        <div class="d-none d-md-block col-md-3 col-lg-3">
            <?php  get_sidebar(); ?>
        </div>
    </div>
</div>
<!-- .container -->

<?php get_footer() ?>