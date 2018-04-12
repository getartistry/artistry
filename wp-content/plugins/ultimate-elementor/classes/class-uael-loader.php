<?php
/**
 * UAEL Loader.
 *
 * @package UAEL
 */

if ( ! class_exists( 'UAEL_Loader' ) ) {

	/**
	 * Class UAEL_Loader.
	 */
	final class UAEL_Loader {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

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

			// Activation hook.
			register_activation_hook( UAEL_FILE, array( $this, 'activation_reset' ) );

			// deActivation hook.
			register_deactivation_hook( UAEL_FILE, array( $this, 'deactivation_reset' ) );

			$this->define_constants();

			add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
		}

		/**
		 * Defines all constants
		 *
		 * @since 0.0.1
		 */
		public function define_constants() {
			define( 'UAEL_BASE', plugin_basename( UAEL_FILE ) );
			define( 'UAEL_DIR', plugin_dir_path( UAEL_FILE ) );
			define( 'UAEL_URL', plugins_url( '/', UAEL_FILE ) );
			define( 'UAEL_VER', '1.0.0' );
			define( 'UAEL_MODULES_DIR', UAEL_DIR . 'modules/' );
			define( 'UAEL_MODULES_URL', UAEL_URL . 'modules/' );
			define( 'UAEL_SLUG', 'uael' );
			define( 'UAEL_CATEGORY', 'Ultimate Addons' );
		}

		/**
		 * Loads plugin files.
		 *
		 * @since 0.0.1
		 *
		 * @return void
		 */
		function load_plugin() {

			if ( ! did_action( 'elementor/loaded' ) ) {
				/* TO DO */
				add_action( 'admin_notices', array( $this, 'uael_fails_to_load' ) );
				return;
			}

			$this->load_textdomain();

			require( UAEL_DIR . 'classes/class-uael-core-plugin.php' );
		}

		/**
		 * Load Ultimate Elementor Text Domain.
		 * This will load the translation textdomain depending on the file priorities.
		 *      1. Global Languages /wp-content/languages/ultimate-elementor/ folder
		 *      2. Local dorectory /wp-content/plugins/ultimate-elementor/languages/ folder
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			// Default languages directory for "ultimate-elementor".
			$lang_dir = UAEL_DIR . 'languages/';

			/**
			 * Filters the languages directory path to use for AffiliateWP.
			 *
			 * @param string $lang_dir The languages directory path.
			 */
			$lang_dir = apply_filters( 'uael_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter.
			global $wp_version;

			$get_locale = get_locale();

			if ( $wp_version >= 4.7 ) {
				$get_locale = get_user_locale();
			}

			/**
			 * Language Locale for Ultimate Elementor
			 *
			 * @var $get_locale The locale to use. Uses get_user_locale()` in WordPress 4.7 or greater,
			 *                  otherwise uses `get_locale()`.
			 */
			$locale = apply_filters( 'plugin_locale', $get_locale, 'uael' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'uael', $locale );

			// Setup paths to current locale file.
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/ultimate-elementor/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/ultimate-elementor/ folder.
				load_textdomain( 'uael', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/ultimate-elementor/languages/ folder.
				load_textdomain( 'uael', $mofile_local );
			} else {
				// Load the default language files.
				load_plugin_textdomain( 'uael', false, $lang_dir );
			}
		}
		/**
		 * Fires admin notice when Elementor is not installed and activated.
		 *
		 * @since 0.0.1
		 *
		 * @return void
		 */
		public function uael_fails_to_load() {
			$class = 'notice notice-error';
			/* translators: %s: html tags */
			$message = sprintf( __( 'The %1$sUltimate Addon for Elementor%2$s plugin requires %1$sElementor%2$s plugin installed & activated.', 'uael' ), '<strong>', '</strong>' );

			$plugin = 'elementor/elementor.php';

			if ( _is_elementor_installed() ) {
				if ( ! current_user_can( 'activate_plugins' ) ) {
					return;
				}

				$action_url   = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
				$button_label = __( 'Activate Elementor', 'uael' );

			} else {
				if ( ! current_user_can( 'install_plugins' ) ) {
					return;
				}

				$action_url   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
				$button_label = __( 'Install Elementor', 'uael' );
			}

			$button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

			printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr( $class ), $message, $button );
		}

		/**
		 * Activation Reset
		 */
		function activation_reset() {

			// Force check graupi bundled products.
			update_site_option( 'bsf_force_check_extensions', true );

			if ( is_network_admin() ) {
				$branding = get_site_option( '_uael_white_label' );
			} else {
				$branding = get_option( '_uael_white_label' );
			}

			if ( isset( $branding['agency']['hide_branding'] ) && false != $branding['agency']['hide_branding'] ) {

				$branding['agency']['hide_branding'] = false;

				if ( is_network_admin() ) {

					update_site_option( '_uael_white_label', $branding );

				} else {
					update_option( '_uael_white_label', $branding );
				}
			}
		}

		/**
		 * Deactivation Reset
		 */
		function deactivation_reset() {
		}
	}

	/**
	 *  Prepare if class 'UAEL_Loader' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	UAEL_Loader::get_instance();
}

/**
 * Check if Elementor Pro is installed
 *
 * @since 1.0.0
 *
 * @access public
 */
if ( ! function_exists( '_is_elementor_installed' ) ) {

	/**
	 * Is elementor plugin installed.
	 */
	function _is_elementor_installed() {
		$path    = 'elementor/elementor.php';
		$plugins = get_plugins();

		return isset( $plugins[ $path ] );
	}
}
