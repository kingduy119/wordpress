<?php

/**
 * ******************************************
 * KDI WIDGETS PRODUCT
 * ******************************************
 */

if( ! class_exists( 'KDI_Product_Widget' ) ) :
class KDI_Product_Widget extends KDI_Fields {

    public function __construct() {
        $this->wg_id            = 'kdi_product_widget';
        $this->wg_name          = __( 'KDI Products', 'kdi' );
        $this->wg_class         = 'kdi_product_widget';
        $this->wg_description   = __( 'Product loop item', 'kdi' );

        $this->settings = array(
            'show'        => array(
                'type'    => 'select',
                'std'     => '',
                'label'   => __( 'Show', 'kdi' ),
                'options' => array(
                    ''          => __( 'All products', 'kdi' ),
                    'featured'  => __( 'Featured products', 'kdi' ),
                    'onsale'    => __( 'On-sale products', 'kdi' ),
                    'category'  => __( 'Category', 'kdi' ),
                ),
            ),
            'product_cat' => array(
                'type'      => 'product_cat',
                'label'     => __( 'Product cat', 'kdi' ),
                'std'       => '',
            ),
            'hide_free'   => array(
                'std'   => 0,
                'type'  => 'checkbox',
                'label' => __( 'Hide free products', 'woocommerce' ),
            ),
            'show_hidden' => array(
                'std'   => 0,
                'type'  => 'checkbox',
                'label' => __( 'Show hidden products', 'woocommerce' ),
            ),
            'posts_per_page' => array(
                'type'  => 'number',
                'std'   => 6,
                'min'   => 1,
                'max'   => 30,
                'label' => __( 'Number of post to show', 'kdi' ),
            ),
            'orderby'        => array(
                'type'      => 'select',
                'std'       =>  'price',
                'label'     => __( 'Orderby', 'kdi' ),
                'options'   => array(
                    'data'  => 'Date',
                    'rand'  => 'Random',
                    'price' => 'Price',
                    'sales' => 'Sales',
                ),
            ),
            'order' => array(
                'type'      => 'select',
                'std'       =>  'DESC',
                'label'     => __( 'Order', 'kdi' ),
                'options'   => array(
                    'DESC'  => 'DESC',
                    'ASC'   => 'ASC',
                ),
            ),
            'xs' => array(
                'style' => 'width: 20%; display: inline-block;',
                'type'  => 'number',
                'std'   => 3,
                'min'   => 1,
                'max'   => 6,
                'label' => __( 'mobile', 'kdi' ),
            ),
            'sm' => array(
                'style' => 'width: 20%; display: inline-block;',
                'type'  => 'number',
                'std'   => 3,
                'min'   => 1,
                'max'   => 6,
                'label' => __( 'tablet', 'kdi' ),
            ),
            'md' => array(
                'style' => 'width: 20%; display: inline-block;',
                'type'  => 'number',
                'std'   => 3,
                'min'   => 1,
                'max'   => 6,
                'label' => __( 'desktop', 'kdi' ),
            ),
        );

        add_action( 'kdi_widget_field_product_cat', array( $this, 'product_cat_field' ), 10, 4 );
        parent::__construct();
    }

    public function product_cat_field( $key, $value, $setting, $instance ) {
        $categories = get_categories( array( 
            'hide_empty'    => false,
            'parent'        => 0,
            'taxonomy'      => 'product_cat',
        ) );
        ?>
        <div>
            <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
                style="height: 5rem;"
                multiple
            >
                <option value="" <?php selected( '', $value ); ?> >--All--</option>
                <?php foreach ( $categories as $cat ) : ?>
                    <option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $cat->slug, $value ); ?>><?php echo esc_html( $cat->name ); ?></option>
                    <?php
                    $children = get_categories( array(
                        'taxonomy'      => 'product_cat',
                        'parent'        => $cat->cat_ID,
                        'hide_empty'    => false,
                    ) );
                    foreach( $children as $child ) :
                    ?>
                        <option value="<?php echo esc_attr( $child->slug ); ?>" <?php selected( $child->slug, $value ); ?>>--<?php echo esc_html( $child->name ); ?></option>
                    <?php endforeach; ?> 
                    
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }

    public function widget( $args, $instance ) {
        extract( $args );
        
        $xs         = isset( $instance['xs'] ) ? $instance['xs'] : $this->settings['xs']['std'];
        $sm         = isset( $instance['sm'] ) ? $instance['sm'] : $this->settings['sm']['std'];
        $md         = isset( $instance['md'] ) ? $instance['md'] : $this->settings['md']['std'];

        $query = $this->get_product( $instance );

        $contain['before']  = '<div class="row row-cols-'.$xs.' row-cols-sm-'.$sm.' row-cols-md-'.$md.' g-1">';
        $contain['after']   = '</div>';
        $template_args = array( 'show_rating' => false );
        

        echo $before_widget;
            echo $contain['before'];
            while( $query->have_posts() ) {
                $query->the_post();
                wc_get_template( 'content-widget-product.php', $template_args );
            }
            echo $contain['after'];
        echo $after_widget;
    }

    public function get_product( $instance ) {
        $show               = ! empty( $instance['show'] ) ? sanitize_title( $instance['show'] ) : $this->settings['show']['std'];
        $posts_per_page     = isset( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : $this->settings['posts_per_page']['std'];
        $orderby            = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
        $order              = isset( $instance['order'] ) ? $instance['order'] : $this->settings['order']['std'];
        $product_cat        = isset( $instance['product_cat'] ) ? $instance['product_cat'] : $this->settings['product_cat']['std'];
        
        $product_visibility_term_ids = wc_get_product_visibility_term_ids();

        $query_args = array(
            'posts_per_page' => $posts_per_page,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'no_found_rows'  => 1,
            'order'          => $order,
            'meta_query'     => array(),
            'tax_query'      => array(
                'relation' => 'AND',
            ),
            'product_cat'   => $product_cat,
        ); // WPCS: slow query ok.

        if ( empty( $instance['show_hidden'] ) ) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
                'operator' => 'NOT IN',
            );
            $query_args['post_parent'] = 0;
        }

        if ( ! empty( $instance['hide_free'] ) ) {
            $query_args['meta_query'][] = array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'DECIMAL',
            );
        }

        if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
            $query_args['tax_query'][] = array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['outofstock'],
                    'operator' => 'NOT IN',
                ),
            ); // WPCS: slow query ok.
        }

        switch ( $show ) {
            case 'featured':
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['featured'],
                );
                break;
            case 'onsale':
                $product_ids_on_sale    = wc_get_product_ids_on_sale();
                $product_ids_on_sale[]  = 0;
                $query_args['post__in'] = $product_ids_on_sale;
                break;
        }

        switch ( $orderby ) {
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
            default:
                $query_args['orderby'] = 'date';
        }

        return new WP_Query( $query_args );
    }
}
endif;

