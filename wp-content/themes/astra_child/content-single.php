<article id="post-<?php the_ID(); ?>" <?php post_class('kdi-astra-child__post container py-4'); ?>>

    <!-- Breadscrums -->
    <?php get_template_part('template-parts/breadcrumb'); ?>

    <!-- Header -->
   <header class="d-flex flex-column gap-2">
        <div class="d-flex text-muted small gap-2 align-items-center">
            <span><i class="bi bi-person"></i> <?php the_author(); ?></span>
            <span class="separator">•</span>
            <span><i class="bi bi-calendar"></i> <?php echo get_the_date(); ?></span>
        </div>
        <h1 class="h2 fw-bold"><?php the_title(); ?></h1>
    </header>

    <!-- Content -->
    <div class="kdi-astra-child__post-content mb-4">
        <?php the_content(); ?>
    </div>

    <!-- Footer -->
    <footer class="pt-3 mt-3">
        <div class="d-flex flex-column align-items-start gap-2">
            <div class="text-muted small">
                <?php the_category(', '); ?>
            </div>
            <div class="text-muted small">
                <?php the_tags('', ', '); ?>
            </div>
        </div>
    </footer>

    <!-- Navigation -->
    <?php if (get_previous_post() || get_next_post()) : ?>
        <nav class="post-navigation my-5">
            <div class="d-flex justify-content-between">
                <div class="text-start">
                    <?php previous_post_link('%link', '<i class="bi bi-arrow-left"></i> %title'); ?>
                </div>
                <div class="text-end">
                    <?php next_post_link('%link', '%title <i class="bi bi-arrow-right"></i>'); ?>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Comments -->
    <?php comments_template(); ?>

</article>