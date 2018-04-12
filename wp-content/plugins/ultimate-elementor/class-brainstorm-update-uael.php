<?php
/**
 * Brainstorm_Update_UAEL initial setup
 *
 * @package UAEL
 * @since 1.0.0
 */

// Ignore the PHPCS warning about constant declaration.
// @codingStandardsIgnoreStart
define( 'BSF_REMOVE_uael_FROM_REGISTRATION_LISTING', true );
// @codingStandardsIgnoreEnd

if ( ! class_exists( 'Brainstorm_Update_UAEL' ) ) :

	/**
	 * Brainstorm Update
	 */
	class Brainstorm_Update_UAEL {

		/**
		 * Instance
		 *
		 * @var object Class object.
		 * @access private
		 */
		private static $instance;

		/**
		 * Initiator
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

			self::version_check();

			add_filter( 'bsf_get_license_message_uael', array( $this, 'license_message_uael' ), 10, 2 );
			add_filter( 'bsf_skip_braisntorm_menu', array( $this, 'skip_menu' ) );
			add_filter( 'bsf_skip_author_registration', array( $this, 'skip_menu' ) );

			// Register Licence Link.
			add_filter( 'bsf_registration_page_url_uael', array( $this, 'get_registration_page_url' ) );

			// Add popup license form on plugin list page.
			add_filter( 'plugin_action_links_' . UAEL_BASE, array( $this, 'plugin_slug_license_form_and_links' ) );
			add_filter( 'network_admin_plugin_action_links_' . UAEL_BASE, array( $this, 'plugin_slug_license_form_and_links' ) );
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @param   mixed $links Plugin Action links.
		 * @return  array        Filtered plugin action links.
		 */
		function plugin_slug_license_form_and_links( $links = array() ) {

			if ( function_exists( 'get_bsf_inline_license_form' ) ) {
				$args = array(
					'product_id'              => 'uael',
					'popup_license_form'      => true,
					'bsf_license_allow_email' => false,
				);
				return get_bsf_inline_license_form( $links, $args, 'edd' );
			}

			return $links;
		}

		/**
		 * Get registration page url for addon.
		 *
		 * @since  1.0.0
		 * @return String URL of the licnense registration page.
		 */
		public function get_registration_page_url() {
			$url = admin_url( 'plugins.php?bsf-inline-license-form=uael' );

			return $url;
		}

		/**
		 * Skip Menu.
		 *
		 * @param array $products products.
		 * @return array $products updated products.
		 */
		function skip_menu( $products ) {
			$products[] = 'uael';

			return $products;
		}

		/**
		 * Update brainstorm product version and product path.
		 *
		 * @return void
		 */
		public static function version_check() {

			$bsf_core_version_file = realpath( dirname( __FILE__ ) . '/admin/bsf-core/version.yml' );

			// Is file 'version.yml' exist?
			if ( is_file( $bsf_core_version_file ) ) {
				global $bsf_core_version, $bsf_core_path;
				$bsf_core_dir = realpath( dirname( __FILE__ ) . '/admin/bsf-core/' );
				$version      = file_get_contents( $bsf_core_version_file );

				// Compare versions.
				if ( version_compare( $version, $bsf_core_version, '>' ) ) {
					$bsf_core_version = $version;
					$bsf_core_path    = $bsf_core_dir;
				}
			}
		}

		/**
		 * Add Message for license.
		 *
		 * @param  string $content       get the link content.
		 * @param  string $purchase_url  purchase_url.
		 * @return string                output message.
		 */
		function license_message_uael( $content, $purchase_url ) {

			$purchase_url = apply_filters( 'uael_licence_url', $purchase_url );

			$message = "<p><a target='_blank' href='" . esc_url( $purchase_url ) . "'>" . esc_html__( 'Get the license >>', 'uael' ) . '</a></p>';
			return $message;
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Brainstorm_Update_UAEL::get_instance();

endif;
