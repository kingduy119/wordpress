<?php
// KDI Astra Child: Bootstrap Pagination
global $wp_query;

$big = 999999999; // số lớn cho rewrite
$pages = paginate_links([
    'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format'    => '?paged=%#%',
    'current'   => max(1, get_query_var('paged')),
    'total'     => $wp_query->max_num_pages,
    'type'      => 'array',
    'prev_text' => __('« Trước', 'kdi-astra-child'),
    'next_text' => __('Sau »', 'kdi-astra-child'),
]);

if (is_array($pages)) :
?>
    <nav aria-label="KDI pagination">
        <ul class="pagination justify-content-center mt-4">
            <?php foreach ($pages as $page) : ?>
                <li class="page-item <?php echo strpos($page, 'current') !== false ? 'active' : ''; ?>">
                    <?php
                    // Chuyển class mặc định của WP thành Bootstrap
                    echo str_replace(
                        ['page-numbers', 'current'],
                        ['page-link', 'active'],
                        $page
                    );
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php
endif;
