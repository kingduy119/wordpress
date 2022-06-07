<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php wp_head(); ?>
    
    <title>
        <?php is_home() ? bloginfo('name') : wp_title('', true, ''); ?>
    </title>
</head>

<body>
    <div id="page">
        <?php 
            // if( ! is_active_sidebar( 'page-header' ) ) { dynamic_sidebar('page-header'); }
        ?>

        <div id="page-content">

