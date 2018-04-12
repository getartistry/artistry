<?php
/**
 * Convert Pro Addon A/B Test loader file
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'CPRO_ABTest_Loader' ) ) {

	/**
	 * Responsible for setting up constants, classes and includes.
	 *
	 * @since 1.0.0
	 */
	final class CPRO_ABTest_Loader {

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
		function __construct() {

			$this->define_constants();
			$this->load_files();
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'bsf_menu_ab_test_action', array( $this, 'ab_testing_page' ) );
			add_filter( 'bsf_menu_options', array( $this, 'add_abtest_menu' ) );
		}

		/**
		 * Adds menu.
		 *
		 * @since 1.0.0
		 * @param array $options Option array.
		 * @return array $return_options
		 */
		public function add_abtest_menu( $options ) {
			$return_options = array();
			foreach ( $options as $key => $value ) {

				$return_options[ $key ] = $value;
				if ( 'create-new' == $key && ! isset( $options['ab-test'] ) ) {
					$return_options['ab-test'] = array(
						'name' => __( 'A/B Test', 'convertpro-addon' ),
						'link' => false,
					);
				}
			}
			return $return_options;
		}

		/**
		 * Loads Main page.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function ab_testing_page() {
			require_once( CP_ABTEST_BASE_DIR . 'admin/main.php' );
		}

		/**
		 * Renders an admin scripts.
		 *
		 * @since 1.0.0
		 * @param string $hook Action string.
		 * @return void
		 */
		public function admin_scripts( $hook ) {

			$current_screen = get_current_screen();

			if ( false !== strpos( $hook, CP_PRO_SLUG . '-ab-test' )
				|| (
					false !== strpos( $hook, CP_PRO_SLUG )
					&& isset( $_GET['action'] )
					&& 'ab-test' == $_GET['action']
					)
				) {

				$dev_mode = get_option( 'cp_dev_mode' );

				wp_enqueue_style( 'cp-datetimepicker-style', CP_V2_BASE_URL . 'assets/admin/css/bootstrap-datetimepicker.min.css' );
				wp_enqueue_script( 'cp-moment-script', CP_V2_BASE_URL . 'assets/admin/js/moment-with-locales.js', false, CP_V2_VERSION, true );

				wp_enqueue_script( 'cp-datetimepicker-style', CP_V2_BASE_URL . 'assets/admin/js/bootstrap-datetimepicker.min.js', false, CP_V2_VERSION, true );

				if ( '1' == $dev_mode ) {
					wp_register_script( 'cp-abtest', CP_ABTEST_BASE_URL . '/assets/js/cp-abtest.js', array( 'jquery' ), time(), true );
					wp_enqueue_script( 'cp-abtest' );
					wp_enqueue_style( 'css-abtest', CP_ABTEST_BASE_URL . 'assets/css/cp-abtest.css' );
				} else {
					wp_register_script( 'cp-abtest', CP_ABTEST_BASE_URL . '/assets/js/cp-abtest.min.js', array( 'jquery' ), time(), true );
					wp_enqueue_script( 'cp-abtest' );
					wp_enqueue_style( 'css-abtest', CP_ABTEST_BASE_URL . 'assets/css/cp-abtest.min.css' );
				}

				wp_localize_script(
					'cp-abtest', 'cp_abtest',
					array(
						'url'                => admin_url( 'admin-ajax.php' ),
						'ajax_nonce'         => wp_create_nonce( 'cp_ajax_nonce' ),
						'create_new_url'     => admin_url( 'admin.php?page=' . CP_PRO_SLUG ),
						'end_date'           => __( 'Please enter the end date.', 'convertpro-addon' ),
						'start_date'         => __( 'Please enter the start date.', 'convertpro-addon' ),
						'delete_test'        => __( 'Are you sure you want to delete this A/B Test?', 'convertpro-addon' ),
						'stop_test'          => __( 'Are you sure you want to stop this A/B Test? Once you stop it, you can not start it again. Also, the winner style will be live.', 'convertpro-addon' ),
						'parent_style'       => __( 'Error: Please select parent style.', 'convertpro-addon' ),
						'two_popups'         => __( 'Error: Please select at least two call-to-actions.', 'convertpro-addon' ),
						'atleast_one_design' => __( 'Error: Please select the call-to-actions you wish to compare.', 'convertpro-addon' ),
						'select_styles'      => __( 'Select Call-to-actions', 'convertpro-addon' ),
						'edit_test'          => __( 'Edit Test', 'convertpro-addon' ),
						'select'             => __( '--Select--', 'convertpro-addon' ),
						'create_test'        => __( 'Create Test', 'convertpro-addon' ),
						'create_new_test'    => __( 'Create New Test', 'convertpro-addon' ),
						'update_test'        => __( 'Update Test', 'convertpro-addon' ),
						'saved'              => __( 'Saved', 'convertpro-addon' ),
					)
				);
			}

			if ( ( 'add' == $current_screen->action && CP_CUSTOM_POST_TYPE == $current_screen->post_type )
				|| ( CP_CUSTOM_POST_TYPE == $current_screen->post_type && ( isset( $_GET['action'] ) && 'edit' == $_GET['action'] && 'post' == $current_screen->base ) ) ) {
				wp_register_script( 'cp-abtest-customiser', CP_ABTEST_BASE_URL . '/assets/js/cp-abtest-customiser.js', array( 'jquery' ), time(), true );
				wp_enqueue_script( 'cp-abtest-customiser' );
			}
		}

		/**
		 * Define constants.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function define_constants() {

			define( 'CP_ABTEST_BASE_DIR', CP_ADDON_DIR . 'addons/ab-test/' );
			define( 'CP_ABTEST_BASE_URL', CP_ADDON_URL . 'addons/ab-test/' );
		}

		/**
		 * Loads classes and includes.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static private function load_files() {
			/* Classes */
			require_once CP_ABTEST_BASE_DIR . 'classes/class-cp-v2-ab-test.php';
			require_once CP_ABTEST_BASE_DIR . 'classes/class-cpro-abtest-admin.php';
			require_once CP_ABTEST_BASE_DIR . 'classes/class-cpro-abtest-helper.php';
		}
	}

	$abtest_loader = CPRO_ABTest_Loader::get_instance();
}
