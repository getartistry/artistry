<?php
/**
 * Convert Pro Addon Import/export class
 *
 * @package ConvertPro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'CPRO_Import_Export' ) ) {

	/**
	 * Responsible for setting up constants, classes and includes.
	 *
	 * @since 0.0.1
	 */
	final class CPRO_Import_Export {

		/**
		 * The unique instance of the plugin.
		 *
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

			$this->define_constants();
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			require_once( 'class-cpro-import-export-helper.php' );
		}

		/**
		 * Renders an admin scripts.
		 *
		 * @since 0.0.1
		 * @return void
		 */
		public function admin_scripts() {

			wp_enqueue_media();

			wp_register_script( 'cp-import-export', CP_IMPORT_EXPORT_BASE_URL . '/assets/js/cp-import-export.js', array( 'jquery' ), time(), true );
			wp_localize_script(
				'cp-import-export', 'cp_import_export',
				array(
					'url' => admin_url( 'admin-ajax.php' ),
				)
			);
			wp_enqueue_script( 'cp-import-export' );
		}

		/**
		 * Define constants.
		 *
		 * @since 0.0.1
		 * @return void
		 */
		private function define_constants() {

			define( 'CP_IMPORT_EXPORT_BASE_DIR', CP_ADDON_DIR . 'addons/import-export/' );
			define( 'CP_IMPORT_EXPORT_BASE_URL', CP_ADDON_URL . 'addons/import-export/' );
		}
	}

	$cpro_import_export = CPRO_Import_Export::get_instance();
}
