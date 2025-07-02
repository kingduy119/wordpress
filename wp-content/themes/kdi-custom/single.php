<?php get_header(); ?>

<div class="container py-5 test">
  <div class="row">
    <!-- Nội dung chính -->
    <div class="col-lg-8">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <!-- Article -->
        <?php get_template_part( 'template-parts/content'); ?>

        <!-- Bình luận -->
        <?php comments_template(); ?>

      <?php endwhile; else : ?>
        <p>Không tìm thấy bài viết.</p>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
      <?php //get_sidebar(); ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>
