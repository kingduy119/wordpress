
<?php get_header(); ?>

<div id="content">
	<div class="product-box pt-2">
		<div class="container">
			<div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
					<div class="category-page-content">
						<h4><?php the_title(); ?></h4>
						<?php while( have_posts() ) : the_post(); ?>
							<p><?php the_category(); ?></p>
							<p><?php the_content(); ?></p>
						<?php endwhile; ?>
					</div>
                </div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
					<div class="sidebar">
						<?php get_sidebar() ?> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer() ?>