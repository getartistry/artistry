<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Stripe
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCSTRIPE' ) ) {
	exit;
} // Exit if accessed directly

if( ! class_exists( 'YITH_WCStripe_Admin_Premium' ) ){
	/**
	 * WooCommerce Stripe main class
	 *
	 * @since 1.0.0
	 */
	class YITH_WCStripe_Admin_Premium extends YITH_WCStripe_Admin {

		/**
		 * Constructor.
		 *
		 * @return \YITH_WCStripe_Admin_Premium
		 * @since 1.0.0
		 */
		public function __construct() {

			parent::__construct();

			// register plugin to licence/update system
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			// if blacklist enabled, create blacklist page
			//if ( ( $gateway = YITH_WCStripe()->get_gateway() ) && $gateway->get_option( 'enable_blacklist' ) == 'yes' ) {
				include_once( 'class-yith-stripe-blacklist-admin.php' );
			//}

		}

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since    2.0.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 */

		public function register_plugin_for_activation() {
			if( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once 'plugin-fw/lib/yit-plugin-licence.php';
			}
			YIT_Plugin_Licence()->register( YITH_WCSTRIPE_INIT, YITH_WCSTRIPE_SECRET_KEY, YITH_WCSTRIPE_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since    2.0.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 */

		public function register_plugin_for_updates() {
			if( ! class_exists( 'YIT_Upgrade' ) ) {
				require_once 'plugin-fw/lib/yit-upgrade.php';
			}
			YIT_Upgrade()->register( YITH_WCSTRIPE_SLUG, YITH_WCSTRIPE_INIT );
		}
	}
}