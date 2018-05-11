<?php

$price_sync_url = wp_nonce_url( add_query_arg( array( 'yith_wcpb_force_sync_bundle_products' => '1',
                                                      'yith_wcpb_redirect'                   => urlencode( admin_url('admin.php?page=yith_wcpb_panel') )
                                               ), admin_url() ), 'yith-wcpb-sync-pip-prices' );

$settings = array(

    'settings' => array(

        'general-options' => array(
            'title' => __( 'General Options', 'yith-woocommerce-product-bundles' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcpb-general-options'
        ),

        'show-bundled-items-in-report' => array(
            'id'      => 'yith-wcpb-show-bundled-items-in-report',
            'name'    => __( 'Show bundled items in Reports', 'yith-woocommerce-product-bundles' ),
            'type'    => 'checkbox',
            'desc'    => __( 'Flag this option to show also the bundled items in WooCommerce Reports.', 'yith-woocommerce-product-bundles' ),
            'default' => 'no'
        ),

        'hide-bundled-items-in-cart' => array(
            'id'      => 'yith-wcpb-hide-bundled-items-in-cart',
            'name'    => __( 'Hide bundled items in Cart and Checkout', 'yith-woocommerce-product-bundles' ),
            'type'    => 'checkbox',
            'desc'    => __( 'Flag this option to hide the bundled items in WooCommerce Cart and Checkout.', 'yith-woocommerce-product-bundles' ),
            'default' => 'no'
        ),

        'bundle-out-of-stock-sync' => array(
            'id'      => 'yith-wcpb-bundle-out-of-stock-sync',
            'name'    => __( 'Out of stock Sync', 'yith-woocommerce-product-bundles' ),
            'type'    => 'checkbox',
            'desc'    => __( 'Flag this option to set the bundle as Out of Stock if it contains at least one Out of Stock item.', 'yith-woocommerce-product-bundles' ),
            'default' => 'no'
        ),

        'pip-bundle-order-pricing' => array(
            'id'      => 'yith-wcpb-pip-bundle-order-pricing',
            'name'    => __( 'Price of "per item pricing" bundles in orders', 'yith-woocommerce-product-bundles' ),
            'type'    => 'select',
            'options' => array(
                'price-in-bundle'        => __( 'Price in bundle', 'yith-woocommerce-product-bundles' ),
                'price-in-bundled-items' => __( 'Price in bundled items', 'yith-woocommerce-product-bundles' ),
            ),
            'desc'    => __( 'Choose how you want to view order pricing for "per item pricing" bundle products', 'yith-woocommerce-product-bundles' ),
            'default' => 'price-in-bundle'
        ),

        'pip-bundle-force-price-sync' => array(
            'name'             => __( 'Bundle price sync', 'yith-woocommerce-product-bundles' ),
            'type'             => 'yith-field',
            'yith-type'        => 'html',
            'yith-display-row' => true,
            'html'             => "<a href='$price_sync_url' class='button'>" . __( 'Force price sync for "per item pricing" bundles', 'yith-woocommerce-product-bundles' ) . "</a>",
        ),

        'general-options-end' => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcqv-general-options'
        )

    )
);

return apply_filters( 'yith_wcpb_panel_settings_options', $settings );