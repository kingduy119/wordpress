
<?php if ( post_password_required() ) { return; } ?>

<div class="post--comments pt-3">
    <?php
    // Check comments active
    if ( ! comments_open() && 0 !== intval( get_comments_number() ) && post_type_supports( get_post_type(), 'comments' ) ) {
        echo '<p class="no-comments">' . esc_html_e( 'Comments are closed.', 'kdi' ) . '</p>';
        return;
    }

    if ( have_comments() ) :
        echo '<p class="h3 fw-bold">'  . __('Comments', 'kdi' ) . '</p>';
        wp_list_comments( array(
            'max_depth' => 2,
            'callback'  => 'kdi_comments_callback',
        ) );
    endif;

    // show_woo_comments();
    get_template_part( 'modules/comments/form' );
    ?>

</div>
