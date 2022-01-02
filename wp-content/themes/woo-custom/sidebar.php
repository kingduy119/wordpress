<div class="category-box">
    <h3>Danh mục</h3>
    <div class="content-cat">
        <ul>
            <?php
                $args = array(
                    'child_of' 		=> 0,
                    'hide_empty'	=> 0,
                    'parent'        => 0,
                    // 'number' 		=> 5,
                    'type' 			=> 'post',
                );
                $categories = get_categories($args);

                foreach( $categories as $category ) { ?>
                    <li>
                        <i class="fa fa-angle-right"></i> 
                        <a href="<?php echo get_term_link($category->slug, 'category'); ?>"><?php echo $category->name; ?></a>
                    </li>				
                <?php }
            ?>
        </ul>
    </div>
</div>
<div class="category-box">
    <h3>Danh mục sản phẩm</h3>
    <div class="content-cat">
        <ul>
            <?php
                $args = array(
                    'child_of' 		=> 0,
                    'hide_empty'	=> 0,
                    'parent'        => 0,
                    // 'number' 		=> 5,
                    'type' 			=> 'product',
                    'taxonomy' 		=> 'product_cat',
                );
                $categories = get_categories($args);

                foreach( $categories as $category ) { ?>
                    <li>
                        <i class="fa fa-angle-right"></i> 
                        <a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>"><?php echo $category->name; ?></a>
                    </li>				
                <?php }
            ?>
        </ul>
    </div>
</div>
<div class="widget">
    <h3>
        <i class="fa fa-bars"></i>
        Tin tức
    </h3>
    <div class="content-w">
        <ul>
            <?php
                $args = array(
                    'post_type' 			=> 'post',
                    'post_status' 			=> 'publish',
                    'posts_per_page' 		=> 5,
                );
                $posts = new WP_Query( $args );
            ?>
            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </a>
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <div class="clear"></div>
                </li>
            <?php endwhile; wp_reset_postdata(); ?>
        </ul>
    </div>
</div>
<?php if( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar') ) : ?><?php endif; ?>

