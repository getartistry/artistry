<?php

namespace AutomateWoo\Compat;

/**
 * @class Coupon
 * @since 2.9
 */
class Coupon {

	/**
	 * @param \WC_Coupon $coupon
	 * @return mixed
	 */
	static function get_code( $coupon ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			return $coupon->code;
		}
		else {
			return $coupon->get_code();
		}
	}


	/**
	 * @param \WC_Coupon $coupon
	 * @param $key
	 * @return mixed
	 */
	static function get_meta( $coupon, $key ) {
		if ( is_callable( [ $coupon, 'get_meta' ] ) ) {
			return $coupon->get_meta( $key );
		}
		else {
			return get_post_meta( $coupon->id, $key, true );
		}
	}


	/**
	 * @param \WC_Coupon $coupon
	 * @param $key
	 * @param $value
	 * @return mixed
	 */
	static function update_meta( $coupon, $key, $value ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			update_post_meta( $coupon->id, $key, $value );
		}
		else {
			$coupon->update_meta_data( $key, $value );
			$coupon->save();
		}
	}


	/**
	 * @param \WC_Coupon $coupon
	 * @param \DateTime $date
	 */
	static function set_date_expires( $coupon, $date ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			update_post_meta( $coupon->id, 'expiry_date', $date->format('Y-m-d') );
		}
		else {
			$coupon->set_date_expires( $date->getTimestamp() );
			$coupon->save();
		}
	}


	/**
	 * @param \WC_Coupon $coupon
	 * @param int $limit
	 */
	static function set_usage_limit( $coupon, $limit ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			update_post_meta( $coupon->id, 'usage_limit', $limit );
		}
		else {
			$coupon->set_usage_limit( $limit );
			$coupon->save();
		}
	}


	/**
	 * @param \WC_Coupon $coupon
	 * @param array $emails
	 */
	static function set_email_restriction( $coupon, $emails ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			update_post_meta( $coupon->id, 'customer_email', $emails );
		}
		else {
			$coupon->set_email_restrictions( $emails );
			$coupon->save();
		}
	}


	/**
	 * @param int $coupon_id
	 * @return int
	 */
	static function get_date_expires_by_id( $coupon_id ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {

			$expiry = get_post_meta( $coupon_id, 'expiry_date', true );

			if ( ! is_numeric( $expiry ) ) {
				$expiry = strtotime( $expiry );
			}

		}
		else {
			$coupon = new \WC_Coupon( $coupon_id );
			$expiry = $coupon->get_date_expires();
			if ( $expiry ) {
				$expiry = $expiry->getTimestamp();
			}
		}

		return $expiry;
	}


	/**
	 * Get coupon ID by code.
	 *
	 * @param string $code
	 * @return int
	 */
	static function get_coupon_id_by_code( $code ) {
		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			global $wpdb;
			return absint( $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_coupon' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 1;", $code ) ) );
		}
		else {
			return wc_get_coupon_id_by_code( $code );
		}
	}


}
