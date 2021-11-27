<?php
    class hd_lastest_post extends WP_Widget {
        function __construct() {  
            parent::__construct(
                'hd_lastest_post',
                __('Lastest posts', 'hd_theme'),
                array('description' => __('Widget show bài viết mới nhất', 'hd_theme'))
            );
        }

        // Frontend
        public function widget($args, $instance) {
            $title = apply_filters( 'widget_title', $instance['title'] );
            $sl =   apply_filters( 'widget_sl', $instance['sl'] );

            echo $args['before_widget'];
            if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

            $posts = get_posts(
                array(
                    'numberposts'   => $sl,
                    'orderby'       => 'date'
                )
            );

            if( empty( $posts ) ) return '';

            $out = '<div class="card-body">';
            foreach( $posts as $post )
            {
                $out .= sprintf( 
                    '<div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="%s" class="img-fluid rounded-start">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h6 class="card-title"><a href="%s">%s</a></h6>
                                    <p class="card-tet">
                                        <small>%s</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>',
                    get_the_post_thumbnail_url($post->ID, 'post-small'),
                    get_permalink( $post ),
                    esc_attr( wp_trim_words($post->post_title, 12 ) ),
                    esc_html( get_the_category( $post->ID )[0]->cat_name )
                );
            }
            $out .= '</div>';
            echo $out;
            echo $args['after_widget'];
        }

        // Backend 
        public function form( $instance ) {
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( '', 'mini_blog' );
            }

            if ( isset( $instance[ 'sl' ] ) ) {
                $sl = $instance[ 'sl' ];
            }
            else {
                $sl = __( '', 'mini_blog' );
            }

            ?>
                <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Tiêu đề:' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
                </p>

                <p>
                <label for="<?php echo $this->get_field_id( 'sl' ); ?>"><?php _e( 'Số lượng:' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'sl' ); ?>" name="<?php echo $this->get_field_name( 'sl' ); ?>" type="number" value="<?php echo esc_attr( $sl ); ?>" />
                </p>

            <?php 
        }

        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['sl'] = ( ! empty( $new_instance['sl'] ) ) ? strip_tags( $new_instance['sl'] ) : '';

            return $instance;
        }
    }
