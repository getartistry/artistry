<?php

namespace AutomateWoo;

/**
 * @class Format
 * @since 2.9
 */
class Format {

	const MYSQL = 'Y-m-d H:i:s';


	/**
	 * @param int|string|\DateTime $date
	 * @param bool|int $max_diff
	 * @param bool $convert_from_gmt If its gmt convert it to local time
	 * @return string|false
	 */
	static function datetime( $date, $max_diff = false, $convert_from_gmt = true ) {

		if ( ! $timestamp = self::mixed_date_to_timestamp( $date ) ) {
			return false;
		}

		if ( $convert_from_gmt ) {
			$timestamp = strtotime( get_date_from_gmt( date( Format::MYSQL, $timestamp ), Format::MYSQL ) );
		}

		$now = current_time( 'timestamp' );

		if ( $max_diff === false ) $max_diff = DAY_IN_SECONDS; // set default

		$diff = $timestamp - $now;

		if ( abs( $diff ) >= $max_diff ) {
			return $date_to_display = date_i18n( 'Y-m-d ' . wc_time_format(), $timestamp );
		}

		return self::human_time_diff( $timestamp );
	}


	/**
	 * @param int|string|\DateTime $date
	 * @param bool|int $max_diff
	 * @param bool $convert_from_gmt If its gmt convert it to local time
	 * @return string|false
	 */
	static function date( $date, $max_diff = false, $convert_from_gmt = true ) {

		if ( ! $timestamp = self::mixed_date_to_timestamp( $date ) ) {
			return false;
		}

		if ( $convert_from_gmt ) {
			$timestamp = strtotime( get_date_from_gmt( date( Format::MYSQL, $timestamp ), Format::MYSQL ) );
		}

		$now = current_time( 'timestamp' );

		if ( $max_diff === false ) $max_diff = WEEK_IN_SECONDS; // set default

		$diff = $timestamp - $now;

		if ( abs( $diff ) >= $max_diff ) {
			return $date_to_display = date_i18n( 'Y-m-d', $timestamp );
		}

		return self::human_time_diff( $timestamp );
	}


	/**
	 * @param integer $timestamp
	 * @return string
	 */
	private static function human_time_diff( $timestamp ) {
		$now = current_time( 'timestamp' );

		$diff = $timestamp - $now;

		if ( $diff < 55 && $diff > -55 ) {
			$diff_string = sprintf( _n( '%d second', '%d seconds', abs( $diff ), 'automatewoo' ), abs( $diff ) );
		}
		else {
			$diff_string = human_time_diff( $now, $timestamp );
		}

		if ( $diff > 0 ) {
			return sprintf( __( '%s from now', 'automatewoo' ), $diff_string );
		}
		else {
			return sprintf( __( '%s ago', 'automatewoo' ), $diff_string );
		}
	}


	/**
	 * @param int|string|\DateTime $date
	 * @return int
	 */
	static function mixed_date_to_timestamp( $date ) {

		$timestamp = 0;

		if ( is_numeric( $date ) ) {
			$timestamp = $date;
		}
		else {
			if ( is_a( $date, 'DateTime' ) ) {
				$timestamp = $date->getTimestamp();
			}
			elseif ( is_string( $date ) ) {
				$timestamp = strtotime( $date );
			}
		}

		if ( $timestamp < 0 ) {
			return 0;
		}

		return $timestamp;
	}


	/**
	 * @param integer $day - 1 (for Monday) through 7 (for Sunday)
	 * @return string|false
	 */
	static function weekday( $day ) {

		global $wp_locale;

		$days = [
			1 => $wp_locale->get_weekday(1),
			2 => $wp_locale->get_weekday(2),
			3 => $wp_locale->get_weekday(3),
			4 => $wp_locale->get_weekday(4),
			5 => $wp_locale->get_weekday(5),
			6 => $wp_locale->get_weekday(6),
			7 => $wp_locale->get_weekday(0),
		];

		if ( ! isset( $days[ $day ] ) ) {
			return false;
		}

		return $days[ $day ];
	}


	/**
	 * @param integer $day - 1 (for Monday) through 7 (for Sunday)
	 * @return string|false
	 */
	static function weekday_abbrev( $day ) {

		global $wp_locale;
		if ( $name = self::weekday( $day ) ) {
			return $wp_locale->get_weekday_abbrev( $name );
		}

		return false;
	}


	/**
	 * @param $time
	 * @return string
	 */
	static function time_of_day( $time ) {

		$parts = explode( ':', $time );

		if ( count( $parts ) !== 2 ) {
			return '-';
		}

		return absint( $parts[0] ) . ':' . zeroise( $parts[1], 2 );
	}


	/**
	 * @param $number
	 * @param int $places
	 * @param bool $trim_zeros
	 * @return string
	 */
	static function decimal( $number, $places = 2, $trim_zeros = false ) {
		return wc_format_decimal( $number, $places, $trim_zeros );
	}


	/**
	 * @param string|float $number
	 * @param $places
	 * @return float
	 */
	static function round( $number, $places = false ) {
		if ( $places === false ) {
			$places = wc_get_price_decimals();
		}
		return round( (float) $number, $places );
	}


	/**
	 * @param Customer $customer
	 * @return string
	 */
	static function customer( $customer ) {

		if ( ! $customer ) {
			return false;
		}

		$name = esc_attr( $customer->get_full_name() );
		$email = esc_attr( $customer->get_email() );

		if ( $customer->is_registered() ) {
			$link = get_edit_user_link( $customer->get_user_id() );
		}
		else {
			$link = Admin::page_url('guest', $customer->get_guest_id() );
		}

		return "<a href='$link'>$name</a>" . ( $customer->is_registered() ? '' : ' ' . __( '[Guest]', 'automatewoo' ) ) . " <a href='mailto:$email'>$email</a>";
	}

}
