<?php
// Nếu bài viết bảo vệ bằng mật khẩu thì không hiển thị bình luận
if (post_password_required()) {
  return;
}
?>

<div id="comments" class="mt-5">
  <?php if (have_comments()) : ?>
    <h3 class="mb-4"><?php comments_number('Chưa có bình luận', '1 bình luận', '% bình luận'); ?></h3>

    <ul class="list-unstyled">
      <?php
      wp_list_comments([
        'style'      => 'ul',
        'short_ping' => true,
        'avatar_size' => 50,
        'callback' => null, // dùng default WP
      ]);
      ?>
    </ul>

    <?php
    if (get_comment_pages_count() > 1 && get_option('page_comments')) :
    ?>
      <nav class="comment-navigation" role="navigation">
        <div class="nav-links">
          <?php paginate_comments_links(); ?>
        </div>
      </nav>
    <?php endif; ?>
  <?php endif; ?>

  <?php if (!comments_open() && get_comments_number()) : ?>
    <p class="text-muted">Bình luận đã bị đóng.</p>
  <?php endif; ?>

  <!-- Form bình luận -->
  <div class="mt-5">
    <?php
    comment_form([
      'class_form' => 'comment-form',
      'title_reply' => '<h4 class="mb-4">Để lại bình luận</h4>',
      'class_submit' => 'btn btn-primary',
      'comment_field' => '
        <div class="form-group mb-3">
          <label for="comment">Bình luận *</label>
          <textarea id="comment" name="comment" class="form-control" rows="4" required></textarea>
        </div>',
      'fields' => [
        'author' => '
          <div class="form-group mb-3">
            <label for="author">Tên *</label>
            <input id="author" name="author" type="text" class="form-control" required />
          </div>',
        'email'  => '
          <div class="form-group mb-3">
            <label for="email">Email *</label>
            <input id="email" name="email" type="email" class="form-control" required />
          </div>',
        'url'    => '
          <div class="form-group mb-3">
            <label for="url">Website</label>
            <input id="url" name="url" type="url" class="form-control" />
          </div>',
      ],
    ]);
    ?>
  </div>
</div>
