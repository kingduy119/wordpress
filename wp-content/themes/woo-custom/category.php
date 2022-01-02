
<?php get_header(); ?>

<div id="content">
	<div class="product-box pt-2">
		<div class="container">
			<div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
					
					<?php 
						while( have_posts() ) : the_post(); 
							get_template_part('templates/category', 'item');
						endwhile; 
					?>
					
                </div>

				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
					<?php get_sidebar() ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer() ?>