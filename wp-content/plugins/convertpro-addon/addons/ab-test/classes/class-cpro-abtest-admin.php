<?php
/**
 * Convert Pro Addon A/B Test admin file
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Main builder admin class.
 *
 * @since 1.0.0
 */
final class CPRO_ABTest_Admin {

	/**
	 * Class Instance.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $instance
	 */
	private static $instance;

	/**
	 * Gets an instance of our plugin.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 100 );
		add_action( 'init', array( $this, 'post_type_setup' ), 15 );
	}

	/**
	 * Setup new post type "cp_ab_test"
	 *
	 * @since 1.0.0
	 */
	function post_type_setup() {
		register_taxonomy(
			CP_AB_TEST_TAXONOMY,
			CP_CUSTOM_POST_TYPE,
			array(
				'label'             => __( 'AB Tests', 'convertpro-addon' ),
				'rewrite'           => false,
				'public'            => false,
				'show_ui'           => false,
				'show_admin_column' => true,
				'show_in_menu'      => false,
				'show_in_rest'      => true,
			)
		);

		register_taxonomy_for_object_type( CP_AB_TEST_TAXONOMY, CP_CUSTOM_POST_TYPE );
	}

	/**
	 * Load scripts and styles on admin area of convertPlug
	 *
	 * @param string $hook hook.
	 * @since 1.0.0
	 */
	function enqueue_admin_scripts( $hook ) {

		$current_screen = get_current_screen();

		if ( strpos( $hook, CP_PRO_SLUG . '-ab-test' ) ) {

			wp_enqueue_script( 'cp-gc-script', 'https://www.gstatic.com/charts/loader.js', false, CP_V2_VERSION, true );
			wp_enqueue_style( 'cp-datetimepicker-style', CP_V2_BASE_URL . 'assets/admin/css/bootstrap-datetimepicker.min.css' );
			wp_enqueue_script( 'cp-moment-script', CP_V2_BASE_URL . 'assets/admin/js/moment-with-locales.js', false, CP_V2_VERSION, true );

			wp_enqueue_script( 'cp-datetimepicker-style', CP_V2_BASE_URL . 'assets/admin/js/bootstrap-datetimepicker.min.js', false, CP_V2_VERSION, true );
		}
	}
}

$abtest_admin = CPRO_ABTest_Admin::get_instance();
