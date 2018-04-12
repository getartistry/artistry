<?php
namespace ElementorExtras;

use Elementor\Core\Settings\Manager as SettingsManager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register elementor widget.
 *
 * @since 0.1.0
 */
class ElementorExtrasPlugin {

	/**
	 * @var Settings
	 */
	public $settings;

	/**
	 * @var Extensions_Manager
	 */
	public $extensions_manager;

	/**
	 * @var Manager
	 */
	public $modules_manager;

	/**
	 * @var Licensing
	 */
	public $licensing;

	/**
	 * @var WPML
	 */
	public $wpml_compatibility;

	/**
	 * @var Plugin
	 */
	public static $instance = null;

	/**
	 * @var GSAP Version
	 */
	public $gsap_version = '1.20.2';

	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 */
	public function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		add_action( 'elementor/init', [ $this, 'init' ], 0 );
		add_action( 'elementor/init', [ $this, 'init_panel_section' ], 0 );
	}

	/**
	 * Init
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	public function init() {

		// Elementor hooks
		$this->add_actions();

		/* INCLUDES */

		// Include extensions
		$this->includes();

		// Include custom controls
		$this->controls_includes();

		// Components
		$this->init_components();

		// Panel
		$this->init_panel_settings();

		// Register controls
		$this->init_controls();

		/* LICENSING & TRACKING */

		// Setup licesing class
		$this->init_licensing();

		do_action( 'elementor_extras/init' );
	}

	/**
	 * Plugin instance
	 * 
	 * @since 0.1.0
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function add_actions() {

		// ——— SCRIPTS ——— //

			// Editor Scripts
			// add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

			// Front-end Scripts
			add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
			add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );

			// Preview
			// add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_scripts' ] );
			// add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] ); 

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

		// ——— STYLES ——— //

			// Admin Styles
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );

			// Editor Styles
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );

			// Editor Preview Styles
			add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_editor_preview_styles' ] );

			// Front-end Styles
			add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 */
	public function enqueue_admin_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register scripts
		wp_register_script(
			'elementor-extras-admin',
			plugins_url( '/assets/js/admin' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			ELEMENTOR_EXTRAS_VERSION,
			true );

		// Enqueue scripts
		wp_enqueue_script( 'elementor-extras-admin' );
	}

	/**
	 * Enqueue admin styles
	 *
	 * @since 1.1.3
	 *
	 * @access public
	 */
	public function enqueue_admin_styles() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register styles
		wp_register_style(
			'elementor-extras-admin',
			plugins_url( '/assets/css/admin' . $suffix . '.css', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			ELEMENTOR_EXTRAS_VERSION
		);

		wp_register_style(
			'namogo-icons',
			ELEMENTOR_EXTRAS_ASSETS_URL . 'lib/nicons/css/nicons.css',
			[],
			ELEMENTOR_EXTRAS_VERSION
		);

		// Enqueue styles
		wp_enqueue_style( 'namogo-icons' );
		wp_enqueue_style( 'elementor-extras-admin' );
	}

	/**
	 * Enqueue editor scripts
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 */
	public function enqueue_editor_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$elementor_extras_frontend_config = [
			'urls' => [
				'assets' => ELEMENTOR_EXTRAS_ASSETS_URL,
			],
		];

		// Register scripts
		wp_register_script(
			'elementor-extras-editor',
			plugins_url( '/assets/js/editor' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			ELEMENTOR_EXTRAS_VERSION,
			true );

		wp_localize_script( 'elementor-extras-editor', 'elementorExtrasFrontendConfig', $elementor_extras_frontend_config );

		// Enqueue scripts
		wp_enqueue_script( 'elementor-extras-editor' );
	}

	/**
	 * Register scripts
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 */
	public function register_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register scripts

		// Non-widget scripts
		wp_register_script(
			'sticky-kit',
			plugins_url( '/assets/lib/sticky-kit/sticky-kit' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.1.3',
			true );

		wp_register_script(
			'parallax-gallery',
			plugins_url( '/assets/lib/parallax-gallery/parallax-gallery' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.0.0',
			true );

		wp_register_script(
			'parallax-element',
			plugins_url( '/assets/lib/parallax-element/parallax-element' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.0.0',
			true );

		wp_register_script(
			'parallax-background',
			plugins_url( '/assets/lib/parallax-background/parallax-background' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.2.0',
			true );

		// Custom widget scripts
		wp_register_script(
			'image-comparison',
			plugins_url( '/assets/lib/image-comparison/image-comparison' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.0.0',
			true );

		wp_register_script(
			'hotips',
			plugins_url( '/assets/lib/hotips/hotips' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.0.0',
			true );

		wp_register_script(
			'unfold',
			plugins_url( '/assets/lib/unfold/unfold' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.0.0',
			true );

		wp_register_script(
			'circle-progress',
			plugins_url( '/assets/lib/circle-progress/circle-progress' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.2.2',
			true );

		wp_register_script(
			'timeline',
			plugins_url( '/assets/lib/timeline/timeline' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery',
			],
			'1.0.0',
			true );

		wp_register_script(
			'ee-switcher',
			plugins_url( '/assets/lib/switcher/switcher' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery',
			],
			'1.0.0',
			true );

		wp_register_script(
			'splittext',
			plugins_url( '/assets/lib/splittext/splittext' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			'0.4.0',
			true );

		wp_register_script(
			'jquery-appear',
			plugins_url( '/assets/lib/jquery-appear/jquery.appear' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'0.3.6',
			true );

		wp_register_script(
			'jquery-easing',
			plugins_url( '/assets/lib/jquery-easing/jquery-easing' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.3.2',
			true );

		wp_register_script(
			'jquery-mobile',
			plugins_url( '/assets/lib/jquery-mobile/jquery-mobile' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.4.3',
			true );

		wp_register_script(
			'jquery-long-shadow',
			plugins_url( '/assets/lib/jquery-long-shadow/jquery.longShadow' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.1.0',
			true );

		wp_register_script(
			'video-player',
			plugins_url( '/assets/lib/video-player/video-player' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[
				'jquery'
			],
			'1.0.0',
			true );

		wp_register_script(
			'iphone-inline-video',
			plugins_url( '/assets/lib/iphone-inline-video/iphone-inline-video' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			'2.2.2',
			true );

		wp_register_script(
			'tablesorter',
			plugins_url( '/assets/lib/tablesorter/jquery.tablesorter' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			'2.2.2',
			true );

		wp_register_script(
			'isotope',
			plugins_url( '/assets/lib/isotope/isotope.pkgd' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			'3.0.4',
			true );

		wp_register_script(
			'filtery',
			plugins_url( '/assets/lib/filtery/filtery' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			'1.0.0',
			true );

		wp_register_script(
			'tilt',
			plugins_url( '/assets/lib/tilt/tilt.jquery' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			'1.0.0',
			true );

		wp_register_script(
			'infinite-scroll',
			plugins_url( '/assets/lib/infinite-scroll/infinite-scroll.pkgd' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			'3.0.2',
			true );

		wp_register_script(
			'jquery-resize',
			plugins_url( '/assets/lib/jquery-resize/jquery.resize' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			'3.0.2',
			true );

		wp_register_script(
			'custom-ease',
			plugins_url( '/assets/lib/custom-ease/custom-ease' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			'0.2.2',
			true );

		wp_register_script(
			'elementor-extras-frontend',
			plugins_url( '/assets/js/frontend' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			ELEMENTOR_EXTRAS_VERSION,
			true );

	}

	/**
	 * Enqueue scripts for frontend
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 */
	public function enqueue_frontend_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$elementor_extras_frontend_config = [
			'urls' => [
				'assets' => ELEMENTOR_EXTRAS_ASSETS_URL,
			],
		];

		// GSAP TweenMax
		if ( 'no' !== $this->settings->get_option( 'load_tweenmax', 'elementor_extras_advanced', false ) ) {
			wp_enqueue_script( 'gsap-js', '//cdnjs.cloudflare.com/ajax/libs/gsap/' . $this->gsap_version . '/TweenMax.min.js', array(), false, true );
		}

		wp_localize_script( 'elementor-extras-frontend', 'elementorExtrasFrontendConfig', $elementor_extras_frontend_config );
		wp_enqueue_script( 'elementor-extras-frontend' );
	}

	/**
	 * Enqueue preview scripts
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 */
	public function enqueue_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register scripts
		wp_register_script(
			'elementor-extras-preview',
			plugins_url( '/assets/js/preview' . $suffix . '.js', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			ELEMENTOR_EXTRAS_VERSION,
			true );

		wp_enqueue_script( 'elementor-extras-preview' );
	}

	/**
	 * Enqueue preview styles
	 *
	 * @since 1.6.0
	 *
	 * @access public
	 */
	public function enqueue_editor_preview_styles() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register styles
		wp_register_style(
			'elementor-extras-editor-preview',
			plugins_url( '/assets/css/editor-preview' . $suffix . '.css', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			ELEMENTOR_EXTRAS_VERSION
		);

		// Enqueue style
		wp_enqueue_style( 'elementor-extras-editor-preview' );
	}

	/**
	 * Enqueue frontend styles
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 */
	public function enqueue_frontend_styles() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		// Register styles
		wp_register_style(
			'elementor-extras-frontend',
			plugins_url( '/assets/css/frontend' . $direction_suffix . $suffix . '.css', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			ELEMENTOR_EXTRAS_VERSION
		);

		wp_register_style(
			'namogo-icons',
			ELEMENTOR_EXTRAS_ASSETS_URL . 'lib/nicons/css/nicons.css',
			[],
			ELEMENTOR_EXTRAS_VERSION
		);

		// Enqueue styles
		wp_enqueue_style( 'namogo-icons' );
		wp_enqueue_style( 'elementor-extras-frontend' );
	}

	/**
	 * Enqueue editor styles
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 */
	public function enqueue_editor_styles() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		// Register styles
		wp_register_style(
			'namogo-icons',
			ELEMENTOR_EXTRAS_ASSETS_URL . 'lib/nicons/css/nicons.css',
			[],
			ELEMENTOR_EXTRAS_VERSION
		);

		// Register styles
		wp_register_style(
			'elementor-extras-editor',
			plugins_url( '/assets/css/editor' . $suffix . '.css', ELEMENTOR_EXTRAS__FILE__ ),
			[],
			ELEMENTOR_EXTRAS_VERSION
		);

		// Enqueue style
		wp_enqueue_style( 'namogo-icons' );
		wp_enqueue_style( 'elementor-extras-editor' );
	}

	/**
	 * Include components
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function includes() {

		// Widget integrations
		elementor_extras_include( 'includes/compatibility/wpml/compatibility.php' );

		// Managers
		elementor_extras_include( 'includes/managers/extensions.php' );
		elementor_extras_include( 'includes/managers/modules.php' );

		// Panel Settings
		elementor_extras_include( 'core/settings/global/model.php' );
		elementor_extras_include( 'core/settings/global/manager.php' );
	}

	/**
	 * Include custom controls
	 *
	 * @since 1.1.4
	 *
	 * @access private
	 */
	private function controls_includes() {

		// Control Groups
		elementor_extras_include( 'includes/controls/groups/sticky.php' );
		elementor_extras_include( 'includes/controls/groups/long-shadow.php' );
		elementor_extras_include( 'includes/controls/groups/button-effect.php' );
		elementor_extras_include( 'includes/controls/groups/transition.php' );
		elementor_extras_include( 'includes/controls/groups/tooltip.php' );
	}

	/**
	 * Sections init
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	public function init_panel_section() {
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'elementor-extras',
			array( 'title'  => esc_html__( 'Elementor Extras', 'elementor-extras' ), ),
			1
		);
	}

	/**
	 * Sections init
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function init_panel_settings() {
		SettingsManager::add_settings_manager( new \ElementorExtras\Core\Settings\General\Manager() );
	}

	/**
	 * Components init
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function init_components() {
		$this->settings 				= new Settings_API();
		$this->extensions_manager 		= new Extensions_Manager();
		$this->modules_manager 			= new Modules_Manager();
		$this->page_settings_manager 	= new SettingsManager();
		$this->wpml_compatibility 		= new Compatibility\WPML();
	}

	/**
	 * Initializes the licensing class
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function init_licensing() {

		if ( is_admin() ) {

			// Setup the settings page and validation
			$this->licensing = new Namogo_Licensing(
				NAMOGO_SL_ITEM_ID,
				NAMOGO_SL_ITEM_NAME,
				ELEMENTOR_EXTRAS_TEXTDOMAIN
			);
		}

	}

	/**
	 * Register Controls
	 *
	 * @since 1.1.4
	 *
	 * @access private
	 */
	private function init_controls() {

		\Elementor\Plugin::instance()->controls_manager->add_group_control( 'sticky', new Group_Control_Sticky() );
		\Elementor\Plugin::instance()->controls_manager->add_group_control( 'long-shadow', new Group_Control_Long_Shadow() );
		\Elementor\Plugin::instance()->controls_manager->add_group_control( 'effect', new Group_Control_Button_Effect() );
		\Elementor\Plugin::instance()->controls_manager->add_group_control( 'ee-transition', new Group_Control_Transition() );
		\Elementor\Plugin::instance()->controls_manager->add_group_control( 'ee-tooltip', new Group_Control_Tooltip() );
	}

	/**
	 * Autoload Classes
	 *
	 * @since 1.6.0
	 */
	public function autoload( $class ) {

		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {

			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);

			$filename = ELEMENTOR_EXTRAS_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	/**
	 * Check if Woocommerce is installed and active
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 */
	public function is_woocommerce_active() {
		return in_array( 
			'woocommerce/woocommerce.php', 
			apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
		);
	}
}

if ( ! defined( 'ELEMENTOR_EXTRAS_TESTS' ) ) {
	// In tests we run the instance manually.
	ElementorExtrasPlugin::instance();
}

