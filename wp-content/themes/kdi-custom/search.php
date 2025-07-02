<?php get_header(); ?>

<div class="container py-5">
  <h1 class="mb-4">Kết quả tìm kiếm cho: "<?php echo get_search_query(); ?>"</h1>

  <div class="row">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <div class="col-md-4 mb-4">
        <?php get_template_part('template-parts/cards/post-card'); ?>
      </div>
    <?php endwhile; else : ?>
      <p>Không tìm thấy kết quả phù hợp.</p>
    <?php endif; ?>
  </div>

  <?php the_posts_pagination(); ?>
</div>

<?php get_footer(); ?>
