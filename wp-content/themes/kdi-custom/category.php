<?php get_header(); ?>

<div class="container mt-5">
  <h1 class="mb-4"><?php single_cat_title(); ?></h1>

  <div class="row">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
            </a>
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?php the_title(); ?></h5>
            <p class="card-text"><?php the_excerpt(); ?></p>
            <a href="<?php the_permalink(); ?>" class="btn btn-primary">Xem chi tiết</a>
          </div>
        </div>
      </div>
    <?php endwhile; else: ?>
      <p>Không có bài viết nào.</p>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>
