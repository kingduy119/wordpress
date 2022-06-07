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
                'post_display' => array(
                    'type'    => 'select',
                    'std'     => 'newest',
                    'label'   => __( 'Post display:', 'kdi' ),
                    'options' => array(
                        'newest'    => __( 'Newest', 'kdi' ),
                        'recent'    => __( 'Recent', 'kdi' ),
                        'feature'   => __( 'Feature', 'kdi' ),
                    ),
                ),
                'post_cat' => array(
                    'type'      => 'post_cat',
                    'label'     => __( 'Categories:', 'kdi' ),
                    'std'       => '',
                ),
                'template' => array(
                    'type'    => 'select',
                    'std'     => 'modules/contents/content',
                    'label'   => __( 'Template', 'kdi' ),
                    'options' => array(
                        'modules/contents/content'              => __( 'Default', 'kdi' ),
                        'modules/contents/content-widget-post'  => __( 'Card horizontal', 'kdi' ),
                    ),
                ),
                'posts_per_page' => array(
                    'type'  => 'number',
                    'std'   => 6,
                    'min'   => 1,
                    'max'   => 30,
                    'label' => __( 'Number of post to show', 'kdi' ),
                ),
                'orderby' => array(
                    'type'      => 'select',
                    'std'       =>  'ID',
                    'label'     => __( 'Orderby', 'kdi' ),
                    'options'   => array(
                        'ID'        => 'ID',
                        'rand'      => 'Random',
                        'post_date' => 'Post date',
                    ),
                ),
                'order' => array(
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

            add_action( 'kdi_widget_field_post_cat', array( $this, 'post_cat_field' ), 10, 4 );
            parent::__construct();
        }

        public function post_cat_field( $key, $value, $setting, $instance ) {
            $categories = get_categories( array( 
                'parent'        => 0,
                'hide_empty'    => true,
                'orderby'       => 'ID',
                'order'         => 'ASC',
            ) );
            ?>
            <div>
                <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; ?></label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
                    style="height: 5rem;"
                    multiple
                >
                    <option value="" <?php selected( '', $value ); ?> >N/a</option>
                    <?php foreach ( $categories as $cat ) : ?>
                        <option value="<?php echo esc_attr( $cat->cat_ID ); ?>" <?php selected( $cat->cat_ID, $value ); ?>><?php echo esc_html( $cat->name ); ?></option>
                        <?php
                        $children = get_categories( array(
                            'parent'        => $cat->cat_ID,
                            'hide_empty'    => true,
                            'orderby'       => 'ID',
                            'order'         => 'ASC',
                        ) );
                        foreach( $children as $child ) :
                        ?>
                            <option value="<?php echo esc_attr( $child->cat_ID ); ?>" <?php selected( $child->cat_ID, $value ); ?>>--<?php echo esc_html( $child->name ); ?></option>
                        <?php endforeach; ?> 
                        
                    <?php endforeach; ?>
                </select>
            </div>
            <?php
        }

        public function widget( $args, $instance ) {
            extract( $args );

            $template   = isset( $instance['template'] ) ? $instance['template'] : $this->settings['template']['std'];
            $xs         = isset( $instance['xs'] ) ? $instance['xs'] : $this->settings['xs']['std'];
            $sm         = isset( $instance['sm'] ) ? $instance['sm'] : $this->settings['sm']['std'];
            $md         = isset( $instance['md'] ) ? $instance['md'] : $this->settings['md']['std'];

            $query = $this->get_post( $instance );

            echo $before_widget;
            kdi_loop_template( array(
                'query'         => $query,
                'template'      => $template,
                'template_args' => '',
                'loop_before'   => '<div class="row row-cols-'.$xs.' row-cols-sm-'.$sm.' row-cols-md-'.$md.' g-1">',
                'loop_after'    => '</div>',
            ) );
            echo $after_widget;
        }

        public function get_post( $instance ) {
            $post_display       = isset( $instance['post_display'] ) ? $instance['post_display'] : $this->settings['post_display']['std'];
            $posts_per_page     = isset( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : $this->settings['posts_per_page']['std'];
            $orderby            = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
            $order              = isset( $instance['order'] ) ? $instance['order'] : $this->settings['order']['std'];
            $post_cat           = isset( $instance['post_cat'] ) ? $instance['post_cat'] : $this->settings['post_cat']['std'];

            $query_args = array(
                'post_type'         => 'post',
                'post_status'       => 'publish',
                'posts_per_page'    => $posts_per_page,
                'orderby'           => $orderby,
                'order'             => $order,
                // 'cat'               => $post_cat,
                'meta_query'        => array(),
                'tax_query'         => array(),
            );

            if( ! empty( $post_cat ) ) {
                $query_args['tax_query'][] = array(
                    'taxonomy'  => 'category',
                    'field'     => 'term_id',
                    'terms'     => $post_cat,
                );
            }

            $query = null;
            switch( $post_display ) {
                case 'feature':
                    $query_args['meta_query'] = array( 'key' => 'featured', 'value' => 1 );
                    break;
                case 'recent':
                    $query = wp_parse_args( $query, kdi_get_recent_post_args() );
                    break;
                default:
                    break;
            }

            return new WP_Query( $query_args );
        }
    }
endif;

