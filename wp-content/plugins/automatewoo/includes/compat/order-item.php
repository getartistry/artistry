<?php

namespace AutomateWoo\Compat;

/**
 * @class Order_Item
 * @since 2.9
 */
class Order_Item {


	/**
	 * @param array|\WC_Order_Item $item
	 * @return int
	 */
	static function get_id( $item ) {
		return is_callable( [ $item, 'get_id' ] ) ? $item->get_id() : $item['id'];
	}


	/**
	 * @param array|\WC_Order_Item_Product $item
	 * @return int
	 */
	static function get_product_id( $item ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return (int) $item['product_id'];
		}
		else {
			return $item->get_product_id();
		}
	}


	/**
	 * @param array|\WC_Order_Item_Product $item
	 * @return int
	 */
	static function get_variation_id( $item ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return (int) $item['variation_id'];
		}
		else {
			return $item->get_variation_id();
		}
	}


	/**
	 * @param array|\WC_Order_Item_Product $item
	 * @param \WC_Order $order
	 * @return \WC_Product
	 */
	static function get_product( $item, $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $order->get_product_from_item( $item );
		}
		else {
			return $item->get_product();
		}
	}


	/**
	 * @param array|\WC_Order_Item_Product $item
	 * @return int
	 */
	static function get_quantity( $item ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return (int) $item['qty'];
		}
		else {
			return $item->get_quantity();
		}
	}


	/**
	 * @param array|\WC_Order_Item_Product $item
	 * @return string
	 */
	static function get_name( $item ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $item['name'];
		}
		else {
			return $item->get_name();
		}
	}


	/**
	 * @param array|\WC_Order_Item $item
	 * @param string $attribute slug
	 * @return false|string
	 */
	static function get_attribute( $item, $attribute ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return isset( $item[$attribute] ) ? $item[$attribute]: false;
		}
		else {
			return $item->get_meta( $attribute );
		}
	}


	/**
	 * @param array|\WC_Order_Item $item
	 * @param string $attribute slug
	 * @return false|string
	 */
	static function get_meta( $item, $key ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return isset( $item[$key] ) ? $item[$key]: false;
		}
		else {
			return $item->get_meta( $key );
		}
	}


	/**
	 * @param array|\WC_Order_Item_Shipping $item
	 * @param bool $discard_instance_id
	 * @return false|string
	 */
	static function get_shipping_method_id( $item, $discard_instance_id = false ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			$id = $item['method_id'];
		}
		else {
			$id = $item->get_method_id();
		}

		if ( $discard_instance_id ) {
			// extract method base id only, discard instance id
			if ( $split = strpos( $id, ':') ) {
				$id = substr( $id, 0, $split );
			}
		}

		return $id;

	}


}