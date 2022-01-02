<?php

require 'inc/woocustom-template-hook.php';
require 'inc/woocustom-template-function.php';

function custom_wc_theme_suppor()
{
    add_theme_support('woocomerce');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

function init_theme()
{
    // Chuyển trình soạn thảo về phiên bản cũ
    // add_filter('use_block_editor_for_post', '__return_false');

    // Menus:
    register_nav_menu('header-top', __('Top menu'));
    register_nav_menu('header-main', __('Main menu'));
    register_nav_menu('footer-menu', __('Footer menu'));

    // Sidebar:
    if (function_exists('register_sidebar')) {
        register_sidebar(array(
            'name' => 'Sidebar',
            'id' => 'sidebar',
            'before_widget' => '<div id="%1$s" class="widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }

    function set_postview($postID)
    {
        $count_key = 'views';
        $count = get_post_meta($postID, $count_key, true);
        if ($count == '') {
            $count = 0;
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
        } else {
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    }

    function get_postview($postID)
    {
        $count_key = 'views';
        $count = get_post_meta($postID, $count_key, true);
        if ($count == '') {
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return '0';
        }
        return $count;
    }
}
add_action('init', 'init_theme');


function slider_custom_post_type()
{
    /*
     * Biến $label để chứa các text liên quan đến tên hiển thị của Post Type trong Admin
     */
    $label = array(
        'name' => 'Images slider', //Tên post type dạng số nhiều
        'singular_name' => 'Image slider' //Tên post type dạng số ít
    );
 
    /*
     * Biến $args là những tham số quan trọng trong Post Type
     */
    $args = array(
        'labels' => $label, //Gọi các label trong biến $label ở trên
        'description' => 'Images slider', //Mô tả của post type
        'supports' => array(
            'title',
            // 'editor',
            // 'excerpt',
            // 'author',
            'thumbnail',
            // 'comments',
            // 'trackbacks',
            // 'revisions',
            // 'custom-fields'
        ), //Các tính năng được hỗ trợ trong post type
        // 'taxonomies' => array( 'category', 'post_tag' ), //Các taxonomy được phép sử dụng để phân loại nội dung
        'hierarchical' => false, //Cho phép phân cấp, nếu là false thì post type này giống như Post, true thì giống như Page
        'public' => true, //Kích hoạt post type
        'show_ui' => true, //Hiển thị khung quản trị như Post/Page
        'show_in_menu' => true, //Hiển thị trên Admin Menu (tay trái)
        'show_in_nav_menus' => true, //Hiển thị trong Appearance -> Menus
        'show_in_admin_bar' => true, //Hiển thị trên thanh Admin bar màu đen.
        'menu_position' => 5, //Thứ tự vị trí hiển thị trong menu (tay trái)
        'menu_icon' => 'dashicons-hammer ', //Đường dẫn tới icon sẽ hiển thị
        'can_export' => true, //Có thể export nội dung bằng Tools -> Export
        'has_archive' => true, //Cho phép lưu trữ (month, date, year)
        'exclude_from_search' => false, //Loại bỏ khỏi kết quả tìm kiếm
        'publicly_queryable' => true, //Hiển thị các tham số trong query, phải đặt true
        'capability_type' => 'post' //
    );
 
    register_post_type('slider', $args); //Tạo post type với slug tên là sanpham và các tham số trong biến $args ở trên
}
add_action('init', 'slider_custom_post_type');

// Product section:
function get_products_most_popular() {
?>
    <div class="product-section">
        <h2 class="title-product">
            <a href="#" class="title">Sản phẩm nổi bật</a>
            <div class="bar-menu"><i class="fa fa-bars"></i></div>
            <div class="list-child">
                <ul>
                    <?php
                        $args = array(
                            'type' 			=> 'product',
                            'child_of' 		=> 0,
                            'parent' 		=> 0,
                            'hide_empty'	=> 0,
                            'number' 		=> 3,
                            'taxonomy' 		=> 'product_cat',
                        );
                        $categories = get_categories($args);
                        foreach( $categories as $category ) { ?>
                            <li><a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>"><?php echo $category->name; ?></a></li>				
                        <?php }
                    ?>
                </ul>
            </div>
            <div class="clear"></div>
        </h2>
        <div class="content-product-box">
            <div class="row">
                <?php
                    $args = array(
                        'post_type' 			=> 'product',
                        'posts_per_page' 		=> 8,
                        'product_cat' => 'dien-thoai',
                    );
                    $loop = new WP_Query( $args );
                ?>
                
                <?php while( $loop->have_posts() ) : $loop->the_post(); ?>
                    <?php get_template_part('templates/product', 'item'); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
<?php
}

function get_products_by_phone() {
    $cat = get_term_by('id', 19, 'product_cat');
    $args = array(
        'type' 			=> 'product',
        'child_of' 		=> 0,
        'hide_empty'	=> 0,
        'number' 		=> 5,
        'taxonomy' 		=> 'product_cat',
        'parent'        => $cat->term_id,
    );
    $categories = get_categories($args);
    ?>
    <div class="product-section">
        <h2 class="title-product">
            <a href="<?php echo $cat->slug; ?>" class="title">
                <?php echo $cat->name; ?>
            </a>
            <div class="bar-menu"><i class="fa fa-bars"></i></div>
            <div class="list-child">
                <ul>
                    <?php
                        foreach( $categories as $category ) { ?>
                            <li><a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>"><?php echo $category->name; ?></a></li>				
                        <?php }
                    ?>
                </ul>
            </div>
            <div class="clear"></div>
        </h2>
        <div class="content-product-box">
            <div class="row">
                <?php
                    $args = array(
                        'post_type' 			=> 'product',
                        'posts_per_page' 		=> 8,
                        'ignore_sticky_posts' 	=> 1,
                        'product_cat'           => $cat->slug,
                    );
                    $loop = new WP_Query( $args );
                ?>
                    
                <?php while($loop->have_posts()) : $loop->the_post(); ?>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <?php get_template_part('content/item_product'); ?>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
    <?php
}

function get_products_by_laptop() {
    $cat = get_term_by('id', 17, 'product_cat');
    $args = array(
        'type' 			=> 'product',
        'child_of' 		=> 0,
        'hide_empty'	=> 0,
        'number' 		=> 5,
        'taxonomy' 		=> 'product_cat',
        'parent'        => $cat->term_id,
    );
    $categories = get_categories($args);
    ?>
    <div class="product-section">
        <h2 class="title-product">
            <a href="<?php echo $cat->slug; ?>" class="title">
                <?php echo $cat->name; ?>
            </a>
            <div class="bar-menu"><i class="fa fa-bars"></i></div>
            <div class="list-child">
                <ul>
                    <?php
                        foreach( $categories as $category ) { ?>
                            <li><a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>"><?php echo $category->name; ?></a></li>				
                        <?php }
                    ?>
                </ul>
            </div>
            <div class="clear"></div>
        </h2>
        <div class="content-product-box">
            <div class="row">
                <?php
                    $args = array(
                        'post_type' 			=> 'product',
                        'posts_per_page' 		=> 8,
                        'ignore_sticky_posts' 	=> 1,
                        'product_cat'           => $cat->slug,
                    );
                    $loop = new WP_Query( $args );
                ?>
                    
                <?php while($loop->have_posts()) : $loop->the_post(); ?>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <?php get_template_part('content/item_product'); ?>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
    <?php
}

function price_sale($price, $price_sale) {
    $sale = $price_sale * 100 / $price;
    return number_format(100 - $sale);
}
