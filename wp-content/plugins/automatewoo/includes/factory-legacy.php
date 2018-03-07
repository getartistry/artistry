<?php

namespace AutomateWoo;

/**
 * @class Factory_Legacy
 * @since 2.8.2
 * @deprecated
 */
class Factory_Legacy {

	/** @var array  */
	static $cache = [];

	/** @var array */
	static $cache_index = [];


	/**
	 * @param $type
	 * @return bool
	 */
	static function get_object_types( $type ) {

		$types = apply_filters( 'automatewoo/factory/object_types', [
			'guest' => 'AutomateWoo\Guest',
			'unsubscribe' => 'AutomateWoo\Unsubscribe',
			'queue' => 'AutomateWoo\Queued_Event',
			'log' => 'AutomateWoo\Log',
			'cart' => 'AutomateWoo\Cart'
		]);

		return isset( $types[$type] ) ? $types[$type] : false;
	}


	/**
	 * @param $object_id
	 * @param $type
	 * @return mixed
	 */
	static function get_object( $object_id, $type ) {

		if ( ! $object_id || ! is_numeric( $object_id ) ) {
			return false;
		}

		if ( ! $class = self::get_object_types( $type ) ) {
			return false;
		}

		if ( $cache = self::get_cached_object( $object_id, $type ) ) {
			return $cache;
		}

		$object = new $class( $object_id );

		return $object;
	}


	/**
	 * Setup cache array for type
	 * @param $type
	 */
	static function setup_cache( $type ) {
		if ( ! isset( self::$cache[$type] ) )  {
			self::$cache[ $type ] = [];
		}
		if ( ! isset( self::$cache_index[$type] ) )  {
			self::$cache_index[ $type ] = [];
		}
	}



	/**
	 * @param $object_id
	 * @param $type
	 * @return bool
	 */
	static function get_cached_object( $object_id, $type ) {
		self::setup_cache( $type );

		if ( ! isset( self::$cache[$type][$object_id] ) ) {
			return false;
		}

		return self::$cache[$type][$object_id];
	}



	/**
	 * @param $object_id
	 * @param $index_key
	 * @param $index_value
	 * @param $type
	 */
	static function set_cache_index( $object_id, $index_key, $index_value, $type ) {
		self::setup_cache( $type );

		if ( ! is_scalar( $index_key ) || ! is_scalar( $index_value ) ) {
			return;
		}

		self::$cache_index[ $type ][ "$index_key=$index_value" ] = (int) $object_id;
	}


	/**
	 * Returns 0 if the cache is found and does the object does not exist, returns false if the object has not been cached
	 *
	 * @param $index_key
	 * @param $index_value
	 * @param $type
	 * @return bool|int
	 */
	function get_object_id_from_cache_index( $index_key, $index_value, $type ) {
		self::setup_cache( $type );

		if ( ! is_scalar( $index_key ) || ! is_scalar( $index_value ) ) {
			return false;
		}

		if ( ! isset( self::$cache_index[ $type ][ "$index_key=$index_value" ] ) ) {
			return false;
		}

		return self::$cache_index[ $type ][ "$index_key=$index_value" ];
	}



}
