<?php


require_once 'modules/kdi-hook.php';
require_once 'modules/kdi-function.php';
require_once 'modules/kdi-shortcode.php';

require_once 'modules/admin/settings.php';

require_once 'modules/widgets/abstract/fields-widget.php';
require_once 'modules/widgets/post-widget.php';

if( kdi_woo_is_actived() ) {
    require_once 'modules/woo/kdi-woo-hook.php';
    require_once 'modules/woo/kdi-woo-function.php';
    require_once 'modules/widgets/product-widget.php';
}

/**
 * ******************************************
 * THEME LOADING
 * ******************************************
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('kdi-css-reset', assets( 'css/reset.css' ) );
    wp_enqueue_style('kdi-css-main', assets( 'css/main.css' ) );
    wp_enqueue_style('kdi-css-bootstrap', assets( 'lib/bootstrap-5/dist/css/bootstrap.css' ) );

    wp_enqueue_style('kdi-icon-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' );
    wp_enqueue_style('kdi-font-google', 'https://fonts.googleapis.com/css2?family=Roboto+Serif:wght@500&display=swap' );


    wp_enqueue_script('kdi-js-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js');
} );

add_action('init', function() {
    // remove_theme_support('widgets-block-editor');
    // ###############################################
    add_theme_support( 'custom-logo');
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // menus:
    register_nav_menu('main-menu', __('Main menu') );
    register_nav_menu('main-menu-product', __('Main menu product') );

    // widget:
    if (function_exists('register_sidebar')) {
        // Header
        register_sidebar(array(
            'name'              => 'Header middle-left',
            'id'                => 'nav-middle-left',
            'before_sidebar'    => '<div id="widget-nav-middle">',
            'after_sidebar'     => '</div>',
            'before_widget'     => '<div id="%1$s" class="wp-widget">',
            'after_widget'      => '</div>',
        ));
        register_sidebar(array(
            'name'              => 'Header middle-right',
            'id'                => 'nav-middle-right',
            'before_sidebar'    => '<div id="widget-nav-middle">',
            'after_sidebar'     => '</div>',
            'before_widget'     => '<div id="%1$s" class="wp-widget">',
            'after_widget'      => '</div>',
        ));
        register_sidebar(array(
            'name'              => 'Header after',
            'id'                => 'middle-header-content',
            'before_sidebar'    => '<div id="middle-header-content" class="container mb-3">',
            'after_sidebar'     => '</div>',
            'before_widget'     => '<div id="%1$s" class="wp-widget">',
            'after_widget'      => '</div>',
        ));

        // Body
        register_sidebar(array(
            'name'              => 'Sidebar',
            'id'                => 'sidebar',
            'before_sidebar'    => '<div id="page-sidebar">',
            'after_sidebar'     => '</div>',
            'before_widget'     => '<div id="%1$s" class="widget">',
            'after_widget'      => '</div>',
        ));

        // Footer
        register_sidebar(array(
            'name'              => 'Footer 1',
            'id'                => 'footer-1',
            'before_sidebar'    => '<div id="widget-footer-1" class="col">',
            'after_sidebar'     => '</div>',
            'before_widget'     => '<div id="%1$s" class="wp-widget">',
            'after_widget'      => '</div>',
        ));
        register_sidebar(array(
            'name'              => 'Footer 2',
            'id'                => 'footer-2',
            'before_sidebar'    => '<div id="widget-footer-2" class="col">',
            'after_sidebar'     => '</div>',
            'before_widget'     => '<div id="%1$s" class="wp-widget">',
            'after_widget'      => '</div>',
        ));
        register_sidebar(array(
            'name'              => 'Footer 3',
            'id'                => 'footer-3',
            'before_sidebar'    => '<div id="widget-footer-3" class="col">',
            'after_sidebar'     => '</div>',
            'before_widget'     => '<div id="%1$s" class="wp-widget">',
            'after_widget'      => '</div>',
        ));
        register_sidebar(array(
            'name'              => 'Footer 4',
            'id'                => 'footer-4',
            'before_sidebar'    => '<div id="widget-footer-4" class="col">',
            'after_sidebar'     => '</div>',
            'before_widget'     => '<div id="%1$s" class="wp-widget">',
            'after_widget'      => '</div>',
        ));
    }
} );

add_action('widgets_init', function() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');

    // Product widget
    unregister_widget( 'WC_Widget_Product_Categories' );
    unregister_widget( 'WC_Widget_Product_Tag_Cloud' );
    unregister_widget( 'WC_Widget_Cart' );
    unregister_widget( 'WC_Widget_Layered_Nav' );
    unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
    unregister_widget( 'WC_Widget_Price_Filter' );
    unregister_widget( 'WC_Widget_Product_Search' );
    unregister_widget( 'WC_Widget_Top_Rated_Products' );
    unregister_widget( 'WC_Widget_Recent_Reviews' );
    unregister_widget( 'WC_Widget_Recently_Viewed' );
    unregister_widget( 'WC_Widget_Product_Categories' );
    unregister_widget( 'WC_Widget_Products' );

    register_widget('KDI_Post');
    register_widget('KDI_Product');
    // register_widget('WC_Widget_Products');
});







// ACF plugin:
// function kdi_get_feature_img($post_ID) {
//     $post_thumbnail_id = get_post_thumbnail_id($post_ID);
//     if ($post_thumbnail_id) {
//         $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
//         return $post_thumbnail_img[0];
//     }
// }

// // [POST]
// add_filter('manage_posts_columns', function ($defaults) {
//     $defaults['featured_image'] = 'Featured Image';
//     return $defaults;
// });
// add_action('manage_posts_custom_column', function ($column_name, $post_ID) {
//     if ($column_name == 'featured_image') {
//         $post_featured_image = kdi_get_feature_img($post_ID);
//         if ($post_featured_image) {
//             echo '<img src="' . $post_featured_image . '" width="50" height="50" />';
//         }
//     }
// }, 10, 2);
// [POST] end

// [CATEGORY]
// add_filter('manage_edit-category_columns', 'category_columns');
// function category_columns() {
//     $new_columns = array(
//         'cb'            => '<input type="checkbox">',
//         'name'          => __('Name'),
//         'image'         => 'Image',
//         'description'   => __('Description'),
//         'slug'          => __('Slug'),
//         'posts'         => __('Posts'),
//     );
//     return $new_columns;
// }

// add_filter('manage_category_custom_column', 'manage_theme_columns', 10, 3);
// function manage_theme_columns($out, $column_name, $term_id) {
//     switch ($column_name) {
//         case 'image': 
// 			$img=get_field("image","category_$term_id");
// 			if(is_array($img)) $img=$img['url'];
// 			if(!$img) $img=get_bloginfo('stylesheet_directory').'/images/placeholder.jpg';
//             $out .= "<img src=\"$img\" width=\"100\" height=\"83\"/>"; 
			
//             break;
 
//         default:
//             break;
//     }
//     return $out;
// }
// end [CATEGORY]

// Add field for attachment
// function ic_image_attachment_columns($columns) {
//     $columns['background'] = __("background","kdi");
//     return $columns;
// }
// add_filter("manage_media_columns", "ic_image_attachment_columns", null, 2);


// add_action('manage_media_custom_column', 'ic_image_attachment_show_column', null, 2);
// function ic_image_attachment_show_column($name) {
//     global $post;//$post->ID
//     switch ($name) {
//         case 'background':
//             $value = get_field("background", $post->ID);
//             echo $value? 'Background':'';
//             break;
//     }
// }

// ################################################


//------------- Ajax wpshare247_action_load_more --------------------------
// function wpshare247_action_load_more_display(){
//     header("Content-Type: application/json", true);
    
//     $arr_response = array();
    
//     //_REQUEST - 1 => Nhận dữ liệu từ Ajax Javascript
//     $i_per_page = $_REQUEST['posts_per_page'];
//     $i_page = $_REQUEST['current_page'] + 1;
    
//     //_ACTION - 2 => Xử lý tại đây
//     $html = '';
//     $args_filter = array(
//                         'post_type' => array('post'), // Thay bằng post_type bạn muốn
//                         'post_status' => array('publish'),
//                         'posts_per_page' => $i_per_page,
//                         'paged' => $i_page,
//                         'orderby'   => 'date',
//                         'order' => 'desc'
//                     );
//     $the_query = new WP_query($args_filter);
//     if($the_query->have_posts()):
//         while ($the_query->have_posts()) : $the_query->the_post();
//             ob_start();
//                 get_template_part( 'template-parts/content', get_post_format() ); 
//                 $html_item = ob_get_contents();
//             ob_end_clean();
            
//             $html .= $html_item;
//         endwhile;
//         wp_reset_postdata();
//     endif;
    
//     //_RESPONSE - 3 => Trả kết quả về cho Ajax Javascript
//     $arr_response = array(
//                         'res' => $html
//                     );
//     wp_send_json($arr_response);
//     die();
    
// }
// add_action( 'wp_ajax_wpshare247_action_load_more', 'wpshare247_action_load_more_display' );
// add_action('wp_ajax_nopriv_wpshare247_action_load_more', 'wpshare247_action_load_more_display');















