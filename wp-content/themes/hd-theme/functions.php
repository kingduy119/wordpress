<?php
require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';

// Ảnh đại diện bên ngoài blog
add_image_size('blog-thumbnail', 850, 350, true);

// Ảnh hiển thị trong bài viết
add_image_size('post-large', 980, 600, true);
add_image_size('post-small', 250, 208, true);


function register_header_menu()
{
    register_nav_menu('header-menu', __('Header Menu'));
}
add_action('init', 'register_header_menu');

function mini_blog_widgets_init()
{
    register_sidebar(array(
        'name'          => __('Sidebar', 'sidebar-mini'),
        'id'            => 'sidebar-mini',
        'description'   => __('Ở đây sẽ chứa những widget của Mini Blog', 'sidebar-mini'),
        'before_widget' => '<div id="%1$s" class="card my-4 %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="card-header">',
        'after_title'   => '</h5>',
    ));
}
add_action('widgets_init', 'mini_blog_widgets_init');


function mini_blog_pagination()
{
    global $wp_query;

    $pages = paginate_links(
        array(
            'format'        => '?paged=%#%',
            'current'       => max(1, get_query_var('paged')),
            'total'         => $wp_query->max_num_pages,
            'type'          => 'array',
            'prev_next'     => true,
            'prev_text'     => __('« Trước'),
            'next_text'     => __('Sau »'),
        )
    );

    if (is_array($pages)) {
        $paged = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');
        $pagination = '<ul class="pagination justify-content-center my-4">';
        foreach ($pages as $page) {
            $pagination .= "<li class='page-item'>$page</li>";
        }
        $pagination .= '</ul>';

        echo $pagination;
    }
}

function hd_theme_breadcrumbs()
{
    if (!is_home()) {
        echo '<nav aria-label="breadcrumb">';
        echo '<ol class="breadcrumb">';
        echo '<li class="breadcrumb-item"><a href="' . home_url('/') . '" class="text-decoration-none">Trang chủ</a></li>';
            if (is_category() || is_single()) {
                $categories = wp_get_post_terms(get_the_id(), 'category');
                if ($categories) :
                    foreach ($categories as $category) : ?>
                        <li class="breadcrumb-item">
                            <a href="<?php echo get_term_link($category->term_id, 'category') ?>" class="text-decoration-none">
                                <?php echo $category->name; ?>
                            </a>
                        </li>
                    <?php endforeach;
                endif;
                if (is_single()) {
                    the_title('<li class="breadcrumb-item active" aria-current="page">', '</li>');
                }
            } elseif (is_page()) {
                the_title('<li class="breadcrumb-item active">', '</li>');
            } elseif (is_tag()) {
                echo '<li class="breadcrumb-item active">Thẻ</li>';
            } elseif (is_search()) {
                echo '<li class="breadcrumb-item active">Tìm kiếm</li>';
            } elseif (is_author()) {
                echo '<li class="breadcrumb-item active">Tác giả</li>';
            } elseif (is_archive()) {
                echo '<li class="breadcrumb-item active">Lưu trữ</li>';
            }else{
                echo '<li class="breadcrumb-item active">Error 404</li>';
            }
        echo '</ol>';
        echo '</nav>';
    }
}

function hd_theme_related_post($title = 'Bài viết liên quan', $count = 5)
{
    global $post;
    $tag_ids = array();
    $current_cat = get_the_category($post->ID);
    $current_cat = $current_cat[0]->cat_ID;
    $this_cat = '';
    $tags = get_the_tags($post->ID);
    if ($tags) {
        foreach ($tags as $tag) {
            $tag_ids[] = $tag->term_id;
        }
    } else {
        $this_cat = $current_cat;
    }

    $args = array(
        'post_type'   => get_post_type(),
        'numberposts' => $count,
        'orderby'     => 'rand',
        'tag__in'     => $tag_ids,
        'cat'         => $this_cat,
        'exclude'     => $post->ID
    );
    $related_posts = get_posts($args);

    if (empty($related_posts)) {
        $args['tag__in'] = '';
        $args['cat'] = $current_cat;
        $related_posts = get_posts($args);
    }
    if (empty($related_posts)) {
        return;
    }
    $post_list = '';
    foreach ($related_posts as $related) {

        $post_list .= '<div class="card mb-1 ">';
        $post_list .=   '<div class="row g-0">';
        $post_list .=       '<div class="col-md-3">';
        $post_list .=           '<img class="img-fluid" style="width: 150px" src="' . get_the_post_thumbnail_url($related->ID, 'post-small') . '" alt="Generic placeholder image">';
        $post_list .=       '</div>';
        $post_list .=       '<div class="col-md-9">';
        $post_list .=           '<div class="card-body">';
        $post_list .=               '<h5 class="mt-0"><a href="' . get_permalink($related->ID) . '">' . $related->post_title . '</a></h5>';
        $post_list .=                   get_the_category($related->ID)[0]->cat_name;
        $post_list .=           '</div>';
        $post_list .=       '</div>';
        $post_list .=   '</div>';
        $post_list .= '</div>';
    }

    return sprintf('
        <div class="card">
            <h4 class="card-header">%s</h4>
            <div class="card-body p-0">%s</div>
        </div>
    ', $title, $post_list);
}

// Home page method:
function hd_home_carouse_category($carousel_id = '' ,$posts_per_page = 1){
    $args = [
        'posts_per_page'      => $posts_per_page,
    ];
    $the_query = new WP_Query($args);
    ?>
        <div id="<?php $carousel_id ?>" class="carousel slide mb-3" data-bs-ride="carousel">

            <div class="carousel-inner">
                <?php if( $the_query->have_posts() ) : ?>
                    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
                        <div class="carousel-item <?php echo $the_query->current_post == 0 ? 'active' : '' ?>">
                            <?php the_post_thumbnail('blog-thumbnail', ['class'=>'d-block w-100']) ?>
                            <div class="carousel-caption d-none d-md-block bg-secondary opacity-75">
                                <h5>
                                    <a class="text-decoration-none text-light" href="<?php the_permalink() ?>">
                                        <p><?php echo $the_query->current_post; ?></p>
                                        <b><?php the_excerpt() ?></b>
                                    </a>
                                </h5>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carousel-home-page" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carousel-home-page" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carousel-home-page" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-home-page" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-home-page" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        
    <?php wp_reset_postdata();
}

function hd_home_page_category_lastest($cat_name = '', $cat_text = '' ,$posts_per_page = 4){
    $args = [
        'category_name' => $cat_name,
        'posts_per_page' => $posts_per_page
    ];
    $the_query = new WP_Query($args);
    ?>
        <div class="card my-4">
            <h5 class="card-header">
                <?php echo $cat_text; ?>
            </h5>
            <div class="card-body">
                <div class="row">
                    <?php if( $the_query->have_posts() ) : ?>
                        <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>

                            <?php get_template_part('template-parts/content-home', get_post_format()); ?>

                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php wp_reset_postdata() ?>
    <?php
}

// comments
function hd_blog_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    ?>
    <?php if ($comment->comment_approved == '1') : ?>
        <li class="media mb-4">
            <?php echo '<img class="d-flex mr-3 rounded-circle" src="' . get_avatar_url($comment) . '" style="width: 60px;">' ?>
            <div class="media-body">
                <?php echo  '<h5 class="mt-0 mb-0"><a rel="nofllow" href="' . get_comment_author_url() . '">' . get_comment_author() . '</a> - <small>' . get_comment_date() . ' - ' . get_comment_time() . '</small></h5>' ?>
                <p class="mt-1">
                    <?php comment_text() ?>
                </p>

                <div class="reply">
                    <?php comment_reply_link(array_merge($args, array('reply_text' => 'Trả lời', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
            </div>
        </li>
    <?php endif; ?>
<?php
}

// Widget custom
require_once get_template_directory() . '/widgets/hd_widget_lastest_post.php';
function hd_load_widget_lastest_post() {
    register_widget( 'hd_lastest_post' ); // gọi ID widget
}
add_action( 'widgets_init', 'hd_load_widget_lastest_post' );



