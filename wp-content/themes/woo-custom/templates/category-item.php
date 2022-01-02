<div class="list-news pt-3">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <a href="<?php the_permalink(); ?>">
                <?php if( has_post_thumbnail() ) {
                    the_post_thumbnail('full', array('style' => 'height: 250px;'));
                } ?>
            </a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <h4>
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
                <p>By: <?php the_author(); ?></p>
            </h4>
            <?php the_excerpt(); ?>
            <a href="<?php the_permalink(); ?>" class="read-more">Read more</a>

            <p><?php the_tags(); ?></p>
        </div>
    </div>
</div>