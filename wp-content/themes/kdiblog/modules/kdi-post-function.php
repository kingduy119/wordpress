<?php
/**
 * ******************************************
 * HELPER FUNCTION
 * ******************************************
 */
function assets ( $path = '' ) {
    return get_stylesheet_directory_uri() . '/assets/' . $path;
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
function kdi_get_recent_post_args() {
    return array(
        'numberposts'      => 10,
        'offset'           => 0,
        'category'         => 0,
        'orderby'          => 'post_date',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'post_status'      => 'draft, publish, future, pending, private',
        // 'post_status'      => 'publish',
        'suppress_filters' => true,
    );
}

if( ! function_exists( 'kdi_loop_template' ) ) {
    function kdi_loop_template( $args = array() ) {
        $query          = isset( $args['query'] ) ? $args['query'] : null;
        $template       = isset( $args['template'] ) ? $args['template'] : '';
        $template_args  = isset( $args['template_args'] ) ? $args['template_args'] : array();
        $loop_before    = isset( $args['loop_before'] ) ? $args['loop_before'] : '';
        $loop_after     = isset( $args['loop_after'] ) ? $args['loop_after'] : '';

        echo $loop_before;
        if( $query->have_posts() ) {
            while( $query->have_posts() ) {
                $query->the_post();
                get_template_part( $template, '', $template_args );
            }
            wp_reset_postdata();
        }
        echo $loop_after;
    }
}

if( ! function_exists( 'kdi_get_content' ) ) {
    function kdi_get_content() {
        $content = 'modules/contents/content';

        if( is_page() ) :
            get_template_part( $content, 'page');

        elseif( is_single() ) :
            if( 'product' == get_post_type() ) :
                get_template_part( $content, 'single-product');
            else :
                get_template_part( $content, 'single' );
            endif;

        else :
            while( have_posts() ) : the_post();
                get_template_part( $content, get_post_format() );
            endwhile;
            wp_reset_postdata();
        endif;

    }
}

/**
 * ******************************************
 * POST SINGLE
 * ******************************************
 */
if( ! function_exists( 'kdi_post_single_title' ) ) {
    function kdi_post_single_title() {
        is_single()
        ? the_title( '<h1 class="post--title">', '</h1>' )
        : the_title( '<h2 class="post--title">', '</h2>' );
    }
}

if( ! function_exists( 'kdi_post_single_meta' ) ) {
    function kdi_post_single_meta() {
        global $post;
        ?>
        <div class="entry--meta border-bottom text-secondary pb-2 mb-3">
            <a
                class="text-secondary text-decoration-none text-uppercase fw-bold"
                href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>"
            >
                <span class="post-author me-2">
                    <img class="img-fluid rounded-circle mb-1" src="<?php echo esc_url( get_avatar_url( $post->post_author, ['size' => 24] ) ); ?>" alt="author_url" >
                    <?php echo esc_html( the_author_meta( 'display_name', $post->post_author ) ); ?>
                </span>
                <span class="post-author-total me-2">
                    <i class="bi bi-pencil-square"></i>
                    <?php echo esc_html( count_user_posts( $post->post_author ) ); ?>
                </span>
            </a>
            <span class="post-date me-2">
                <i class="bi bi-calendar3"></i>
                <time datetime="<?php esc_html( get_the_date( 'c' ) ); ?>">
                    <?php echo esc_html( get_the_date() ); ?>
                </time>
            </span>
            <span class="post-views me-2">
                <i class="bi bi-eye"></i>
                <?php echo esc_html( kdi_get_postviews() ); ?>
            </span>
            
        </div>
        <?php
        kdi_set_postview();
    }
}

if( ! function_exists( 'kdi_post_content' ) ) {
    function kdi_post_content() {
        the_content();
    }
}

if( ! function_exists( 'kdi_post_nav' ) ) {
    function kdi_post_nav() {
    ?>
        <div class="entry-navigation">
            <?php
                the_post_navigation( array(
                    'prev_text' => __( 'Previous', 'kdi' ),
                    'next_text' => __( 'Next', 'kdi' ),
                ) ); 
            ?>
        </div>
    <?php
    }
}

if( ! function_exists( 'kdi_post_single_related' ) ) {
    function kdi_post_single_related() {
        global $post;
        $tag_ids = array();
        $this_cat = '';
        $current_cat = get_the_category( $post->ID );
        $current_cat = $current_cat[0]->cat_ID;
        $tags = get_the_tags( $post->ID );

        if ($tags) {
            foreach( $tags as $tag ) {
                $tag_ids[] = $tag->term_id;
            }
        } else {
            $this_cat = $current_cat;
        }

        $args = array(
            'posts_per_page' => 3,
            'post_type'   => get_post_type(),
            'numberposts' => 3,
            'orderby'     => 'rand',
            'tag__in'     => $tag_ids,
            'cat'         => $this_cat,
            'exclude'     => $post->ID,
        );
        $related_posts = new WP_Query( $args );

        if ( empty($related_posts) ) {
            $args['tag__in'] = '';
            $args['cat'] = $current_cat;
            $related_posts = new WP_Query( $args );
        }
        if ( empty( $related_posts ) ) {
            return;
        }

        if( $related_posts->have_posts() ) :
            echo '<p class="h4 fw-bold">' . __( 'Releated post', 'kdi') . '</p>';
            echo '<div id="entry-related-list" class="row row-cols-3 g-2">';
            while( $related_posts->have_posts() ) {
                $related_posts->the_post();
                get_template_part( 'modules/contents/content' );
            }
            echo '</div>';
        endif;
    }
}

if( ! function_exists( 'kdi_post_comments' ) ) {
    function kdi_post_comments() {
        comments_template('/comments.php');
    }
}

if( ! function_exists( 'kdi_comments_callback' ) ) {
    function kdi_comments_callback($comment, $args, $depth) {
        // $GLOBALS['comment'] = $comment;
        if ($comment->comment_approved == '1') :
        ?>
            <div class="comments d-flex mb-1">
                <div class="comments-avatar px-1">
                    <img class="img-fluid rounded-circle" src="<?php echo esc_url( get_avatar_url( $comment, ['size' => 24] ) ); ?>" alt="author_url" >
                </div>
                    
                <div class="comment-content px-1 w-100 border bg-body">
                    <?php 
                        echo sprintf(
                            '<h6 class="comments-header"><a class="comments-author" href="%1$s">%2$s</a><small>%3$s - %4$s</small></h6>', 
                            '#', //get_comment_author_url(),
                            get_comment_author(),
                            get_comment_date(),
                            get_comment_time()
                        ); 
                        
                        comment_text();

                        // comments-actions
                        comment_reply_link(
                            array_merge(
                                $args,
                                array(
                                    'reply_text' => __( 'Relpy', 'kdi' ),
                                    'depth' => $depth,
                                    'max_depth' => $args['max_depth']
                                )
                            )
                        );
                    ?>
                </div>            
            </div>
        <?php endif;
    }
}

