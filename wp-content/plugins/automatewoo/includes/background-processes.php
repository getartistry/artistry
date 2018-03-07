<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registry class for background processes
 */
class Background_Processes extends Registry {

	/** @var array */
	static $includes;

	/** @var array  */
	static $loaded = [];


	/**
	 * @return array
	 */
	static function load_includes() {

		$path = AW()->path( '/includes/background-processes/' );

		$includes = [
			'events' => $path . 'events.php',
			'queue' => $path . 'queue.php',
			'abandoned_carts' => $path . 'abandoned-carts.php',
			'customer_win_back' => $path . 'customer-win-back.php',
			'setup_registered_customers' => $path . 'setup-registered-customers.php',
			'setup_guest_customers' => $path . 'setup-guest-customers.php',
			'wishlist_item_on_sale' => $path . 'wishlist-item-on-sale.php',
			'subscription_before_renewal' => $path . 'subscription-before-renewal.php',
			'subscription_before_end' => $path . 'subscription-before-end.php',
			'delete_expired_coupons' => $path . 'delete-expired-coupons.php'
		];

		return apply_filters( 'automatewoo/background_processes/includes', $includes );
	}


	/**
	 * @return Background_Processes\Base[]
	 */
	static function get_all() {
		return parent::get_all();
	}


	/**
	 * @param $name
	 * @return Background_Processes\Base|false
	 */
	static function get( $name ) {
		return parent::get( $name );
	}

}
