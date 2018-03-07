<?php

namespace AutomateWoo\Admin;

use AutomateWoo\Registry;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Admin controller registry class
 */
class Controllers extends Registry {

	/** @var array */
	static $includes;

	/** @var array */
	static $loaded = [];

	/**
	 * @return array
	 */
	static function load_includes() {

		$path = AW()->admin_path( '/controllers/' );

		$includes = [
			'guests' => $path . 'guests.php',
			'queue' => $path . 'queue.php',
			'logs' => $path . 'logs.php',
			'licenses' => $path . 'licenses.php',
			'dashboard' => $path . 'dashboard.php',
			'carts' => $path . 'carts.php',
			'reports' => $path . 'reports.php',
			'settings' => $path . 'settings.php',
			'tools' => $path . 'tools.php',
			'unsubscribes' => $path . 'unsubscribes.php',
			'events' => $path . 'events.php'
		];

		return apply_filters( 'automatewoo/admin/controllers/includes', $includes );
	}


	/**
	 * @return Controllers\Base[]
	 */
	static function get_all() {
		return parent::get_all();
	}


	/**
	 * @param $name
	 * @return Controllers\Base|false
	 */
	static function get( $name ) {
		return parent::get( $name );
	}


	/**
	 * Optional method to implement
	 * @param string $name
	 * @param Controllers\Base $controller
	 */
	static function after_loaded( $name, $controller ) {
		$controller->name = $name;
	}

}
