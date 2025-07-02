<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo home_url(); ?>">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <?php
      wp_nav_menu([
        'theme_location'  => 'main-menu',
        'depth'           => 2, // hỗ trợ dropdown
        'container'       => false,
        'menu_class'      => 'navbar-nav me-auto mb-2 mb-lg-0',
        'fallback_cb'     => '__return_false',
        'walker'          => new Bootstrap_NavWalker(),
      ]);
      ?>
    </div>
  </div>
</nav>
