<?php

// if ( ! function_exists( 'kdi_post_popular' ) ) {
// 	function kdi_post_popular() {
//         $my_query;
//         $tags = wp_get_post_tags($post->ID);
//         if( $tags ) {
//             $tag_ids = array();
//             foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
//             $args = array(
//                 'tag__in' => $tag_ids,
//                 'post__not_in' => array($post->ID), // Loại trừ bài viết hiện tại
//                 'showposts'=>5, // Số bài viết bạn muốn hiển thị.
//                 'caller_get_posts'=>1
//             );
//             $my_query = new WP_Query($args);
//         } 
//         else {
//             $categories = get_the_category($post->ID);
//             if ( $categories ) {
//                 $category_ids = array();
//                 foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
        
//                 $args=array(
//                 'category__in' => $category_ids,
//                 'post__not_in' => array($post->ID),
//                 'showposts'=>5, // Số bài viết bạn muốn hiển thị.
//                 'caller_get_posts'=>1
//                 );
//                 $my_query = new WP_Query($args);
//             }
//         }
    
//         if( $my_query->have_posts() ) {
//             echo '<section id="section-post-single-popular" >';
//             echo '<h3>Bài viết liên quan</h3>';
//             while( $my_query->have_posts() ) {
//                 $my_query->the_post();
//                 get_template_part('modules/post/card');
//             }
//             wp_reset_postdata();
//             echo '</section>';
//         }
//     }
// }

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
                get_template_part( $content, 'single');

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
        <header class="entry-header mb-2">  
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
        <div class="entry-content mb-2">
        
        <?php the_content(); ?>

        </div>
    <?php
    }
}

if( ! function_exists( 'kdi_post_taxonomy' ) ) {
    function kdi_post_taxonomy() {
        echo '<aside class="post-taxonomy mb-2">';
            kdi_post_tags();
        echo '</aside>';
    }
}

if( ! function_exists( 'kdi_post_nav' ) ) {
    function kdi_post_nav() {
    ?>
        <div class="entry-pagelink mb-4">
            <?php the_post_navigation(); ?>
        </div>
    <?php
    }
}

if( ! function_exists( 'kdi_post_comments' ) ) {
    function kdi_post_comments() {
    ?>
    <div class="entry-comment">
        <?php
        if ( comments_open() || 0 !== intval( get_comments_number() ) ) {
            comments_template();
        }
        ?>
    </div>
    <?php
    }
}

/**
 * POST-end
 */

