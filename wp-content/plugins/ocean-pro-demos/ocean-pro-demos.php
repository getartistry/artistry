<?php
/**
 * Plugin Name:			Ocean Pro Demos
 * Description:			Import the OceanWP pro demos, widgets and customizer settings with one click.
 * Version:				1.0.4
 * Author:				OceanWP
 * Author URI:			https://oceanwp.org/
 * Requires at least:	4.0.0
 * Tested up to:		4.9.4
 *
 * Text Domain: ocean-pro-demos
 * Domain Path: /languages/
 *
 * @package Ocean_Pro_Demos
 * @category Core
 * @author OceanWP
 * @see This plugin is based on: https://github.com/proteusthemes/one-click-demo-import/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of Ocean_Pro_Demos to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Ocean_Pro_Demos
 */
function Ocean_Pro_Demos() {
	return Ocean_Pro_Demos::instance();
} // End Ocean_Pro_Demos()

Ocean_Pro_Demos();

/**
 * Main Ocean_Pro_Demos Class
 *
 * @class Ocean_Pro_Demos
 * @version	1.0.0
 * @since 1.0.0
 * @package	Ocean_Pro_Demos
 */
final class Ocean_Pro_Demos {
	/**
	 * Ocean_Pro_Demos The single instance of Ocean_Pro_Demos.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct( $widget_areas = array() ) {
		$this->token 			= 'ocean-pro-demos';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.4';

		define( 'OPD_PATH', $this->plugin_path );
		define( 'OPD_URL', $this->plugin_url );

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'setup' ) );
		add_action( 'init', array( $this, 'updater' ), 1 );
	}

	/**
	 * Initialize License Updater.
	 * Load Updater initialize.
	 * @return void
	 */
	public function updater() {

		// Plugin Updater Code
		if ( class_exists( 'OceanWP_Plugin_Updater' ) ) {
			$license	= new OceanWP_Plugin_Updater( __FILE__, 'Pro Demos', $this->version, 'OceanWP' );
		}
	}

	/**
	 * Main Ocean_Pro_Demos Instance
	 *
	 * Ensures only one instance of Ocean_Pro_Demos is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Ocean_Pro_Demos()
	 * @return Main Ocean_Pro_Demos instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'ocean-pro-demos', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Setup all the things.
	 * Only executes if OceanWP or a child theme using OceanWP as a parent is active and the extension specific filter returns true.
	 * @return void
	 */
	public function setup() {
		$theme = wp_get_theme();

		if ( 'OceanWP' == $theme->name || 'oceanwp' == $theme->template ) {
			if ( is_admin()
				&& version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				require_once( OPD_PATH .'/includes/class/class-helpers.php' );
				require_once( OPD_PATH .'/includes/importer.php' );
				require_once( OPD_PATH .'/includes/install-demos.php' );
			}
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		}
	}

	/**
	 * Load scripts
	 *
	 * @since 1.0.0
	 */
	public static function scripts( $hook_suffix ) {

		if ( 'theme-panel_page_oceanwp-panel-pro-demos' == $hook_suffix ) {

			// CSS
			wp_enqueue_style( 'opd-style', plugins_url( '/assets/css/admin.min.css', __FILE__ ) );

			// JS
			wp_enqueue_script( 'opd-js', plugins_url( '/assets/js/admin.min.js', __FILE__ ) );

		}

	}

} // End Class
