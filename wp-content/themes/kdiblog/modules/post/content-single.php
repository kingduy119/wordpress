
<article id="post-<?php the_ID(); ?>" class="post pb-4">
    
    <?php
    do_action('kdi_single_post_top');

    /**
     * @hooked kdi_post_header      - 5
     * @hooked kdi_post_content     - 10
     * @hooked kdi_post_taxonomy    - 20
     */
    do_action('kdi_single_post');

    /**
     * @hooked kdi_post_nav         - 10
     * @hooked kdi_post_comments    - 20
     */
    do_action('kdi_single_post_bottom');
    ?>

</article>


