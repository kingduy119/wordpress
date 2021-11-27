<!-- Title -->
<h1 class="mt-4"><?php the_title() ?></h1>

<p>Posted on <?php echo get_the_date() ?></p>

<hr />



<!-- Post Content -->
<?php the_content() ?>

<!-- Related post -->
<?php //echo hd_theme_related_post('Bài viết liên quan', 4) ?>