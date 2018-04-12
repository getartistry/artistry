<?php
/**
 * Brainstorm_Update_Astra_Pro_Sites
 *
 * @package Astra
 * @since 1.0.0
 */

// Ignore the PHPCS warning about constant declaration.
// @codingStandardsIgnoreStart
define( 'BSF_REMOVE_astra-pro-sites_FROM_REGISTRATION_LISTING', true );
// @codingStandardsIgnoreEnd

if ( ! class_exists( 'Brainstorm_Update_Astra_Pro_Sites' ) ) :

	/**
	 * Brainstorm Update
	 */
	class Brainstorm_Update_Astra_Pro_Sites {

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

			// Load only the latets graupi.
			$this->version_check();

			add_action( 'init', array( $this, 'load' ), 999 );
			add_filter( 'bsf_skip_braisntorm_menu', array( $this, 'skip_menu' ) );
			add_filter( 'bsf_skip_author_registration', array( $this, 'skip_menu' ) );
			add_filter( 'bsf_is_product_bundled', array( $this, 'remove_astra_pro_bundled_products' ), 20, 3 );
			add_action( 'bsf_get_plugin_information', array( $this, 'plugin_information' ) );
			add_filter( 'bsf_license_form_heading_astra-pro-sites', array( $this, 'license_form_titles' ), 10, 3 );
			add_filter( 'bsf_registration_page_url_astra-pro-sites', array( $this, 'license_form_link' ) );
			add_filter( 'bsf_product_activation_notice_astra-pro-sites', array( $this, 'activation_notice' ), 10, 3 );

			add_filter( 'bsf_get_license_message_astra-pro-sites', array( $this, 'license_notice' ), 10 );

		}

		/**
		 * License Notice
		 *
		 * @since 1.0.0
		 *
		 * @param  string $purchase_nag Product Purchase nag.
		 * @return string               Purchase nag.
		 */
		function license_notice( $purchase_nag ) {

			$purchase_url = Astra_Pro_Sites_White_Label::get_option( 'astra-agency', 'licence' );

			// Not have a white label then return nag as it is.
			if ( empty( $purchase_url ) ) {
				return $purchase_nag;
			}

			/* translators: %1$s product purchase link */
			return sprintf( __( '<p>If you don\'t have a license, you can <a target="_blank" href="%1$s">get it here »</a></p>', 'astra-sites' ), esc_url( $purchase_url ) );
		}

		/**
		 * Product Activation Link
		 *
		 * @since 1.0.0
		 *
		 * @param  string $message      Activation notice message.
		 * @param  string $url          Product activation link.
		 * @param  string $product_name Product Name.
		 * @return mixed               Activation notice.
		 */
		function activation_notice( $message = '', $url = '', $product_name = '' ) {

			$product_name = Astra_Pro_Sites_White_Label::get_option( 'astra-sites', 'name', ASTRA_PRO_SITES_NAME );

			/* translators: %1$s product activation link %2$s white label plugin name */
			return sprintf( __( 'Please <a href="%1$s">activate</a> your copy of the <i>%2$s</i> to get update notifications, access to support features & other resources!', 'astra-sites' ), $url, $product_name );
		}

		/**
		 * Update brainstorm product version and product path.
		 *
		 * @return void
		 */
		public function version_check() {

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
		 * Remove bundled products for Astra Pro Sites.
		 * For Astra Pro Sites the bundled products are only used for one click plugin installation when importing the Astra Site.
		 * License Validation and product updates are managed separately for all the products.
		 *
		 * @since 1.0.0
		 *
		 * @param  array  $product_parent  Array of parent product ids.
		 * @param  String $bsf_product    Product ID or  Product init or Product name based on $search_by.
		 * @param  String $search_by      Reference to search by id | init | name of the product.
		 *
		 * @return array                 Array of parent product ids.
		 */
		public function remove_astra_pro_bundled_products( $product_parent, $bsf_product, $search_by ) {

			// Bundled plugins are installed when the demo is imported on Ajax request and bundled products should be unchanged in the ajax.
			if ( ! defined( 'DOING_AJAX' ) ) {

				$key = array_search( 'astra-pro-sites', $product_parent );

				if ( false !== $key ) {
					unset( $product_parent[ $key ] );
				}
			}

			return $product_parent;
		}

		/**
		 * Load the brainstorm updater.
		 *
		 * @return void
		 */
		function load() {
			global $bsf_core_version, $bsf_core_path;
			if ( is_file( realpath( $bsf_core_path . '/index.php' ) ) ) {
				include_once realpath( $bsf_core_path . '/index.php' );
			}
		}

		/**
		 * Install Pluigns Filter
		 *
		 * Add brainstorm bundle products in plugin installer list though filter.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $brainstrom_products   Brainstorm Products.
		 * @return array                        Brainstorm Products merged with Brainstorm Bundle Products.
		 */
		function plugin_information( $brainstrom_products = array() ) {

			$main_products = (array) get_option( 'brainstrom_bundled_products', array() );

			foreach ( $main_products as $single_product_key => $single_product ) {
				foreach ( $single_product as $bundle_product_key => $bundle_product ) {

					if ( is_object( $bundle_product ) ) {
						$type = $bundle_product->type;
						$slug = $bundle_product->slug;
					} else {
						$type = $bundle_product['type'];
						$slug = $bundle_product['slug'];
					}

					// Add bundled plugin in installer list.
					if ( 'plugin' === $type ) {
						$brainstrom_products['plugins'][ $slug ] = (array) $bundle_product;
					}
				}
			}

			return $brainstrom_products;
		}

		/**
		 * License Form Link
		 *
		 * @since 1.0.0
		 *
		 * @param  string $link License form link.
		 * @return string       Popup License form link.
		 */
		function license_form_link( $link = '' ) {
			return admin_url( 'plugins.php?astra-pro-sites-license-form' );
		}


		/**
		 * License Form Text.
		 *
		 * @since 1.0.0
		 *
		 * @param  string $form_heading         Form Heading.
		 * @param  string $license_status_class Form status class.
		 * @param  string $license_status       Form status.
		 * @return mixed                        HTML markup of the license form heading.
		 */
		function license_form_titles( $form_heading = '', $license_status_class = '', $license_status = '' ) {

			if ( 'Active!' === $license_status ) {
				return '<h3>' . __( 'Congratulation!', 'astra-sites' ) . '</h3>';
			}
			if ( 'Not Active!' === $license_status ) {
				/* translators: %1$s white label plugin name */
				return '<h3>' . sprintf( __( 'Activate %1$s license.', 'astra-sites' ), Astra_Pro_Sites_White_Label::get_option( 'astra-sites', 'name', ASTRA_PRO_SITES_NAME ) ) . '</h3>';
			}

		}

		/**
		 * Skip Menu.
		 *
		 * @param array $products products.
		 * @return array $products updated products.
		 */
		function skip_menu( $products ) {

			$products[] = 'uabb';
			$products[] = 'convertpro';
			$products[] = 'astra-addon';
			$products[] = 'astra-pro-sites';
			$products[] = 'astra-sites-showcase';

			return $products;
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Brainstorm_Update_Astra_Pro_Sites::get_instance();

endif;
