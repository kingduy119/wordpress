<?php
$product_categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
]);

if (!empty($product_categories) && !is_wp_error($product_categories)) :
    $first = true;
?>
    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <?php foreach ($product_categories as $cat) : ?>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link <?php echo $first ? 'active' : ''; ?>"
                    id="tab-<?php echo esc_attr($cat->slug); ?>-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-<?php echo esc_attr($cat->slug); ?>"
                    type="button"
                    role="tab"
                    aria-controls="tab-<?php echo esc_attr($cat->slug); ?>"
                    aria-selected="<?php echo $first ? 'true' : 'false'; ?>">
                    <?php echo esc_html($cat->name); ?>
                </button>
            </li>
            <?php $first = false; ?>
        <?php endforeach; ?>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="myTabContent">
        <?php
        $first = true;
        foreach ($product_categories as $cat) :
            // Query các sản phẩm trong từng danh mục
            $args = [
                'post_type' => 'product',
                'posts_per_page' => 4,
                'tax_query' => [
                    [
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $cat->slug,
                    ],
                ],
            ];
            $query = new WP_Query($args);
        ?>
            <div
                class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>"
                id="tab-<?php echo esc_attr($cat->slug); ?>"
                role="tabpanel"
                aria-labelledby="tab-<?php echo esc_attr($cat->slug); ?>-tab">
                
                <?php if ($query->have_posts()) : ?>
                    <div class="row">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <div class="col-md-3 mb-4">
                                
                                <?php get_template_part('template-parts/loop/product-card'); ?>

                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <p>Không có sản phẩm nào trong danh mục này.</p>
                <?php endif; ?>
            </div>
            <?php
            wp_reset_postdata();
            $first = false;
        endforeach;
        ?>
    </div>

<?php endif; ?>