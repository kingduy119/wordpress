<!-- content-post -->
<div class="h-100 border rounded p-2">
    <a href="<?php the_permalink(); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium', ['class' => 'img-fluid mb-2']); ?>
        <?php endif; ?>
    </a>

    <h6 class="fw-bold mb-1">
        <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
            <?php the_title(); ?>
        </a>
    </h6>

    <div class="text-muted mb-2">
        <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
    </div>

    <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary w-100">
        Đọc tiếp
    </a>
</div>