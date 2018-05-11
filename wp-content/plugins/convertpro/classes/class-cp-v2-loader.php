<?php
/**
 * Cp_V2_Loader.
 *
 * @package ConvertPro
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'Cp_V2_Loader' ) ) {

	/**
	 * Class Cp_V2_Loader.
	 */
	final class Cp_V2_Loader {

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

					// minimum requirement for PHP version.
			$php = '5.4';

			// If current version is less than minimum requirement, display admin notice.
			if ( version_compare( PHP_VERSION, $php, '<' ) ) {

				add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
				return;
			}

			$this->define_constants();
			$this->load_files();
			add_filter( 'all_plugins', __CLASS__ . '::plugins_page' );
		}

		/**
		 * Branding addon on the plugins page.
		 *
		 * @since 1.0.4
		 * @param array $plugins An array data for each plugin.
		 * @return array
		 */
		static public function plugins_page( $plugins ) {

			$branding = Cp_V2_Loader::get_branding();
			$basename = plugin_basename( CP_V2_BASE_DIR . 'convertpro.php' );

			if ( isset( $plugins[ $basename ] ) && is_array( $branding ) ) {

				$plugin_name = ( array_key_exists( 'name', $branding ) ) ? $branding['name'] : CP_PRO_NAME;
				$plugin_desc = ( array_key_exists( 'description', $branding ) ) ? $branding['description'] : CPRO_DESCRIPTION;
				$author_name = ( array_key_exists( 'author', $branding ) ) ? $branding['author'] : CPRO_AUTHOR_NAME;
				$author_url  = ( array_key_exists( 'author_url', $branding ) ) ? $branding['author_url'] : CPRO_AUTHOR_URL;

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

			return $plugins;
		}

		/**
		 * Returns Branding details for the plugin.
		 *
		 * @since 1.0.4
		 * @return array
		 */
		static public function get_branding() {

			$branding['name']              = get_option( 'cpro_branding_plugin_name' );
			$branding['description']       = get_option( 'cpro_branding_plugin_desc' );
			$branding['author']            = get_option( 'cpro_branding_plugin_author_name' );
			$branding['author_url']        = esc_url( get_option( 'cpro_branding_plugin_author_url' ) );
			$branding['kb_enabled']        = get_option( 'cpro_branding_enable_kb' );
			$branding['kb_url']            = esc_url( get_option( 'cpro_branding_url_kb' ) );
			$branding['support_enabled']   = get_option( 'cpro_branding_enable_support' );
			$branding['support_url']       = esc_url( get_option( 'cpro_branding_url_support' ) );
			$branding['addon_desc']        = get_option( 'cpro_addon_branding_plugin_desc' );
			$branding['hide_branding']     = get_option( 'cpro_hide_branding' );
			$branding['hide_refresh_temp'] = get_option( 'cpro_hide_refresh_template' );

			$branding['kb_enabled']      = ( false === $branding['kb_enabled'] || '1' == $branding['kb_enabled'] ) ? '1' : '0';
			$branding['support_enabled'] = ( false === $branding['support_enabled'] || '1' == $branding['support_enabled'] ) ? '1' : '0';
			$branding['hide_branding']   = ( false === $branding['hide_branding'] || '0' == $branding['hide_branding'] ) ? '0' : '1';

			$branding['hide_refresh_temp'] = ( false === $branding['hide_refresh_temp'] || '0' == $branding['hide_refresh_temp'] ) ? '0' : '1';

			return $branding;
		}

		/**
		 * Shows an admin notice for outdated php version.
		 */
		function php_version_notice() {

			$message = __( 'Your server seems to be running outdated, unsupported and vulnerable version of PHP. You are advised to contact your host provider and upgrade to PHP version 5.6 or greater.', 'convertpro' );

			$this->render_admin_notice( $message, 'warning' );
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

			define( 'CP_V2_DIR_NAME', plugin_basename( $file ) );
			define( 'CP_V2_BASE_FILE', trailingslashit( $file ) . CP_V2_DIR_NAME . '.php' );
			define( 'CP_V2_BASE_DIR', plugin_dir_path( CP_V2_BASE_FILE ) );
			define( 'CP_V2_BASE_URL', plugins_url( '/', CP_V2_BASE_FILE ) );
			define( 'CP_CUSTOM_POST_TYPE', 'cp_popups' );
			define( 'CP_CAMPAIGN_TAXONOMY', 'cp_campaign' );
			define( 'CP_CONNECTION_TAXONOMY', 'cp_connections' );
			define( 'CP_AB_TEST_TAXONOMY', 'cp_ab_test' );
			define( 'BSF_REMOVE_CONVERTPLUG_PRO_FROM_REGISTRATION_LISTING', true );
			define( 'CP_PRO_NAME', 'Convert Pro' );
			define( 'CP_PRO_SLUG', 'convert-pro' );
			define( 'CP_POWERED_BY_URL', 'https://www.convertplug.com/pro/?utm_source=customer-website&utm_medium=credit-link&utm_campaign=powered-by' );
			define( 'CP_KNOWLEDGE_BASE_URL', 'https://www.convertplug.com/pro/docs/' );
			define( 'CP_SUPPORT_URL', 'https://www.convertplug.com/pro/submit-a-ticket/' );
			define( 'CPRO_AUTHOR_NAME', 'Brainstorm Force' );
			define( 'CPRO_AUTHOR_URL', 'https://www.brainstormforce.com' );
			define( 'CPRO_DESCRIPTION', CP_PRO_NAME . ' is an advanced lead generation popup plugin with a drag and drop editor that helps you create beautiful popups and opt-in forms to boost your website conversions. With ' . CP_PRO_NAME . ' you can build email lists, drive traffic, promote videos, offer lead magnets and a lot more.' );

			$plugin_name = get_option( 'cpro_branding_plugin_name' );
			$_name       = ( '' == $plugin_name ) ? CP_PRO_NAME : $plugin_name;

			define( 'CPRO_BRANDING_NAME', $_name );

			// Remove convert pro from license registration listing.
			// Ignore the PHPCS warning about constant declaration.
			// @codingStandardsIgnoreStart
			define( 'BSF_REMOVE_convertpro_FROM_REGISTRATION_LISTING', true );
			// @codingStandardsIgnoreEnd
		}

		/**
		 * Loads classes and includes.
		 *
		 * @since 1.0
		 * @return void
		 */
		static private function load_files() {
			/* Classes */
			$cp_is_admin = is_admin();
			require_once CP_V2_BASE_DIR . 'includes/common-helper-functions.php';
			require_once CP_V2_BASE_DIR . 'classes/class-cp-v2-fonts.php';

			require_once CP_V2_BASE_DIR . 'classes/class-cp-v2-post-type.php';

			require_once CP_V2_BASE_DIR . 'classes/class-cp-v2-auto-update.php';
			require_once CP_V2_BASE_DIR . 'includes/ajax-actions.php';
			require_once CP_V2_BASE_DIR . 'classes/class-cp-v2-model.php';
			require_once CP_V2_BASE_DIR . 'classes/class-cp-v2-popups.php';

			if ( $cp_is_admin ) {

				require_once CP_V2_BASE_DIR . 'classes/class-bsf-updater.php';

				require_once CP_V2_BASE_DIR . 'includes/admin-helper-functions.php';
				require_once CP_V2_BASE_DIR . 'framework/classes/class-cp-framework.php';

				// load framework mapper class.
				require_once CP_V2_BASE_DIR . 'classes/class-cp-v2-admin.php';
				require_once CP_V2_BASE_DIR . 'classes/class-cp-v2-cloud-templates.php';
				require_once CP_V2_BASE_DIR . 'includes/insights-actions.php';
				require_once CP_V2_BASE_DIR . 'includes/class-bsf-menu.php';
				require_once CP_V2_BASE_DIR . 'classes/class-cp-v2-tab-menu.php';

			}

			require_once CP_V2_BASE_DIR . 'framework/class-add-convertplug-v2-widget.php';

		}
	}

	$cp_v2_loader = Cp_V2_Loader::get_instance();
} else {

	add_action( 'admin_notices', 'admin_notices' );
	add_action( 'network_admin_notices', 'admin_notices' );

	/**
	 * Function Name: admin_notices.
	 * Function Description: admin notices.
	 */
	function admin_notices() {

		$url = admin_url( 'plugins.php' );

		echo '<div class="notice notice-error"><p>';
		/* translators: %s URL */
		echo sprintf( __( "You currently have two versions of <strong>Convert Pro</strong> active on this site. Please <a href='%s'>deactivate one</a> before continuing.", 'convertpro' ), $url );
		echo '</p></div>';

	}
}

add_action( 'wp_ajax_cp_dismiss_notice', 'cp_dismiss_notice' );
if ( ! function_exists( 'cp_dismiss_notice' ) ) {

	/**
	 * Function Name: cp_dismiss_notice.
	 * Function Description: cp dismiss notice.
	 */
	function cp_dismiss_notice() {
		$notice = esc_attr( $_POST['notice'] );
		$x      = update_option( $notice, true );
		echo ( $x ) ? true : false;
		die();
	}
}
