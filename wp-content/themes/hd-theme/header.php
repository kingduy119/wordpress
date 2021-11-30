<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- <meta name="description" content="" /> -->
    <!-- <meta name="author" content="" /> -->
    <title>
        <?php 
            if( is_home() ) {
                bloginfo('name');
            } elseif( is_single() ) {
                echo get_field('seo_title');
            } else {
                wp_title('', true, '');
            }
        ?>
    </title>

    <?php if( is_home() ) : ?>
        <meta name="description" content="<?php bloginfo('description') ?>" />
    <?php elseif( is_single() ) : ?>
        <meta name="description" content="<?php echo get_field('seo_description'); ?>" />
        <meta name="keywords" content="<?php get_field('seo_keywords'); ?>" />
    <?php endif; ?>

    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri() ?>/assets/favicon.ico" />

    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="<?php echo get_template_directory_uri() ?>/css/styles.css" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri() ?>/style.css" rel="stylesheet" />
    <?php wp_head() ?>
</head>

<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
        <div class="container">
            <a class="navbar-brand" href="<?php echo home_url() ?>">KDI Tech</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" aria-current="page" href="#">Blog</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown link
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                        </ul>
                    </li>
                </ul> -->
                <?php
                    // wp_nav_menu(array(
                    //     'theme_location' => 'header-menu',
                    //     'depth' => 2,
                    //     'container' => false,
                    //     'menu_class' => 'navbar-nav ms-auto mb-2 mb-lg-0',
                    //     'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                    //     'walker'          => new WP_Bootstrap_Navwalker()
                    // ));

                    // bootstrap-5:
                    wp_nav_menu(array(
                        'theme_location' => 'header-menu',
                        'container' => false,
                        'menu_class' => '',
                        'fallback_cb' => '__return_false',
                        'items_wrap' => '<ul id="%1$s" class="navbar-nav me-auto mb-2 mb-md-0 %2$s">%3$s</ul>',
                        'depth' => 2,
                        'walker' => new bootstrap_5_wp_nav_menu_walker()
                    ));
                ?>
            </div>
        </div>
    </nav>
    <div class="container">
        <?php hd_theme_breadcrumbs() ?>
    </div>