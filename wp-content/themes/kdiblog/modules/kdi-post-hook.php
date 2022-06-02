<?php

add_action( 'kdi_content' , 'kdi_get_content', 10);

/**
 * POST_SINGLE
 */
add_action( 'kdi_post_single_header', 'kdi_post_single_title', 10 );
add_action( 'kdi_post_single_header', 'kdi_post_single_meta', 20 );
add_action( 'kdi_post_single_body', 'kdi_post_content', 10 );
add_action( 'kdi_post_single_bottom', 'kdi_post_nav', 10 );
add_action( 'kdi_post_single_bottom', 'kdi_post_comments', 20 );
add_action( 'kdi_post_single_bottom', 'kdi_post_single_related', 30 );




