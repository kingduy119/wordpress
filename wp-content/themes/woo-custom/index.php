<?php get_header(); ?>
<div id="content">
	<div class="container">
		<?php get_template_part("slider"); ?>
	</div>
	<div class="product-box">
		<div class="container">
			<div class="row">
				<div class="sidebar col-xs-12 col-sm-12 col-md-12 col-lg-3 order-lg-0 order-1">
					<!-- <div class="sidebar"> -->
						<?php get_sidebar() ?>
					<!-- </div> -->
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9 order-lg-1 order-0">
					<?php get_products_most_popular(); ?>
					<?php get_products_by_phone(); ?>

				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer() ?>