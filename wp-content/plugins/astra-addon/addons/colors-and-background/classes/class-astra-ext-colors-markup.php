<?php
/**
 * Colors & Background Markup
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Astra_Ext_Colors_Markup' ) ) {

	/**
	 * Colors & Background Markup Initial Setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Colors_Markup {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {

			add_filter( 'primary_submenu_border_class', array( $this, 'submenu_class_callback' ) );
		}

		/**
		 * Filter primary submenu border class for nav_menu
		 *
		 * @since 1.0
		 * @param array $class  Navigation argument array.
		 * @return array $class
		 */
		function submenu_class_callback( $class ) {

			$primary_submenu_border = astra_get_option( 'primary-submenu-border' );
			if ( ! $primary_submenu_border ) {
				$class = '';
			}
			return $class;
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Ext_Colors_Markup::get_instance();
