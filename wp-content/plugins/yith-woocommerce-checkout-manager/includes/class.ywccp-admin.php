<?php
/**
 * Admin class
 *
 * @author Yithemes
 * @package YITH WooCommerce Checkout Manager
 * @version 1.0.0
 */

if ( ! defined( 'YWCCP' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YWCCP_Admin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YWCCP_Admin {

		/**
		 * Single instance of the class
		 *
		 * @var \YWCCP_Admin
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin options
		 *
		 * @var array
		 * @access public
		 * @since 1.0.0
		 */
		public $options = array();

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YWCCP_VERSION;

		/**
		 * @var $_panel Panel Object
		 */
		protected $_panel;

		/**
		 * @var string Checkout Manager panel page
		 */
		protected $_panel_page = 'ywccp_panel';

		/**
		 * Various links
		 *
		 * @var string
		 * @access public
		 * @since 1.0.0
		 */
		public $doc_url = 'https://yithemes.com/docs-plugins/yith-woocommerce-checkout-manager/';

		/**
		 * Returns single instance of the class
		 *
		 * @return \YWCCP_Admin
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_panel' ), 5) ;

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Add action links
			add_filter( 'plugin_action_links_' . plugin_basename( YWCCP_DIR . '/' . basename( YWCCP_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );

			// Register plugin to licence/update system
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			// custom tab
			add_action( 'ywccp_fields_general_section', array( $this, 'fields_general_section' ), 10, 2 );

			// edit and new fields form
			add_action( 'admin_footer', array( $this, 'print_add_edit_fields_form' ) );

			// save options
			add_action( 'admin_init', array( $this, 'save_options' ) );
			// reset options
			add_action( 'admin_init', array( $this, 'reset_options' ) );

			add_action( 'ywccp_print_admin_fields_section_table', array( $this, 'load_fields_table' ), 10, 1 );

			// filter customer details on edit order section
            add_filter( 'woocommerce_ajax_get_customer_details', array( $this, 'filter_ajax_customer_details'), 10, 3 );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function enqueue_scripts() {

			$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			// admin style
			wp_register_style( 'ywccp-admin-style', YWCCP_ASSETS_URL . '/css/ywccp-admin.css', array(), $this->version, 'all' );
			// admin script
			wp_register_script( 'ywccp-admin-script', YWCCP_ASSETS_URL . '/js/ywccp-admin'.$min.'.js', array( 'jquery', 'jquery-ui-dialog' ), $this->version, true );

			if ( $this->needs_scripts() ) {
				wp_enqueue_style( 'ywccp-admin-style' );
				wp_enqueue_script( 'ywccp-admin-script' );

				wp_localize_script( 'ywccp-admin-script', 'ywccp_admin', array(
					'popup_add_title' => __( 'Add new field', 'yith-woocommerce-checkout-manager' ),
					'popup_edit_title' => __( 'Edit field', 'yith-woocommerce-checkout-manager' ),
					'enabled' => '<span class="status-enabled tips" data-tip="' .  __( 'Yes', 'yith-woocommerce-checkout-manager' ) . '"></span>'
				));
			}
		}

		/**
		 * Check if currently admin section needs plugin scripts
		 *
		 * @since 1.0.5
		 * @author Francesco Licandro
		 * @return boolean
		 */
		protected function needs_scripts(){
			global $post;

			$return = ( isset( $_GET['page'] ) && $_GET['page'] == $this->_panel_page ) ||
			          ( isset( $post->post_type ) && $post->post_type == 'shop_order' );

			return apply_filters( 'ywccp_admin_needs_scripts', $return );
		}

		/**
		 * Action Links
		 *
		 * add the action links to plugin admin page
		 *
		 * @param $links | links plugin array
		 *
		 * @return   mixed Array
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return mixed
		 * @use plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {
			$links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'yith-woocommerce-checkout-manager' ) . '</a>';

			return $links;
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use     /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$admin_tabs = array(
				'general' 	=> __( 'Settings', 'yith-woocommerce-checkout-manager' ),
				'fields' 	=> __( 'Checkout fields', 'yith-woocommerce-checkout-manager' ),
			);

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => __( 'Checkout Manager', 'yith-woocommerce-checkout-manager' ),
				'menu_title'       => __( 'Checkout Manager', 'yith-woocommerce-checkout-manager' ),
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->_panel_page,
				'admin-tabs'       => apply_filters( 'ywccp_admin_tabs', $admin_tabs ),
				'options-path'     => YWCCP_DIR . '/plugin-options'
			);

			/* === Fixed: not updated theme  === */
			if( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once( YWCCP_DIR . '/plugin-fw/lib/yit-plugin-panel-wc.php' );
			}

			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );

		}

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once( YWCCP_DIR . '/plugin-fw/licence/lib/yit-licence.php' );
				require_once( YWCCP_DIR . '/plugin-fw/licence/lib/yit-plugin-licence.php' );
			}

			YIT_Plugin_Licence()->register( YWCCP_INIT, YWCCP_SECRET_KEY, YWCCP_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_updates() {
			if( ! class_exists( 'YIT_Plugin_Licence' ) ){
				require_once( YWCCP_DIR . '/plugin-fw/lib/yit-upgrade.php' );
			}

			YIT_Upgrade()->register( YWCCP_SLUG, YWCCP_INIT );
		}

		/**
		 * plugin_row_meta
		 *
		 * add the action links to plugin admin page
		 *
		 * @param $plugin_meta
		 * @param $plugin_file
		 * @param $plugin_data
		 * @param $status
		 *
		 * @return   Array
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use plugin_row_meta
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( defined( 'YWCCP_INIT') && YWCCP_INIT == $plugin_file ) {
				$plugin_meta[] = '<a href="' . $this->doc_url . '" target="_blank">' . __( 'Plugin documentation', 'yith-woocommerce-checkout-manager' ) . '</a>';
			}

			return $plugin_meta;
		}

		/**
		 * Print fields table
		 *
		 * @access public
		 * @param array $options
		 * @return void
		 * @since 1.0.0
		 */
		public function fields_general_section( $options ) {

			if( isset( $_GET['page'] ) && $_GET['page'] == $this->_panel_page
			    && isset( $_GET['tab'] ) && $_GET['tab'] == 'fields'
			    && file_exists( YWCCP_TEMPLATE_PATH . '/admin/fields-general.php' ) ) {

				// define variables
				$sections = array( 'billing', 'shipping', 'additional' );
				$current  = isset( $_GET['section'] ) ? esc_attr( $_GET['section'] ) : 'billing';
				$base_page_url = admin_url( "admin.php?page={$this->_panel_page}&tab=fields" );

				include_once( YWCCP_TEMPLATE_PATH . '/admin/fields-general.php' );
			}
		}

		/**
		 * Print edit form fields
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function print_add_edit_fields_form() {
			if ( isset( $_GET['page'] ) && $_GET['page'] == $this->_panel_page && file_exists( YWCCP_TEMPLATE_PATH . '/admin/fields-edit.php' ) ) {

				// define variables
				$positions = ywccp_get_array_positions_field();
				$validation = ywccp_get_array_validation_field();

				include_once( YWCCP_TEMPLATE_PATH . '/admin/fields-edit.php' );
			}
		}

		/**
		 * Load fields table based on current visible section
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro
		 * @param string $current
		 */
		public function load_fields_table( $current = 'billing' ){
			if ( isset( $_GET['page'] ) && $_GET['page'] == $this->_panel_page && file_exists( YWCCP_TEMPLATE_PATH . '/admin/fields-table.php' ) ) {

				// define variables
				$fields = ywccp_get_checkout_fields( $current, true );
				$default_fields_key = ywccp_get_default_fields_key( $current );

				include_once( YWCCP_TEMPLATE_PATH . '/admin/fields-table.php' );
			}
		}

		/**
		 * Save options fields
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function save_options() {

			if( ! isset( $_GET['page'] ) || $_GET['page'] != $this->_panel_page || ! isset( $_POST['ywccp-admin-action'] ) || $_POST['ywccp-admin-action'] != 'fields-save' ) {
				return;
			}

			$section = isset( $_POST['ywccp-admin-section'] ) ? $_POST['ywccp-admin-section'] : '';
			$names = isset( $_POST['field_name'] ) ? $_POST['field_name'] : array();
			if( empty( $names ) ) {
				return;
			}

			// get max number
			$max = max( array_map( 'absint', array_keys( $names ) ) );
			$new_fields = array();

			for( $i = 0; $i <= $max; $i++ ){

				// get name
				$name =  wc_clean( stripslashes( $names[$i] ) );
				$name = str_replace( ' ', '_', $name );
				$defaults = array(
						'custom_attributes' => array(),
				);

				if( ! empty( $_POST['field_deleted'][ $i ] ) ) {
					$this->save_ordermeta( $name );
					continue;
				}

				$new_fields[ $name ]                = array();
				$new_fields[ $name ]['type']        = ! empty( $_POST['field_type'][ $i ] ) ? $_POST['field_type'][ $i ] : 'text';
				$new_fields[ $name ]['label']       = ! empty( $_POST['field_label'][ $i ] ) ? stripslashes( $_POST['field_label'][ $i ] ) : '';
				$new_fields[ $name ]['placeholder'] = ! empty( $_POST['field_placeholder'][ $i ] ) ? stripslashes( $_POST['field_placeholder'][ $i ] ) : '';
				$new_fields[ $name ]['options']     = ! empty( $_POST['field_options'][ $i ] ) ? $this->crete_options_array( $_POST['field_options'][ $i ], $new_fields[ $name ]['type'] ) : array();
				$new_fields[ $name ]['class']       = ! empty( $_POST['field_class'][ $i ] ) ? array_map( 'wc_clean', explode( ',', $_POST['field_class'][ $i ] ) ) : array();
				$new_fields[ $name ]['label_class'] = ! empty( $_POST['field_label_class'][ $i ] ) ? array_map( 'wc_clean', explode( ',', $_POST['field_label_class'][ $i ] ) ) : '';
				$new_fields[ $name ]['validate']    = ! empty( $_POST['field_validate'][ $i ] ) ? explode( ',', $_POST['field_validate'][ $i ] ) : '';
				$new_fields[ $name ]['required']    = ( ! empty( $_POST['field_required'][ $i ] ) && $new_fields[ $name ]['type'] != 'heading' ) ? true : false;
				$new_fields[ $name ]['enabled']     = ! empty( $_POST['field_enabled'][ $i ] ) ? true : false;
				// check also in bulk action
				if( ( $_POST['bulk_action'] || $_POST['bulk_action_bottom'] ) && isset( $_POST['select_field'][$i] ) ) {
					$new_fields[ $name ]['enabled'] = ( $_POST['bulk_action'] == 'enable' || $_POST['bulk_action_bottom'] == 'enable' ) ? true : false;
				}
				$new_fields[ $name ]['show_in_email'] = ! empty( $_POST['field_show_in_email'][ $i ] ) ? true : false;
				$new_fields[ $name ]['show_in_order'] = ! empty( $_POST['field_show_in_order'][ $i ] ) ? true : false;
				$new_fields[ $name ]['custom_attributes'] = array(
					'data-tooltip' => ! empty( $_POST['field_tooltip'][ $i ] ) ? $_POST['field_tooltip'][ $i ] : ''
				);
				if( ! empty( $_POST['field_position'][ $i ] ) ) {
					array_push( $new_fields[ $name ]['class'], $_POST['field_position'][ $i ] );
				}
			}

			if( ! empty( $new_fields ) ) {
				// save option
				update_option( 'ywccp_fields_' . $section . '_options', $new_fields );
			}
		}

		/**
		 * Create options array for field
		 *
		 * @access protected
		 * @since 1.0.0
		 * @author Francesco Licandro
		 * @param string $options
		 * @param string $type
		 * @return array
		 */
		protected function crete_options_array( $options, $type = '' ) {

			$options_array = array();

			$options = array_map( 'wc_clean', explode( '|', $options ) ); // create array from string
			$options = array_unique( $options ); // remove double entries

			// first of all add empty options for placeholder if type is option
			if( $type == 'select' )
				$options_array[''] = '';

			foreach ( $options as $option ) {
				$has_key = strpos( $option, '::' );
				if( $has_key ){
					list( $key, $option ) = explode( '::', $option );
				}
				else {
					$key = $option;
				}

				// create key
				$key = sanitize_title_with_dashes( $key );
				$options_array[ $key ] = stripslashes( $option );
			}

			return $options_array;
		}

		/**
		 * Create order meta for prevent losing information if a fields was deleted
		 *
		 * @access protected
		 * @since 1.0.0
		 * @author Francesco Licandro
		 * @param string $field The field name to convert
		 */
		protected function save_ordermeta( $field ) {
			global $wpdb;

			$query   = $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_key = %s WHERE meta_key LIKE %s", $field, '_' . $field );
			$wpdb->query( $query );
		}

		/**
		 * Reset default options
		 *
		 * @since 1.0.0
		 * @access public
		 * @author Francesco Licandro
		 */
		public function reset_options() {
			if( ! isset( $_GET['page'] ) || $_GET['page'] != $this->_panel_page || ! isset( $_POST['ywccp-admin-action'] ) || $_POST['ywccp-admin-action'] != 'fields-reset' ) {
				return;
			}

			$section = isset( $_POST['ywccp-admin-section'] ) ? $_POST['ywccp-admin-section'] : '';
			delete_option( 'ywccp_fields_' . $section . '_options' );
		}

		/**
         * Filter WooCommerce Get customer details via ajax
         *
         * @since 1.0.11
         * @author Francesco Licandro
         * @access public
         * @param array $data Customer details
         * @param object $customer \WC_Customer
         * @param string|integer $user_id The customer id
         * @return array
         */
		public function filter_ajax_customer_details( $data, $customer, $user_id ) {

            $custom_fields = array(
                'billing'   => ywccp_get_fields_key_filtered( 'billing', true ),
                'shipping'  => ywccp_get_fields_key_filtered( 'shipping', true )
            );

            // loop custom fields
            foreach( $custom_fields as $section => $fields ) {
                // double check id data section exists
                if( ! isset( $data[ $section ] ) ) {
                    continue;
                }
                // loop section fields
                foreach( $fields as $field ) {
                    $data[$section][$field] = $customer->get_meta($section . '_' . $field);
                }
            }

		    return $data;
        }
	}
}
/**
 * Unique access to instance of YWCCP_Admin class
 *
 * @return \YWCCP_Admin
 * @since 1.0.0
 */
function YWCCP_Admin(){
	return YWCCP_Admin::get_instance();
}