<?php

get_header() ?>

<div class="container">
    <div class="row">
        <div class="col-md-9 col-lg-9">
            <h1><?php printf( esc_attr__( 'Search Results for: %s', 'kdi' ), '<span>' . get_search_query() . '</span>' ); ?></h1>

            <div id="page-archive">
                <div class="row row-cols-2 row-cols-md-3 row-cols-xl-3 g-2">
                    <?php
                        do_action( 'kdi_content' );
                    ?>
                </div>

                <?php
                    // do_action( 'kdi_content' );
                    the_posts_pagination( array(
                        'type'      => 'list',
                        'next_text' => _x( 'Next', 'Next post', 'kdi' ),
                        'prev_text' => _x( 'Previous', 'Previous post', 'kdi' ),
                    ) );
                ?>
            </div>
		</div>
        <div class="d-none d-md-block col-md-3 col-lg-3">
            <?php  get_sidebar(); ?>
        </div>
    </div>
</div>


<?php
get_footer();
