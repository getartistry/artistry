<?php
/**
 * Main builder BSF Updater class.
 *
 * @package ConvertPro
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
if ( ! class_exists( 'BSF_Updater' ) ) {
	/**
	 * Class BSF_Updater.
	 */
	class BSF_Updater {

		/**
		 * Constructor.
		 */
		function __construct() {

			$this->set_bsf_path();
			add_action( 'init', array( $this, 'bsf_core_load' ), 999 );
			add_action( 'wp_ajax_bsf_dismiss_notice', array( $this, 'bsf_dismiss_notice' ) );
			add_filter( 'bsf_skip_braisntorm_menu', array( $this, 'skip_brainstorm_menu' ) );
			add_filter( 'bsf_skip_author_registration', array( $this, 'skip_brainstorm_menu' ) );

			// Registartion on CP Pro.
			add_filter( 'bsf_registration_page_url_convertpro', array( $this, 'registration_page_url' ) );
			add_filter( 'bsf_license_form_heading_convertpro', array( $this, 'license_form_heading' ), 10, 3 );
			add_filter(
				'bsf_license_not_activate_message_convertpro', array( $this, 'license_not_active_message' ),
				10, 3
			);
		}

		/**
		 * Function Name: set_bsf_path.
		 * Function Description: Set path.
		 */
		function set_bsf_path() {

			$bsf_core_version_file = CP_V2_BASE_DIR . '/admin/bsf-core/version.yml';
			if ( is_file( $bsf_core_version_file ) ) {
				global $bsf_core_version, $bsf_core_path;
				$bsf_core_dir = CP_V2_BASE_DIR . '/admin/bsf-core/';
				$version      = file_get_contents( $bsf_core_version_file );
				if ( version_compare( $version, $bsf_core_version, '>' ) ) {
					$bsf_core_version = $version;
					$bsf_core_path    = $bsf_core_dir;
				}
			}

			if ( isset( $_GET['hide-bsf-core-notice'] ) && 're-enable' === $_GET['hide-bsf-core-notice'] ) {
				$x = $this->bsf_update_option( 'hide-bsf-core-notice', false );
			}

		}

		/**
		 * Function Name: bsf_core_load.
		 * Function Description: Load Core.
		 */
		function bsf_core_load() {
			global $bsf_core_version, $bsf_core_path;
			if ( is_file( realpath( $bsf_core_path . '/index.php' ) ) ) {
				include_once realpath( $bsf_core_path . '/index.php' );
			}
		}

		/**
		 * Sub Heading for the extensions installer screen
		 *
		 * @return String: Sub Heading to which will appear on Extensions installer page
		 */
		function cp_bsf_extensioninstaller_subheading() {
			/* translators: %s percentage */
			return sprintf( __( 'Addons extend the functionality of %1$s. With these addons, you can connect with third party softwares, integrate new features and make %2$s even more powerful.', 'convertpro' ), CPRO_BRANDING_NAME, CPRO_BRANDING_NAME );
		}


		/**
		 * Heading for the extensions installer screen
		 *
		 * @return String: Heading to which will appear on Extensions installer page
		 */
		function cp_extensioninstaller_heading() {
			return __( 'Addons', 'convertpro' );
		}

		/**
		 * Function Name: bsf_get_option.
		 * Function Description: Get options.
		 *
		 * @param Boolean $request true or false.
		 */
		function bsf_get_option( $request = false ) {
			$bsf_options = get_option( 'bsf_options' );
			if ( ! $request ) {
				return $bsf_options;
			} else {
				return ( isset( $bsf_options[ $request ] ) ) ? $bsf_options[ $request ] : false;
			}
		}

		/**
		 * Function Name: bsf_update_option.
		 * Function Description: Update options.
		 *
		 * @param Boolean $request request.
		 * @param string  $value value.
		 */
		function bsf_update_option( $request, $value ) {
			$bsf_options             = get_option( 'bsf_options' );
			$bsf_options[ $request ] = $value;
			return update_option( 'bsf_options', $bsf_options );
		}

		/**
		 * Function Name: bsf_dismiss_notice.
		 * Function Description: Dismiss BSF Notice.
		 */
		function bsf_dismiss_notice() {
			$notice = $_POST['notice'];
			$x      = $this->bsf_update_option( $notice, true );
			echo ( $x ) ? true : false;
			die();
		}

		/**
		 * Function Name: product_extensions_menu.
		 * Function Description: Register Next Extensions installer menu.
		 *
		 * @param string $reg_menu reg_menu.
		 */
		function product_extensions_menu( $reg_menu ) {
			$reg_menu = get_site_option( 'bsf_installer_menu', $reg_menu );

			$_dir = CP_V2_BASE_DIR;

			$bsf_your_prefix_id = bsf_extract_product_id( $_dir );

			$reg_menu['ConvertPlugAddon'] = array(
				'parent_slug' => 'convertpro',
				'page_title'  => __( 'Addons', 'convertpro' ),
				'menu_title'  => __( 'Addons', 'convertpro' ),
				'product_id'  => $bsf_your_prefix_id,
			);

			update_site_option( 'bsf_installer_menu', $reg_menu );

			return $reg_menu;
		}

		/**
		 * Function Name: skip_brainstorm_menu.
		 * Function Description: skip_brainstorm_menu.
		 *
		 * @param string $products products.
		 */
		function skip_brainstorm_menu( $products ) {

			$priduct_id = 'convertpro';
			$products[] = $priduct_id;

			return $products;
		}

		/**
		 * Function Name: registration_page_url.
		 * Function Description: registration_page_url.
		 *
		 * @param string $url url.
		 */
		function registration_page_url( $url ) {

			return CP_V2_Tab_Menu::get_page_url( 'general-settings' ) . '#license';
		}

		/**
		 * Function Name: license_not_active_message.
		 * Function Description: license_not_active_message.
		 *
		 * @param string $not_activate not_activate.
		 * @param string $license_status_class license_status_class.
		 * @param string $license_not_activate_message license_not_activate_message.
		 */
		function license_not_active_message( $not_activate, $license_status_class, $license_not_activate_message ) {
			$not_activate = '<span class="license-error-heading ' . $license_status_class . ' ' . $license_not_activate_message . '">UPDATES UNAVAILABLE! Please enter your license key below to enable automatic updates.</span>';

			return $not_activate;
		}

		/**
		 * Function Name: license_form_heading.
		 * Function Description: license_form_heading.
		 *
		 * @param string $form_heading form_heading.
		 * @param string $license_status_class license_status_class.
		 * @param string $license_status license_status.
		 */
		function license_form_heading( $form_heading, $license_status_class, $license_status ) {

			if ( 'bsf-license-not-active-convertpro' == $license_status_class ) {
				if ( empty( $branding_name ) && empty( $branding_short_name ) ) {

					$license_key_url = 'https://store.brainstormforce.com/licenses/?utm_source=wp-dashboard&utm_medium=license-screen&utm_campaign=get-license';

					$license_string = '<a rel="noopener" href="' . esc_url( $license_key_url ) . '" target="_blank">license key</a>';
				} else {
					$license_string = 'license key';
				}
				$form_heading = $form_heading . '<p>Enter your ' . $license_string . ' to enable remote updates and support.</p>';
			}

			return $form_heading;
		}
	}
}

if ( class_exists( 'BSF_Updater' ) ) {
	new BSF_Updater();
}
