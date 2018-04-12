<?php
/**
 * UAEL Core Plugin.
 *
 * @package UAEL
 */

namespace UltimateElementor;

use UltimateElementor\Classes\UAEL_Helper;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * UAEL_Core_Plugin.
 *
 * @package UAEL
 */
class UAEL_Core_Plugin {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Member Variable
	 *
	 * @var Modules Manager
	 */
	public $modules_manager;

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
	 * Constructor
	 */
	public function __construct() {

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->includes();

		$this->setup_actions_filters();
	}

	/**
	 * AutoLoad
	 *
	 * @since 0.0.1
	 * @param string $class class.
	 */
	public function autoload( $class ) {

		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$class_to_load = $class;

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = UAEL_DIR . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}
	}

	/**
	 * Includes.
	 *
	 * @since 0.0.1
	 */
	private function includes() {
		require UAEL_DIR . 'classes/class-uael-admin.php';
		require UAEL_DIR . 'includes/manager/modules-manager.php';
	}

	/**
	 * Setup Actions Filters.
	 *
	 * @since 0.0.1
	 */
	private function setup_actions_filters() {

		add_action( 'elementor/init', array( $this, 'elementor_init' ) );

		add_action( 'elementor/elements/categories_registered', array( $this, 'widget_category' ) );

		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_widget_scripts' ) );

		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_widget_styles' ) );
	}

	/**
	 * Elementor Init.
	 *
	 * @since 0.0.1
	 */
	public function elementor_init() {

		$this->modules_manager = new Module_Manager();

		$this->init_category();

		do_action( 'ultimate_elementor/init' );
	}

	/**
	 * Sections init
	 *
	 * @since 0.0.1
	 * @param object $this_cat class.
	 */
	public function widget_category( $this_cat ) {
		$branding = UAEL_Helper::get_white_labels();
		$category = ( isset( $branding['plugin']['cat_name'] ) && '' != $branding['plugin']['cat_name'] ) ? $branding['plugin']['cat_name'] : UAEL_CATEGORY;

		$this_cat->add_category(
			'ultimate-elements',
			[
				'title' => $category,
				'icon'  => 'eicon-font',
			]
		);

		return $this_cat;
	}


	/**
	 * Sections init
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function init_category() {

		if ( version_compare( ELEMENTOR_VERSION, '2.0.0' ) < 0 ) {
			$branding = UAEL_Helper::get_white_labels();
			$category = ( isset( $branding['plugin']['cat_name'] ) && '' != $branding['plugin']['cat_name'] ) ? $branding['plugin']['cat_name'] : UAEL_CATEGORY;

			\Elementor\Plugin::instance()->elements_manager->add_category(
				'ultimate-elements',
				[
					'title' => $category,
				],
				1
			);
		}
	}

	/**
	 * Register module required js on elementor's action.
	 *
	 * @since 0.0.1
	 */
	function register_widget_scripts() {

		$js_files = UAEL_Helper::get_widget_script();

		foreach ( $js_files as $handle => $data ) {

			wp_register_script( $handle, UAEL_URL . $data['path'], $data['dep'], UAEL_VER, $data['in_footer'] );
		}
	}

	/**
	 * Enqueue module required styles.
	 *
	 * @since 0.0.1
	 */
	function enqueue_widget_styles() {

		$css_files = UAEL_Helper::get_widget_style();

		if ( ! empty( $css_files ) ) {
			foreach ( $css_files as $handle => $data ) {

				wp_register_style( $handle, UAEL_URL . $data['path'], $data['dep'], UAEL_VER );
				wp_enqueue_style( $handle );
			}
		}
	}
}

/**
 *  Prepare if class 'UAEL_Core_Plugin' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
UAEL_Core_Plugin::get_instance();
