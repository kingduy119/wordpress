<?php
    global $product;
    $price_sale = $product->get_sale_price();
    $price_current = intval( $product->get_regular_price() );
    if( $product->is_on_sale() && 0 != $price_current ) {
        $percen =  100 - ( $price_sale / $price_current * 100);
        ?>
            <span class="product--sale-flash">
                <?php echo round( $percen ) . '%'; ?>
            </span>
        <?php
    }
?>
