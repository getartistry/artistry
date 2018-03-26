<?php
/**
 * Plugins Functions and Utilities
 *
 * @author Yithemes
 * @package YITH WooCommerce Checkout Manager
 * @version 1.0.0
 */

if ( ! defined( 'YWCCP' ) ) {
	exit;
} // Exit if accessed directly

if( ! function_exists( 'ywccp_get_checkout_fields' ) ) {
	/**
	 * Get checkout fields by section
	 *
	 * @since 1.0.0
	 * @param string $section billing|shipping|additional
	 * @return array
	 * @author Francesco Licandro
	 */
	function ywccp_get_checkout_fields( $section = 'billing', $validate = false ){

		// first check in options
		$fields = get_option( 'ywccp_fields_' . $section . '_options', array() );

		// if options is empty and is a wc address fields get WC standard and use XX for force WC to return all fields
		if( empty( $fields ) ) {
			$fields = ywccp_get_default_fields( $section );
		}

		// first validate if is admin
		if( $validate ){
			$fields = ywccp_validate_fields_option( $fields );
		}
		elseif( isset( $_POST[ $section . '_country' ] ) ) {
			$country = $_POST[ $section . '_country' ];
			$locale  = WC()->countries->get_country_locale();
			$locale_posted = isset( $locale[ $country ] ) ? $locale[ $country ] : array();

			if( ! empty( $locale_posted ) ) {
				foreach( $locale_posted as $key => $value ) {
					$field_key = $section . '_' . $key;
					if( isset( $fields[ $field_key ] ) ) {
						$fields[ $field_key ] = array_merge( $fields[ $field_key ], $value );
					}
				}
			}
		}

		return $fields;
	}
}

if( ! function_exists( 'ywccp_get_all_checkout_fields' ) ) {
	/**
	 * Get all checkout fields
	 * 
	 * @since 1.0.5
	 * @author Francesco Licandro
	 * @return array
	 */
	function ywccp_get_all_checkout_fields(){
		$billing    = ywccp_get_checkout_fields( 'billing' );
		$shipping   = ywccp_get_checkout_fields( 'shipping' );
		$additional = ywccp_get_checkout_fields( 'additional' );
		
		return array_merge( $billing, $shipping, $additional );
	}
}

if( ! function_exists( 'ywccp_get_custom_fields' ) ) {
	/**
	 * Get an array with only custom fields for section
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param string $section
	 * @return array
	 */
	function ywccp_get_custom_fields( $section = 'billing' ) {

		$fields = get_option( 'ywccp_fields_' . $section . '_options', array() );

		if( empty( $fields ) ) {
			return array();
		}

		$default_keys = ywccp_get_default_fields_key( $section );

		foreach( $fields as $key => $field ) {
			if( in_array( $key, $default_keys ) ){
				unset( $fields[$key] );
			}
		}

		return $fields;
	}
}

if( ! function_exists( 'ywccp_get_fields_localisation_address_formats') ) {
	/**
	 * Add address localisation formats
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param string $section billing | shipping | all
	 * @param boolean $return_keys
	 * @return mixed
	 */
	function ywccp_get_fields_localisation_address_formats( $section = 'billing', $return_keys = false ) {

		$fields         = array();
		$keep_order     = get_option( 'ywccp-override-formatted-addresses', 'no' ) != 'yes';

		if( $section != 'all' ) {
			$fields = ywccp_get_fields_key_filtered( $section, $keep_order );
		}
		else {
			$fields_billing = ywccp_get_fields_key_filtered( 'billing', $keep_order );
			$fields_shipping = ywccp_get_fields_key_filtered( 'shipping', $keep_order );
			$fields = array_merge( $fields_billing, $fields_shipping );
		}

		// prevent multiple entries
		$fields = array_unique( $fields );

		if( $return_keys ) {
			return $fields;
		}

		if( empty( $fields ) ) {
			return '';
		}

		$replace = '';
		foreach( $fields as $field ) {
			$replace .= "\n{{$field}}";
		}

		return $replace;
	}
}

if( ! function_exists( 'ywccp_get_fields_key_filtered' ) ) {
	/**
	 * Get custom fields key for section filtered by location( billing | shipping )
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param string $section
	 * @param boolean $custom Choose to get only custom
	 * @return array
	 */
	function ywccp_get_fields_key_filtered( $section = 'billing', $custom = false ){

		global $pagenow;

		$fields = get_option( 'ywccp_fields_' . $section . '_options', array() );

		if( empty( $fields ) ) {
			return array();
		}

		// check where I am
		if( ( is_admin() &&
		      ( ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'shop_order' )
		        || ( $pagenow == 'post.php' && isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) ) ) ) {

			$where_im = '';
		}
		elseif( is_order_received_page() || is_account_page() ) {
			$where_im = 'show_in_order';
		}
		else {
			$where_im = 'show_in_email';
		}
		// remove fields based on where I am
		if( $where_im ) {
			foreach ( $fields as $key => $value ) {
				if( in_array( $key, array( 'billing_email', 'billing_phone' ) )
				    || ( isset($value[ $where_im ] ) && ! $value[ $where_im ] ) ) {
					unset( $fields[$key] );
				};
			}
		}

		// fields keys
		$fields_keys = array_keys( $fields );

		if( $custom ) {
			// default section keys
			$default_keys = ywccp_get_default_fields_key( $section );
			// custom keys
			$fields_keys = array_diff( $fields_keys, $default_keys );
		}

		foreach ( $fields_keys as &$value ) {
			$value = str_replace( $section . '_', '', $value );
		}

		return apply_filters( 'ywccp_get_fields_key_filtered_return', $fields_keys );
	}
}

if( ! function_exists( 'ywccp_get_address_replacement' ) ) {
    /**
     * Get address replacement for custom fields
     *
     * @since 1.0.5
     * @author Francesco Licandro
     * @param string $section
     * @param object $order \WC_Order
     * @return array
     */
    function ywccp_get_address_replacement( $section, $order ) {

        $replacement = array();

        if( ! $section ) {
            return $replacement;
        }

        $custom_fields_key = ywccp_get_fields_key_filtered( $section, true );
        if( empty( $custom_fields_key ) ) {
            return $replacement;
        }

        $fields = ywccp_get_custom_fields( $section );

        foreach( $custom_fields_key as $custom_field_key ) {
	        $value = yit_get_prop( $order, '_'. $section .'_'. $custom_field_key, true );
            // if value is empty continue
            if( empty( $value ) ) {
                continue;
            }
            // save it
            $replacement[ $custom_field_key ] = ywccp_format_field_value( $value, $fields[ $section . '_' . $custom_field_key ] );
        }

        return apply_filters( 'ywccp_get_address_replacement_filter', $replacement, $section, $order );
    }
}

if( ! function_exists( 'ywccp_validate_fields_option' ) ) {
	/**
	 * Validate fields option and add defaults value
	 *
	 * @since 1.0.0
	 * @param array $fields
	 * @return array
	 * @author Francesco Licandro
	 */
	function ywccp_validate_fields_option( $fields ) {

		if( empty( $fields ) ){
			return array();
		}

		foreach( $fields as &$field ) {
			// type standard text fo not set
			! isset( $field['type'] ) &&  $field['type'] = 'text';
			// label empty if not set
			! isset( $field['label'] ) && $field['label'] = '';
			// placeholder empty if not set
			! isset( $field['placeholder'] ) && $field['placeholder'] = '';
			// tooltip empty if not set
			! isset( $field['tooltip'] ) && $field['tooltip'] = '';
			// set options for select type
			$options = '';
			if( isset( $field['options'] ) && is_array( $field['options'] ) ) {
				foreach ( $field['options'] as $key => $value ) {

				    // support no latin language
                    $key   = urldecode( $key );
                    $value = urldecode( $value );

					// exclude empty options
					if( ! $key && ! $value ) {
						continue;
					}
					$options .= $key.'::'.$value;
					if( key( array_slice( $field['options'], -1, 1, TRUE ) ) != $key ) {
						$options .= '|';
					}
				}
			}
			$field['options'] = $options;
			// set class and position for field
			if( isset( $field['class'] ) && is_array( $field['class'] ) ) {
				$positions = ywccp_get_array_positions_field();
				foreach( $field['class'] as $key => $single_class ) {
					if( is_array( $positions ) && array_key_exists( $single_class, $positions ) ){
						$field['position'] = $single_class;
						unset( $field['class'][$key] );
						break;
					}
				}
				$field['class'] = implode( ',', $field['class'] );
			}
			// set empty if position not set
			! isset( $field['position'] ) && $field['position'] = '';
			// set label class foe field
			$field['label_class'] = ( isset( $field['label_class'] ) && is_array( $field['label_class'] ) ) ? implode( ',', $field['label_class'] ) : '';
			// set validation
			$field['validate'] = ( isset( $field['validate'] ) && is_array( $field['validate'] ) ) ?  implode( ',', $field['validate'] ) : '';
			// set required ( default false )
			$field['required'] = ( ! isset( $field['required'] ) || ! $field['required'] ) ? '0' : '1';
			// set clear ( default false )
			$field['clear'] = ( ! isset( $field['clear'] ) || ! $field['clear'] ) ? '0' : '1';
			// set enabled ( default true )
			$field['enabled'] = ( isset( $field['enabled'] ) && ! $field['enabled'] ) ? '0': '1';
			// set show in email ( default true )
			$field['show_in_email'] = ( isset( $field['show_in_email'] ) && ! $field['show_in_email'] ) ? '0' : '1';
			// set show in order ( default true )
			$field['show_in_order'] = ( isset( $field['show_in_order'] ) && ! $field['show_in_order'] ) ? '0' : '1';
			// set show in my-account ( default true )
			$field['show_in_account'] = ( isset( $field['show_in_account'] ) && ! $field['show_in_account'] ) ? '0' : '1';
			// set tooltip
			$field['tooltip'] = isset( $field['custom_attributes']['data-tooltip'] ) ? $field['custom_attributes']['data-tooltip'] : '';
		}

		return $fields;
	}
}

if( ! function_exists( 'ywccp_get_default_fields_key' ) ) {
	/**
	 * Get default fields key
	 *
	 * @since 1.0.0
	 * @param string $section
	 * @return array
	 * @author Francesco Licandro
	 */
	function ywccp_get_default_fields_key( $section = 'billing' ) {

		$fields = ywccp_get_default_fields( $section );

		return is_array( $fields ) ? array_keys( $fields ) : array();
	}
}

if( ! function_exists( 'ywccp_get_default_fields' ) ) {
	/**
	 * Get default fields key
	 *
	 * @since 1.0.0
	 * @param string $section
	 * @param string $country
	 * @return array
	 * @author Francesco Licandro
	 */
	function ywccp_get_default_fields( $section = 'billing', $country = 'XX' ) {


		if( $section == 'billing' || $section == 'shipping' ) {
            // remove actions to prevent infinite loops and errors
		    $priority = has_filter( 'woocommerce_' . $section . '_fields', 'ywccp_load_custom_' . $section . '_fields' );
		    $priority === false && $priority = 50;
            remove_filter( 'woocommerce_' . $section . '_fields', 'ywccp_load_custom_' . $section . '_fields', $priority );
            if( class_exists( 'YWCCP_Front' ) ) {
                remove_filter( 'woocommerce_get_country_locale_default', array( YWCCP_Front(), 'set_locale_default' ), 10 );
                remove_filter( 'woocommerce_get_country_locale', array( YWCCP_Front(), 'set_locale' ), 10 );
            }
            // get default fields
			$fields = WC()->countries->get_address_fields( $country, $section . '_' );
			// add action previously removed
			add_filter( 'woocommerce_' . $section . '_fields', 'ywccp_load_custom_' . $section . '_fields', $priority, 1 );
            if( class_exists( 'YWCCP_Front' ) ) {
                add_filter( 'woocommerce_get_country_locale_default', array( YWCCP_Front(), 'set_locale_default' ), 10, 1 );
                add_filter( 'woocommerce_get_country_locale', array( YWCCP_Front(), 'set_locale' ), 10, 1 );
            }

			return $fields;
		}
		else {
			return apply_filters( 'ywccp_default_additional_fields', array(
				'order_comments' => array(
					'type'        => 'textarea',
					'class'       => array('notes'),
					'label'       => __( 'Order notes', 'wc_checkout_fields' ),
					'placeholder' => _x( 'Notes on your order, e.g. special notes concerning delivery.', 'placeholder', 'wc_checkout_fields' )
				)
			) );
		}
	}
}

if( ! function_exists( 'ywccp_get_field_type' ) ) {
	/**
	 * Get type for fields
	 *
	 * @since 1.0.0
	 * @return array
	 * @author Francesco Licandro
	 */
	function ywccp_get_field_type() {
		$types = array(
			'text'        => __( 'Text', 'yith-woocommerce-checkout-manager' ),
			'password'    => __( 'Password', 'yith-woocommerce-checkout-manager' ),
			'tel'         => __( 'Phone', 'yith-woocommerce-checkout-manager' ),
			'textarea'    => __( 'Textarea', 'yith-woocommerce-checkout-manager' ),
			'radio'       => __( 'Radio', 'yith-woocommerce-checkout-manager' ),
			'checkbox'    => __( 'Checkbox', 'yith-woocommerce-checkout-manager' ),
			'select'      => __( 'Select', 'yith-woocommerce-checkout-manager' ),
			'multiselect' => __( 'Multi select', 'yith-woocommerce-checkout-manager' ),
			'datepicker'  => __( 'Date', 'yith-woocommerce-checkout-manager' ),
			'timepicker'  => __( 'Time', 'yith-woocommerce-checkout-manager' ),
			'heading'     => __( 'Heading', 'yith-woocommerce-checkout-manager' )
		);

		return apply_filters( 'ywccp_field_types', $types );
	}
}

if( ! function_exists( 'ywccp_get_array_positions_field' ) ) {
	/**
	 * Get an array with all positions field
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @return array
	 */
	function ywccp_get_array_positions_field(){
		return apply_filters( 'ywccp_positions_field_options_array', array(
			'form-row-first' => __( 'First', 'yith-woocommerce-checkout-manager' ),
			'form-row-last'  => __( 'Last', 'yith-woocommerce-checkout-manager' ),
			'form-row-wide'  => __( 'Wide', 'yith-woocommerce-checkout-manager' )
		));
	}
}

if( ! function_exists( 'ywccp_get_array_validation_field' ) ) {
	/**
	 * Get an array with all validation field
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @return array
	 */
	function ywccp_get_array_validation_field(){
		return apply_filters( 'ywccp_validation_field_options_array', array(
			''         => __( 'No validation', 'yith-woocommerce-checkout-manager' ),
			'postcode' => __( 'PostCode', 'yith-woocommerce-checkout-manager' ),
			'phone'    => __( 'Phone', 'yith-woocommerce-checkout-manager' ),
			'email'    => __( 'Email', 'yith-woocommerce-checkout-manager' ),
			'state'    => __( 'State', 'yith-woocommerce-checkout-manager' ),
			'vat'      => __( 'VAT', 'yith-woocommerce-checkout-manager' )
		));
	}
}

if( ! function_exists( 'ywccp_build_fields_array_admin' ) ) {
	/**
	 * Remove specified prefix from array keys
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param array $fields
	 * @param array $old
	 * @param string $prefix
	 * @return array
	 */
	function ywccp_build_fields_array_admin( $fields, $old, $prefix = 'billing_' ){

		global $theorder, $post;

		if ( ! is_object( $theorder ) ) {
			$theorder = wc_get_order( $post->ID );
		}

		$new = array();

		foreach ( $fields as $key => $opt ) {

			$key = str_replace( $prefix, '', $key );
			$new[ $key ] = array();

			// if exists load default
			if( array_key_exists( $key, $old ) ) {
				$new[ $key ] = $old[$key];
				// update label
				$new[ $key ]['label'] = isset( $opt['label'] ) ? $opt['label'] : '';
			}
			else {
				// get value
				//$value = get_post_meta( $theorder->id, '_' . $prefix . $key, true );
				$value = yit_get_prop( $theorder, '_' . $prefix . $key, true );

				switch( $opt['type'] ) {
					case 'select' :
					case 'radio' :
						$new[ $key ]['type'] = 'select';
						$new[ $key ]['class'] = 'select short';
						// set options
						! empty( $opt['options'] ) && $new[ $key ]['options'] = $opt['options'];

						break;

					case 'multiselect' :
						$new[ $key ]['type'] = 'select';
						$new[ $key ]['class'] = 'select short ywccp_multiselect_admin';
						$new[ $key ]['custom_attributes'] = array(
							'multiple' =>'multiple',
							'data-value' => $value
						);
						// set options
						! empty( $opt['options'] ) && $new[ $key ]['options'] = $opt['options'];

						break;

					case 'checkbox' :

						$new[ $key ]['type'] = 'checkbox';
						if( $value ) {
							$new[ $key ]['custom_attributes'] = array('checked' =>'checked');
						}
						$new[ $key ]['value'] = '1';

						break;
					case 'datepicker' :
						$new[ $key ]['type'] = 'datepicker';
						$new[ $key ]['class'] = 'ywccp_datepicker_admin';
						$new[ $key ]['value'] = $value;
						$new[ $key ]['custom_attributes'] = array(
							'data-format' => get_option( 'ywccp-date-format-datepicker', 'mm/dd/yy' )
						);
						break;
					default :
						break;
				}

				$new[ $key ]['show'] = false;
				// set label
				isset( $opt['label'] ) && $new[ $key ]['label'] = $opt['label'];
			}
		}

		return $new;
	}
}

if( ! function_exists( 'ywccp_add_custom_style' ) ) {
	/**
	 * Add custom style from plugin options
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @return string
	 */
	function ywccp_add_custom_style() {

		$css = "";

		// checkout columns
		$one_columns = get_option( 'ywccp-field-checkout-columns' ) == 'yes';
		if( $one_columns ) {
			$css .= "#customer_details .col-1, #customer_details .col-2 { float: none; width:100%; margin-bottom: 10px; }
		#customer_details .col-1:after, #customer_details .col-2:after { content: ''; display: table; clear: both; }";
		}

		$input_height		   = get_option( 'ywccp-field-input-height' );
		$color_border          = get_option( 'ywccp-field-border-color' );
		$focus_color_border    = get_option( 'ywccp-field-border-color-focus' );
		$success_color_border  = get_option( 'ywccp-field-border-color-success' );
		$invalid_color_border  = get_option( 'ywccp-field-border-color-error' );
		$invalid_color_message = get_option( 'ywccp-field-error-color' );

		$css .= "
			.woocommerce form .form-row input.input-text,
			.woocommerce form .form-row .select2-container .select2-choice,
			.woocommerce form .form-row select { height: {$input_height}px; }
			.woocommerce form .form-row .select2-container .select2-choice,
			.woocommerce form .form-row input.input-text ,
			.woocommerce form .form-row select,
			.woocommerce form .form-row textarea { border-color: {$color_border}; }
			.woocommerce form .form-row .select2-container .select2-choice,
			.woocommerce form .form-row input.input-text :focus,
			.woocommerce form .form-row select:focus, 
			.woocommerce form .form-row textarea:focus { border-color: {$focus_color_border}; }
			.woocommerce form .form-row.woocommerce-validated .select2-container .select2-choice, 
			.woocommerce form .form-row.woocommerce-validated input.input-text , 
			.woocommerce form .form-row.woocommerce-validated select,
			.woocommerce form .form-row.woocommerce-validated textarea { border-color: {$success_color_border}; }
			.woocommerce form .form-row.woocommerce-invalid .select2-container .select2-choice, 
			.woocommerce form .form-row.woocommerce-invalid input.input-text , 
			.woocommerce form .form-row.woocommerce-invalid select,
			.woocommerce form .form-row.woocommerce-invalid textarea { border-color: {$invalid_color_border}; }
			.woocommerce form .form-row.woocommerce-invalid label,
			.woocommerce form .form-row.woocommerce-invalid .ywccp_error { color: {$invalid_color_message}; }";

		return apply_filters( 'ywccp_custom_style_frontend', $css );
	}
}

if( ! function_exists( 'ywccp_get_all_custom_fields' ) ) {
	/**
	 * Get custom fields for all section
	 * 
	 * @since 1.0.3
	 * @author Francesco Licandro
	 * @return mixed 
	 */
	function ywccp_get_all_custom_fields(){
		
		$fields = array();
		// get billing
		$fields['billing'] = ywccp_get_custom_fields('billing');
		// get shipping
		$fields['shipping'] = ywccp_get_custom_fields('shipping');
		// get additional
		$fields['additional'] = ywccp_get_custom_fields('additional');
		
		return $fields;
	}
}

if( ! function_exists( 'ywccp_is_2_7' ) ) {
	/**
	 * Check if WooCommerce version is 2.7
	 * 
	 * @since 1.0.5
	 * @author Francesco Licandro
	 * @return boolean
	 */
	function ywccp_is_2_7(){
		return version_compare( WC()->version, '2.7', '>=' );
	}
}

if( ! function_exists( 'ywccp_field_filter_wpml_strings' ) ) {
	/**
	 * Filter field strings for WPML translations
	 *
	 * @since 1.0.10
	 * @author Francesco Licandro
	 * @param string $field_key
	 * @param array $field
	 * @return array
	 */
	function ywccp_field_filter_wpml_strings( $field_key, $field ) {
		// get label if any
		if( isset( $field['label'] ) && $field['label'] ) {
			$field['label'] = apply_filters( 'wpml_translate_single_string', $field['label'], 'yith-woocommerce-checkout-manager', 'plugin_ywccp_' . $field_key . '_label' );
		}
		// get placeholder if any
		if( isset( $field['placeholder'] ) && $field['placeholder'] ) {
			$field['placeholder'] = apply_filters( 'wpml_translate_single_string', $field['placeholder'], 'yith-woocommerce-checkout-manager', 'plugin_ywccp_' . $field_key . '_placeholder' );
		}
		// get tooltip
		if( isset( $field['custom_attributes']['data-tooltip'] ) && $field['custom_attributes']['data-tooltip'] ) {
			$field['custom_attributes']['data-tooltip'] = apply_filters( 'wpml_translate_single_string', $field['custom_attributes']['data-tooltip'], 'yith-woocommerce-checkout-manager', 'plugin_ywccp_' . $field_key . '_tooltip' );
		}

		if( ! empty( $field['options'] ) ) {
			foreach ( $field['options'] as $option_key => $option ) {
				if( $option === '' ) {
					continue;
				}
				// register single option
				$field['options'][$option_key] = apply_filters( 'wpml_translate_single_string', $option, 'yith-woocommerce-checkout-manager', 'plugin_ywccp_' . $field_key . '_' . $option_key );
			}
		}
		
		return $field;
	}
}

if( ! function_exists( 'ywccp_customer_get_address' ) ) {
	/**
	 * Get customer address
	 *
	 * @since 1.1.0
	 * @author Francesco Licandro
	 * @param array $value
	 * @param object $customer \WC_Customer
	 * @param string $section
	 * @return array
	 */
	function ywccp_customer_get_address( $value, $customer, $section = 'billing' ) {

		$fields         = ywccp_get_custom_fields( $section );

		if( empty( $fields ) ) {
			return $value;
		}

		$fields_to_show = ywccp_get_fields_key_filtered( $section, true );
		foreach( $fields_to_show as $key ) {
			if( ! isset( $fields[$section.'_'.$key] ) ) {
				continue;
			}
			$field_value = get_user_meta( get_current_user_id(), $section . '_' . $key, true );
			$field_value && $value[ $key ] = ywccp_format_field_value( $field_value, $fields[$section.'_'.$key] );
		}

		return apply_filters( 'ywccp_customer_get_address_filter', $value, $customer, $section );
	}
}

if( ! function_exists( 'ywccp_format_field_value' ) ) {
	/**
	 * Format a field value
	 *
	 * @since 1.1.0
	 * @author Francesco Licandro
	 * @param array $field
	 * @param string|array $value
	 * @return string
	 */
	function ywccp_format_field_value( $value, $field ){

		if( in_array( $field['type'], array( 'select', 'multiselect', 'radio' ) ) ) {
			// explode if multiple
			$array_values = explode(', ', $value );
			$new_value = array();

			foreach ( $array_values as $key ) {
				$new_value[] = isset( $field['options'][ $key ] ) ? $field['options'][ $key ] : $key;
			}

			$value = implode(', ', $new_value );
		}

		// prepend label if any
		( $field['label'] && get_option( 'ywccp-show-label-formatted-addresses', 'yes' ) == 'yes' ) && $value = $field['label'] . ': ' . $value;

		return apply_filters( 'ywccp_format_field_value_filter', $value, $field );
	}
}