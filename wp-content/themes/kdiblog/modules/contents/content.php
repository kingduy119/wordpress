<?php
    extract( $args );
    $date = isset( $date ) ? $date : 1;
    $author = isset( $author ) ? $author : 1;
    $excerpt = isset( $excerpt ) ? $excerpt : 1;
?>

<div class="col">
    <div class="card h-100" id="post-<?php the_ID(); ?>">
        
        <div class="post--thumbnail ratio ratio-4x3 overflow-hidden">
            <a href="<?php echo esc_attr( get_permalink() ); ?>" class="text-black text-decoration-none">
            <?php
                get_template_part( 'modules/loop/thumbnail' );
            ?>
            </a>
        </div>

        <div class="card-body px-3 py-1">
        <?php
            if( $author ) { get_template_part( 'modules/loop/author' ); }
            if( $date ) { get_template_part( 'modules/loop/date' ); }
            get_template_part( 'modules/loop/title' );
            if( $excerpt ) { get_template_part( 'modules/loop/excerpt' ); }
        ?>
        </div>

    </div>
</div>
