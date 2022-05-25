<?php

/**
 * ******************************************
 * HELPER FUNCTION
 * ******************************************
 */
function assets ( $path = '' ) {
    return get_stylesheet_directory_uri() . '/assets/' . $path;
}

function kdi_post_part( $path ) {
    get_template_part( 'modules/post/' . $path );
}

function kdi_product_part( $path ) {
    get_template_part( 'modules/woo/' . $path );
}

function kdi_set_postview() {
    $postID = get_the_ID();
    $count = get_post_meta($postID, 'views', true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, 'views');
        add_post_meta($postID, 'views', 0);
    } else {
        $count++;
        update_post_meta($postID, 'views', $count);
    }
}

function kdi_get_postviews() {
    $post_ID = get_the_ID();
    $count = get_post_meta($post_ID, 'views', true);
    if ($count == '') {
        delete_post_meta($post_ID, 'views');
        add_post_meta($post_ID, 'views', 0);
        return 0;
    }
    return $count;
}

/**
 * ******************************************
 * DEFINE
 * ******************************************
 */
if( !function_exists( 'kdi_post_loop' ) ) {
    function kdi_post_loop(
        $query      = null,
        $template   = '',
        $item       = array( 'before' => '', 'after' => '' ),
        $contain    = array( 'before' => '', 'after' => '' )
    ) {
        echo $contain['before'];
        if( $query ) : while( $query->have_posts() ) : $query->the_post();
            echo $item['before'];
                get_template_part( $template );
            echo $item['after'];
        endwhile; endif;
        echo $contain['after'];

        wp_reset_postdata();
    }
}

if ( ! function_exists( 'kdi_woo_is_actived' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function kdi_woo_is_actived() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}
}

if ( ! function_exists( 'kdi_woo_cart_actived' ) ) {
    function kdi_woo_cart_actived() {
        $woo = WC();
        return $woo instanceof \WooCommerce && $woo->cart instanceof \WC_Cart;
    }
}

/**
 * ******************************************
 * POST FUNCTION
 * ******************************************
 */
if( ! function_exists( 'kdi_loop_content' ) ) {
    function kdi_loop_content() {
        $content = 'modules/post/content';

        do_action( 'kdi_loop_content_before' );
        while( have_posts() ) : the_post();
        
            if( is_page() ) :
                    get_template_part( $content, 'page');

            elseif ( is_search() || is_archive() ) :
                get_template_part('modules/post/card');

            elseif( is_single() ) :
                if( 'post' == get_post_type() ) :
                    get_template_part( $content, 'single');

                elseif( 'product' == get_post_type() ) :
                    $content = 'modules/woo/content';
                    get_template_part( $content, 'single-product');

                endif;
            else :
                get_template_part( $content, get_post_format() );
            endif;
        
        endwhile;
        do_action( 'kdi_loop_content_after' );
    }
}

if( ! function_exists( 'kdi_rating' ) ) {
    function kdi_rating() {
        if( function_exists( 'kk_star_ratings' ) ) :
            echo kk_star_ratings();
        endif;
    }
}

/**
 * POST
 * kdi_post_header
 * kdi_post_content
 * kdi_post_taxonomy
 * kdi_post_nav
 * kdi_post_comments
 */
if( ! function_exists( 'kdi_post_header' ) ) {
    function kdi_post_header() {
    ?>
        <header class="entry-header">  
        <?php 
            do_action('kdi_post_header_before');
            kdi_post_part( 'loop/title' );
            do_action('kdi_post_header_after');
        ?>
        </header>
    <?php
    }
}

if( ! function_exists( 'kdi_post_content' ) ) {
    function kdi_post_content() {
    ?>
        <div class="entry-content">
        <?php the_content(); ?>
        </div>
    <?php
    }
}


if( ! function_exists( 'kdi_post_nav' ) ) {
    function kdi_post_nav() {
    ?>
        <div class="entry-pagelink">
            <?php the_post_navigation(); ?>
        </div>
    <?php
    }
}

if( ! function_exists( 'kdi_post_comments' ) ) {
    function kdi_post_comments() {
        comments_template('/comments.php');
    }
}

/**
 * POST-end
 */

