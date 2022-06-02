<?php 
    global $post; 
    $show_avatar = isset( $args['show_avatar'] ) ? $args['show_avatar'] : true;
?>
<span class="post--author">
    <a
        class="text-secondary text-decoration-none text-uppercase fw-bold"
        href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>"
    >
        <?php if( (bool) $show_avatar ) : ?>
            <img class="img-fluid rounded-circle" src="<?php echo esc_url( get_avatar_url( $post->post_author, ['size' => 24] ) ); ?>" alt="author_url" >
        <?php endif; ?>
        <?php echo esc_html( the_author_meta( 'display_name', $post->post_author ) ); ?>
    </a>
</span>