<?php
/**
 * Plugin Name: AutomateWoo
 * Plugin URI: http://automatewoo.com
 * Description: Powerful marketing automation for your WooCommerce store.
 * Version: 3.6.1
 * Author: AutomateWoo
 * Author URI: http://automatewoo.com
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Text Domain: automatewoo
 * Domain Path: /languages
 *
 * WC requires at least: 2.4
 * WC tested up to: 3.3
 */

// Copyright (c) AutomateWoo. All rights reserved.
//
// Released under the GPLv3 license
// http://www.gnu.org/licenses/gpl-3.0
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'AUTOMATEWOO_NAME', __( 'AutomateWoo', 'automatewoo' ) );
define( 'AUTOMATEWOO_SLUG', 'automatewoo' );
define( 'AUTOMATEWOO_VERSION', '3.6.1' );
define( 'AUTOMATEWOO_FILE', __FILE__ );
define( 'AUTOMATEWOO_PATH', dirname( __FILE__ ) );
define( 'AUTOMATEWOO_MIN_PHP_VER', '5.4' );


/**
 * @class AutomateWoo_Loader
 * @since 2.9
 */
class AutomateWoo_Loader {

	static $errors = array();


	static function load() {
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ), 8 );
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );

		if ( self::check() ) {
			include AUTOMATEWOO_PATH . '/includes/automatewoo.php';
		}
	}


	static function load_textdomain() {
		load_plugin_textdomain( 'automatewoo', false, "automatewoo/languages" );
	}


	/**
	 * @return bool
	 */
	static function check() {
		$ok = true;

		if ( version_compare( phpversion(), AUTOMATEWOO_MIN_PHP_VER, '<' ) ) {
			self::$errors[] = sprintf( __( '<strong>%s</strong> requires PHP version %s+.' , 'automatewoo' ), AUTOMATEWOO_NAME, AUTOMATEWOO_MIN_PHP_VER );
			$ok = false;
		}

		return $ok;
	}


	static function admin_notices() {
		if ( empty( self::$errors ) ) return;
		echo '<div class="notice notice-warning"><p>';
		echo implode( '<br>', self::$errors );
		echo '</p></div>';
	}

}

AutomateWoo_Loader::load();
