<?php 
function show_comments() {
    if ( have_comments() ) :
        echo '<h5>' .esc_html_e( 'Bình luận:' , 'kdi' ). '</h5>';
        wp_list_comments( array(
            'max_depth' => 2,
            'callback'  => 'comments_callback',
        ) );
        echo '</div>';
    endif;
}

function comments_callback($comment, $args, $depth) {
    // $GLOBALS['comment'] = $comment;
    if ($comment->comment_approved == '1') :
    ?>
        <div class="comments d-flex mb-1">
            <div class="comments-avatar px-1">
                <img class="img-fluid rounded-circle" src="<?php echo esc_url( get_avatar_url( $comment, ['size' => 24] ) ); ?>" alt="author_url" >
            </div>
                
            <div class="comment-content px-1 w-100 border bg-body">
                <?php 
                    echo sprintf(
                        '<h6 class="comments-header"><a class="comments-author" href="%1$s">%2$s</a><small>%3$s - %4$s</small></h6>', 
                        '#', //get_comment_author_url(),
                        get_comment_author(),
                        get_comment_date(),
                        get_comment_time()
                    ); 
                    
                    comment_text();

                    // comments-actions
                    comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'reply_text' => 'Relpy',
                                'depth' => $depth,
                                'max_depth' => $args['max_depth']
                            )
                        )
                    );
                ?>
            </div>            
        </div>
    <?php endif;
}


function show_woo_comments() {
    if ( have_comments() ) : ?>
        <ol class="commentlist">
            <?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
        </ol>
        <?php
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
            echo '<nav class="woocommerce-pagination">';
            paginate_comments_links(
                apply_filters(
                    'woocommerce_comment_pagination_args',
                    array(
                        'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                        'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                        'type'      => 'list',
                    )
                )
            );
            echo '</nav>';
        endif;
        ?>
    <?php else : ?>
        <p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
    <?php endif; 
}

function comments_closed() {
    if ( ! comments_open() && 0 !== intval( get_comments_number() ) && post_type_supports( get_post_type(), 'comments' ) ) {
        echo '<p class="no-comments">' . esc_html_e( 'Comments are closed.', 'kdi' ) . '</p>';
    }
}

function show_comment_form() {
    $comments_arg = array(
        // 'form' => array(), 
        'fields' => array(
            'author'    => '<div class="form-group">'.
                                '<label>' . __('Name') . '</label>' .
                                '<input id="author" name="author" class="form-control" />' .
                            '</div>',
            'email'     => '<div class="form-group">'.
                                '<label for="email">' . __( 'Email' ) . '</label> ' .
                                '<input type="email" id="email" name="email" class="form-control" type="text" size="30" />
                            </div>',
            'url'       => '<div class="form-group">'.
                                '<label for="url">' . __( 'URL' ) . '</label> ' .
                                '<input type="url" id="url" name="url" class="form-control" type="text" size="30" />
                            </div>' 
        ),
        'comment_field' => '<div class="form-group">' . 
                                '<label for="comment">' . __( 'Bình luận' ) . '</label><span>*</span>' .
                                '<textarea id="comment" class="form-control" name="comment" rows="3" aria-required="true"></textarea>' . 
                            '</div>',
        // 'comment_notes_after'   => '',
        // 'title_reply'           => 'Bình luận của bạn',
        // 'title_reply_to'        => 'Trả lời bình luận của %s',
        // 'cancel_reply_link'     => '( Hủy )',
        // 'comment_notes_before'  => 'Địa chỉ email của bạn sẽ không công khai.',
        // 'class_submit'          => 'btn btn-primary',
        // 'label_submit'          => 'Gửi bình luận'
    );

    comment_form($comments_arg);
}

if ( post_password_required() ) { return; } 
?>

<section id="comments" class="mb-4">
    
    <?php
    // show_comments();

    show_woo_comments();

    // comments_closed();

    // show_comment_form();

    ?>

</section>
