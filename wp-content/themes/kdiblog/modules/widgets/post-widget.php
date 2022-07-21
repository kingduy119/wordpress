<?php
/**
 * ******************************************
 * KDI WIDGETS
 * ******************************************
 */

function init_number( $style = '', $label = '' ) {
    return array(
        'type'  => 'number',
        'label' => $label,
        'style' => $style,
        'std'   => 3,
        'min'   => 1,
        'max'   => 12,
    );
}
function init_title( $label = 'Title', $style = 'font-weight: 700; background: black; color: white;', $std = '' ) {
    return array(
        'type'      => 'title',
        'label'     => __( $label , 'kdi' ),
        'style'     => $style,
        'std'       => $std,
    );
}
function init_checkbox( $label = 'Checkbox', $std = -1, $style = '' ) {
    return array(
        'label' => __( $label, 'woocommerce' ),
        'type'  => 'checkbox',
        'std'   => $std,
    );
}

if( ! class_exists( 'KDI_Widget_Post' ) ) :
class KDI_Widget_Post extends KDI_Fields {
    function __construct() {
        $this->wg_id            = 'kdi_post_widget';
        $this->wg_class         = 'kdi_widget_post';
        $this->wg_name          = __( 'KDI Post', 'kdi' );
        $this->wg_description   = __( 'Post loop', 'kdi' );

        $this->settings = array(
            'option_title' => init_title( 'Options' ),
            'post_display' => array(
                'type'    => 'select',
                'std'     => 'newest',
                'label'   => __( 'Post display:', 'kdi' ),
                'options' => array(
                    'newest'    => __( 'Newest', 'kdi' ),
                    'views'     => __( 'Views', 'kdi' ),
                ),
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
            
            'query_title' => init_title( 'Query' ),
            'post_cat' => array(
                'type'      => 'post_cat',
                'label'     => __( 'Categories:', 'kdi' ),
                'std'       => '',
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
                    'post_date' => 'Date',
                    'rand'      => 'Random',
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

            'ROW' => init_title( 'Row' ),
            'xs' => init_number( 'width: 20%; display: inline-block;', __( 'mobile', 'kdi' ) ),
            'sm' => init_number( 'width: 20%; display: inline-block;', __( 'tablet', 'kdi' ) ),
            'md' => init_number( 'width: 20%; display: inline-block;', __( 'desktop', 'kdi' ) ),

            'COL' => init_title( 'Col' ),
            'ixs' => init_number( 'width: 20%; display: inline-block;', __( 'mobile', 'kdi' ) ),
            'ism' => init_number( 'width: 20%; display: inline-block;', __( 'tablet', 'kdi' ) ),
            'imd' => init_number( 'width: 20%; display: inline-block;', __( 'desktop', 'kdi' ) ),

            'POST'      => init_title( 'Post' ),
            'date'      => init_checkbox( 'Date', 1 ),
            'author'    => init_checkbox( 'Author', 1 ),
            'excerpt'   => init_checkbox( 'Excerpt', 1 ),
        );

        add_action( 'kdi_widget_field_post_cat', array( $this, 'post_cat_field' ), 10, 4 );
        add_action( 'kdi_widget_field_title', array( $this, 'field_title' ), 15, 4 );
        parent::__construct();
    }

    public function field_title( $key, $value, $setting, $instance ) {
        ?>
        <div class="field-title" style="<?php echo esc_attr( $setting['style'] ); ?>"><?php echo wp_kses_post( $setting['label'] ); ?></div>
        <?php
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
                <option value="" <?php selected( '', $value ); ?> ><?php echo __( 'All', 'kdi' ); ?></option>
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

        $template_args = array(
            'date'      => isset( $instance['date'] ) ? $instance['date'] : $this->settings['date']['std'],
            'author'    => isset( $instance['author'] ) ? $instance['author'] : $this->settings['author']['std'],
            'excerpt'   => isset( $instance['excerpt'] ) ? $instance['excerpt'] : $this->settings['excerpt']['std'],
        );

        $query = $this->get_post( $instance );

        echo $before_widget;
        kdi_loop_template( array(
            'query'         => $query,
            'template'      => $template,
            'template_args' => $template_args,
            'loop_before'   => '<div class="row row-cols-'.$xs.' row-cols-sm-'.$sm.' row-cols-md-'.$md.' g-1">',
            'loop_after'    => '</div>',
        ) );
        echo $after_widget;
    }

    public function get_post( $instance ) {
        $posts_per_page     = isset( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : $this->settings['posts_per_page']['std'];
        $post_display       = isset( $instance['post_display'] ) ? $instance['post_display'] : $this->settings['post_display']['std'];
        $post_cat           = isset( $instance['post_cat'] ) ? $instance['post_cat'] : $this->settings['post_cat']['std'];
        $orderby            = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
        $order              = isset( $instance['order'] ) ? $instance['order'] : $this->settings['order']['std'];

        $query_args = array(
            'post_type'         => 'post',
            'post_status'       => 'publish',
            'posts_per_page'    => $posts_per_page,
            'orderby'           => $orderby,
            'order'             => $order,
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

        switch( $post_display ) {
            case 'views':
                $query_args['meta_key'] = 'views';
                $query_args['orderby']  = 'meta_value_num';
                break;
            default:
                break;
        }

        return new WP_Query( $query_args );
    }
}
endif;

