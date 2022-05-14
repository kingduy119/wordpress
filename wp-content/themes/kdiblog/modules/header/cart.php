

<?php if( kdi_woo_is_actived() ) : ?>
<head>
    <style>
    .kdi-header-cart {
        display: inline-block;
        position: relative;
    }
    .kdi-header-cart:hover .kdi-header-cart__content {
        display: block;
    }
    .kdi-header-cart__link {
        color: black;
        text-decoration: none;
    }
    .kdi-header-cart__content {
        display: none;
        position: absolute;
        min-width: 200px;
        right: 0;
        background-color: #ccc;
        z-index: 9;
        padding: 4px 8px;
    }

    .kdi-header-cart__content {
        background-color: #96D6FF;
    }
    .kdi-header-cart__content a {
        text-decoration: none;
    }

    .kdi-header-cart__content .quantity,
    .woocommerce-mini-cart__empty-message,
    .woocommerce-mini-cart__total.total {
        color: white;
    }

    .woocommerce-mini-cart__buttons .wc-forward {
        background-color: teal !important;
        opacity: 0.7;
    }
    </style>
</head>
<div class="kdi-header-cart">

    <!-- Cart-link -->
    <a class="kdi-header-cart__link" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="View Shopping cart">
        <i class="bi bi-cart4"></i>
        <?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?> 

        <!-- <span class="count">
            <?php //echo wp_kses_data( sprintf( '%d item', WC()->cart->get_cart_contents_count() ) ); ?>
        </span> -->
    </a>

    <!-- Cart-dropdown -->
    <div class="kdi-header-cart__content">
    <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
    </div>
</div>

<?php endif; ?>