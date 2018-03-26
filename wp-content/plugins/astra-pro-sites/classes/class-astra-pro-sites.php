<?php
/**
 * Astra Pro Sites
 *
 * @since 1.0.0
 * @package Astra Pro Sites
 */

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'Astra_Pro_Sites' ) ) :

	/**
	 * Astra Pro Sites
	 *
	 * @since 1.0.0
	 */
	class Astra_Pro_Sites {

		/**
		 * Instance of Astra_Pro_Sites
		 *
		 * @since 1.0.0
		 * @var object class object.
		 */
		private static $instance = null;

		/**
		 * Instance of Astra_Pro_Sites.
		 *
		 * @since 1.0.0
		 *
		 * @return object Class object.
		 */
		public static function set_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {

			self::includes();

			add_action( 'admin_notices', array( $this, 'admin_notices' ), 1 );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'admin_head', array( $this, 'license_form' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
			add_filter( 'astra_sites_localize_vars', array( $this, 'update_vars' ) );
			add_filter( 'astra_sites_api_params', array( $this, 'api_request_params' ) );
			add_filter( 'astra_sites_menu_page_title', array( $this, 'page_title' ) );

		}

		/**
		 * Include Files.
		 *
		 * @since 1.0.7
		 */
		private static function includes() {
			require_once ASTRA_PRO_SITES_DIR . 'classes/class-astra-pro-sites-update.php';
			require_once ASTRA_PRO_SITES_DIR . 'classes/class-astra-pro-sites-white-label.php';
		}

		/**
		 * API Request Params
		 *
		 * @since 1.0.5
		 *
		 * @param  array $args API request arguments.
		 * @return arrray       Filtered API request params.
		 */
		function api_request_params( $args = array() ) {

			$args['site_url']     = site_url();
			$args['purchase_key'] = self::get_license_key();

			return $args;
		}

		/**
		 * Get Astra Addon's License Key.
		 */
		public static function get_license_key() {

			if ( class_exists( 'BSF_License_Manager' ) ) {
				if ( BSF_License_Manager::bsf_is_active_license( 'astra-pro-sites' ) ) {
					return BSF_License_Manager::instance()->bsf_get_product_info( 'astra-pro-sites', 'purchase_key' );
				}
			}

			return '';
		}

		/**
		 * Page Title
		 *
		 * @since 1.0.0
		 *
		 * @param  string $title Page Title.
		 * @return string        Filtered Page Title.
		 */
		function page_title( $title = '' ) {
			return Astra_Pro_Sites_White_Label::get_option( 'astra-sites', 'name', ASTRA_SITES_NAME );
		}

		/**
		 * Update Vars
		 *
		 * @since 1.0.0
		 *
		 * @param  array $vars Localize variables.
		 * @return array        Filtered localize variables.
		 */
		function update_vars( $vars = array() ) {

			$vars['getProText'] = __( 'Validate License!', 'astra-sites' );
			$vars['getProURL']  = admin_url( 'plugins.php?astra-pro-sites-license-form' );

			return $vars;
		}

		/**
		 * Load Scripts
		 *
		 * @since 1.0.0
		 *
		 * @param  string $hook Current Hook.
		 * @return void
		 */
		function load_scripts( $hook = '' ) {

			if ( 'plugins.php' === $hook ) {
				wp_enqueue_style( 'astra-pro-sites-license-form', ASTRA_PRO_SITES_URI . 'admin/assets/css/license-form-popup.css', array(), ASTRA_PRO_SITES_VER, 'all' );
				wp_enqueue_script( 'astra-pro-sites-license-form', ASTRA_PRO_SITES_URI . 'admin/assets/js/license-form-popup.js', array( 'jquery' ), ASTRA_PRO_SITES_VER, true );
			}

		}

		/**
		 * License Form
		 *
		 * @since 1.0.0
		 *
		 * @return null If invalid screen ID.
		 */
		function license_form() {

			if ( ! isset( get_current_screen()->id ) ) {
				return;
			}

			if ( 'plugins' != get_current_screen()->id ) {
				return;
			}

			require_once ASTRA_PRO_SITES_DIR . 'includes/license-form.php';

			if ( isset( $_GET['astra-pro-sites-license-form'] ) ) {
				?>
				<script type="text/javascript">
					jQuery( document ).ready( function() {
						setTimeout(function() {

							// Show Popup.
							tb_show( null, '<?php echo admin_url( 'plugins.php?TB_inline&width=714&inlineId=astra-pro-sites-license-form&height=618' ); ?>',null);

							jQuery( 'body' ).addClass('astra-license-form-open');

							setTimeout(function() {
								jQuery( '#TB_window' )
									.addClass('astra-license-form')
									.removeClass('thickbox-loading')
									.find('.inner').removeAttr('style');
							}, 100);
						}, 800);
					});
				</script>
				<?php
			}

		}

		/**
		 * Loads textdomain for the plugin.
		 *
		 * @since 1.0.0
		 */
		function load_textdomain() {
			load_plugin_textdomain( 'astra-sites' );
		}

		/**
		 * Admin Notices
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function admin_notices() {

			Astra_Sites_Notices::add_notice(
				array(
					'type'    => 'error',
					'show_if' => ( is_plugin_active( 'astra-sites/astra-sites.php' ) ) ? true : false,
					/* translators: %1$s white label plugin name and %2$s deactivation link */
					'message' => sprintf( __( 'You have two versions of the %1$s activated, click here to <a href="%2$s">deactivate one</a>.', 'astra-sites' ), Astra_Pro_Sites_White_Label::get_option( 'astra-sites', 'name', ASTRA_SITES_NAME ), esc_url( $this->deactivation_link() ) ),
				)
			);

			if ( ! defined( 'ASTRA_THEME_SETTINGS' ) ) {
				return;
			}

			add_action( 'plugin_action_links_' . ASTRA_PRO_SITES_BASE, array( $this, 'action_links' ) );
			add_action( 'plugin_action_links_' . ASTRA_PRO_SITES_BASE, array( $this, 'license_link' ) );
		}

		/**
		 * Plugin Deactivation Link
		 *
		 * @since 1.0.0
		 *
		 * @param  string $slug Plugin Slug.
		 * @return string       Plugin Deactivation Link.
		 */
		private function deactivation_link( $slug = 'astra-sites' ) {

			$deactivate_url = admin_url( 'plugins.php' );
			if ( is_plugin_active_for_network( ASTRA_SITES_BASE ) ) {
				$deactivate_url = network_admin_url( 'plugins.php' );
			}
			return add_query_arg(
				array(
					'action'        => 'deactivate',
					'plugin'        => rawurlencode( $slug . '/' . $slug . '.php' ),
					'plugin_status' => 'all',
					'paged'         => '1',
					'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $slug . '/' . $slug . '.php' ),
				), $deactivate_url
			);
		}
		/**
		 * Show action links on the plugin screen.
		 *
		 * @since 1.0.0
		 *
		 * @param   mixed $links Plugin Action links.
		 * @return  array        Filtered plugin action links.
		 */
		function action_links( $links = array() ) {
			$action_links = array(
				'settings' => '<a href="' . admin_url( 'themes.php?page=astra-sites' ) . '" aria-label="' . esc_attr__( 'See Library', 'astra-sites' ) . '">' . esc_html__( 'See Library', 'astra-sites' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @since 1.0.0
		 *
		 * @param   mixed $links Plugin Action links.
		 * @return  array        Filtered plugin action links.
		 */
		function license_link( $links = array() ) {

			$status         = 'inactive';
			$license_string = __( 'Activate License', 'astra-sites' );
			if ( function_exists( 'bsf_extract_product_id' ) ) {
				$product_id = bsf_extract_product_id( ASTRA_PRO_SITES_DIR );

				if ( class_exists( 'BSF_License_Manager' ) ) {
					if ( BSF_License_Manager::bsf_is_active_license( $product_id ) ) {
						$status         = 'active';
						$license_string = __( 'License', 'astra-sites' );
					}
				}
			}

			$action_links = array(
				'license' => '<a class="thickbox ' . esc_attr( $status ) . '" id="astra-pro-sites-license-form-btn" href="#TB_inline?width=400&height=200&inlineId=astra-pro-sites-license-form" aria-label="' . esc_attr( $license_string ) . '">' . esc_html( $license_string ) . '</a>',
			);
			return array_merge( $links, $action_links );
		}

	}

	/**
	 * Kicking this off by calling 'set_instance()' method
	 */
	Astra_Pro_Sites::set_instance();

endif;
