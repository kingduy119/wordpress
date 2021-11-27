<div class="col-lg-6">
    <div class="card mb-4">
        <a href="<?php the_permalink() ?>">
            <!-- <img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="..." /> -->
            <?php the_post_thumbnail('post-small', ['class'=>'card-img-top']) ?>
        </a>
        <div class="card-body">
            <div class="small text-muted"><?php echo get_the_date() ?></div>
            <h2 class="card-title h4">
                <a href="<?php the_permalink() ?>" class="text-decoration-none">
                    <?php echo wp_trim_words(get_the_title(), 12) ?>
                </a>
            </h2>
            <!-- <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla.</p> -->
            <a class="btn btn-primary" href="<?php the_permalink() ?>">Read more →</a>
        </div>
    </div>
</div>