<?php

get_header() ?>

<div class="container">
    <div class="row g-1">
        <div class="col-md-12 col-lg-9">
			<h1>
                <?php printf( esc_attr__( 'Search Results for: %s', 'kdi' ), '<span>' . get_search_query() . '</span>' ); ?>
            </h1>
            
            <?php
                do_action( 'kdi_loop_content' );

                the_posts_pagination( array(
                    'type'      => 'list',
                    'next_text' => _x( 'Next', 'Next post', 'kdi' ),
                    'prev_text' => _x( 'Previous', 'Previous post', 'kdi' ),
                ) );
            ?>
		</div>
        <div class="col-md-12 col-lg-3"><?php  get_sidebar(); ?></div>
    </div>
</div>


<?php
get_footer();
