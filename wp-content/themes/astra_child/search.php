<?php get_header(); ?>

<div class="container my-5 kdi-astra-child__search">
    <h2 class="mb-4">
        Result for: <span class="text-secondary">"<?php echo get_search_query(); ?>"</span>
    </h2>

    <?php if (have_posts()) : ?>
        <div class="row g-4">
            <?php while (have_posts()) : the_post(); ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm kdi-astra-child__post">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="kdi-astra-child__post-thumb">
                                <?php the_post_thumbnail('medium', ['class' => 'card-img-top', 'alt' => get_the_title()]); ?>
                            </a>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title text-truncate-2">
                                <a href="<?php the_permalink(); ?>" class="stretched-link text-decoration-none text-dark fw-semibold">
                                    <?php the_title(); ?>
                                </a>
                            </h5>

                            <p class="card-text text-muted text-truncate-2 mb-2">
                                <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                            </p>
                        </div>

                        <div class="card-footer bg-transparent border-0 small text-muted">
                            <i class="bi bi-person"></i> <?php the_author(); ?> &nbsp;•&nbsp;
                            <i class="bi bi-clock"></i> <?php echo get_the_date(); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php get_template_part('template-parts/pagination'); ?>

    <?php else : ?>
        <div class="alert alert-warning mt-4">
            No results were found matching your keyword.
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>