<?php
/**
 * Plugin Name: Jet Elements For Elementor
 * Plugin URI:  http://jetelements.zemez.io/
 * Description: Brand new addon for Elementor Page builder. It provides the set of modules to create different kinds of content, adds custom modules to your website and applies attractive styles in the matter of several clicks!
 * Version:     1.9.3
 * Author:      Zemez
 * Author URI:  https://zemez.io/wordpress/
 * Text Domain: jet-elements
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 *
 * @package jet-elements
 * @author  Zemez
 * @version 1.9.3
 * @license GPL-2.0+
 * @copyright  2017, Zemez
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Jet_Elements` doesn't exists yet.
if ( ! class_exists( 'Jet_Elements' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Elements {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private $core = null;

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Plugin version
		 *
		 * @var string
		 */

		private $version = '1.9.3';

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Load the installer core.
			add_action( 'after_setup_theme', require( dirname( __FILE__ ) . '/cherry-framework/setup.php' ), 0 );

			// Load the core functions/classes required by the rest of the plugin.
			add_action( 'after_setup_theme', array( $this, 'get_core' ), 1 );
			// Load the modules.
			add_action( 'after_setup_theme', array( 'Cherry_Core', 'load_all_modules' ), 2 );

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );
			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Loads the core functions. These files are needed before loading anything else in the
		 * plugin because they have required functions for use.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public function get_core() {

			/**
			 * Fires before loads the plugin's core.
			 *
			 * @since 1.0.0
			 */
			do_action( 'jet-elements/core_before' );

			global $chery_core_version;

			if ( null !== $this->core ) {
				return $this->core;
			}

			if ( 0 < sizeof( $chery_core_version ) ) {
				$core_paths = array_values( $chery_core_version );
				require_once( $core_paths[0] );
			} else {
				die( 'Class Cherry_Core not found' );
			}

			$this->core = new Cherry_Core( array(
				'base_dir' => $this->plugin_path( 'cherry-framework' ),
				'base_url' => $this->plugin_url( 'cherry-framework' ),
				'modules'  => array(
					'cherry-js-core' => array(
						'autoload' => true,
					),
					'cherry-ui-elements' => array(
						'autoload' => false,
					),
					'cherry-handler' => array(
						'autoload' => false,
					),
					'cherry-interface-builder' => array(
						'autoload' => false,
					),
					'cherry-utility' => array(
						'autoload' => true,
						'args'     => array(
							'meta_key' => array(
								'term_thumb' => 'cherry_terms_thumbnails'
							),
						)
					),
					'cherry-widget-factory' => array(
						'autoload' => true,
					),
					'cherry-term-meta' => array(
						'autoload' => false,
					),
					'cherry-post-meta' => array(
						'autoload' => false,
					),
					'cherry-dynamic-css' => array(
						'autoload' => false,
					),
					'cherry5-insert-shortcode' => array(
						'autoload' => false,
					),
					'cherry5-assets-loader' => array(
						'autoload' => false,
					),
					'cherry-handler' => array(
						'autoload' => false,
					),
					'cherry-db-updater' => array(
						'autoload' => false,
					),
				),
			) );

			return $this->core;
		}

		/**
		 * Returns plugin version
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			$this->load_files();

			jet_elements_assets()->init();
			jet_elements_download_handler()->init();
			jet_elements_integration()->init();
			jet_elements_shortocdes()->init();
			jet_elements_svg_manager()->init();
			jet_elements_templates_manager()->init();
			jet_elements_compatibility()->init();
			jet_elements_ext_section()->init();

			if ( is_admin() ) {

				require $this->plugin_path( 'includes/updater/class-jet-elements-plugin-update.php' );

				jet_elements_updater()->init( array(
					'version' => $this->get_version(),
					'slug'    => 'jet-elements',
				) );

				if ( ! $this->has_elementor() ) {
					$this->required_plugins_notice();
				}

			}

		}

		/**
		 * Show recommended plugins notice.
		 *
		 * @return void
		 */
		public function required_plugins_notice() {
			require $this->plugin_path( 'includes/lib/class-tgm-plugin-activation.php' );
			add_action( 'tgmpa_register', array( $this, 'register_required_plugins' ) );
		}

		/**
		 * Register required plugins
		 *
		 * @return void
		 */
		public function register_required_plugins() {

			$plugins = array(
				array(
					'name'     => 'Elementor',
					'slug'     => 'elementor',
					'required' => true,
				),
			);

			$config = array(
				'id'           => 'jet-elements',
				'default_path' => '',
				'menu'         => 'tgmpa-install-plugins',
				'parent_slug'  => 'plugins.php',
				'capability'   => 'manage_options',
				'has_notices'  => true,
				'dismissable'  => true,
				'dismiss_msg'  => '',
				'is_automatic' => false,
				'strings'      => array(
					'notice_can_install_required'     => _n_noop(
						'Jet Elements for Elementor requires the following plugin: %1$s.',
						'Jet Elements for Elementor requires the following plugins: %1$s.',
						'jet-elements'
					),
					'notice_can_install_recommended'  => _n_noop(
						'Jet Elements for Elementor recommends the following plugin: %1$s.',
						'Jet Elements for Elementor recommends the following plugins: %1$s.',
						'jet-elements'
					),
				),
			);

			tgmpa( $plugins, $config );

		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		/**
		 * [elementor description]
		 * @return [type] [description]
		 */
		public function elementor() {
			return \Elementor\Plugin::$instance;
		}

		/**
		 * Returns utility instance
		 *
		 * @return object
		 */
		public function utility() {
			$utility = $this->get_core()->modules['cherry-utility'];
			return $utility->utility;
		}

		/**
		 * Load required files.
		 *
		 * @return void
		 */
		public function load_files() {
			require $this->plugin_path( 'includes/class-jet-elements-assets.php' );
			require $this->plugin_path( 'includes/class-jet-elements-download-handler.php' );
			require $this->plugin_path( 'includes/class-jet-elements-tools.php' );
			require $this->plugin_path( 'includes/class-jet-elements-integration.php' );
			require $this->plugin_path( 'includes/class-jet-elements-shortcodes.php' );
			require $this->plugin_path( 'includes/class-jet-elements-settings.php' );
			require $this->plugin_path( 'includes/class-jet-elements-svg-manager.php' );
			require $this->plugin_path( 'includes/class-jet-elements-ajax-handlers.php' );
			require $this->plugin_path( 'includes/template-library/class-jet-elements-templates-manager.php' );
			require $this->plugin_path( 'includes/lib/compatibility/class-jet-elements-compatibility.php' );
			require $this->plugin_path( 'includes/ext/class-jet-elements-ext-section.php' );
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}
		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function lang() {
			load_plugin_textdomain( 'jet-elements', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'jet-elements/template-path', 'jet-elements/' );
		}

		/**
		 * Returns path to template file.
		 *
		 * @return string|bool
		 */
		public function get_template( $name = null ) {

			$template = locate_template( $this->template_path() . $name );

			if ( ! $template ) {
				$template = $this->plugin_path( 'templates/' . $name );
			}

			if ( file_exists( $template ) ) {
				return $template;
			} else {
				return false;
			}
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function deactivation() {
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

if ( ! function_exists( 'jet_elements' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function jet_elements() {
		return Jet_Elements::get_instance();
	}
}

jet_elements();
