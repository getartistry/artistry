<?php
/**
 * Plugin Name:			Ocean Elementor Widgets
 * Plugin URI:			https://oceanwp.org/extension/ocean-elementor-widgets/
 * Description:			Add some new widgets to the popular free page builder Elementor.
 * Version:				1.0.16
 * Author:				OceanWP
 * Author URI:			https://oceanwp.org/
 * Requires at least:	4.0.0
 * Tested up to:		4.9.2
 *
 * Text Domain: ocean-elementor-widgets
 * Domain Path: /languages/
 *
 * @package Ocean_Elementor_Widgets
 * @category Core
 * @author OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of Ocean_Elementor_Widgets to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Ocean_Elementor_Widgets
 */
function Ocean_Elementor_Widgets() {
	return Ocean_Elementor_Widgets::instance();
} // End Ocean_Elementor_Widgets()

Ocean_Elementor_Widgets();

/**
 * Main Ocean_Elementor_Widgets Class
 *
 * @class Ocean_Elementor_Widgets
 * @version	1.0.0
 * @since 1.0.0
 * @package	Ocean_Elementor_Widgets
 */
final class Ocean_Elementor_Widgets {
	/**
	 * Ocean_Elementor_Widgets The single instance of Ocean_Elementor_Widgets.
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
	public function __construct() {
		$this->token 			= 'ocean-elementor-widgets';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.16';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'setup' ) );
		add_action( 'init', array( $this, 'updater' ), 1 );

		// Add new category for Elementor
		add_action( 'elementor/init', array( $this, 'elementor_init' ), 1 );
		
		// Add the action here so that the widgets are always visible
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );
		
		// Translate widgets with WPML
		add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'wpml_widgets_to_translate_filter' ) );
	}

	/**
	 * Initialize License Updater.
	 * Load Updater initialize.
	 * @return void
	 */
	public function updater() {

		// Plugin Updater Code
		if( class_exists( 'OceanWP_Plugin_Updater' ) ) {
			$license	= new OceanWP_Plugin_Updater( __FILE__, 'Elementor Widgets', $this->version, 'OceanWP' );
		}
	}

	/**
	 * Main Ocean_Elementor_Widgets Instance
	 *
	 * Ensures only one instance of Ocean_Elementor_Widgets is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Ocean_Elementor_Widgets()
	 * @return Main Ocean_Elementor_Widgets instance
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
		load_plugin_textdomain( 'ocean-elementor-widgets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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
			require_once( $this->plugin_path .'/includes/helpers.php' );
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'scripts' ) );
			add_action( 'elementor/frontend/after_register_styles', array( $this, 'styles' ) );
		}
	}

	/**
	 * Add new category for Elementor.
	 *
	 * @since 1.0.0
	 */
	public function elementor_init() {

		// Theme branding
		if ( function_exists( 'oceanwp_theme_branding' ) ) {
			$brand = oceanwp_theme_branding();
		} else {
			$brand = 'OceanWP';
		}

		$elementor = \Elementor\Plugin::$instance;

		// Add element category in panel
		$elementor->elements_manager->add_category(
			'oceanwp-elements',
			[
				'title' => $brand . ' ' . __( 'Elements', 'ocean-elementor-widgets' ),
				'icon' => 'font',
			],
			1
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	public function scripts() {

		// Load custom js methods
		wp_register_script( 'isotope', plugins_url( '/assets/js/isotope.min.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'appear', plugins_url( '/assets/js/appear.min.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'oew-alert', plugins_url( '/assets/js/alert.min.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'oew-blog-carousel', plugins_url( '/assets/js/blog-carousel.min.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'oew-blog-grid', plugins_url( '/assets/js/blog-grid.min.js', __FILE__ ), [ 'jquery', 'oceanwp-main' ], false, true );
		wp_register_script( 'oew-search', plugins_url( '/assets/js/search.min.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'oew-skillbar', plugins_url( '/assets/js/skillbar.min.js', __FILE__ ), [ 'jquery' ], false, true );

	}

	/**
	 * Enqueue styles.
	 *
	 * @since 1.0.0
	 */
	public function styles() {

		// Load main stylesheet
		wp_enqueue_style( 'oew-style', plugins_url( '/assets/css/style.min.css', __FILE__ ) );

		// If rtl
		if ( is_RTL() ) {
			wp_enqueue_style( 'oew-style-rtl', plugins_url( '/assets/css/rtl.css', __FILE__ ) );
		}

	}

	/**
	 * Register the widgets
	 *
	 * @since 1.0.0
	 */
	public function widgets_registered() {

		// We check if the Elementor plugin has been installed / activated.
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {

			// Define dir
			$dir = $this->plugin_path .'widgets/';

			// Array of new widgets
			$build_widgets = apply_filters( 'oew_widgets', array(
				'alert' 			=> $dir .'alert.php',
				'blog_carousel' 	=> $dir .'blog_carousel.php',
				'blog_grid' 		=> $dir .'blog_grid.php',
				'logged-in-out' 	=> $dir .'logged-in-out.php',
				'logo' 				=> $dir .'logo.php',
				'navigation' 		=> $dir .'navigation.php',
				'newsletter_form' 	=> $dir .'newsletter_form.php',
				'pricing' 			=> $dir .'pricing.php',
				'search' 			=> $dir .'search.php',
				'skillbar' 			=> $dir .'skillbar.php',
			) );

			// Load files
			foreach ( $build_widgets as $widget_filename ) {
				include $widget_filename;
			}

		}

	}

	/**
	 * Translate widgets with WPML
	 *
	 * @since 1.0.16
	 */
	public function wpml_widgets_to_translate_filter( $widgets ) {
		$widgets[ 'oew-alert' ] = array(
			'conditions' => array( 'widgetType' => 'oew-alert' ),
			'fields'     => array(
				array(
					'field'       => 'title',
					'type'        => __( 'Alert Message Title', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'content',
					'type'        => __( 'Alert Message Content', 'ocean-elementor-widgets' ),
					'editor_type' => 'VISUAL'
				),
			),
		);

		$widgets[ 'oew-logged-in-out' ] = array(
			'conditions' => array( 'widgetType' => 'oew-logged-in-out' ),
			'fields'     => array(
				array(
					'field'       => 'logged_in_content',
					'type'        => __( 'Logged In Content', 'ocean-elementor-widgets' ),
					'editor_type' => 'VISUAL'
				),
				array(
					'field'       => 'logged_out_content',
					'type'        => __( 'Logged Out Content', 'ocean-elementor-widgets' ),
					'editor_type' => 'VISUAL'
				),
			),
		);

		$widgets[ 'oew-newsletter' ] = array(
			'conditions' => array( 'widgetType' => 'oew-newsletter' ),
			'fields'     => array(
				array(
					'field'       => 'placeholder_text',
					'type'        => __( 'Newsletter Placeholder Text', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'submit_text',
					'type'        => __( 'Newsletter Submit Button Text', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINE'
				),
			),
		);

		$widgets[ 'oew-search' ] = array(
			'conditions' => array( 'widgetType' => 'oew-search' ),
			'fields'     => array(
				array(
					'field'       => 'placeholder',
					'type'        => __( 'Search Placeholder', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINE'
				),
			),
		);

		$widgets[ 'oew-pricing' ] = array(
			'conditions' => array( 'widgetType' => 'oew-pricing' ),
			'fields'     => array(
				array(
					'field'       => 'plan',
					'type'        => __( 'Pricing Plan', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'cost',
					'type'        => __( 'Pricing Cost', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'per',
					'type'        => __( 'Pricing Per', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'content',
					'type'        => __( 'Pricing Features', 'ocean-elementor-widgets' ),
					'editor_type' => 'VISUAL'
				),
				array(
					'field'       => 'button_text',
					'type'        => __( 'Pricing Button Text', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINE'
				),
				'link' => array(
					'field'       => 'button_url',
					'type'        => __( 'Pricing Button link', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINK'
				),
			),
		);

		$widgets[ 'oew-skillbar' ] = array(
			'conditions' => array( 'widgetType' => 'oew-skillbar' ),
			'fields'     => array(
				array(
					'field'       => 'title',
					'type'        => __( 'Skillbar Title', 'ocean-elementor-widgets' ),
					'editor_type' => 'LINE'
				),
			),
		);

		return $widgets;
	}

} // End Class