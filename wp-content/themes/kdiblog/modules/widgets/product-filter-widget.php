<?php

/**
 * ******************************************
 * KDI WIDGETS PRODUCT CAT
 * ******************************************
 */
if( ! class_exists( 'KDI_Product_Filter_Widget' ) ) :
    class KDI_Product_Filter_Widget extends KDI_Fields {
        public function __construct() {
            $this->wg_id            = 'kdi_product_filter_widget';
            $this->wg_name          = __( 'KDI Product Filter', 'kdi' );
            $this->wg_class         = 'kdi_product_filter';
            $this->wg_description   = __( 'Filter product', 'kdi' );

            $this->settings = array(
                'terms' => array(
                    'type'      => 'select',
                    'std'       => 'all',
                    'label'     => __( 'Terms', 'kdi' ),
                    'options'   => array(
                        'all'       => __( 'Show all category', 'kdi' ),
                        'child'    => __( 'Show child category', 'kdi' ),
                    ),
                ),
                // 'template' => array(
                //     'type'      => 'select',
                //     'std'       => 'default',
                //     'label'     => __( 'Template', 'kdi' ),
                //     'options'   => array(
                //         'default'    => __( 'Default', 'kdi' ),
                //     ),
                // ),
                'orderby'        => array(
                    'type'      => 'select',
                    'std'       =>  'name',
                    'label'     => __( 'Orderby', 'kdi' ),
                    'options'   => array(
                        'name'  => 'Name',
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
            );

            parent::__construct();
        }

        public function widget( $args, $instance ) {
            extract( $args );

            $template       = 'modules/categories/default';
            $part           = '';
            // $template   = isset( $instance['template'] ) ? $instance['template'] : $this->settings['template']['std'];
            $orderby    = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
            $order      = isset( $instance['order'] ) ? $instance['order'] : $this->settings['order']['std'];
            $terms      = isset( $instance['terms'] ) ? $instance['terms'] : $this->settings['terms']['std'];
            
            $parent = 0;
            if( 'child' == $terms ) {
                $cat = get_queried_object();
                $parent = ( 0 != $cat->parent ) ? $cat->parent : $cat->term_id;
            }
            
            $params['categories'] = get_categories( array(
                'hide_empty'    => true,
                'parent'        => $parent,
                'taxonomy'      => 'product_cat',
                'orderby'       => $orderby,
                'order'         => $order,
            ) );

            echo $before_widget;
                get_template_part( $template, $part, $params);
            echo $after_widget;
        }
    }
endif;

