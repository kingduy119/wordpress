<?php


add_action('kdi_loop_content', 'kdi_loop_content', 5);

// remove_shortcode('product_category__shortcode_tag');

/**
 * loop post
 */
add_action('kdi_loop_post', 'kdi_post_header', 5);
add_action('kdi_loop_post', 'kdi_post_content', 10);
add_action('kdi_loop_post', 'kdi_post_taxonomy', 20);

/**
 * single-post
 */
add_action('kdi_single_post', 'kdi_post_header', 5);
add_action('kdi_single_post', 'kdi_post_content', 10);
add_action('kdi_single_post', 'kdi_post_taxonomy', 20);
add_action('kdi_single_post_bottom', 'kdi_post_nav', 10);
add_action('kdi_single_post_bottom', 'kdi_post_comments', 20);

/**
 * post-page
 */
// add_action('kdi_loop_page', 'kdi_post_header', 5);
add_action('kdi_loop_page', 'kdi_post_content', 10);


