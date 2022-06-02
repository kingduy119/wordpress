<?php
/**
 * ******************************************
 * KDI WIDGETS
 * ******************************************
 */

if( ! class_exists( 'KDI_Widget_Post' ) ) :
    class KDI_Widget_Post extends KDI_Fields {

        function __construct() {
            $this->wg_id            = 'kdi_post_widget';
            $this->wg_name          = __( 'KDI Post', 'kdi' );
            $this->wg_class         = 'kdi_widget_post';
            $this->wg_description   = __( 'Post loop', 'kdi' );

            $this->settings = array(
                'posts_per_page'    => array(
                    'type'  => 'number',
                    'std'   => 6,
                    'min'   => 1,
                    'max'   => 30,
                    'label' => __( 'Number of post to show', 'kdi' ),
                ),
                'oderby'        => array(
                    'type'      => 'select',
                    'std'       =>  'date',
                    'label'     => __( 'Orderby', 'kdi' ),
                    'options'   => array(
                        'data'  => 'Date',
                        'rand'  => 'Random',
                    ),
                ),
                'order'        => array(
                    'type'      => 'select',
                    'std'       =>  'DESC',
                    'label'     => __( 'Order', 'kdi' ),
                    'options'   => array(
                        'DESC'  => 'Hight to low',
                        'ASC'  => 'Low to hight',
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
            parent::__construct();
        }

        // front-end
        public function widget( $args, $instance ) {
            extract( $args );

            $xs         = isset( $instance['xs'] ) ? $instance['xs'] : $this->settings['xs']['std'];
            $sm         = isset( $instance['sm'] ) ? $instance['sm'] : $this->settings['sm']['std'];
            $md         = isset( $instance['md'] ) ? $instance['md'] : $this->settings['md']['std'];

            $contain['before']  = '<div class="row row-cols-'.$xs.' row-cols-sm-'.$sm.' row-cols-md-'.$md.' g-1">';
            $contain['after']   = '</div>';

            $query = kdi_get_recent_post( array(
                'post_status'       => 'publish',
            ) );

            echo $before_widget;

            // echo '<div class="row row-cols-1 g-1">';
            echo $contain['before'];
            kdi_loop_template( array(
                'query'     => $query,
                'template'  => 'modules/contents/content-widget-post',
            ) );
            echo $contain['after'];
            // echo '</div>';

            echo $after_widget;
        }

        // get_post( $instance ) {

        // }
    }
endif;

