</div> <!-- end page-body -->

<footer class="page-footer">
    <?php  if( is_active_sidebar( 'page-footer' ) ) { dynamic_sidebar('page-footer');  } ?>
</footer>


<!-- remove p -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll(".page-header p, .page-footer p").forEach(function(p) {
    if (p.textContent.trim().length === 0) {
      p.style.display = "none";
    }
  });
});
</script>

</body>
<?php wp_footer(); ?>
</html>