<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Main class
 *
 * @class   YITH_WC_Quick_Checkout_Digital_Goods
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */

if ( ! class_exists( 'YITH_WC_Quick_Checkout_Digital_Goods' ) ) {

	class YITH_WC_Quick_Checkout_Digital_Goods {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WC_Quick_Checkout_Digital_Goods
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Panel object
		 *
		 * @var     /Yit_Plugin_Panel object
		 * @since   1.0.0
		 * @see     plugin-fw/lib/yit-plugin-panel.php
		 */
		protected $_panel = null;

		/**
		 * @var string Premium version landing link
		 */
		protected $_premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-quick-checkout-for-digital-goods/';

		/**
		 * @var string Plugin official documentation
		 */
		protected $_official_documentation = 'http://yithemes.com/docs-plugins/yith-woocommerce-quick-checkout-for-digital-goods/';

		/**
		 * @var string YITH WooCommerce Quick Checkout for Digital Goods panel page
		 */
		protected $_panel_page = 'yith-wc-quick-checkout-for-digital-goods';

		/**
		 * @var bool Check if WooCommerce version is lower than 2.6
		 */
		public $is_wc_lower_2_6;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WC_Quick_Checkout_Digital_Goods
		 * @since 1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self;

			}

			return self::$instance;

		}

		/**
		 * Constructor
		 *
		 * @since   1.0.0
		 * @return  mixed
		 * @author  Alberto Ruggiero
		 */
		public function __construct() {

			if ( ! function_exists( 'WC' ) ) {
				return;
			}

			//Load plugin framework
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 12 );
			add_filter( 'plugin_action_links_' . plugin_basename( YWQCDG_DIR . '/' . basename( YWQCDG_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );

			// register plugin to licence/update system
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 5 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );


			if ( 'yes' == get_option( 'ywqcdg_enable_plugin' ) ) {

				$this->includes();

				if ( is_admin() ) {

					add_filter( 'product_type_options', array( $this, 'add_product_option' ) );
					add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_option' ) );
					add_action( 'product_cat_edit_form_fields', array( $this, 'write_taxonomy_options' ), 99 );
					add_action( 'product_tag_edit_form_fields', array( $this, 'write_taxonomy_options' ), 99 );
					add_action( 'edited_product_cat', array( $this, 'save_taxonomy_options' ) );
					add_action( 'edited_product_tag', array( $this, 'save_taxonomy_options' ) );
					add_action( 'woocommerce_variation_options', array( $this, 'add_variation_option' ), 10, 3 );
					add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation_option' ), 10, 2 );
					add_action( 'woocommerce_admin_settings_sanitize_option_ywqcdg_fields_to_show', array( $this, 'sanitize_empty_array' ) );

				} else {

					add_action( 'init', array( $this, 'user_can_download' ), 9, 1 );
					add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
					add_action( 'woocommerce_checkout_init', array( $this, 'checkout_init' ) );
					add_action( 'woocommerce_after_available_downloads', array( $this, 'notify_incomplete_profile' ) );
					add_action( 'woocommerce_after_edit_address_form_billing', array( $this, 'hidden_billing_country' ) );
					add_action( 'woocommerce_single_product_summary', array( $this, 'quick_checkout_product_page' ), 35 );
					add_filter( 'woocommerce_checkout_fields', array( $this, 'override_checkout_fields' ) );
					add_filter( 'woocommerce_enable_order_notes_field', array( $this, 'hide_notes_field' ) );
					add_filter( 'woocommerce_customer_get_downloadable_products', array( $this, 'alter_download_links' ) );
					add_filter( 'woocommerce_address_to_edit', array( $this, 'can_edit_billing_country' ) );
					add_filter( 'woocommerce_process_myaccount_field_billing_country', array( $this, 'avoid_update_billing_country' ) );
					add_action( 'template_redirect', array( $this, 'add_to_cart' ), 20 );

				}

			}

		}

		/**
		 * Files inclusion
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		private function includes() {

			include_once( 'includes/class-ywqcdg-shortcode.php' );

			if ( is_admin() ) {

				include_once( 'includes/class-yith-custom-table.php' );
				include_once( 'includes/functions-ywqcdg-json.php' );
				include_once( 'templates/admin/class-ywqcdg-custom-select.php' );
				include_once( 'templates/admin/active-checkout-table.php' );

			}

		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 * @use     /Yit_Plugin_Panel class
		 * @see     plugin-fw/lib/yit-plugin-panel.php
		 */
		public function add_menu_page() {

			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$admin_tabs = array(
				'general'         => __( 'General Settings', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
				'active-checkout' => __( 'Quick Checkout', 'yith-woocommerce-quick-checkout-for-digital-goods' )
			);

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => _x( 'Quick Checkout for Digital Goods', 'plugin name in admin page title', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
				'menu_title'       => _x( 'Quick Checkout for Digital Goods', 'plugin name in admin WP menu', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->_panel_page,
				'admin-tabs'       => $admin_tabs,
				'options-path'     => YWQCDG_DIR . 'plugin-options'
			);

			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );

		}

		/**
		 * Initializes CSS and javascript
		 *
		 * @since   1.0.3
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function admin_scripts() {

			global $pagenow;

			if ( ( 'post.php' != $pagenow && 'post-new.php' != $pagenow ) || ( isset( $_GET['page'] ) && $this->_panel_page != $_GET['page'] ) ) {
				return;
			}

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'ywqcdg-admin', YWQCDG_ASSETS_URL . '/js/ywqcdg-admin' . $suffix . '.js', array( 'jquery' ), YWQCDG_VERSION );

		}

		/**
		 * Initializes CSS and javascript
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function frontend_scripts() {

			if ( is_checkout() ) {
				return;
			}

			$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$assets_path = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';

			wp_enqueue_script( 'select2', $assets_path . 'js/select2/select2' . $suffix . '.js', array( 'jquery' ), '3.5.4', true );
			wp_enqueue_script( 'wc-checkout', $assets_path . 'js/frontend/checkout' . $suffix . '.js', array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ), WC_VERSION, true );
			wp_enqueue_script( 'ywqcdg-frontend', YWQCDG_ASSETS_URL . '/js/ywqcdg-frontend' . $suffix . '.js', array( 'jquery' ), YWQCDG_VERSION );

			wp_enqueue_style( 'select2', $assets_path . 'css/select2.css', array(), WC_VERSION );

			wp_localize_script( 'wc-checkout', 'wc_checkout_params', array(
				'ajax_url'                  => WC()->ajax_url(),
				'wc_ajax_url'               => WC_AJAX::get_endpoint( "%%endpoint%%" ),
				'update_order_review_nonce' => wp_create_nonce( 'update-order-review' ),
				'apply_coupon_nonce'        => wp_create_nonce( 'apply-coupon' ),
				'remove_coupon_nonce'       => wp_create_nonce( 'remove-coupon' ),
				'option_guest_checkout'     => 'yes',
				'checkout_url'              => WC_AJAX::get_endpoint( "checkout" ),
				'is_checkout'               => 1,
				'debug_mode'                => defined( 'WP_DEBUG' ) && WP_DEBUG,
				'i18n_checkout_error'       => esc_attr__( 'Error processing checkout. Please try again.', 'woocommerce' ),
			) );

			wp_localize_script( 'ywqcdg-frontend', 'ywqcdg_frontend', array(
				'active_variations' => $this->get_active_quick_checkout_variations()
			) );

		}

		/**
		 * Set checkout fields
		 *
		 * @since   1.0.0
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function get_list_fields() {

			$wc_fields = WC()->countries->get_address_fields( '', 'billing_' );

			$field_list = array();

			foreach ( $wc_fields as $key => $val ) {

				if ( 'billing_email' != $key ) {

					$field_list[ $key ] = isset( $val['label'] ) ? $val['label'] : $val['placeholder'];

				}

			}

			return apply_filters( 'ywqcdg_billing_custom_fields', $field_list );

		}

		/**
		 * Add quick checkout checkbox in each product
		 *
		 * @since   1.0.0
		 *
		 * @param   $options
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function add_product_option( $options ) {

			if ( 'selection' == get_option( 'ywqcdg_active_elements' ) ) {

				$product_type_options = array(
					'ywqcdg_active_checkout' => array(
						'id'            => '_ywqcdg_active_checkout',
						'wrapper_class' => 'show_if_downloadable show_if_virtual',
						'label'         => __( 'Quick Checkout', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
						'description'   => __( 'Enable quick checkout for this product', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
						'default'       => 'no'
					)
				);

				$options = array_merge( $options, $product_type_options );

			}

			return $options;

		}

		/**
		 * Save product quick checkout setting
		 *
		 * @since   1.0.0
		 *
		 * @param   $post_id
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function save_product_option( $post_id ) {

			if ( 'selection' == get_option( 'ywqcdg_active_elements' ) ) {

				$quick_checkout = isset( $_POST['_ywqcdg_active_checkout'] ) ? 'yes' : 'no';
				$product        = wc_get_product( $post_id );

				yit_save_prop( $product, '_ywqcdg_active_checkout', $quick_checkout );

			}

		}

		/**
		 * Add quick checkout checkbox to each product variation
		 *
		 * @since   1.0.0
		 *
		 * @param   $loop
		 * @param   $variation_data
		 * @param   $variation
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function add_variation_option( $loop, $variation_data, $variation ) {

			if ( 'selection' == get_option( 'ywqcdg_active_elements' ) ) {

				$quick_checkout = yit_get_prop( $variation, '_ywqcdg_active_checkout', true );

				?>

				<label class="ywqcdg_active_checkout"><input type="checkbox" class="checkbox" name="_ywqcdg_active_checkout[<?php echo $loop; ?>]" <?php checked( isset( $quick_checkout ) ? $quick_checkout : '', 'yes' ); ?> /> <?php _e( 'Quick Checkout', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?> <?php echo wc_help_tip( __( 'Enable quick checkout for this variation' ) ); ?>
				</label>

				<?php

			}

		}

		/**
		 * Save quick checkout setting of each product variations
		 *
		 * @since   1.0.0
		 *
		 * @param   $variation_id
		 * @param   $loop
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function save_variation_option( $variation_id, $loop ) {

			if ( 'selection' == get_option( 'ywqcdg_active_elements' ) ) {

				$quick_checkout = ( isset( $_POST['_ywqcdg_active_checkout'][ $loop ] ) ? 'yes' : 'no' );
				$product        = wc_get_product( $variation_id );

				yit_save_prop( $product, '_ywqcdg_active_checkout', $quick_checkout );

			}

		}

		/**
		 * Add quick checkout checkbox in category/tag edit page
		 *
		 * @since   1.0.0
		 *
		 * @param   $taxonomy
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function write_taxonomy_options( $taxonomy ) {

			if ( 'selection' == get_option( 'ywqcdg_active_elements' ) ) {

				$taxonomy_type = ( 'product_cat' == $taxonomy->taxonomy ) ? __( 'category', 'yith-woocommerce-quick-checkout-for-digital-goods' ) : __( 'tag', 'yith-woocommerce-quick-checkout-for-digital-goods' );

				if ( $this->is_wc_lower_2_6 ) {

					$quick_checkout = ( 'yes' == get_woocommerce_term_meta( $taxonomy->term_id, '_ywqcdg_active_checkout', true ) ) ? 'checked' : '';

				} else {

					$quick_checkout = ( 'yes' == get_term_meta( $taxonomy->term_id, '_ywqcdg_active_checkout', true ) ) ? 'checked' : '';

				}

				?>

				<tr class="form-field">
					<th>
						<label for="_ywqcdg_active_checkout"><?php _e( 'Quick Checkout', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?></label>
					</th>
					<td>
						<input id="_ywqcdg_active_checkout" name="_ywqcdg_active_checkout" type="checkbox" <?php echo $quick_checkout; ?> />

						<p class="description"><?php echo sprintf( __( 'Enable Quick Checkout for downloadable/virtual products of this %s', 'yith-woocommerce-quick-checkout-for-digital-goods' ), $taxonomy_type ); ?></p>
					</td>
				</tr>

				<?php

			}

		}

		/**
		 * Save quick checkout category/tag option
		 *
		 * @since   1.0.0
		 *
		 * @param   $taxonomy_id
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function save_taxonomy_options( $taxonomy_id ) {

			if ( ! $taxonomy_id ) {
				return;
			}

			if ( 'selection' == get_option( 'ywqcdg_active_elements' ) ) {

				$quick_checkout = isset( $_POST['_ywqcdg_active_checkout'] ) ? 'yes' : 'no';

				if ( $this->is_wc_lower_2_6 ) {

					update_woocommerce_term_meta( $taxonomy_id, '_ywqcdg_active_checkout', $quick_checkout );

				} else {

					update_term_meta( $taxonomy_id, '_ywqcdg_active_checkout', $quick_checkout );

				}

			}

		}

		/**
		 * Set checkout fields
		 *
		 * @since   1.0.0
		 *
		 * @param   $fields
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function override_checkout_fields( $fields ) {

			$user = wp_get_current_user();

			if ( ! $user->exists() ) {

				if ( ! $this->cart_has_physical_goods() ) {

					$option_value  = get_option( 'ywqcdg_fields_to_show' );
					$active_fields = ( $option_value ) ? $option_value : array();

					foreach ( $fields['billing'] as $key => $val ) {

						if ( ! in_array( $key, $active_fields ) && 'billing_email' != $key ) {

							unset( $fields['billing'][ $key ] );

						}

					}

					if ( empty( $active_fields ) ) {

						$fields['billing']['billing_email']['class'] = array( 'form-row-full' );

					}

				}

			}

			return $fields;

		}

		/**
		 * Hide order notes fields
		 *
		 * @since   1.0.0
		 *
		 * @param   $value
		 *
		 * @return  boolean
		 * @author  Alberto Ruggiero
		 */
		public function hide_notes_field( $value ) {

			$user = wp_get_current_user();

			if ( ! $user->exists() ) {

				if ( ! $this->cart_has_physical_goods() && 'yes' == get_option( 'ywqcdg_hide_order_notes' ) ) {

					$value = false;

				}

			}

			return $value;

		}

		/**
		 * Initialize checkout
		 * @since   1.0.0
		 *
		 * @param   $checkout
		 *
		 * @return  WC_Checkout
		 * @author  Alberto Ruggiero
		 */
		public function checkout_init( WC_Checkout $checkout ) {

			if ( ! $this->cart_has_physical_goods() ) {

				//WC2.6
				$checkout->enable_guest_checkout = false;
				$checkout->must_create_account   = is_user_logged_in() ? false : true;

				//WC3.0
				add_filter( 'woocommerce_checkout_registration_required', ( is_user_logged_in() ? '__return_false' : '__return_true' ) );

			}

			return $checkout;

		}

		/**
		 * Check if cart has physical goods
		 *
		 * @since   1.0.0
		 * @return  bool
		 * @author  Alberto Ruggiero
		 */
		public function cart_has_physical_goods() {

			if ( ! empty( WC()->cart->cart_contents ) ) {

				$cart = WC()->cart->get_cart();

				foreach ( $cart as $item ) {

					$product = wc_get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );

					if ( ! empty( $product ) && $product->exists() && 0 < $item['quantity'] ) {

						if ( ! $this->can_show_quick_checkout( $product ) ) {

							return true;

						}

					}

				}

			}

			return false;

		}

		/**
		 * Check if quick checkout can be shown
		 *
		 * @since   1.0.0
		 *
		 * @param   $product
		 *
		 * @return  bool
		 * @author  Alberto Ruggiero
		 */
		public function can_show_quick_checkout( $product ) {

			if ( 'selection' == get_option( 'ywqcdg_active_elements' ) && ! $this->product_has_quick_checkout( $product ) ) {

				return false;

			} else {

				if ( ! yit_get_prop( $product, 'virtual' ) && ! yit_get_prop( $product, 'downloadable' ) ) {

					return false;

				}

			}

			return true;

		}

		/**
		 * Check if product has quick checkout active
		 *
		 * @since   1.0.0
		 *
		 * @param   $product
		 *
		 * @return  bool
		 * @author  Alberto Ruggiero
		 */
		public function product_has_quick_checkout( WC_Product $product ) {

			if ( ! yit_get_prop( $product, 'virtual' ) && ! yit_get_prop( $product, 'downloadable' ) ) {

				return false;

			}

			if ( 'yes' == yit_get_prop( $product, '_ywqcdg_active_checkout' ) ) {

				return true;

			}

			$product_cats = wp_get_object_terms( yit_get_product_id( $product ), 'product_cat', array( 'fields' => 'ids' ) );
			foreach ( $product_cats as $cat_id ) {

				if ( $this->is_wc_lower_2_6 ) {

					$cat_active_checkout = get_woocommerce_term_meta( $cat_id, '_ywqcdg_active_checkout', true );

				} else {

					$cat_active_checkout = get_term_meta( $cat_id, '_ywqcdg_active_checkout', true );

				}

				if ( 'yes' == $cat_active_checkout ) {

					return true;

				}

			}

			$product_tags = wp_get_object_terms( yit_get_product_id( $product ), 'product_tag', array( 'fields' => 'ids' ) );
			foreach ( $product_tags as $tag_id ) {

				if ( $this->is_wc_lower_2_6 ) {

					$tag_active_checkout = get_woocommerce_term_meta( $tag_id, '_ywqcdg_active_checkout', true );

				} else {

					$tag_active_checkout = get_term_meta( $tag_id, '_ywqcdg_active_checkout', true );

				}

				if ( 'yes' == $tag_active_checkout ) {

					return true;

				}

			}

			return false;

		}

		/**
		 * Check variation with quick checkout active
		 *
		 * @since   1.0.0
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function get_active_quick_checkout_variations() {

			global $post;

			$active_variations = array();

			if ( ! empty( $post ) ) {

				$product = wc_get_product( $post->ID );

				if ( $product && $product->is_type( 'variable' ) ) {

					$variations = array_filter( $product->get_available_variations() );

					if ( count( $variations ) > 0 ) {

						foreach ( $variations as $variation ) {

							$product_variation = wc_get_product( $variation['variation_id'] );

							if ( $this->can_show_quick_checkout( $product_variation ) ) {

								$active_variations[] = $variation['variation_id'];

							}

						}

					}

				}

			}

			return $active_variations;

		}

		/**
		 * Alter links if user cannot download
		 *
		 * @since   1.0.0
		 *
		 * @param   $downloads
		 * @param   $item
		 * @param   $order
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function alter_download_links( $downloads, $item = '', WC_Order $order = null ) {

			if ( $this->is_profile_complete( $order ) || empty( $downloads ) ) {

				return $downloads;

			}

			$new_downloads = array();

			foreach ( $downloads as $key => $download ) {

				$new_downloads[ $key ] = $download;
				$new_downloads[ $key ]['download_url'] .= '&denied=denied';

			}

			return $new_downloads;

		}

		/**
		 * Notify if the user must complete the profile
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function notify_incomplete_profile() {

			if ( ! $this->is_profile_complete() && ( $downloads = WC()->customer->get_downloadable_products() ) ) {

				?>

				<div class="woocommerce-info">

					<?php echo sprintf( __( 'Enter your %1$s billing details %2$s to be able to download purchased products. %1$s Edit %2$s ', 'yith-woocommerce-quick-checkout-for-digital-goods' ), '<a href="' . wc_get_endpoint_url( 'edit-address', 'billing' ) . '" class="edit">', '</a>' ); ?>

				</div>

				<?php

			}

		}

		/**
		 * Check if user profile is complete
		 *
		 * @since   1.0.0
		 *
		 * @param   $order
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function is_profile_complete( WC_Order $order = null ) {

			if ( $order ) {

				$user = $order->get_user();

			} else {

				$user = wp_get_current_user();

			}

			if ( $user->exists() ) {

				$address_fields = WC()->countries->get_address_fields( $user->billing_country );

				foreach ( $address_fields as $key => $field ) {

					if ( isset( $field['required'] ) && $field['required'] && empty( $user->$key ) ) {

						return false;

					}

				}

			}

			return true;

		}

		/**
		 * Check if user can download
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function user_can_download() {

			if ( isset( $_GET['download_file'] ) && isset( $_GET['order'] ) && isset( $_GET['email'] ) && isset( $_GET['denied'] ) ) {

				wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
				wc_add_notice( __( 'Enter you billing details to be able to download your products.', 'yith-woocommerce-quick-checkout-for-digital-goods' ), 'error' );

			}

		}

		/**
		 * Check if user billing country is required
		 *
		 * @since   1.0.0
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function required_billing_country() {

			$option_value  = get_option( 'ywqcdg_fields_to_show' );
			$active_fields = ( $option_value ) ? $option_value : array();

			return ! $this->is_profile_complete() && in_array( 'billing_country', $active_fields );

		}

		/**
		 * Disable billing country field, if required
		 *
		 * @since   1.0.0
		 *
		 * @param   $address
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function can_edit_billing_country( $address ) {

			if ( $this->required_billing_country() ) {

				if ( isset( $address['billing_country'] ) ) {

					$address['billing_country']['custom_attributes']['disabled'] = 'disabled';

				}

			}

			return $address;

		}

		/**
		 * Check if user billing country can be edited
		 *
		 * @since   1.0.0
		 *
		 * @param   $value
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function avoid_update_billing_country( $value ) {

			if ( $this->required_billing_country() ) {

				$value = get_user_meta( get_current_user_id(), 'billing_country', true );

			}

			return $value;

		}

		/**
		 * Add hidden billing country field, if required
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function hidden_billing_country() {

			if ( $this->required_billing_country() ) { ?>

				<input type="hidden" name="billing_country" value="<?php echo get_user_meta( get_current_user_id(), 'billing_country', true ) ?>" />

			<?php }

		}

		/**
		 * Add quick checkout to product page
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function quick_checkout_product_page() {

			global $product;

			if ( 'yes' == get_option( 'ywqcdg_product_page' ) ) {

				if ( ! $product->is_type( 'variation' ) ) {

					if ( ! $this->can_show_quick_checkout( $product ) ) {

						return;

					}

				}

				$this->get_quick_checkout();

			}

		}

		/**
		 * Get quick checkout for shortcode or product page
		 *
		 * @since   1.0.0
		 *
		 * @param   $product_id
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function get_quick_checkout( $product_id = null ) {

			if ( is_null( WC()->cart ) ) {
				return;
			}

			?>
			<div class="woocommerce ywqcdg-wrapper">
				<?php

				// Show non-cart errors
				wc_print_notices();

				// Get checkout object
				$checkout = WC()->checkout();

				if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout ) {
					return;
				}

				// Check cart has contents
				if ( ! WC()->cart->is_empty() ) {

					// Check cart contents for errors
					do_action( 'woocommerce_check_cart_items' );

					// Calc totals
					WC()->cart->calculate_totals();

					if ( empty( $_POST ) && wc_notice_count( 'error' ) > 0 ) {

						wc_get_template( 'checkout/cart-errors.php', array( 'checkout' => $checkout ) );

					} else {

						$non_js_checkout = ! empty( $_POST['woocommerce_checkout_update_totals'] ) ? true : false;

						if ( wc_notice_count( 'error' ) == 0 && $non_js_checkout ) {
							wc_add_notice( __( 'Order totals have been updated. Please, confirm your order by pressing "Place Order" button at the bottom of the page.', 'woocommerce' ) );
						}

						wc_print_notices();

						do_action( 'woocommerce_before_checkout_form', $checkout );

						?>

						<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

							<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

								<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

								<div class="<?php echo apply_filters( 'ywqcdg_checkout_class', '' ) ?>" id="customer_details">
									<div class="col-1">
										<?php do_action( 'woocommerce_checkout_billing' ); ?>
									</div>

									<div class="col-2">
										<?php do_action( 'woocommerce_checkout_shipping' ); ?>
									</div>
								</div>

								<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

							<?php endif; ?>

							<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

							<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

							<div id="order_review" class="woocommerce-checkout-review-order">

								<?php do_action( 'woocommerce_checkout_order_review' ); ?>

							</div>

							<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

						</form>

						<?php do_action( 'woocommerce_after_checkout_form', $checkout );

					}

				}

				?>
			</div>
			<?php

		}

		/**
		 * If option is enabled add the product to cart
		 *
		 * @since   1.0.3
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function add_to_cart() {

			if ( ! is_product() ) {
				return;
			}

			//global $product;

			$qc_on_page = get_option( 'ywqcdg_product_page' );
			$auto_atc   = get_option( 'ywqcdg_product_page_atc' );
			if ( 'yes' == $qc_on_page && 'yes' == $auto_atc ) {
				global $post;

				if ( ! $post ) {
					return;
				}

				$product = wc_get_product( $post->ID );

				if ( ! $product ) {
					return;
				}

				if ( ! $this->can_show_quick_checkout( $product ) ) {
					return;
				}

				$in_cart    = false;
				$cart_items = WC()->cart->get_cart();

				foreach ( $cart_items as $item ) {

					if ( $post->ID == $item['product_id'] ) {

						$in_cart = true;
						break;

					}

				}

				if ( ! $in_cart ) {

					WC()->cart->add_to_cart( $post->ID );

				}

			}

		}

		/**
		 * Sanitize empty array
		 *
		 * @since   1.0.7
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function sanitize_empty_array( $option ) {
			return ( empty( $option ) ? array() : $option );
		}

		/**
		 * YITH FRAMEWORK
		 */

		/**
		 * Load plugin framework
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Andrea Grillo
		 * <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {

			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {

				global $plugin_fw_data;

				if ( ! empty( $plugin_fw_data ) ) {

					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once( $plugin_fw_file );

				}

			}

		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @return  string The premium landing link
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function get_premium_landing_uri() {

			return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing;

		}

		/**
		 * Action Links
		 *
		 * add the action links to plugin admin page
		 * @since   1.0.0
		 *
		 * @param   $links | links plugin array
		 *
		 * @return  mixed
		 * @author  Andrea Grillo  <andrea.grillo@yithemes.com>
		 * @use     plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {

			$links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'yith-woocommerce-quick-checkout-for-digital-goods' ) . '</a>';

			return $links;

		}

		/**
		 * Plugin row meta
		 *
		 * add the action links to plugin admin page
		 *
		 * @since   1.0.0
		 *
		 * @param   $plugin_meta
		 * @param   $plugin_file
		 * @param   $plugin_data
		 * @param   $status
		 *
		 * @return  array
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use     plugin_row_meta
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

			if ( ( defined( 'YWQCDG_INIT' ) && ( YWQCDG_INIT == $plugin_file ) ) ) {

				$plugin_meta[] = '<a href="' . $this->_official_documentation . '" target="_blank">' . __( 'Plugin documentation', 'yith-woocommerce-quick-checkout-for-digital-goods' ) . '</a>';

			}

			return $plugin_meta;

		}

		/**
		 * Register plugins for activation tab
		 *
		 * @since   2.0.0
		 * @return  void
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once 'plugin-fw/licence/lib/yit-licence.php';
				require_once 'plugin-fw/licence/lib/yit-plugin-licence.php';
			}
			YIT_Plugin_Licence()->register( YWQCDG_INIT, YWQCDG_SECRET_KEY, YWQCDG_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @since   2.0.0
		 * @return  void
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function register_plugin_for_updates() {
			if ( ! class_exists( 'YIT_Upgrade' ) ) {
				require_once( 'plugin-fw/lib/yit-upgrade.php' );
			}
			YIT_Upgrade()->register( YWQCDG_SLUG, YWQCDG_INIT );
		}

	}

}

add_filter( 'woocommerce_get_item_downloads', array( YITH_WQCDG(), 'alter_download_links' ), 10, 3 );
