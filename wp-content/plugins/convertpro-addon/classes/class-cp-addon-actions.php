<?php
/**
 * Convert Pro Addon actions
 *
 * @package Convert Pro Addon
 */

if ( ! class_exists( 'CP_Addon_Actions' ) ) {

	/**
	 * CP_Addon_Actions initial setup
	 *
	 * @since 1.0.0
	 */
	class CP_Addon_Actions {

		/**
		 * Class instance.
		 *
		 * @access private
		 * @var $instance Class instance.
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {

			if ( is_admin() ) {
				add_filter( 'cp_general_addon_page', array( $this, 'addon_content' ), 10, 1 );
				add_action( 'cp_admin_settinga_scripts', array( $this, 'addon_scripts' ), 10, 1 );

				// Ajax requests.
				add_action( 'wp_ajax_cp_addon_activate_module', array( $this, 'activate_module' ) );
				add_action( 'wp_ajax_cp_addon_deactivate_module', array( $this, 'deactivate_module' ) );
			}

		}

		/**
		 * Implement addon update logic.
		 *
		 * @since 1.0.0
		 * @param string $content string parameter.
		 * @return string
		 */
		public function addon_content( $content ) {

			ob_start();
			require_once CP_ADDON_DIR . 'includes/view-extension.php';
			$content = ob_get_clean();

			return $content;
		}

		/**
		 * Implement addon update logic.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function addon_scripts() {

			// Styles.
			wp_enqueue_style( 'cp-addon-admin-settings', CP_ADDON_URL . 'admin/assets/css/cp-addon-admin-settings.css', array(), CP_ADDON_VER );

			// Scripts.
			wp_enqueue_script( 'cp-addon-admin-settings', CP_ADDON_URL . 'admin/assets/js/cp-addon-admin-settings.js', array(), CP_ADDON_VER );

			$options = array(
				'ajax_nonce'  => wp_create_nonce( 'cp-addon-module-nonce' ),
				'ab_test_url' => admin_url( 'admin.php?page=' . CP_PRO_SLUG . '-ab-test' ),
			);

			wp_localize_script( 'jquery', 'cpAddonModules', $options );
		}

		/**
		 * Activate module
		 */
		function activate_module() {

			check_ajax_referer( 'cp-addon-module-nonce', 'nonce' );
			$module_id                = sanitize_text_field( $_POST['module_id'] );
			$extensions               = CP_Addon_Extension::get_enabled_extension();
			$extensions[ $module_id ] = $module_id;
			$extensions               = array_map( 'esc_attr', $extensions );

			CP_Addon_Admin_Helper::update_admin_settings_option( '_cp_addon_enabled_extensions', $extensions );

			do_action( 'cpro_module_activation' );

			echo $module_id;
			die();
		}

		/**
		 * Deactivate module
		 */
		function deactivate_module() {

			check_ajax_referer( 'cp-addon-module-nonce', 'nonce' );
			$module_id                = sanitize_text_field( $_POST['module_id'] );
			$extensions               = CP_Addon_Extension::get_enabled_extension();
			$extensions[ $module_id ] = false;
			$extensions               = array_map( 'esc_attr', $extensions );

			CP_Addon_Admin_Helper::update_admin_settings_option( '_cp_addon_enabled_extensions', $extensions );

			do_action( 'cpro_module_deactivation' );

			echo $module_id;
			die();
		}

	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
CP_Addon_Actions::get_instance();
