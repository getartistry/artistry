<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Guest_Factory
 * @since 2.9
 */
class Guest_Factory extends Factory {

	static $model = 'AutomateWoo\Guest';


	/**
	 * @param int $guest_id
	 * @return Guest|bool
	 */
	static function get( $guest_id ) {
		return parent::get( $guest_id );
	}


	/**
	 * @param $email
	 * @return Guest|bool
	 */
	static function get_by_email( $email ) {

		if ( ! is_email( $email ) ) return false;

		if ( Cache::exists( $email, 'guest_email' ) ) {
			return static::get( Cache::get( $email, 'guest_email' ) );
		}

		$guest = new Guest();
		$guest->get_by( 'email', $email );

		if ( ! $guest->exists ) {
			Cache::set( $email, 0, 'guest_email' );
			return false;
		}

		return $guest;
	}


	/**
	 * @param $key
	 * @return Guest|bool
	 */
	static function get_by_key( $key ) {

		if ( ! $key ) return false;

		if ( Cache::exists( $key, 'guest_key' ) ) {
			return static::get( Cache::get( $key, 'guest_key' ) );
		}

		$guest = new Guest();
		$guest->get_by( 'tracking_key', $key );

		if ( ! $guest->exists ) {
			Cache::set( $key, 0, 'guest_key' );
			return false;
		}

		return $guest;
	}


	/**
	 * @param Guest $guest
	 */
	static function update_cache( $guest ) {
		parent::update_cache( $guest );

		Cache::set( $guest->get_email(), $guest->get_id(), 'guest_email' );
		Cache::set( $guest->get_key(), $guest->get_id(), 'guest_key' );
	}


	/**
	 * @param Guest $guest
	 */
	static function clean_cache( $guest ) {
		parent::clean_cache( $guest );

		static::clear_cached_prop( $guest, 'email', 'guest_email' );
		static::clear_cached_prop( $guest, 'tracking_key', 'guest_key' );
	}


	/**
	 * @param string $email
	 * @param string|bool $tracking_key
	 * @return Guest
	 */
	static function create( $email, $tracking_key = false ) {

		if ( ! $tracking_key ) {
			$tracking_key = aw_generate_key( 32 );
		}

		$guest = new Guest();
		$guest->set_email( Clean::email( $email) );
		$guest->set_key( $tracking_key );
		$guest->set_date_created( new \DateTime() );
		$guest->save();

		return $guest;
	}

}
