
<article id="post-<?php the_ID(); ?>" class="post pb-4">
    
    <?php
    /**
     * @hooked kdi_post_header      - 5
     * @hooked kdi_post_content     - 10
     */
    do_action('kdi_loop_page');
    ?>

</article>


