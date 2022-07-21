<?php

if( ! class_exists( 'KDI_Shortcode' ) ) {
    class KDI_Shortcode {
        function __construct() {
            $this->init();
        }

        public static function init() {
            $shortcodes = array(
                // 'categories'            => __CLASS__ . '::categories',
                // 'archives'              => __CLASS__ . '::archives',
                'tabs_link'             => __CLASS__ . '::tabs_link',

                'products_cat'          => __CLASS__ . '::filter_products_by_category',
            );

            foreach ( $shortcodes as $shortcode => $function ) {
                add_shortcode( "kdi_{$shortcode}", $function );
            }
        }

        public static function tabs_link() {
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

        public static function categories() {
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

        public static function archives() {
            ?>
            <div class="widget">
                <ul id="widget-list-date" class="list-group list-group-flush">
                    <?php wp_get_archives('type=monthly'); ?>
                </ul>
            </div>
            <?php wp_reset_postdata();
        }
        
        public static function tags() {
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
    }
}
