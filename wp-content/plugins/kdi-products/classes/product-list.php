<?php

class Product_List_WG extends FieldBase_WG {
    public function __construct() {
        $this->wg_id          = 'product_list_widget';
        $this->wg_name        = __('KDI Products List', 'text_domain');
        $this->wg_description = __('Hiển thị danh sách sản phẩm mới nhất', 'text_domain');
        $this->wg_class       = ''; // Có thể thêm class nếu muốn

        $this->settings = [
            'title' => [
                'type'  => 'text',
                'label' => __('Title', 'text_domain'),
                'std'   => __('Title', 'text_domain'),
                'style' => '',
                'class' => '',
            ],
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
        ];

        parent::__construct(); // ← Gọi đúng như FieldBase yêu cầu
    }

    // Giao diện ngoài frontend
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $total = !empty($instance['total']) ? (int)$instance['total'] : 4;

        echo $args['before_widget'];
        echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];

        $query = new WP_Query([
            'post_type' => 'product',
            'posts_per_page' => $total,
        ]);

        echo '<div class="row g-4">';  // g-4 để tạo khoảng cách giữa các cột
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());

            echo '<div class="col-6 col-md-4 col-xl-3">';
            include plugin_dir_path(dirname(__FILE__)) . 'templates/product-template.php';
            echo '</div>';
        }
        echo '</div>';

        wp_reset_postdata();
        echo $args['after_widget'];
    }
}

?>