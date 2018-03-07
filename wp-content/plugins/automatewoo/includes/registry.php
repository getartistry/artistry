<?php

namespace AutomateWoo;

/**
 * @class Registry
 * @since 3.2.4
 */
abstract class Registry {

	/** @var array - must be declared in child class */
	static $includes;

	/** @var array - must be declared in child class */
	static $loaded = [];


	/**
	 * Implement this method in sub classes
	 * @return array
	 */
	static function load_includes() {
		return [];
	}


	/**
	 * Optional method to implement
	 * @param string $name
	 * @param mixed $object
	 */
	static function after_loaded( $name, $object ) {}


	/**
	 * @return array
	 */
	static function get_includes() {
		if ( ! isset( static::$includes ) ) {
			static::$includes = static::load_includes();
		}
		return static::$includes;
	}


	/**
	 * @return mixed
	 */
	static function get_all() {
		foreach ( static::get_includes() as $name => $path ) {
			static::load( $name );
		}
		return static::$loaded;
	}


	/**
	 * @param $name
	 * @return mixed
	 */
	static function get( $name ) {
		static::load( $name );
		return static::$loaded[ $name ];
	}


	/**
	 * @param $name
	 * @return bool
	 */
	static function is_loaded( $name ) {
		return isset( static::$loaded[ $name ] );
	}


	/**
	 * @param $name
	 * @return void
	 */
	static function load( $name ) {

		if ( self::is_loaded( $name ) ) {
			return;
		}

		$object = false;
		$includes = static::get_includes();

		if ( ! empty( $includes[ $name ] ) ) {
			if ( file_exists( $includes[ $name ] ) ) {
				$object = include_once $includes[ $name ];
				if ( ! is_object( $object ) ) {
					$object = false;
				}
				else {
					static::after_loaded( $name, $object );
				}
			}
		}

		static::$loaded[ $name ] = $object;
	}

}
