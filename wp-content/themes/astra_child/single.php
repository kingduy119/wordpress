<?php get_header(); ?>

<main class="kdi-astra-child__main">
    <div class="kdi-astra-child__container container">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                get_template_part('content', 'single');
            endwhile;
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>