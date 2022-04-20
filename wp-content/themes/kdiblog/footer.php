        
        
            </div> <!-- #page-content -->
        </div><!-- #page -->
        
        <footer id="page-footer" class="bg-dark text-light">
            <div class="container py-4">
                <div class="row row-cols-sm-1 row-cols-md-2 row-cols-lg-4">
                    <?php 
                        // !dynamic_sidebar('footer-1');
                        if( function_exists('dynamic_sidebar') ) : 
                            if( is_active_sidebar( 'footer-1' ) ) { dynamic_sidebar('footer-1'); }
                            if( is_active_sidebar( 'footer-2' ) ) { dynamic_sidebar('footer-2'); }
                            if( is_active_sidebar( 'footer-3' ) ) { dynamic_sidebar('footer-3'); }
                            if( is_active_sidebar( 'footer-4' ) ) { dynamic_sidebar('footer-4'); }
                        endif;
                    ?>
                </div>

                <!-- <p>Copyright &copy; Your Website 2021</p> -->
            </div>
        </footer>

        <!-- Bootstrap core JS-->
        <script src="<?php bloginfo("stylesheet_directory"); ?>/assets/lib/bootstrap-5/dist/js/bootstrap.js"></script>
        <script src="<?php bloginfo("stylesheet_directory"); ?>/assets/js/main.js"></script>
    
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v13.0&appId=927729581108661&autoLogAppEvents=1" nonce="6PAoSsq1"></script>

    <?php wp_footer(); ?>
</body>
 </html>