
<?php
    if( 'product' == get_post_type() ) :
        dynamic_sidebar('sidebar-product');
    else :
        dynamic_sidebar('sidebar');
    endif;
?>

