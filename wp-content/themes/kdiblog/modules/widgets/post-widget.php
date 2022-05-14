<?php
// namespace kdi;

/**
 * ******************************************
 * KDI WIDGETS
 * ******************************************
 */

if( ! class_exists( 'KDI_Widget_Post' ) ) :
    class KDI_Widget_Post extends KDI_Fields {

        function __construct() {
            $this->wg_id            = 'kdi_posts';
            $this->wg_name          = __( 'KDI Post', 'kdi' );
            $this->wg_class         = 'kdi_widget_posts';
            $this->wg_description   = __( 'Loop post with template', 'kdi' );

            $this->settings         = array(
                'title'             => array(
                    'type'  => 'text',
                    'std'   => '',
                    'label' => __( 'Title', 'kdi' ),
                ),
                'posts_per_page'    => array(
                    'type'  => 'number',
                    'std'   => 6,
                    'min'   => 1,
                    'max'   => 30,
                    'label' => __( 'Number of post to show', 'kdi' ),
                ),
                'xs'                => array(
                    'type'  => 'number',
                    'std'   => 3,
                    'min'   => 1,
                    'max'   => 6,
                    'label' => __( 'Number of post to show on mobile', 'kdi' ),
                ),
                'md'                => array(
                    'type'  => 'number',
                    'std'   => 3,
                    'min'   => 1,
                    'max'   => 6,
                    'label' => __( 'Number of post to show on tablet', 'kdi' ),
                ),
                'lg'                => array(
                    'type'  => 'number',
                    'std'   => 3,
                    'min'   => 1,
                    'max'   => 6,
                    'label' => __( 'Number of post to show on desktop', 'kdi' ),
                ),
                'oderby'        => array(
                    'type'      => 'select',
                    'std'       =>  'date',
                    'label'     => __( 'Orderby', 'kdi' ),
                    'options'   => array(
                        'date'  => 'Date',
                        'name'  => 'Name',
                    ),
                ),
                'order'        => array(
                    'type'      => 'select',
                    'std'       =>  'DESC',
                    'label'     => __( 'Order', 'kdi' ),
                    'options'   => array(
                        'DESC'  => 'DESC',
                        'ASC'  => 'ASC',
                    ),
                ),
                // 'template'          => array(
                //     'type'      => 'select',
                //     'std'       => 'modules/product/card',
                //     'label'     => __( 'Template', 'kdi' ),
                //     'options'   => array(
                //         'modules/product/card'  => __( 'Product', 'kdi' ),
                //     ),
                // ),
            );
            parent::__construct();
        }

        // front-end
        public function widget( $args, $instance ) {
            extract( $args );
            echo $before_widget;

            if( ! empty( $instance['title'] ) ) {
                echo '<h5 class="post--title">' . $instance['title'] . '</h5>';
            }

            $this->query = new WP_Query( array(
                'post_type'         => 'post',
                'post_status'       => 'publish',
                'posts_per_page'    => intval( $instance['posts_per_page'] ),
                'oderby'            => strval( $instance['oderby'] ),
                'order'             => strval( $instance['order'] ),
            ) );

            $echo_container = isset( $instance['container'] ) && isset( $instance['container_close'] );
            $echo_item = isset( $instance['item'] ) && isset( $instance['item_close'] );
            
            if( $echo_container ) { echo $instance['container']; }
            while( $this->query->have_posts() ) : $this->query->the_post();

                if( $echo_item ) { $instance['item']; }
                get_template_part( $instance['template'], $instance['part'] );
                if( $echo_item ) { $instance['item_close']; }                

            endwhile;
            
            if( $echo_container ) { echo $instance['container_close']; }
            echo $after_widget;
        }

    }
endif;

