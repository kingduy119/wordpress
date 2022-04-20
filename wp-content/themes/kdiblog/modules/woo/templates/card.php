 
<div id="product-<?php esc_attr( the_ID() ); ?>" class="productcard">
    <header class="productcard--header">
    <?php
    /**
     * kdi_product_thumbnail    - 10
     */
    do_action( 'kdi_template_card_header' );
    ?>
    </header>

    <div class="productcard--content">
    <?php
    /**
     *  kdi_product_title   - 5
     *  kdi_product_price   - 10
     */
    do_action( 'kdi_template_card_content' );
    ?>
    </div>

</div>



