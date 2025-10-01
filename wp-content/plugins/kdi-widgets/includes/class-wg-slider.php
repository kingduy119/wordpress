<?php

if( ! class_exists( 'KDI_WG_Slider' ) ) {
    class KDI_WG_Slider extends KDI_WG_Field {

        public function __construct() {
            $this->wg_id            = 'kdi_wg_slider';
            $this->wg_class         = 'kdi_wg_slider';
            $this->wg_name          = __( 'KDI Slider', 'kdi' );
            $this->wg_description   = __( 'Slider widget', 'kdi' );

            parent::__construct();
        }

        public function widget( $args, $instance ) {
            extract( $args );
    
            echo $before_widget;
            
            // $temp_1 = 'includes/templates/slider.php';
            // $temp_2 = 'includes/templates/carousel.php';
            // $temp_3 = 'includes/templates/gallery.php';

            // $template_args = array();
            // kdi_get_template_part( $temp_1, $template_args );
            // kdi_get_template_part( $temp_2, $template_args );
            // kdi_get_template_part( $temp_3, $template_args );
            
            echo $after_widget;
        }
    }
}

if( ! class_exists( 'KDI_WG_Posts' ) ) {
    class KDI_WG_Posts extends KDI_WG_Field {

        public function __construct() {
            $this->wg_id            = 'kdi_wg_post';
            $this->wg_class         = 'kdi_wg_post';
            $this->wg_name          = __( 'KDI Posts', 'kdi' );
            $this->wg_description   = __( 'Posts widget', 'kdi' );

            $this->settings = [
                'total' => [
                    'type' => 'number',
                    'label' => __('Total posts', 'text_domain'),
                    'std' => 4,
                    'min' => 1,
                    'max' => 20,
                ],
                'orderby' => [
                    'type' => 'select',
                    'label' => __('Order by', 'text_domain'),
                    'options' => [
                        'date' => 'Date',
                        'title' => 'Title',
                        'views' => 'Views',
                    ],
                    'std' => 'date',
                ],
                'order' => [
                    'type' => 'select',
                    'label' => __('Order', 'text_domain'),
                    'options' => [
                        'ASC' => 'Ascending',
                        'DESC' => 'Descending',
                    ],
                    'std' => 'DESC',
                ],
                'category' => [
                    'type' => 'text',
                    'label' => __('Category slug or ID', 'text_domain'),
                ],
                'tag' => [
                    'type' => 'text',
                    'label' => __('Tag slug or ID', 'text_domain'),
                ],
                'min_views' => [
                    'type' => 'number',
                    'label' => __('Minimum views', 'text_domain'),
                    'std' => 0,
                ],
                'row_class' => [
                    'type'  => 'text',
                    'label' => __('Row class (responsive grid)', 'text_domain'),
                    'std'   => 'row g-4 row-cols-2 row-cols-md-3 row-cols-xl-4',
                ],
                'column_class' => [
                    'type'  => 'text',
                    'label' => __('Column class (if needed)', 'text_domain'),
                    'std'   => 'col',
                ],
            ];

            parent::__construct();
        }

        public function widget( $args, $instance ) {
            // $total        = !empty($instance['total']) ? (int)$instance['total'] : 4;
            $total = isset($instance['total']) && $instance['total'] 
                    ? absint($instance['total']) 
                    : (isset($this->settings['total']['std']) ? $this->settings['total']['std'] : 4);
            $row_class    = !empty($instance['row_class']) ? esc_attr($instance['row_class']) : 'row g-4';
            $column_class = !empty($instance['column_class']) ? esc_attr($instance['column_class']) : 'col-md-3';

            echo $args['before_widget'];

            $query = new WP_Query([
                'post_type'           => 'post',
                'posts_per_page'      => $total,
                'ignore_sticky_posts' => 1,
                'no_found_rows'       => true,
            ]);


            // echo '<pre>';
            // echo 'Total setting: ' . $total . '<br>';
            // echo 'Post count (post_count): ' . $query->post_count . '<br>';
            // echo 'Post count (count array): ' . count( $query->posts ) . '<br>';
            // echo '</pre>';

            echo '<div class="' . $row_class . '">';
            while ( $query->have_posts() ) {
                $query->the_post();

                echo '<div class="' . $column_class . '">';
                kdi_widget_get_template( 'content-post', [
                    'post' => get_post()
                ]);
                echo '</div>';
            }
            echo '</div>';

            wp_reset_postdata();
            echo $args['after_widget'];
        }
    }
}
