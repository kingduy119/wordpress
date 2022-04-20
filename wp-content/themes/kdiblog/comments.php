<?php 

if ( post_password_required() ) { return; } 

function comments_callback($comment, $args, $depth)
{
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
?>

<section id="comments" class="mb-4">

    <h5><?php esc_html_e( 'Bình luận:' , 'kdi' ); ?></h5>
    <div class="comments-wrapper-list mb-4">
        <?php
            if( have_comments() ) {
                wp_list_comments( array(
                    'max_depth'     => 2,
                    'callback'      => 'comments_callback',
                ) );
            }
        ?>
    </div>

    <?php if ( ! comments_open() && 0 !== intval( get_comments_number() ) && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'kdi' ); ?></p>
    <?php endif;

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
    ?>
</section>
