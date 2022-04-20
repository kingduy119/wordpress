

<span class="post--date">
    <a href="<?php esc_attr( get_permalink() ); ?>" rel="bookmark" >
        <i class="bi bi-clock"></i> 
        <time datetime="<?php esc_html( get_the_date( 'c' ) ); ?>">
            <?php esc_html( get_the_date() ); ?>
        </time>
    </a>
</span>
