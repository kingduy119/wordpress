<div class="card mb-4">
    <!-- <a href="#!"><img class="card-img-top" src="https://dummyimage.com/850x350/dee2e6/6c757d.jpg" alt="..." /></a> -->
    <?php the_post_thumbnail('blog-thumbnail', ['class' => 'card-img-top']) ?>
    <div class="card-body">
        <div class="small text-muted">
            <!-- January 1, 2021 -->
            <?php echo get_the_date() ?> by <a href="#"><?php the_author() ?></a>
        </div>
        <h2 class="card-title">
            <!-- Featured Post Title -->
            <?php the_title() ?>
        </h2>
        <p class="card-text">
        <!-- Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis animi vero voluptate voluptatibus possimus, veniam magni quis! -->
            <?php the_excerpt() ?>
        </p>
        <a class="btn btn-primary" href="<?php echo the_permalink() ?>">Read more →</a>
    </div>
</div>