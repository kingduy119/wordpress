<?php

if( ! class_exists( 'KDI_Shortcode' ) ) {
    class KDI_Shortcode {
        private $query;

        function __construct() {
            add_shortcode( 'kdi_categories', array( $this, 'categories' ) );
            add_shortcode( 'kdi_archives', array( $this, 'archives' ) );
            add_shortcode( 'kdi_recents', array( $this, 'recents' ) );
            add_shortcode( 'kdi_tags', array( $this, 'tags' ) );

            add_shortcode( 'kdi_loop', array( $this, 'loop_template_part' ) );

            add_shortcode( 'kdi_tabs_link', array( $this, 'tabs_link' ) );


            add_shortcode( 'products_by_category', array( $this, 'filter_products_by_category' ) );
            add_shortcode( 'kdi_product_moi_nhat', array( $this, 'product_moi_nhat' ) );
            add_shortcode( 'kdi_product_danh_muc', array( $this, 'product_danh_muc' ) );
            add_shortcode( 'kdi_product_noibat', array( $this, 'products_noibat' ) );
            add_shortcode( 'kdi_product_ban_chay', array( $this, 'product_ban_chay' ) );
        }

        function tabs_link() {
            ?>
            <div id="tabs-link" class="kdi-tabs">
                <div class="tabs-header row g-0">
                    <div class="tabs--item col-6 active" onclick="onTabSelect(event, 'tabs-link', 'tab-categories')" >Categories</div>
                    <div class="tabs--item col-6" onclick="onTabSelect(event, 'tabs-link', 'tab-archives')" >Archives</div>
                </div>
                <div id="tab-categories" class="tabs-content active">
                    <?php get_template_part('modules/list/categories'); ?>
                </div>
                <div id="tab-archives" class="tabs-content">
                    <?php get_template_part('modules/list/archives'); ?>
                </div>
            </div>
            <script>
                function onTabSelect(ev, tabs, id) {
                   $(`#${tabs}, .tabs--item`).each(function(){
                       $(this).removeClass(' active');
                   });
                   ev.currentTarget.className += ' active';

                   $(`#${tabs}, .tabs-content`).each(function(){
                       $(this).removeClass(' active');
                   });
                   $(`#${id}`).addClass(' active');
                }
            </script>
            <?php
        }

        function categories() {
            $args = array(
                'orderby'   => 'name',
                'order'     => 'ASC',
                'parent'    => '0',
            );
            $categories = get_categories( $args );
            ?>
            <div class="list-categories">
                <ul class="list-group list-group-flush">
                <?php
                    foreach( $categories as $cat ) {
                        $link_class = 'text-dark text-decoration-none';
                        $link = sprintf('<a class="%1$s" href="%2$s">%3$s (%4$s)</a>', $link_class, get_term_link($cat->slug, 'category'), $cat->name, $cat->count);
                        $item = sprintf('<li class="list-group-item">%s</li>', $link);
                        echo $item;
                    }
                ?>
                </ul>
            </div>
            <?php  wp_reset_postdata();
        }
        function archives() {
            ?>
            <div class="widget">
                <ul id="widget-list-date" class="list-group list-group-flush">
                    <?php wp_get_archives('type=monthly'); ?>
                </ul>
            </div>
            <?php wp_reset_postdata();
        }
        function recents() {
            $recent_posts = wp_get_recent_posts( array(
                'numberposts' => 4,
                'post_status' => 'publish',
            ) );
            ?>
                <?php foreach( $recent_posts as $post_item ) : ?>
                    <a class="text-light text-decoration-none" href="<?php echo get_permalink($post_item['ID']) ?>">
                        <p class="slider-caption-class"><?php echo $post_item['post_title']; ?></p>
                    </a>
                <?php endforeach; ?>
            <?php wp_reset_postdata();
        }
        function tags() {
            $tags = get_tags();
            $html = '<div class="widget">';
            foreach ( $tags as $tag ) {
                $tag_link = get_tag_link( $tag->term_id );
                $tag_class = 'badge bg-secondary text-light text-decoration-none me-1';
                
                $html .= "<a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag_class}'>{$tag->name}</a>";
            }
            $html .= '</div>';
            echo $html;

            wp_reset_postdata();
        }

        function loop_template_part( $atts = array(), $content = null, $tag = null ) {
            $atts = array_change_key_case( (array) $atts, CASE_LOWER );
            $default = array(
                'posts_per_page'    => 5,
                'post_type'         => 'post',
                'post_status'       => 'publish',
                'paged'             => 1,
            );
            $args = shortcode_atts( $default, $atts, $tag );
            $atts = array_merge( [ 'template' => 'post/card' ], $atts );

            $this->query = new WP_Query( $args );

            return $this->do_loop( $atts );
        }

        function do_loop( $args = array() ) {
            $template = isset( $args['template'] ) ? 'modules/'.$args['template'] : '';
            $part = isset( $args['part'] ) ? $args['part'] : '';

            $container = isset( $args['container'] ) ? $args['container'] : '';
            $container_class = $args['container_class'];

            $item = isset( $args['item'] ) ? $args['item'] : '';
            $item_class = $args['item_class'];

            ob_start();
            if( $container ) echo '<'.$container.' class="'.$container_class.'">';

            while( $this->query->have_posts() ) : $this->query->the_post();
                if( $item ) echo '<'.$item.' class="'.$item_class.'">';
                    
                    get_template_part( $template, $part );
                    
                if( $item ) echo '</'.$item.'>';
            endwhile;

            if( $container ) echo '</'.$container.'>';

            wp_reset_postdata();
            return ob_get_clean();
        }

        // product filter
        function filter_products_by_category() {
            $args = array(
                'orderby'   => 'name',
                'order'     => 'ASC',
                'parent'    => '0',
                // 'taxonomy'  => 'product_cat',
            );
            $categories = get_categories( $args );

            $block = '<div class="card container border-info">';
            
            foreach( $categories as $cat ) {
                $block .= '<div class="form-check">';
                $block .= '<input class="form-check-input" type="checkbox" value="' .$cat->slug. '" id="' .$cat->slug. '">';
                $block .= '<label class="form-check-label" for="' .$cat->slug. '">' .$cat->name. '</label>';
                $block .= '</div>';
            }

            $block .= '</div>';
            return $block;
        }

        // product section
        function product_moi_nhat() {
            $args_filter = array(
                'post_type'         => array('product'),
                'post_status'       => array('publish'),
                'posts_per_page'    => 6,
            );

            $this->query = new WP_query($args_filter);

            ob_start();
            echo '<div class="row row-cols-6">';
            while( $this->query->have_posts() ) : $this->query->the_post();

            echo '<div class="col">';
            get_template_part('modules/product/card');
            echo '</div>';

            endwhile;
            echo '</div>';

            wp_reset_postdata();
            return ob_get_clean();
        }

        function product_danh_muc() {
            $args_filter = array(
                'post_type'         => array('product'),
                'post_status'       => array('publish'),
                'posts_per_page'    => 6,
                'product_cat'       => 'dien-thoai',
            );

            $this->query = new WP_query($args_filter);

            ob_start();
            echo '<div class="row row-cols-6">';
            while( $this->query->have_posts() ) : $this->query->the_post();

            echo '<div class="col">';
            get_template_part('modules/product/card');
            echo '</div>';

            endwhile;
            echo '</div>';

            wp_reset_postdata();
            return ob_get_clean();
        }

        function products_noibat() {
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; // trang dữ hiện tại cần lấy dữ liệu
            $posts_per_page = 6;

            $args_filter = array(
                'post_type'         => array('product'),
                'post_status'       => array('publish'),
                'posts_per_page'    => $posts_per_page,
                'meta_key'          => '_featured',
                'meta_value'        => 'yes',
            );

            $this->query = new WP_query($args_filter);

            ob_start();
            echo '<div class="row row-cols-6">';
            while( $this->query->have_posts() ) : $this->query->the_post();

            echo '<div class="col">';
            get_template_part('modules/product/card');
            echo '</div>';

            endwhile;
            echo '</div>';

            wp_reset_postdata();
            return ob_get_clean();
        }

        function product_ban_chay() {
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; // trang dữ hiện tại cần lấy dữ liệu
            $posts_per_page = 6;

            $args_filter = array(
                'post_type' => array('product'),
                'post_status' => array('publish'),
                'meta_key' => 'total_sales',
                'orderby' => 'meta_value_num',
                'posts_per_page' => $posts_per_page,
                'paged' => $paged
            );

            $this->query = new WP_query($args_filter);

            ob_start();
            echo '<div class="row row-cols-6">';
            while( $this->query->have_posts() ) : $this->query->the_post();

            echo '<div class="col">';
            get_template_part('modules/product/card');
            echo '</div>';

            endwhile;
            echo '</div>';

            wp_reset_postdata();
            return ob_get_clean();
        }

    }

}

return new KDI_Shortcode();


function kdi_shortcode_posts_view_popular($atts = [], $content = null, $tag = null) {
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );

    $default = array(
        'size'  => 5,
    );
    $args = shortcode_atts( $default, $atts, $tag);

    $m_query = new WP_Query( array(
        'meta_key'      => 'views',
        'orderby'       => 'meta_value_num',
        'order'         => 'DESC',
        'posts_per_page' => $args['size'],
    ) );
    ?>
        <div class="widget">
            <?php 
                echo '<ul id="list-popular-post" class="list-group list-group-flush">';
                while( $m_query->have_posts() ) : $m_query->the_post();
                    $class = 'text-dark text-decoration-none';
                    $link = sprintf('<a class="%1$s" href="%2$s">%3$s</a>', $class, get_permalink(), get_the_title() );
                    echo '<li class="list-group-item">' . $link . '</li>';
                endwhile;
                echo '</ul>';
            ?>
        </div>
    <?php wp_reset_postdata();
}

// #Post:

if( ! function_exists( 'kdi_featured_section' ) ) {
    function kdi_featured_section() {
        $categories = get_categories( array(
            'orderby'       => 'name',
            'parent'        => 0,
            'hide_tempty'   => 0,
        ) );
        global $m_query, $post;
        $m_query = new WP_Query( array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'order' => 'DESC',
        ) );
        $posts = $m_query->get_posts();
        ?>
            <section id="home-feature-section" class="section mb-4">
                <div class="section-header d-flex align-items-end mb-4">
                    <h3 class="section-title flex-grow-1 m-0">Featured Stories</h3>
                    <div class="section-subcat">
                        <ul>
                            <?php
                                foreach( $categories as $cat ) {
                                    echo sprintf('<li><a href="%s">%s</a></li>',
                                        get_term_link( $cat->slug, 'category' ),
                                        $cat->name
                                    );
                                }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="section-content">
                    <div class="row">
                        <div id="home-feature-section-left" class="col-12 col-xs-6 col-md-6">
                            <?php
                                $post = $posts[0];
                                get_template_part('modules/post/card', 'vertical');
                            ?>
                        </div>
                        <div id="home-feature-section-right" class="col-12 col-xs-6 col-md-6">
                            <?php
                                for( $i = 1; $i < count( $posts ); $i++ ) :
                                    $post = $posts[$i];
                                    get_template_part('modules/post/card');
                                endfor;
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php
    }
}

function sc_kdi_posts( $atts = array(), $content = null, $tag = null) {
    $atts = array_change_key_case( $atts, CASE_LOWER );
    $default = array(
        'posts_per_page'    => 6,
        'post__not_in'      => array( get_the_ID() ),
        'post_status'       => 'publish',
        'post_type'         => 'post',
        'order'             => 'DESC',
    );
    
    $args = shortcode_atts( $default, $atts, $tag);
    $m_query = new WP_Query( $args );
    
    ob_start();

    echo '<div class="row row-cols-3" >';
    while( $m_query->have_posts() ) : $m_query->the_post();
        echo '<div class="col p-0" >';
        get_template_part('modules/post/card', 'vertical');
        echo '</div>';
    endwhile;
    echo '</div>';

    $out = ob_get_clean();

    return $out;
}

function kdi_shortcode_categories_link() {
    $categories = get_categories(array( 'parent' => 0 ));
    $item = '<div class="col"><a class="text-light text-decoration-none fw-bold" href="%1$s" >%2$s</a></div>';
    
    $html = '<div class="widget row row-cols-2">';
    foreach( $categories as $cat ) :
        $html .= sprintf($item, get_term_link($cat->slug, 'category'), $cat->name );
    endforeach;
    $html .= '</div>';

    echo $html;
}

/**
 * PRODUCT
 */

add_shortcode('kdi_products_cat', 'kdi_shortcode_products_cat');

function kdi_shortcode_products_cat($atts = [], $content = null, $tag = null) {
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
    $default = array(
        'parent'        => 0,
        'child_of'      => 0,
        'hide_empty'    => 0,
        'type'          => 'product',
        'taxonomy'      => 'product_cat',
    );

    $args = shortcode_atts( $default, $atts, $tag);
    $categories = get_categories( $args );

    ?>
    <div class="list-products-cat">
        <ul class="list-group list-group-flush">
        <?php
            foreach( $categories as $cat ) {
                $class = 'text-dark text-decoration-none';
                $slug = get_term_link($cat->slug, 'product_cat');
                $link = sprintf('<a class="%1$s" href="%2$s">%3$s (%4$s)</a>', $class, $slug, $cat->name, $cat->count);
                $item = sprintf('<li class="list-group-item">%s</li>', $link);
                echo $item;
            }
        ?>
        </ul>
    </div>
    <?php wp_reset_postdata();
}


