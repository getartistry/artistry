<?php

namespace AutomateWoo;

/**
 * @class Factories
 * @since 2.9
 */
class Factories {

	/** @var array */
	private static $factories;


	/**
	 * @return array
	 */
	static function get_factories() {
		if ( ! isset( self::$factories ) ) {
			self::$factories = apply_filters( 'automatewoo/factories', [
				'guest' => 'AutomateWoo\Guest_Factory',
				'unsubscribe' => 'AutomateWoo\Unsubscribe_Factory',
				'queue' => 'AutomateWoo\Queued_Event_Factory',
				'log' => 'AutomateWoo\Log_Factory',
				'cart' => 'AutomateWoo\Cart_Factory',
				'customer' => 'AutomateWoo\Customer_Factory',
				'event' => 'AutomateWoo\Event_Factory'
			]);
		}
		return self::$factories;
	}


	/**
	 * @param $type
	 * @return bool|Factory
	 */
	static function get_factory( $type ) {

		if ( ! $type ) {
			return false;
		}

		$factories = self::get_factories();

		return isset( $factories[$type] ) ? $factories[$type] : false;
	}


	/**
	 * @param Model $object
	 */
	static function update_object_cache( $object ) {
		if ( $factory = self::get_factory( $object->object_type ) ) {
			$factory::update_cache( $object );
		}
		else {
			_doing_it_wrong( __FUNCTION__, __( 'Factory class must be registered.', 'automatewoo' ), '2.9.0' );
		}
	}


	/**
	 * @param Model $object
	 */
	static function clean_object_cache( $object ) {
		if ( $factory = self::get_factory( $object->object_type ) ) {
			$factory::clean_cache( $object );
		}
	}

}