<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$query_args     = array(
    'page' => isset( $_GET['page'] ) ? $_GET['page'] : '',
    'tab'  => 'active-checkout',
);
$activation_url = esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );

return array(

    'general' => array(

        'ywqcdg_main_section_title' => array(
            'name' => __( 'Quick Checkout for Digital Goods settings', 'yith-woocommerce-review-for-discounts' ),
            'type' => 'title',
        ),
        'ywqcdg_enable_plugin'      => array(
            'name'    => __( 'Enable YITH WooCommerce Quick Checkout for Digital Goods', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
            'type'    => 'checkbox',
            'id'      => 'ywqcdg_enable_plugin',
            'default' => 'yes',
        ),
        'ywqcdg_fields_to_show'     => array(
            'name'    => __( 'Fields shown', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
            'type'    => 'ywqcdg-select',
            'id'      => 'ywqcdg_fields_to_show',
            'desc'    => __( 'Select the fields that will be shown in quick checkout. "Email" is a mandatory field and cannot be removed.', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
            'options' => YITH_WQCDG()->get_list_fields(),
            'default' => array( 'billing_first_name', 'billing_last_name' )
        ),
        'ywqcdg_hide_order_notes'   => array(
            'name'    => __( 'Hide order notes', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
            'type'    => 'checkbox',
            'id'      => 'ywqcdg_hide_order_notes',
            'default' => 'yes',
        ),
        'ywqcdg_active_elements'    => array(
            'name'    => __( 'Enable quick checkout on' ),
            'type'    => 'radio',
            'id'      => 'ywqcdg_active_elements',
            'options' => array(
                'all'       => __( 'all downloadable and/or virtual products' ),
                'selection' => sprintf( __( 'items in the "%sQuick Checkout List%s" only', 'yith-woocommerce-catalog-mode' ), '<a href="' . $activation_url . '">', '</a>' ),

            ),
            'default' => 'all'
        ),
        'ywqcdg_product_page'       => array(
            'name'          => __( 'Show in product page', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
            'desc'          => __( 'Show quick checkout in product page', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
            'type'          => 'checkbox',
            'id'            => 'ywqcdg_product_page',
            'default'       => 'no',
            'checkboxgroup' => 'start',

        ),
        'ywqcdg_product_page_atc'   => array(
            'name'          => __( 'Add to cart', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
            'desc'          => __( 'Add product to cart automatically', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
            'type'          => 'checkbox',
            'id'            => 'ywqcdg_product_page_atc',
            'default'       => 'no',
            'checkboxgroup' => 'end',

        ),
        'ywqcdg_main_section_end'   => array(
            'type' => 'sectionend',
        ),

    )

);