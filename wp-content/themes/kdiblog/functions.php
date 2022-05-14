<?php


require_once dirname( __FILE__ ) . '/modules/kdi-post.php';
require_once dirname( __FILE__ ) . '/modules/kdi-post-hook.php';
require_once dirname( __FILE__ ) . '/modules/kdi-post-function.php';
require_once dirname( __FILE__ ) . '/modules/kdi-post-shortcode.php';

require_once dirname( __FILE__ ) . '/modules/admin/settings.php';

if( kdi_woo_is_actived() ) {
    require_once dirname( __FILE__ ) . '/modules/kdi-product.php';
    require_once dirname( __FILE__ ) . '/modules/kdi-product-hook.php';
    require_once dirname( __FILE__ ) . '/modules/kdi-product-function.php';
}















































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
//                         'orderby'   => 'date',category
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















