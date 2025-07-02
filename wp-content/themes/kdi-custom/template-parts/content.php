
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<!-- Tiêu đề -->
	<h1 class="mb-3"><?php the_title(); ?></h1>

	<!-- Thông tin meta -->
	<div class="mb-4 text-muted small">
	<span>🗓️ <?php echo get_the_date(); ?></span> |
	<span>👤 <?php the_author(); ?></span> |
	<span>📂 <?php the_category(', '); ?></span>
	</div>

	<!-- Ảnh đại diện -->
	<?php if (has_post_thumbnail()) : ?>
	<div class="mb-4">
		<?php the_post_thumbnail('large', ['class' => 'img-fluid rounded']); ?>
	</div>
	<?php endif; ?>

	<!-- Nội dung bài viết -->
	<div class="post-content mb-5">
	<?php the_content(); ?>
	</div>

	<!-- Thẻ -->
	<div class="mb-3">
	<strong>Tags:</strong> <?php the_tags('', ', ', ''); ?>
	</div>
</article>