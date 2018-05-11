<?php
/**
 * WC_PB_COG_Compatibility class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Product Bundles
 * @since    4.11.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cost of Goods Compatibility.
 *
 * @since  4.11.4
 */
class WC_PB_COG_Compatibility {

	public static function init() {

		// Cost of Goods support.
		add_filter( 'wc_cost_of_goods_set_order_item_cost_meta_item_cost', array( __CLASS__, 'cost_of_goods_set_order_item_bundled_item_cost' ), 10, 3 );
	}

	/**
	 * Cost of goods compatibility: Zero order item cost for bundled products that belong to statically priced bundles.
	 *
	 * @param  double    $cost
	 * @param  array     $item
	 * @param  WC_Order  $order
	 * @return double
	 */
	public static function cost_of_goods_set_order_item_bundled_item_cost( $cost, $item, $order ) {

		if ( $parent_item = wc_pb_get_bundled_order_item_container( $item, $order ) ) {

			$bundled_item_priced_individually = isset( $item[ 'bundled_item_priced_individually' ] ) ? 'yes' === $item[ 'bundled_item_priced_individually' ] : null;

			// Back-compat.
			if ( null === $bundled_item_priced_individually ) {
				if ( isset( $parent_item[ 'per_product_pricing' ] ) ) {
					$bundled_item_priced_individually = 'yes' === $parent_item[ 'per_product_pricing' ];
				} elseif ( isset( $item[ 'bundled_item_id' ] ) ) {
					if ( $bundle = wc_get_product( $parent_item[ 'product_id' ] ) ) {
						$bundled_item_id                  = $item[ 'bundled_item_id' ];
						$bundled_item                     = $bundle->get_bundled_item( $bundled_item_id );
						$bundled_item_priced_individually = is_a( $bundled_item, 'WC_Bundled_Item' ) ? $bundled_item->is_priced_individually() : false;
					}
				}
			}

			if ( false === $bundled_item_priced_individually ) {
				$cost = 0;
			}
		}

		return $cost;
	}
}

WC_PB_COG_Compatibility::init();
