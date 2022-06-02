<article id="post-<?php the_ID(); ?>" class="post pb-4">
    <div id="entry-breadcrumb" class="fw-bold">
        <?php get_template_part('modules/single-post/breadcrumb'); ?>
    </div>
    <header class="entry-header">
        <?php
        /**
         * @hooked kdi_post_title     - 10
         */
        do_action('kdi_post_single_header');
        ?>
    </header>

    <div class="entry-body">
        <?php
        /**
         * @hooked kdi_post_content     - 10
         */
        do_action('kdi_post_single_body');

        /**
         * @hooked kdi_post_nav         - 10
         * @hooked kdi_post_comments    - 20
         */
        do_action('kdi_post_single_bottom');
        ?>
    </div>

</article>


