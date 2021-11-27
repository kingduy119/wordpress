<!-- Title -->
<h1 class="mt-4"><?php the_title() ?></h1>

<!-- Author -->
<p class="lead">
    by
    <?php the_author_posts_link() ?>
    in
    <?php the_category(' &nbsp;&#47;&nbsp; ') ?>
    posted on
    <?php echo get_the_date() ?>
</p>
<hr>

<!-- Preview Image -->
<?php //the_post_thumbnail('post-large',['class'=> 'img-fluid rounded']) ?>

<!-- Post Content -->
<?php the_content() ?>

<!-- Tags -->
<?php the_tags() ?>
<br />

<!-- Related post -->
<?php echo hd_theme_related_post('Bài viết liên quan', 4) ?>

<hr>
<div class="mt-3">
    <?php 
        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }
    ?>
</div>