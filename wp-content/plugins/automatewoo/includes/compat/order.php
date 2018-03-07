<?php

namespace AutomateWoo\Compat;
use AutomateWoo\Format;
use AutomateWoo\Time_Helper;

/**
 * @class Order
 * @since 2.9
 */
class Order {


	/**
	 * @param \WC_Order $order
	 * @return int
	 */
	static function get_id( $order ) {
		return is_callable( [ $order, 'get_id' ] ) ? $order->get_id() : $order->id;
	}


	/**
	 * Returns mysql format
	 *
	 * @param \WC_Order $order
	 * @param bool $gmt
	 * @return string
	 */
	static function get_date_created( $order, $gmt = false ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			$date = $order->order_date;
		}
		else {
			$date = $order->get_date_created() ? $order->get_date_created()->date( Format::MYSQL ) : false;
		}

		if ( $gmt && $date ) {
			return get_gmt_from_date( $date, Format::MYSQL );
		}

		return $date;
	}


	/**
	 * @param \WC_Order $order
	 * @param \DateTime $date
	 */
	static function set_date_created( $order, $date ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			Time_Helper::convert_from_gmt( $date );

			wp_update_post([
				'ID' => $order->id,
				'post_date' => $date->format( Format::MYSQL )
			]);
		}
		else {
			$order->set_date_created( $date->getTimestamp() );
			$order->save();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_customer_ip( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $order->customer_ip_address;
		}
		else {
			return $order->get_customer_ip_address();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @param $key
	 * @return mixed
	 */
	static function get_meta( $order, $key ) {
		if ( is_callable( [ $order, 'get_meta' ] ) ) {
			return $order->get_meta( $key );
		}
		else {
			return get_post_meta( $order->id, $key, true );
		}
	}


	/**
	 * @param \WC_Order $order
	 * @param $key
	 * @param $value
	 * @return mixed
	 */
	static function update_meta( $order, $key, $value ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			update_post_meta( $order->id, $key, $value );
		}
		else {
			$order->update_meta_data( $key, $value );
			$order->save();
		}
	}

	/**
	 * @param \WC_Order $order
	 * @param $key
	 * @return mixed
	 */
	static function delete_meta( $order, $key ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			delete_post_meta( $order->id, $key );
		}
		else {
			$order->delete_meta_data( $key );
			$order->save();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @param $value
	 */
	static function set_customer_id( $order, $value ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			update_post_meta( $order->id, '_customer_user', $value );
		}
		else {
			$order->set_customer_id( $value );
			$order->save();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @param $value
	 */
	static function set_billing_email( $order, $value ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			update_post_meta( $order->id, '_billing_email', $value );
		}
		else {
			$order->set_billing_email( $value );
			$order->save();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_email( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_email', true );
		}
		else {
			return $order->get_billing_email();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_first_name( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_first_name', true );
		}
		else {
			return $order->get_billing_first_name();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_last_name( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_last_name', true );
		}
		else {
			return $order->get_billing_last_name();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_company( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_company', true );
		}
		else {
			return $order->get_billing_company();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_phone( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_phone', true );
		}
		else {
			return $order->get_billing_phone();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_country( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_country', true );
		}
		else {
			return $order->get_billing_country();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_address_1( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_address_1', true );
		}
		else {
			return $order->get_billing_address_1();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_address_2( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_address_2', true );
		}
		else {
			return $order->get_billing_address_2();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_city( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_city', true );
		}
		else {
			return $order->get_billing_city();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_state( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_state', true );
		}
		else {
			return $order->get_billing_state();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_billing_postcode( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_billing_postcode', true );
		}
		else {
			return $order->get_billing_postcode();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_shipping_country( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_shipping_country', true );
		}
		else {
			return $order->get_shipping_country();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_shipping_address_1( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_shipping_address_1', true );
		}
		else {
			return $order->get_shipping_address_1();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_shipping_address_2( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_shipping_address_2', true );
		}
		else {
			return $order->get_shipping_address_2();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_shipping_city( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_shipping_city', true );
		}
		else {
			return $order->get_shipping_city();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_shipping_state( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_shipping_state', true );
		}
		else {
			return $order->get_shipping_state();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_shipping_postcode( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return get_post_meta( $order->id, '_shipping_postcode', true );
		}
		else {
			return $order->get_shipping_postcode();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @param \WC_Order_Item_Product|array $item
	 * @return \WC_Product
	 */
	static function get_product_from_item( $order, $item ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $order->get_product_from_item( $item );
		}
		else {
			return $item->get_product();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_order_key( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $order->order_key;
		}
		else {
			return $order->get_order_key();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_payment_method( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $order->payment_method;
		}
		else {
			return $order->get_payment_method();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_payment_method_title( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $order->payment_method_title;
		}
		else {
			return $order->get_payment_method_title();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @param $note
	 */
	static function set_customer_note( $order, $note ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			wp_update_post([
				'ID' => $order->id,
				'post_excerpt' => $note
			]);
		}
		else {
			$order->set_customer_note( $note );
			$order->save();
		}
	}


	/**
	 * @param \WC_Order $order
	 * @return string
	 */
	static function get_customer_note( $order ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $order->customer_note;
		}
		else {
			return $order->get_customer_note();
		}
	}


	/**
	 * @see wc_get_is_paid_statuses()
	 * @return array
	 */
	static function get_paid_statuses() {
		return apply_filters( 'woocommerce_order_is_paid_statuses', [ 'processing', 'completed' ] );
	}


}