<?php
  
  function kdi_form_search() {
    ?>
    <form class="f-search" action="<?php echo home_url('/'); ?>">
      <input name="s" type="search" placeholder="Search" aria-label="Search">
      <button class="btn-search" type="button">search</button>
    </form>
    <script>
      jQuery('.f-search button').click(function() {
          $('.f-search input').css('display', 'block').focus();
      });

      jQuery('.f-search input').focusout(function() {
        $(this).css('display', 'none');
      })
    </script>
    <?php
  }

?>

<!-- header-footer -->
<nav id="page-main-navbar" class="navigation">
  <div class="container">
    <div class="d-flex align-items-center">

      <?php
        wp_nav_menu( array(
          // 'container_class'   => 'flex-grow-1',
          'theme_location'    => 'main-menu',
          'menu_id'           => 'main-menu',
        ) );
      ?>

      <?php //get_template_part('modules/header/cart'); ?>

    </div>
  </div> <!-- container -->
</nav>
