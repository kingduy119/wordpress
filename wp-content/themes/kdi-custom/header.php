<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body>
	<div class="page-header">
	<?php 
		// get_template_part( 'template-parts/header/navbar' );
		if( is_active_sidebar( 'header' ) ) { dynamic_sidebar('header'); }
	?>
	</div>

	<div class="page-body">
	<!-- </div> on footer.php -->
