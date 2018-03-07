<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Type_Order_Item
 */
class Data_Type_Order_Item extends Data_Type {

	/**
	 * @param $item
	 * @return bool
	 */
	function validate( $item ) {
		return ( is_array( $item ) || is_a( $item, 'WC_Order_Item' ) ); // was array < WC 3.0
	}


	/**
	 * @param $item array
	 * @return mixed
	 */
	function compress( $item ) {
		return Compat\Order_Item::get_id( $item );
	}


	/**
	 * Order items are retrieved from the order object so we must ensure that an order is always present in the data layer
	 *
	 * @param $order_item_id
	 * @param $compressed_data_layer
	 * @return mixed
	 */
	function decompress( $order_item_id, $compressed_data_layer ) {

		if ( ! isset( $compressed_data_layer['order'] ) )
			return false;

		if ( ! $order = wc_get_order( $compressed_data_layer['order'] ) )
			return false;

		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			$items = $order->get_items();

			if ( ! isset( $items[ $order_item_id ] ) )
				return false;

			$item = $items[ $order_item_id ];
		}
		else {
			$item = $order->get_item( $order_item_id );
		}

		return AW()->order_helper->prepare_order_item( $order_item_id, $item );
	}

}

return new Data_Type_Order_Item();
