<?php
/**
 * CP_Addon_Loader.
 *
 * @package Convert Pro Addon
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'CP_Addon_Loader' ) ) {

	/**
	 * Class CP_Addon_Loader.
	 */
	final class CP_Addon_Loader {

		/**
		 * The unique instance of the plugin.
		 *
		 * @var instance
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

			if ( ! class_exists( 'Cp_V2_Loader' ) ) {

				add_action( 'admin_notices', array( $this, 'cp_pro_required_notice' ) );
				return;
			}

			$this->define_constants();
			$this->load_files();
			$this->load_textdomain();

			add_filter( 'all_plugins', __CLASS__ . '::plugins_page' );
		}

		/**
		 * Load plugin text domain.
		 *
		 * @since 1.0.0
		 */
		function load_textdomain() {

			// Traditional WordPress plugin locale filter.
			$locale = apply_filters( 'plugin_locale', get_locale(), 'convertpro-addon' );

			// Setup paths to current locale file.
			$mofile_global = trailingslashit( WP_LANG_DIR ) . 'plugins/convertpro-addon/' . $locale . '.mo';
			$mofile_local  = trailingslashit( CP_ADDON_DIR ) . 'languages/' . $locale . '.mo';

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/plugins/convertpro-addon/ folder.
				return load_textdomain( 'convertpro-addon', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/convertpro-addon/languages/ folder.
				return load_textdomain( 'convertpro-addon', $mofile_local );
			}

			// Nothing found.
			return false;
		}

		/**
		 * Admin Notice.
		 *
		 * @since 1.0.0
		 */
		function cp_pro_required_notice() {
			$message = __( 'Please install and activate <strong>Convert Pro</strong> to use <strong>Convert Pro - Addon</strong>.', 'convertpro-addon' );

			$this->render_admin_notice( $message, 'warning notice notice-error is-dismissible' );
		}

		/**
		 * Branding addon on the plugins page.
		 *
		 * @since 1.0.4
		 * @param array $plugins An array data for each plugin.
		 * @return array
		 */
		static public function plugins_page( $plugins ) {

			if ( class_exists( 'Cp_V2_Loader' ) ) {
				if ( method_exists( 'Cp_V2_Loader', 'get_branding' ) ) {
					$branding = Cp_V2_Loader::get_branding();
					$basename = plugin_basename( CP_ADDON_DIR . 'convertpro-addon.php' );

					if ( isset( $plugins[ $basename ] ) && is_array( $branding ) ) {

						$plugin_name = ( array_key_exists( 'name', $branding ) && '' != $branding['name'] ) ? $branding['name'] . ' Addon' : CP_PRO_NAME . ' Addon';

						$plugin_desc = ( array_key_exists( 'addon_desc', $branding ) && '' != $branding['addon_desc'] ) ? $branding['addon_desc'] : CPRO_ADDON_DESC;

						$author_name = ( array_key_exists( 'author', $branding ) && '' != $branding['author'] ) ? $branding['author'] : 'Brainstorm Force';

						$author_url = ( array_key_exists( 'author_url', $branding ) && '' != $branding['author_url'] ) ? $branding['author_url'] : 'http://www.brainstormforce.com';

						$_name = $plugin_name;

						if ( '' != $plugin_name ) {
							$plugins[ $basename ]['Name']  = $plugin_name;
							$plugins[ $basename ]['Title'] = $plugin_name;
						}

						if ( '' != $plugin_desc ) {
							$plugins[ $basename ]['Description'] = $plugin_desc;
						}

						if ( '' != $author_name ) {
							$plugins[ $basename ]['Author']     = $author_name;
							$plugins[ $basename ]['AuthorName'] = $author_name;
						}

						if ( '' != $author_url ) {
							$plugins[ $basename ]['AuthorURI'] = $author_url;
							$plugins[ $basename ]['PluginURI'] = $author_url;
						}
					}
				}
			}

			return $plugins;
		}

		/**
		 * Function Name: render_admin_notice.
		 * Function Description: Renders an admin notice.
		 *
		 * @param string $message string parameter.
		 * @param string $type string parameter.
		 */
		private function render_admin_notice( $message, $type = 'update' ) {

			if ( ! is_admin() ) {
				return;
			} elseif ( ! is_user_logged_in() ) {
				return;
			} elseif ( ! current_user_can( 'update_core' ) ) {
				return;
			}

			echo '<div class="' . $type . '">';
			echo '<p>' . $message . '</p>';
			echo '</div>';
		}

		/**
		 * Define constants.
		 *
		 * @since 1.0
		 * @return void
		 */
		private function define_constants() {

			$file = dirname( dirname( __FILE__ ) );

			define( 'CP_ADDON_VER', '1.1.0' );
			define( 'CP_ADDON_DIR_NAME', plugin_basename( $file ) );
			define( 'CP_ADDON_FILE', trailingslashit( $file ) . CP_ADDON_DIR_NAME . '.php' );
			define( 'CP_ADDON_DIR', plugin_dir_path( CP_ADDON_FILE ) );
			define( 'CP_ADDON_URL', plugins_url( '/', CP_ADDON_FILE ) );
			define( 'CPRO_GA_EVENT_NAME', 'CONVERTPRO' );

			if ( ! defined( 'CPRO_BRANDING_NAME' ) ) {
				define( 'CPRO_BRANDING_NAME', CP_PRO_NAME );
			}

			define( 'CPRO_ADDON_DESC', 'Convert Pro Addon is a collection of advanced modules and features that you can enable or disable as per your needs. With these modules, you can integrate with third party mailers, view analytics, A/B test your designs, add advanced scripts and view grid lines within the editor.' );
		}

		/**
		 * Loads classes and includes.
		 *
		 * @since 1.0
		 * @return void
		 */
		static private function load_files() {

			require_once CP_ADDON_DIR . 'classes/class-cp-addon-admin-helper.php';
			require_once CP_ADDON_DIR . 'classes/class-cp-addon-extension.php';
			require_once CP_ADDON_DIR . 'classes/class-cp-addon-actions.php';
			require_once CP_ADDON_DIR . 'classes/class-cp-addon-auto-update.php';

			/* Module Loader */
			require_once CP_ADDON_DIR . 'classes/class-cp-addon-module-loader.php';
		}
	}

	$cp_addon_loader = CP_Addon_Loader::get_instance();
}
