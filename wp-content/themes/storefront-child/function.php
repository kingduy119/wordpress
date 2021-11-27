<?php

/**
 * Xóa đi các thành phần không sử dụng trong homepage
 * @hook after_setup_theme
 *
 * template-homepage.php
 * @hook homepage
 * @hooked storefront_homepage_content – 10
 * @hooked storefront_product_categories – 20
 * @hooked storefront_recent_products – 30
 * @hooked storefront_featured_products – 40
 * @hooked storefront_popular_products – 50
 * @hooked storefront_on_sale_products – 60
 * @hooked storefront_best_selling_products – 70
 */
function tp_homepage_blocks()
{
    /*
    * Sử dụng: remove_action( 'homepage', 'tên_hàm_cần_xóa', số thứ tự mặc định );
    */
    remove_action('homepage', 'storefront_featured_products', 40);
    remove_action('homepage', 'storefront_popular_products', 50);
}

/**
 * Tùy biến Product by Category
 * @hook storefront_product_categories_args
 *
 */
function tp_product_categories_args($args)
{
    $args = array(
        'limit' => 6,
        'title' => __('Danh mục sản phẩm', 'duyhoang')
    );
    return $args;
}
add_filter('storefront_product_categories_args', 'tp_product_categories_args');


/**
 * Ẩn mã bưu chính
 * Ẩn địa chỉ thứ hai
 * Đổi tên Bang / Hạt thành Tỉnh / Thành
 * Đổi tên Tỉnh / Thành phố thành Quận / Huyện
 *
 *
 * @hook woocommerce_checkout_fields
 * @param $fields
 * @return mixed
 */
function tp_custom_checkout_fields($fields)
{
    // Ẩn mã bưu chính
    unset($fields['postcode']);
    // Ẩn địa chỉ thứ hai
    unset($fields['address_2']);


    // Đổi tên Bang / Hạt thành Tỉnh / Thành
    $fields['state']['label'] = 'Tỉnh / Thành';


    // Đổi tên Tỉnh / Thành phố thành Quận / Huyện
    $fields['city']['label'] = 'Quận / Huyện';


    return $fields;
}
add_filter('woocommerce_default_address_fields', 'tp_custom_checkout_fields');

function my_hide_shipping_when_free_is_available($rates)
{
    $free = array();
    foreach ($rates as $rate_id => $rate) {
        if ('free_shipping' === $rate->method_id) {
            $free[$rate_id] = $rate;
            break;
        }
    }
    return !empty($free) ? $free : $rates;
}


add_filter('woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100);
