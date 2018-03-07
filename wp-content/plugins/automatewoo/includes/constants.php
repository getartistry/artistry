<?php

namespace AutomateWoo;

/**
 * @class Constants
 */
class Constants {


	static function init() {
		self::set_defaults();
	}


	static function set_defaults() {

		if ( ! defined('AW_PREVENT_WORKFLOWS') ) {
			define( 'AW_PREVENT_WORKFLOWS', false );
		}

		if ( ! defined('AUTOMATEWOO_DISABLE_ASYNC_CUSTOMER_NEW_ACCOUNT') ) {
			define( 'AUTOMATEWOO_DISABLE_ASYNC_CUSTOMER_NEW_ACCOUNT', false );
		}

		if ( ! defined('AUTOMATEWOO_DISABLE_ASYNC_SUBSCRIPTION_STATUS_CHANGED') ) {
			define( 'AUTOMATEWOO_DISABLE_ASYNC_SUBSCRIPTION_STATUS_CHANGED', false );
		}

		if ( ! defined('AUTOMATEWOO_DISABLE_ASYNC_ORDER_STATUS_CHANGED') ) {
			define( 'AUTOMATEWOO_DISABLE_ASYNC_ORDER_STATUS_CHANGED', false );
		}

		if ( ! defined('AUTOMATEWOO_LOG_ASYNC_EVENTS' ) ) {
			define( 'AUTOMATEWOO_LOG_ASYNC_EVENTS', false );
		}


		// this constant may be temporary
		if ( ! defined('AUTOMATEWOO_ENABLE_EXTRA_ASYNC_ORDER_CREATED_CHECK') ) {
			define( 'AUTOMATEWOO_ENABLE_EXTRA_ASYNC_ORDER_CREATED_CHECK', false );
		}

	}

}
