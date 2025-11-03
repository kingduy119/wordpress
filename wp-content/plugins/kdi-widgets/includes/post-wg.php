<?php
if (! class_exists('KDI_WG_Posts')) {
    class KDI_WG_Posts extends KDI_WG_Field
    {

        public function __construct()
        {
            $this->wg_id            = 'kdi_wg_post';
            $this->wg_class         = 'kdi_wg_post';
            $this->wg_name          = __('KDI Posts', 'kdi');
            $this->wg_description   = __('Posts widget', 'kdi');

            $this->settings = [
                'sticky' => [
                    'type'    => 'select',
                    'label'   => __('Sticky posts', 'kdi_widgets'),
                    'options' => [
                        'all'      => __('All posts', 'kdi_widgets'),
                        'only'     => __('Only sticky posts', 'kdi_widgets'),
                        'exclude'  => __('Exclude sticky posts', 'kdi_widgets'),
                    ],
                    'std' => 'all',
                ],
                'total' => [
                    'type' => 'number',
                    'label' => __('Total posts', 'kdi_widgets'),
                    'std' => 4,
                    'min' => 1,
                    'max' => 20,
                ],
                'orderby' => [
                    'type' => 'select',
                    'label' => __('Order by', 'kdi_widgets'),
                    'options' => [
                        'date' => 'Date',
                        'title' => 'Title',
                        'views' => 'Views',
                    ],
                    'std' => 'date',
                ],
                'order' => [
                    'type' => 'select',
                    'label' => __('Order', 'kdi_widgets'),
                    'options' => [
                        'ASC' => 'Ascending',
                        'DESC' => 'Descending',
                    ],
                    'std' => 'DESC',
                ],
                'category' => [
                    'type' => 'text',
                    'label' => __('Category slug or ID', 'kdi_widgets'),
                ],
                'tag' => [
                    'type' => 'text',
                    'label' => __('Tag slug or ID', 'kdi_widgets'),
                ],
                'min_views' => [
                    'type' => 'number',
                    'label' => __('Minimum views', 'kdi_widgets'),
                    'std' => 0,
                ],
                'row_class' => [
                    'type'  => 'text',
                    'label' => __('Row class (responsive grid)', 'kdi_widgets'),
                    'std'   => 'row g-4 row-cols-2 row-cols-md-3 row-cols-xl-4',
                ],
                'column_class' => [
                    'type'  => 'text',
                    'label' => __('Column class (if needed)', 'kdi_widgets'),
                    'std'   => 'col',
                ],
            ];

            parent::__construct();
        }

        public function widget($args, $instance)
        {
            $total      = !empty($instance['total']) ? absint($instance['total']) : 4;
            $orderby    = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
            $order      = !empty($instance['order']) ? $instance['order'] : 'DESC';
            $category   = !empty($instance['category']) ? $instance['category'] : '';
            $tag        = !empty($instance['tag']) ? $instance['tag'] : '';
            $min_views  = isset($instance['min_views']) ? absint($instance['min_views']) : 0;

            $row_class    = !empty($instance['row_class']) ? esc_attr($instance['row_class']) : 'row g-4';
            $column_class = !empty($instance['column_class']) ? esc_attr($instance['column_class']) : '';

            $args = [
                'post_type' => 'post',
                'posts_per_page' => $total,
                'orderby' => $orderby === 'views' ? 'meta_value_num' : $orderby,
                'order' => $order,
            ];

            if ($orderby === 'views') {
                $args['meta_key'] = 'post_views_count';
            }

            if ($category) {
                $args[is_numeric($category) ? 'cat' : 'category_name'] = $category;
            }

            if ($tag) {
                $args[is_numeric($tag) ? 'tag_id' : 'tag'] = $tag;
            }

            if ($min_views) {
                $args['meta_query'][] = [
                    'key' => 'post_views_count',
                    'value' => $min_views,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                ];
            }

            $sticky_posts = get_option('sticky_posts');
            $sticky = !empty($instance['sticky']) ? $instance['sticky'] : '';
            if ($sticky === 'only') {
                $args['post__in'] = $sticky_posts;
                $args['ignore_sticky_posts'] = 0;
            } elseif ($sticky === 'exclude') {
                $args['post__not_in'] = $sticky_posts;
                $args['ignore_sticky_posts'] = 1;
            } else {
                $args['ignore_sticky_posts'] = 0;
            }

            $query = new WP_Query($args);


            echo '<div class="' . $row_class . '">';
            while ($query->have_posts()) {
                $query->the_post();

                echo '<div class="' . $column_class . '">';
                kdi_widget_get_template('content-post', [
                    'post' => get_post()
                ]);
                echo '</div>';
            }
            echo '</div>';

            wp_reset_postdata();
            echo $args['after_widget'];
        }
    }
}
