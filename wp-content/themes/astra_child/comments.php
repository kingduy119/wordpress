<?php
if (post_password_required()) {
    return;
}
?>

<div class="container">
    <div class="comments">
        <?php if (have_comments()) : ?>
            <h2 class="comments__title">
                <?php
                $comment_count = get_comments_number();
                echo esc_html($comment_count) . ' Comments';
                ?>
            </h2>

            <ul class="comments__list">
                <?php
                wp_list_comments([
                    'style'       => 'ul',
                    'short_ping'  => true,
                    'avatar_size' => 48,
                    'callback'    => 'custom_comment_template', // dùng hàm bên dưới
                ]);
                ?>
            </ul>

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                <nav class="comments__navigation" role="navigation">
                    <div class="comments__nav-links">
                        <?php previous_comments_link('← Previous'); ?>
                        <?php next_comments_link('Next →'); ?>
                    </div>
                </nav>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!comments_open()) : ?>
            <p class="comments__closed">Comments are closed.</p>
        <?php endif; ?>

        <div class="comments__form">
            <?php
            comment_form([
                'class_form'           => 'comment-form',
                'title_reply'          => 'Leave a Comment',
                'label_submit'         => 'Post Comment',
                'comment_notes_before' => '',
                'comment_notes_after'  => '',
                'fields'               => [
                    'author' =>
                        '<div class="comment-form__field">
                            <label for="author">Name</label>
                            <input id="author" name="author" type="text" required>
                        </div>',
                    'email' =>
                        '<div class="comment-form__field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" required>
                        </div>',
                    'url' =>
                        '<div class="comment-form__field">
                            <label for="url">Website</label>
                            <input id="url" name="url" type="url">
                        </div>',
                ],
                'comment_field' =>
                    '<div class="comment-form__field">
                        <label for="comment">Comment</label>
                        <textarea id="comment" name="comment" rows="5" required></textarea>
                    </div>',
            ]);
            ?>
        </div>
    </div>
</div>
