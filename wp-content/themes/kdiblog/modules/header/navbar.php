<?php
  
  function kdi_form_search() {
    ?>
    <form class="f-search" action="<?php echo home_url('/'); ?>">
      <input name="s" type="search" placeholder="Search" aria-label="Search">
      <button class="btn-search" type="button">search</button>
    </form>
    <script>
      $('.f-search button').click(function() {
          $('.f-search input').css('display', 'block').focus();
      });

      $('.f-search input').focusout(function() {
        $(this).css('display', 'none');
      })
    </script>
    <?php
  }

  function list_social_icon() {
    ?>
    <ul class="d-flex m-0 p-0">
      <li><a class="link-dark me-2" href="#"><i class="bi bi-instagram"></i></a></li>
      <li><a class="link-dark me-2" href="#"><i class="bi bi-messenger"></i></a></li>
      <li><a class="link-dark me-2" href="#"><i class="bi bi-twitter"></i></a></li>
      <li><a class="link-dark me-2" href="#"><i class="bi bi-youtube"></i></a></li>
    </ul>
    <?php
  }

  function navbar_main() {
    ?>
    <nav id="page-main-navbar" class="navigation">
      <div class="container">
      <?php
        wp_nav_menu( array(
          'theme_location'    => 'main-menu',
          'menu_id'           => 'main-menu',
        ) );
      ?>
      </div> <!-- container -->
    </nav>
    <?php
  }

?>

<!-- header-top -->
<!-- <div id="page-top-navbar" class="border-bottom">
  <div class="container">
    <div class="row g-0">
      
      <div class="col-6">
        <?php //list_social_icon(); ?>
      </div>

      <div class="col-6">
        <div class="d-flex justify-content-end align-items-center">
          <?php //kdi_form_search(); ?>
        </div>
      </div>

    </div>
  </div>
</div> -->

<!-- header-middle -->
<div id="page-middle-navbar">
  <div class="container">
    <div class="row g-0 align-items-center">

      <div class="col-4">
        <div id="page--logo" class="me-2">
          <!-- LOGO -->
          <?php if( is_active_sidebar( 'nav-middle-left' ) ) { dynamic_sidebar('nav-middle-left'); } ?>

        </div>
      </div>

      <div class="col-8">
        <?php if( is_active_sidebar( 'nav-middle-right' ) ) { dynamic_sidebar('nav-middle-right'); } ?>
      </div>

    </div> <!-- row -->
  </div>
</div>

<!-- header-footer -->
<nav id="page-main-navbar" class="navigation">
  <div class="container">
    <div class="d-flex align-items-center">

      <?php
        wp_nav_menu( array(
          'container_class'   => 'flex-grow-1',
          'theme_location'    => 'main-menu',
          'menu_id'           => 'main-menu',
        ) );
      ?>

      <?php get_template_part('modules/header/cart'); ?>

    </div>


  </div> <!-- container -->
</nav>
