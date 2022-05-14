<?php

function kdi_product_loop( $query ) {
    while( $query->have_posts() ) : $query->the_post();
        wc_get_template_part( 'content', 'product' );
    endwhile;
    wp_reset_postdata();
}

if( ! function_exists( 'kdi_wc_thumbnail' ) ) {
    function kdi_wc_thumbnail() {
        kdi_product_part( 'loop/thumbnail' );
    }
}

if( ! function_exists( 'kdi_wc_title' ) ) {
    function kdi_wc_title() {
        kdi_product_part( 'loop/title' );
    }
}

if( ! function_exists( 'kdi_wc_price' ) ) {
    function kdi_wc_price() {
        kdi_product_part( 'loop/price' );
    }
}

if( ! function_exists( 'kdi_wc_comments' ) ) {
    function kdi_wc_comments() {
        comments_template('/woocommerce/single-product-reviews.php');
    }
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
if( ! function_exists( 'kdi_upsell_display' ) ) {
    function kdi_upsell_display( $limit = '-1', $columns = 4, $orderby = 'rand', $order = 'desc' ) {
        global $product;

		if ( ! $product ) {
			return;
		}

		$args = array(
            'posts_per_page' => $limit,
            'orderby'        => $orderby,
            'order'          => $order,
            'columns'        => $columns,
        );

		$orderby = isset( $args['orderby'] ) ? $args['orderby'] : $orderby;
		$order   = isset( $args['order'] ) ? $args['order'] : $order;
		$limit   = isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit;

		// Get visible upsells then sort them at random, then limit result set.
		$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
		$upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;

        echo '<div class="row row-cols-2 row-cols-sm-4 g-1">';
        foreach( $upsells as $upsell ) {
            $post_object = get_post( $upsell->get_id() );
            setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
            
            echo '<div class="col">';
            get_template_part( 'modules/woo/templates/card' );
            echo '</div>';
        }
        echo '</div>';

        wp_reset_postdata();
    }   
}

// -------------------------------------
function kdi_product_related( $args = array() ) {

    global $product;
    if ( ! $product ) { return; }

    $product_cats = get_the_terms( get_the_ID(), 'product_cat');
    $slugs = '';
    foreach( $product_cats as $cat ){
        $slugs .= $cat->slug . ',';
    }

    $defaults = array(
        'posts_per_page'    => 4,
        'columns'           => 4,
        'orderby'           => 'rand', // @codingStandardsIgnoreLine.
        'order'             => 'desc',
        'product_cat'       => $slugs,
    );
    $args = wp_parse_args( $args, $defaults );
    $query = new WP_Query( $args );

    $template           = 'modules/woo/templates/card';
    $item['before']     = '<div class="col">';
    $item['after']      = '</div>';
    $contain['before']  = '<div class="row row-cols-2 row-cols-sm-4 g-1">';
    $contain['after']   = '</div>';

    kdi_post_loop( $query, $template, $item, $contain );
}

// -------------------------------------
if ( ! function_exists( 'kdi_custom_product_single_tabs' ) ) {
	
	function kdi_custom_product_single_tabs( $tabs = array() ) {
		global $product, $post;

		// Description tab - shows product content.
		if ( $post->post_content ) {
			$tabs['description'] = array(
				'title'    => __( 'Description', 'woocommerce' ),
				'priority' => 10,
				'callback' => 'woocommerce_product_description_tab',
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


function kdi_query_product() {
    header("Content-Type: application/json", true);

    $post_in    = $_REQUEST['post_in'];
    $order      = $_REQUEST['order'];

    $query_args = array(
        'post_status'   => 'publish',
        'post_type'     => 'product',
        'orderby'       => 'date',
        'product_cat'   => $post_in,
        'order'         => $order,
        'meta_query'    => array(),
    );

    if( isset( $_REQUEST['orderby'] ) ) {
        switch( $_REQUEST['orderby'] ) {
            case 'price':
                $query_args['meta_key'] = '_price'; // WPCS: slow query ok.
                $query_args['orderby']  = 'meta_value_num';
                break;
            case 'rand':
                $query_args['orderby'] = 'rand';
                break;
            case 'sales':
                $query_args['meta_key'] = 'total_sales'; // WPCS: slow query ok.
                $query_args['orderby']  = 'meta_value_num';
                break;
            // case 'name':
            //     $query_args['orderby'] = 'name';
            default:
                $query_args['orderby'] = 'date';
        }
    }

    // price
    if( isset( $_REQUEST['min_price'] ) && isset( $_REQUEST['max_price'] ) ) {
        $min_price  = absint( $_REQUEST['min_price'] );
        $max_price  = absint( $_REQUEST['max_price'] );
        $query_args['meta_query'][] = array(
            'key'       => '_price',
            'value'     => array( $min_price, $max_price ),
            'compare'   => 'BETWEEN',
            'type'      => 'NUMERIC',
        );
    }

    $query = new WP_Query( $query_args );

    ob_start();
    kdi_product_loop( $query );
    $res = ob_get_clean();
    
    wp_send_json( $res );
    die();
}
add_action( 'wp_ajax_kdi_query_product', 'kdi_query_product' );
add_action( 'wp_ajax_noprive_kdi_query_product', 'kdi_query_product' );


function kdi_page_product_cat() {
    $query_vars = json_decode( stripslashes( $_REQUEST['query_vars'] ), true );
    $query_vars['paged'] = $_REQUEST['page'];
    $query_vars['posts_per_page']   = isset( $_REQUEST['per_page'] ) ? $_REQUEST['per_page'] : -1;

    $query = new WP_Query( $query_vars );

    ob_start();
    if( ! $query->have_posts() ) {
        echo '<div>NO CONTENT</div>';
    }
    else {
        kdi_product_loop( $query );
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
        'per_page'       => $query_vars,
    ) );
    die();
}
add_action( 'wp_ajax_kdi_page_product_cat', 'kdi_page_product_cat' );
add_action( 'wp_ajax_nopriv_kdi_page_product_cat', 'kdi_page_product_cat' );