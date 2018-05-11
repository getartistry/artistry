<?php
/**
 * WC_PB_Admin_Post_Types class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    5.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add hooks to the edit posts view for the 'product' post type.
 *
 * @class    WC_PB_Admin_Post_Types
 * @version  5.5.0
 */
class WC_PB_Admin_Post_Types {

	/**
	 * Hook in.
	 */
	public static function init() {

		// Add details to admin product stock info when the bundled stock is insufficient.
		add_filter( 'woocommerce_admin_stock_html', array( __CLASS__, 'admin_stock_html' ), 10, 2 );
	}

	/**
	 * Add details to admin stock info when contents stock is insufficient.
	 *
	 * @param  string      $stock_status
	 * @param  WC_Product  $product
	 * @return string
	 */
	public static function admin_stock_html( $stock_status, $product ) {

		if ( 'bundle' === $product->get_type() ) {
			if ( $product->is_parent_in_stock() && $product->contains( 'out_of_stock_strict' ) ) {

				$stock_status             = '<mark class="outofstock insufficient_stock">' . __( 'Insufficient stock', 'woocommerce-product-bundles' ) . '</mark>';
				$insufficient_stock_items = array();

				foreach ( $product->get_bundled_items() as $bundled_item ) {
					if ( false === $bundled_item->is_in_stock() ) {
						$edit_link                  = get_edit_post_link( $bundled_item->get_product_id() );
						$title                      = $bundled_item->product->get_title();
						$insufficient_stock_items[] = '<a class="item" href="' . esc_url( $edit_link ) . '">' . esc_html( $title ) . '</a>';
					}
				}

				$item_list = array();

				if ( ! empty( $insufficient_stock_items ) ) {

					foreach ( $insufficient_stock_items as $item ) {
						$item_list[] = '<li>' . $item . '</li>';
					}

					$stock_status .= ' ' . '<a href="#" class="show_insufficient_stock_items closed" title="' . esc_attr ( __( 'View details', 'woocommerce-product-bundles' ) ) . '"></a>' . '<div class="insufficient_stock_items" style="display:none;"><ul>' . implode( '', $item_list ) . '</ul></div>';
				}
			}
		}

		return $stock_status;
	}
}

WC_PB_Admin_Post_Types::init();
