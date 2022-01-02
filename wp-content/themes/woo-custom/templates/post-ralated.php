
<div class="related-post">
    <?php 
        $categories = get_the_category($post->ID);
        if($categories) {
            $category_ids = array();
            foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;

            $args = array(
                'category__in'		=> $category_ids,
                'post__not_in'	=> array($post->ID),
                'showposts'			=> 5
            );
            $my_query = new WP_query($args);
            if($my_query->have_posts()) {
                echo '<h3>Relate posts:</h3>';
                while($my_query->have_posts()) {
                    $my_query->the_post();
                    ?>
                    <li>
                        <div class="news-img">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(85, 75)); ?></a>
                        </div>
                        <div class="item-list">
                            <h4>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h4>
                            <?php the_excerpt(); ?>
                        </div>
                    </li>
                    <?php
                }
            }
        }
    ?>
</div>