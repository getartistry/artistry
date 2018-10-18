<?php
/**
 * Navigation Menu Extension
 *
 * @package Astra Addon
 */

define( 'ASTRA_EXT_NAV_MENU_DIR', ASTRA_EXT_DIR . 'addons/nav-menu/' );
define( 'ASTRA_EXT_NAV_MENU_URL', ASTRA_EXT_URI . 'addons/nav-menu/' );

if ( ! class_exists( 'Astra_Ext_Nav_Menu' ) ) {

	/**
	 * Footer Widgets Initial Setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Nav_Menu {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 *
		 * @return object
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {
			require_once ASTRA_EXT_NAV_MENU_DIR . 'classes/class-astra-ext-nav-menu-loader.php';
			require_once ASTRA_EXT_NAV_MENU_DIR . 'classes/class-astra-ext-nav-menu-markup.php';
			require_once ASTRA_EXT_NAV_MENU_DIR . 'classes/class-astra-ext-nav-widget-support.php';

			if ( ! is_admin() ) {
				require_once ASTRA_EXT_NAV_MENU_DIR . 'classes/dynamic.css.php';
			}

		}
	}

	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Astra_Ext_Nav_Menu::get_instance();
}
