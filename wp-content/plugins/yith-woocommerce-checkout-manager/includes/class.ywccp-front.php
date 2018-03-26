<?php
/**
 * Frontend class
 *
 * @author Yithemes
 * @package YITH WooCommerce Checkout Manager
 * @version 1.0.0
 */

if ( ! defined( 'YWCCP' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YWCCP_Front' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YWCCP_Front {

		/**
		 * Single instance of the class
		 *
		 * @var \YWCCP_Front
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YWCCP_VERSION;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YWCCP_Front
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
			
			// enqueue scripts and styles
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );

			// multiselect form fields type
			add_filter( 'woocommerce_form_field_multiselect', array( $this, 'multiselect_type' ), 10, 4 );
			// datepicker form fields type
			add_filter( 'woocommerce_form_field_datepicker', array( $this, 'datepicker_type' ), 10, 4 );
			// heading form fields type
			add_filter( 'woocommerce_form_field_heading', array( $this, 'heading_type' ), 10, 4 );
			// timepicker form fields type
			add_filter( 'woocommerce_form_field_timepicker', array( $this, 'timepicker_type' ), 10, 4 );

			// add additional table on order view
			add_action( 'woocommerce_order_details_after_order_table', array( $this, 'additional_info_table' ), 10, 1 );

			// validate fields
			add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_fields' ), 10, 1 );
			
			// filter locale array
			add_filter( 'woocommerce_get_country_locale_default', array( $this, 'set_locale_default' ), 10, 1 );
			add_filter( 'woocommerce_get_country_locale', array( $this, 'set_locale' ), 10, 1 );
		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		public function enqueue_scripts(){

			global $wp_scripts;

			$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';
			// frontend style
			wp_register_style( 'ywccp-front-style', YWCCP_ASSETS_URL . '/css/ywccp-frontend.css', array(), $this->version, 'all' );
			// external scripts
			wp_register_script( 'ywccp-external-script', YWCCP_ASSETS_URL . '/js/ywccp-external.min.js', array( 'jquery' ), $this->version, true );
			// frontend script
			wp_register_script( 'ywccp-front-script', YWCCP_ASSETS_URL . '/js/ywccp-frontend'.$min.'.js', array( 'jquery', 'ywccp-external-script' ), $this->version, true );

			if ( is_checkout() || $this->check_myaccount() ) {
				wp_enqueue_script( 'ywccp-external-script' );
				wp_enqueue_script( 'ywccp-front-script' );
				wp_enqueue_style( 'ywccp-front-style' );
				wp_enqueue_script( 'jquery-ui-datepicker');

				wp_register_script( 'wc-address-i18n', YWCCP_ASSETS_URL . '/js/ywccp-address-i18n'.$min.'.js', array( 'jquery', 'ywccp-front-script' ), $this->version, true );

				wp_localize_script( 'ywccp-front-script', 'ywccp_front', array(
					'validation_enabled' => get_option('ywccp-enable-js-error-check') == 'yes',
					'vat_validation_enabled' => get_option( 'ywccp-enable-js-vat-check' ) == 'yes',
					'err_msg'   => __( 'This is a required field.', 'yith-woocommerce-checkout-manager' ),
					'err_msg_vat' => __( 'The VAT number you have entered seems to be wrong.', 'yith-woocommerce-checkout-manager' ),
					'err_msg_mail' => __( 'The mail you have entered seems to be wrong.', 'yith-woocommerce-checkout-manager' ),
					'time_format'  => get_option( 'ywccp-time-format-datepicker', '12' ) == '12'
				));

				$inline_style = ywccp_add_custom_style();
				if( $inline_style ) {
					wp_add_inline_style( 'ywccp-front-style', $inline_style );
				}
			}
		}

		/**
		 * Check if is page my-account and set class variable
		 *
		 * @access protected
		 * @since 1.0.0
		 * @author Francesco Licandro
		 */
		protected function check_myaccount() {
			global $post;

			if( ! is_null( $post ) && strpos( $post->post_content, '[woocommerce_my_account' ) !== false && is_user_logged_in() ) {
				return true;
			}

			return false;
		}

		/**
		 * Multiselect fields type
		 *
		 * @since 1.0.0
		 * @param string $field
		 * @param string $key
		 * @param array $args
		 * @param string $value
		 * @return string
		 * @author Francesco Licandro
		 */
		public function multiselect_type( $field, $key, $args, $value ){

			$required = $args['required'] ? ' <abbr class="required" title="' . esc_attr__( 'required', 'yith-woocommerce-checkout-manager'  ) . '">*</abbr>' : '';
			// get value as array
			$value = is_string( $value ) ? explode( ', ', $value ) : $value;

			ob_start();
			?>

			<label for="<?php esc_attr( $args['id'] ) ?>" class="<?php echo esc_attr( implode( ' ', $args['label_class'] ) ) ?>">
				<?php echo esc_html( $args['label'] ) . $required ?>
			</label>
			<select name="<?php echo esc_attr( $key ) ?>[]" id="<?php echo esc_attr( $args['id'] ) ?>" class="ywccp-multiselect-type" multiple="multiple" data-placeholder="<?php echo esc_attr( $args['placeholder'] )?>">
				<?php foreach( $args['options'] as $key => $option ) : ?>
					<option value="<?php echo $key ?>" <?php echo in_array( $key, $value ) ? 'selected=selected' : ''; ?>><?php echo $option ?></option>
				<?php endforeach; ?>
			</select>

			<?php
			$field = ob_get_clean();

			return $this->wrap_field( $field, $args ) ;

		}

		/**
		 * Datepicker fields type
		 *
		 * @since 1.0.0
		 * @param string $field
		 * @param string $key
		 * @param array $args
		 * @param string $value
		 * @return string
		 * @author Francesco Licandro
		 */
		public function datepicker_type( $field, $key, $args, $value ){

			$required = $args['required'] ? ' <abbr class="required" title="' . esc_attr__( 'required', 'yith-woocommerce-checkout-manager'  ) . '">*</abbr>' : '';
			$format = get_option( 'ywccp-date-format-datepicker', 'mm/dd/yy' );
			
			ob_start();
			?>

			<label for="<?php esc_attr( $args['id'] ) ?>" class="<?php echo esc_attr( implode( ' ', $args['label_class'] ) ) ?>">
				<?php echo esc_html( $args['label'] ) . $required ?>
			</label>
			<input name="<?php echo esc_attr( $key ) ?>" id="<?php echo esc_attr( $args['id'] ) ?>" type="text" class="ywccp-datepicker-type" 
			       value="<?php echo $value ?>" placeholder="<?php echo esc_attr( $args['placeholder'] )?>" data-format="<?php echo $format ?>">

			<?php
			$field = ob_get_clean();

			return $this->wrap_field( $field, $args ) ;
		}

		/**
		 * Timepicker fields type
		 *
		 * @since 1.0.0
		 * @param string $field
		 * @param string $key
		 * @param array $args
		 * @param string $value
		 * @return string
		 * @author Francesco Licandro
		 */
		public function timepicker_type( $field, $key, $args, $value ){

			$required = $args['required'] ? ' <abbr class="required" title="' . esc_attr__( 'required', 'yith-woocommerce-checkout-manager'  ) . '">*</abbr>' : '';

			ob_start();
			?>

			<label for="<?php esc_attr( $args['id'] ) ?>" class="<?php echo esc_attr( implode( ' ', $args['label_class'] ) ) ?>">
				<?php echo esc_html( $args['label'] ) . $required ?>
			</label>
			<input name="<?php echo esc_attr( $key ) ?>" id="<?php echo esc_attr( $args['id'] ) ?>" type="text" class="ywccp-timepicker-type" value="<?php echo $value ?>" placeholder="<?php echo esc_attr( $args['placeholder'] )?>">

			<?php
			$field = ob_get_clean();

			return $this->wrap_field( $field, $args ) ;
		}

		/**
		 * Heading fields type
		 *
		 * @since 1.0.0
		 * @param string $field
		 * @param string $key
		 * @param array $args
		 * @param string $value
		 * @return string
		 * @author Francesco Licandro
		 */
		public function heading_type( $field, $key, $args, $value ){

			$field = '<div class="clear"></div><h3>'. $args['label'].'</h3>';

			return $field;
		}

		/**
		 * Wrap field
		 *
		 * @since 1.0.0
		 * @param string $content
		 * @param array $args
		 * @return string
		 * @author Francesco Licandro
		 */
		public function wrap_field( $content, $args ){
			// set id
			$container_id = esc_attr( $args['id'] ) . '_field';
			// set class
			$container_class = ! empty( $args['class'] ) ? 'form-row ' . esc_attr( implode( ' ', $args['class'] ) ) : '';
			// set clear
			$after = ! empty( $args['clear'] ) ? '<div class="clear"></div>' : '';

			return '<p class="'.$container_class.'" id="'.$container_id.'">' . $content . '</p>' . $after;
		}

		/**
		 * Add additional field table on view order
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro
		 * @param object $order
		 */
		public function additional_info_table( $order ) {

			$fields = ywccp_get_custom_fields( 'additional' );

			// build template content
			$content = array();
			foreach ( $fields as $key => $field ) {
				// check if value exists for order
				//$value = get_post_meta( $order->id, $key, true );
				$value = yit_get_prop( $order, $key, true );
				if( $value && $field['show_in_order'] ) {

					$content[$key] = array(
						'label' => $field['label'],
						'value' => isset( $field['options'][$value] ) ? $field['options'][$value] : $value
					);
				}
			}

			if( empty( $content ) ) {
				return;
			}

			wc_get_template( 'ywccp-additional-fields-table.php', array( 'fields' => $content ), '', YWCCP_TEMPLATE_PATH . '/' );
		}

		/**
		 * Custom validation for fields
		 *
		 * @since 1.0.0
		 * @access public
		 * @author Francesco Licandro
		 * @param  array $posted Array of posted params
		 */
		public function validate_fields( $posted ) {

			$checkout_fields = WC()->checkout->checkout_fields;

			foreach ( $checkout_fields as $fieldset_key => $fieldset ) {

				if( 'shipping' === $fieldset_key && ( ! $posted['ship_to_different_address'] || ! WC()->cart->needs_shipping_address() ) ) {
					continue;
				}

				foreach ( $fieldset as $key => $field ) {
					if ( isset( $posted[ $key ] ) ) {
						// Validation rules
						if ( ! empty( $field['validate'] ) && is_array( $field['validate'] ) && $posted[ $key ] !== '' ) {
							foreach ( $field['validate'] as $rule ) {
								switch ( $rule ) {
									case 'vat' :
										// get country
										$country = isset( $posted[ $fieldset_key . '_country' ] ) ? $posted[ $fieldset_key . '_country' ] : '';
										// validate vat
										$this->validate_vat_field( $posted[ $key ], $country );
										break;
									default :
										if( $rule )
											do_action( 'ywccp_validation_field_' . $rule, $key, $field, $fieldset_key, $posted );
										break;
								}
							}
						}
					}
				}
			}
		}



		/**
		 * Validate vat number using vatValidation.class
		 *
		 * @param $vat
		 * @param $country
		 * @author Francesco Licandro
		 */
		public function validate_vat_field( $vat, $country ) {

			// check for european vat
			switch( $country ) {
				case 'AT':
					$regex = '/^(AT){0,1}U[0-9]{8}$/i';
					break;
				case 'BE':
					$regex = '/^(BE){0,1}[0]{0,1}[0-9]{9}$/i';
					break;
				case 'BG':
					$regex = '/^(BG){0,1}[0-9]{9,10}$/i';
					break;
				case 'CY':
					$regex = '/^(CY){0,1}[0-9]{8}[A-Z]$/i';
					break;
				case 'CZ':
					$regex = '/^(CZ){0,1}[0-9]{8,10}$/i';
					break;
				case 'DK':
					$regex = '/^(DK){0,1}([0-9]{2}[\ ]{0,1}){3}[0-9]{2}$/i';
					break;
				case 'EE':
				case 'EL':
				case 'PT':
				case 'DE':
					$regex = '/^(EE|EL|DE|PT){0,1}[0-9]{9}$/i';
					break;
				case 'FR':
					$regex = '/^(FR){0,1}[0-9A-Z]{2}[\ ]{0,1}[0-9]{9}$/i';
					break;
				case 'FI':
				case 'HU':
				case 'LU':
				case 'MT':
				case 'SI':
					$regex = '/^(FI|HU|LU|MT|SI){0,1}[0-9]{8}$/i';
					break;
				case 'IE':
					$regex = '/^(IE){0,1}[0-9][0-9A-Z\+\*][0-9]{5}[A-Z]$/i';
					break;
				case 'IT':
				case 'LV':
					$regex = '/^(IT|LV){0,1}[0-9]{11}$/i';
					break;
				case 'LT':
					$regex = '/^(LT){0,1}([0-9]{9}|[0-9]{12})$/i';
					break;
				case 'NL':
					$regex = '/^(NL){0,1}[0-9]{9}B[0-9]{2}$/i';
					break;
				case 'PL':
				case 'SK':
					$regex = '/^(PL|SK){0,1}[0-9]{10}$/i';
					break;
				case 'RO':
					$regex = '/^(RO){0,1}[0-9]{2,10}$/i';
					break;
				case 'SE':
					$regex = '/^(SE){0,1}[0-9]{12}$/i';
					break;
				case 'ES':
					$regex = '/^(ES){0,1}([0-9A-Z][0-9]{7}[A-Z])|([A-Z][0-9]{7}[0-9A-Z])$/i';
					break;
				case 'GB':
					$regex = '/^(GB){0,1}([1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2})|([1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2}[\ ]{0,1}[0-9]{3})|((GD|HA)[0-9]{3})$/i';
					break;
				default:
					$regex = false;
					break;
			}

			$error = false;

			if( ! $regex ) {
				$res = preg_match_all( "/[0-9]/", $vat );
				if( $res < 4 ) {
					$error = true;
				}
			}
			else {
				$res = preg_match( $regex, $vat );
				if( ! $res || $res == 0 ) {
					$error = true;
				}
			}

			if( $error ) {
				wc_add_notice( __( 'The VAT number you have entered seems to be wrong. Please, check it.', 'yith-woocommerce-checkout-manager' ), 'error' );
			}

			return;
		}

		/**
		 * Filter locale default for ywccp_address_i18n
		 *
		 * @since 1.0.6
		 * @author Francesco Licandro
		 * @param array $locale
		 * @return array
		 */
		public function set_locale_default( $locale ){

            // remove itself to prevent infinite loop
            remove_filter( 'woocommerce_get_country_locale_default', array( $this, 'set_locale_default' ), 10 );

			$new_locale = array();
			$keys = array( 'address_1', 'address_2', 'state', 'postcode', 'city' );
			$billing = ywccp_get_checkout_fields('billing');
			$shipping = ywccp_get_checkout_fields('shipping');

            foreach( $billing as $key => $value ) {
	            // check for translations
	            $value  = ywccp_field_filter_wpml_strings( $key, $value );
                $key    = str_replace('billing_', '', $key );
                if( in_array( $key, $keys ) ) {
                    $new_locale['billing'][ $key ] = array(
                        'required'    => isset( $value['required'] ) ? $value['required'] : false,
                        'label'       => isset( $value['label'] ) ? $value['label'] : '',
                        'placeholder' => isset( $value['placeholder'] ) ? $value['placeholder'] : ''
                    );
                }
            }
            foreach( $shipping as $key => $value ) {
	            // check for translations
	            $value  = ywccp_field_filter_wpml_strings( $key, $value );
                $key = str_replace('shipping_', '', $key);
                if( in_array( $key, $keys ) ) {
                    $new_locale['shipping'][ $key ] = array(
                        'required'    => isset( $value['required'] ) ? $value['required'] : false,
                        'label'       => isset( $value['label'] ) ? $value['label'] : '',
                        'placeholder' => isset( $value['placeholder'] ) ? $value['placeholder'] : ''
                    );
                }
            }

            // re-add
            add_filter( 'woocommerce_get_country_locale_default', array( $this, 'set_locale_default' ), 10, 1 );

            return $new_locale;
		}

		/**
		 * Filter locale for ywccp_address_i18n
		 *
		 * @since 1.0.6
		 * @author Francesco Licandro
		 * @param array $locale
		 * @return array
		 */
		public function set_locale( $locale ) {

			foreach ( $locale as $country => &$fields ) {
				foreach ( $fields as $key => &$field ) {
					if( ! is_array( $field ) ) {
						continue;
					}
					foreach( $field as $attr => $attr_value ) {
						if( $attr == 'label' || $attr == 'placeholder' ) {
							unset( $field[$attr] );
						}
					}
				}
			}

			return $locale;
		}
	}
}
/**
 * Unique access to instance of YWCCP_Front class
 *
 * @return \YWCCP_Front
 * @since 1.0.0
 */
function YWCCP_Front(){
	return YWCCP_Front::get_instance();
}