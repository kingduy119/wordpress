<?php get_header(); ?>

<div class="container py-5">
  <h1 class="mb-4">
    <?php
    if (is_category()) {
      single_cat_title();
    } elseif (is_tag()) {
      single_tag_title();
    } elseif (is_author()) {
      the_author();
    } elseif (is_day()) {
      echo 'Lưu trữ ngày: ' . get_the_date();
    } elseif (is_month()) {
      echo 'Lưu trữ tháng: ' . get_the_date('F Y');
    } elseif (is_year()) {
      echo 'Lưu trữ năm: ' . get_the_date('Y');
    } else {
      echo 'Lưu trữ';
    }
    ?>
  </h1>

  <div class="row">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <div class="col-md-4 mb-4">
        <?php get_template_part('template-parts/loop/post-card'); ?>
      </div>
    <?php endwhile; else : ?>
      <p>Không có bài viết nào.</p>
    <?php endif; ?>
  </div>

  <?php the_posts_pagination(); ?>
</div>

<?php get_footer(); ?>
