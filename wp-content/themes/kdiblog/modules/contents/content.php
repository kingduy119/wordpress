
<div class="col">
    <div class="card h-100" id="post-<?php the_ID(); ?>">
        <a href="<?php echo esc_attr( get_permalink() ); ?>" class="text-black text-decoration-none">
        <?php
            get_template_part( 'modules/loop/thumbnail' );
        ?>
        </a>

        <div class="card-body px-3 py-1">
        <?php
            get_template_part( 'modules/loop/author' );
            get_template_part( 'modules/loop/date' );
            get_template_part( 'modules/loop/title' );
            get_template_part( 'modules/loop/excerpt' );
        ?>
        </div>

    </div>
</div>
