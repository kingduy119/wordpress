<?php

if( ! class_exists( 'Product_List_WG' ) ) {
    class Product_List_WG extends FieldBase_WG {
        public function __construct() {
            $this->wg_id          = 'product_list_widget';
            $this->wg_class       = '';
            $this->wg_name        = __('KDI Products List', 'text_domain');
            $this->wg_description = __('Hiển thị danh sách sản phẩm mới nhất', 'text_domain');

            $this->settings = [
                'total' => [
                    'type'  => 'number',
                    'label' => __('Total', 'text_domain'),
                    'std'   => 4,
                    'min'   => 1,
                    'max'   => 20,
                    'step'  => 1,
                    'style' => '',
                    'class' => '',
                ],
                'row_class' => [
                    'type'  => 'text',
                    'label' => __('Row class (responsive grid)', 'text_domain'),
                    'std'   => 'row g-4 row-cols-2 row-cols-md-3 row-cols-xl-4',
                ],
                'column_class' => [
                    'type'  => 'text',
                    'label' => __('Column class (if needed)', 'text_domain'),
                    'std'   => 'col',
                ],

            ];

            parent::__construct(); // ← Gọi đúng như FieldBase yêu cầu
        }

        // frontend
        public function widget($args, $instance) {
            $total = !empty($instance['total']) ? (int)$instance['total'] : 4;
            $row_class    = !empty($instance['row_class']) ? esc_attr($instance['row_class']) : 'row g-4';
            $column_class = !empty($instance['column_class']) ? esc_attr($instance['column_class']) : '';


            echo $args['before_widget'];

            $query = new WP_Query([
                'post_type' => 'product',
                'posts_per_page' => $total,
            ]);

            echo '<div class="' . $row_class . '">';
            while ($query->have_posts()) {
                $query->the_post();
                $product = wc_get_product(get_the_ID());

                echo '<div class="' . $layout_class . '">';
                wc_get_template_part( 'content', 'product' );
                echo '</div>';
            }
            echo '</div>';

            wp_reset_postdata();
            echo $args['after_widget'];
        }
    }
}
