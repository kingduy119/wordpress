<?php

/**
 * Enqueue parent and child theme styles
 */
function kdi_astra_child_enqueue_styles()
{
    // Parent theme stylesheet
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');

    // Child theme stylesheet (load sau để override)
    wp_enqueue_style('kdi-astra-child-style', get_stylesheet_directory_uri() . '/style.css', ['astra-parent-style'], wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'kdi_astra_child_enqueue_styles');


/**
 * Mở rộng tìm kiếm để bao gồm category, tag (taxonomy) slug hoặc tên.
 */
function kdi_include_category_in_search($query)
{
    if ($query->is_search() && $query->is_main_query() && !is_admin()) {
        global $wpdb;

        // Lấy từ khóa tìm kiếm
        $search_term = $query->get('s');

        // Nếu không có từ khóa, bỏ qua
        if (empty($search_term)) {
            return;
        }

        // Lấy các bài viết thuộc category hoặc tag có tên hoặc slug khớp
        $term_ids = get_terms([
            'taxonomy'   => ['category', 'post_tag'],
            'hide_empty' => false,
            'fields'     => 'ids',
            'name__like' => $search_term, // khớp theo tên
            'slug'       => $search_term  // hoặc slug
        ]);

        if (!empty($term_ids)) {
            $posts_in_terms = get_objects_in_term($term_ids, ['category', 'post_tag']);

            if (!empty($posts_in_terms)) {
                $query->set('post__in', $posts_in_terms);
            }
        }

        // Giữ nguyên search theo nội dung
        $query->set('post_type', ['post']);
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'kdi_include_category_in_search');


// comments.php call back:
if (!function_exists('custom_comment_template')) {
    function custom_comment_template($comment, $args, $depth) {
        $tag = ('div' === $args['style']) ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> <?php comment_class('comment'); ?> id="comment-<?php comment_ID(); ?>">
            <div class="comment__avatar">
                <?php echo get_avatar($comment, $args['avatar_size']); ?>
            </div>

            <div class="comment__body">
                <div class="comment__header">
                    <div class="comment__author"><?php echo get_comment_author(); ?></div>
                    <div class="comment__meta">
                        <span class="comment__date"><?php echo get_comment_date(); ?></span>
                        <span class="comment__time"><?php echo get_comment_time(); ?></span>
                    </div>
                </div>

                <div class="comment__text"><?php comment_text(); ?></div>

                <div class="comment__reply">
                    <?php
                    comment_reply_link([
                        'reply_text' => 'Reply',
                        'depth'      => $depth,
                        'max_depth'  => $args['max_depth'],
                        'before'     => '',
                        'after'      => '',
                    ]);
                    ?>
                </div>
            </div>
        </<?php echo $tag; ?>>
        <?php
    }
}
