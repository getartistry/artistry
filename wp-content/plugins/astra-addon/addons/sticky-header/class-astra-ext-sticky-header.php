<?php
/**
 * Sticky Header Extension
 *
 * @package Astra Addon
 */

define( 'ASTRA_EXT_STICKY_HEADER_DIR', ASTRA_EXT_DIR . 'addons/sticky-header/' );
define( 'ASTRA_EXT_STICKY_HEADER_URI', ASTRA_EXT_URI . 'addons/sticky-header/' );

if ( ! class_exists( 'Astra_Ext_Sticky_Header' ) ) {

	/**
	 * Sticky Header Initial Setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Sticky_Header {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			require_once ASTRA_EXT_STICKY_HEADER_DIR . 'classes/class-astra-ext-sticky-header-loader.php';
			require_once ASTRA_EXT_STICKY_HEADER_DIR . 'classes/class-astra-ext-sticky-header-markup.php';

			// Include front end files.
			if ( ! is_admin() ) {
				require_once ASTRA_EXT_STICKY_HEADER_DIR . 'classes/dynamic-css/dynamic.css.php';
				// Check Header Sections is activated.
				if ( Astra_Ext_Extension::is_active( 'header-sections' ) ) {
					require_once ASTRA_EXT_STICKY_HEADER_DIR . 'classes/dynamic-css/header-sections-dynamic.css.php';
				}
				// Check Site Layouts is activated.
				if ( Astra_Ext_Extension::is_active( 'site-layouts' ) ) {
					require_once ASTRA_EXT_STICKY_HEADER_DIR . 'classes/dynamic-css/site-layouts-dynamic.css.php';
				}
			}
		}
	}

	/**
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Astra_Ext_Sticky_Header::get_instance();

}
