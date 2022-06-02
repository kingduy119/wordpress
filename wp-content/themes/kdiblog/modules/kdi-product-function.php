<?php

function kdi_product_loop( $query ) {
    while( $query->have_posts() ) : $query->the_post();
        wc_get_template_part( 'content', 'product' );
    endwhile;
    wp_reset_postdata();
}

if( ! function_exists( 'kdi_wc_template_single_title' ) ) {
    function kdi_wc_template_single_title() {
        wc_get_template( 'single-product/title.php' );
    }
}

if( ! function_exists( 'kdi_wc_comments' ) ) {
    function kdi_wc_comments() {
        comments_template('/woocommerce/single-product-reviews.php');
    }
}

if ( ! function_exists( 'kdi_custom_product_single_tabs' ) ) {	
	function kdi_custom_product_single_tabs( $tabs = array() ) {
		global $product, $post;

		// Description tab - shows product content.
		if ( $post->post_content ) {
			$tabs['description'] = array(
				'title'    => __( 'Description', 'woocommerce' ),
				'callback' => 'woocommerce_product_description_tab',
				'priority' => 10,
			);
		}

		// Additional information tab - shows attributes.
		if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
			$tabs['additional_information'] = array(
				'title'    => __( 'Additional information', 'woocommerce' ),
				'priority' => 20,
				'callback' => 'woocommerce_product_additional_information_tab',
			);
		}

		// Reviews tab - shows comments.
		if ( comments_open() ) {
			$tabs['comments'] = array(
				'title'    => sprintf( __( 'Comments (%d)', 'woocommerce' ), $product->get_review_count() ),
				'priority' => 30,
				// 'callback' => 'kdi_single_product_comments',
                'callback' => 'kdi_wc_comments',
			);
		}
		return $tabs;
	}
}

function show_woo_comments() {
    if ( have_comments() ) : ?>
        <ol class="commentlist">
            <?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
        </ol>
        <?php
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
            echo '<nav class="woocommerce-pagination">';
            paginate_comments_links(
                apply_filters(
                    'woocommerce_comment_pagination_args',
                    array(
                        'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                        'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                        'type'      => 'list',
                    )
                )
            );
            echo '</nav>';
        endif;
        ?>
    <?php else : ?>
        <p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
    <?php endif; 
}

// -------------------------------------
function kdi_shop_loop_item_title() {
    wc_get_template( 'loop/title.php' );
}

function products_custoom_open_shortcode() {
    echo '<div class="container">' .
            '<div class="row g-1">' .
                '<div class="col col-md-12 col-lg-3">';
                dynamic_sidebar('sidebar-product-catalog');
    echo        '</div>'.
                '<div class="col col-md-12 col-lg-9 position-relative">' .
                '<div id="products-loading" class="position-absolute ratio ratio-1x1" style="z-index: 100;">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>';
}

function products_custoom_close_shortcode() {
    echo '</div></div></div>';
}

if ( ! function_exists( 'kdi_wc_pagination' ) ) {
	function kdi_wc_pagination() {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}

		$args = array(
			'total'     => wc_get_loop_prop( 'total_pages' ),
			'current'   => wc_get_loop_prop( 'current_page' ),
            'per_page'  => wc_get_loop_prop( 'per_page' ),
		);

        echo '<nav class="product--pagination">';
		wc_get_template( 'loop/pagination.php', $args );
        echo '</nav>';
	}
}
// -------------------------------------

// Ajax
function kdi_load_category() {
    $parent = 0;
    
    $categories = get_categories( array(
        'hide_empty'    => true,
        'parent'        => $parent,
        'taxonomy'      => 'product_cat',
    ) );

    ob_start();
    foreach( $categories as $cat ) :
    ?>
        <div class="form-check">
            <input
                id="product-content"
                class="form-check-input"
                value="<?php echo esc_attr( $cat->slug ); ?>"
                type="checkbox"
            >
            <label class="form-check-label" for="product-content"><?php echo esc_html( $cat->name ) . '('. $cat->count .')'; ?></label>
        </div>
    <?php
    endforeach;
    $res = ob_get_clean();

    wp_send_json( $res );
    die();
}
add_action( 'wp_ajax_kdi_load_category', 'kdi_load_category' );
add_action( 'wp_ajax_noprive_kdi_load_category', 'kdi_load_category' );

function kdi_page_product_cat() {
    $query_vars                     = json_decode( stripslashes( $_GET['query_vars'] ), true );
    $query_vars['paged']            = $_GET['paged'];
    $query_vars['posts_per_page']   = $_GET['per_page'];

    // PRODUCT_CAT FILTER
    if( ! empty( $_GET['category_name'] ) ) {
        $query_vars['product_cat']      = $_GET['category_name'];
    }

    // SORT FILTER
    if( isset( $_GET['orderby'] ) ) {
        switch( $_GET['orderby'] ) {
            case 'price':
                $query_vars['meta_key'] = '_price'; // WPCS: slow query ok.
                $query_vars['orderby']  = 'meta_value_num';
                break;
            case 'rand':
                $query_vars['orderby'] = 'rand';
                break;
            case 'sales':
                $query_vars['meta_key'] = 'total_sales'; // WPCS: slow query ok.
                $query_vars['orderby']  = 'meta_value_num';
                break;
            default:
                $query_vars['orderby'] = 'date';
        }
    }
    if( isset( $_GET['order'] ) ) {
        $query_vars['order'] = $_GET['order'];
    }

    // PRICE FILTER
    if( isset( $_GET['min_price'] ) && isset( $_GET['max_price'] ) ) {
        $min_price  = absint( $_GET['min_price'] );
        $max_price  = absint( $_GET['max_price'] );
        $query_vars['meta_query'][] = array(
            'key'       => '_price',
            'compare'   => 'BETWEEN',
            'type'      => 'NUMERIC',
            'value'     => array( $min_price, $max_price ),
        );
    }

    $query = new WP_Query( $query_vars );

    ob_start();
    if( $query->have_posts() ) {
        kdi_product_loop( $query );
    }
    else {
        echo '<div>NO CONTENT</div>';
    }
    $content = ob_get_clean();

    ob_start();
    if( $query->max_num_pages > 1) {
        $args = array(
            'total'     => $query->max_num_pages,
            'current'   => $query_vars['paged'],
            'per_page'  => $query_vars['posts_per_page'],
        );
        wc_get_template( 'loop/pagination.php', $args );
    }
    $pagination = ob_get_clean();

    wp_send_json( array(
        'content'       => $content,
        'pagination'    => $pagination,
    ) );
    die();
}
add_action( 'wp_ajax_kdi_page_product_cat', 'kdi_page_product_cat' );
add_action( 'wp_ajax_nopriv_kdi_page_product_cat', 'kdi_page_product_cat' );