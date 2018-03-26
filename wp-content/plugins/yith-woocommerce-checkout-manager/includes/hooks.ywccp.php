<?php
/**
 * Plugins Hooks
 *
 * @author Yithemes
 * @package YITH WooCommerce Checkout Manager
 * @version 1.0.0
 */

if ( ! defined( 'YWCCP' ) ) {
	exit;
} // Exit if accessed directly

// billing fields
add_filter( 'woocommerce_billing_fields', 'ywccp_load_custom_billing_fields', 50, 1 );
add_filter( 'woocommerce_admin_billing_fields', 'ywccp_load_custom_billing_fields_admin', 50, 1 );
// shipping fields
add_filter( 'woocommerce_shipping_fields', 'ywccp_load_custom_shipping_fields', 50, 1 );
add_filter( 'woocommerce_admin_shipping_fields', 'ywccp_load_custom_shipping_fields_admin', 50, 1 );

// additional fields
add_filter( 'woocommerce_checkout_fields', 'ywccp_add_additional_fields', 100, 1 );
// add additional meta to order. First check action to use based on woocommerce version
$action_additional_fields = ywccp_is_2_7() ? 'woocommerce_checkout_create_order' : 'woocommerce_checkout_update_order_meta';
add_action( $action_additional_fields, 'ywccp_add_additional_fields_meta', 10, 2 );

// other
add_filter( 'woocommerce_localisation_address_formats', 'ywccp_add_address_formats', 100, 1 );
add_filter( 'woocommerce_order_formatted_billing_address', 'ywccp_update_formatted_billing_address_order', 10, 2 );
add_filter( 'woocommerce_order_formatted_shipping_address', 'ywccp_update_formatted_shipping_address_order', 10, 2 );
add_filter( 'woocommerce_formatted_address_replacements', 'ywccp_update_address_replacement', 10, 2 );
add_action( 'woocommerce_email_after_order_table', 'ywccp_email_additional_fields_list', 10, 4 );

// filter customer billing and shipping address
add_filter( 'woocommerce_customer_get_billing', 'ywccp_customer_get_billing', 10, 2 );
add_filter( 'woocommerce_customer_get_shipping', 'ywccp_customer_get_shipping', 10, 2 );

// compatibility with WooCommerce Customer Order CSV Export
add_filter( 'wc_customer_order_csv_export_order_headers', 'ywccp_customer_order_csv_export_order_headers', 1, 2 );
add_filter( 'wc_customer_order_csv_export_order_row', 'ywccp_customer_order_csv_export_order_row', 1, 3 );

// filter strings for WPML
add_filter( 'woocommerce_checkout_fields', 'ywccp_filter_wpml_strings', 999, 1 );

if( ! function_exists( 'ywccp_load_custom_billing_fields' ) ) {
	/**
	 * Load customized billing fields function.
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param array $old
	 * @return array
	 */
	function ywccp_load_custom_billing_fields( $old ) {
		$new = ywccp_get_checkout_fields( 'billing' );

		if( empty( $new ) ) {
			return $old;
		}
		// remove disabled
		foreach( $new as $key => &$value ){
			if( isset( $value['enabled'] ) && ! $value['enabled'] ) {
				unset( $new[$key] );
			}
		}

		return $new;
	}
}

if( ! function_exists( 'ywccp_load_custom_billing_fields_admin' ) ){
	/**
	 * Load customized billing fields for admin section.
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param array $old
	 * @return array
	 */
	function ywccp_load_custom_billing_fields_admin( $old ) {

		$fields = ywccp_get_checkout_fields( 'billing' );

		if( ! is_array( $fields ) || empty( $fields ) ) {
			return $old;
		}

		return ywccp_build_fields_array_admin( $fields, $old, 'billing_' );
	}
}

if( ! function_exists( 'ywccp_load_custom_shipping_fields' ) ){
	/**
	 * Load customized shipping fields function.
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param array $old
	 * @return array
	 */
	function ywccp_load_custom_shipping_fields( $old ) {
		$new = ywccp_get_checkout_fields( 'shipping' );

		if( empty( $new ) ) {
			return $old;
		}
		// remove disabled
		foreach( $new as $key => &$value ){
			if( isset( $value['enabled'] ) && ! $value['enabled'] ) {
				unset( $new[$key] );
			}
		}

		return $new;
	}
}

if( ! function_exists( 'ywccp_load_custom_shipping_fields_admin' ) ){
	/**
	 * Load customized shipping fields for admin section.
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param array $old
	 * @return array
	 */
	function ywccp_load_custom_shipping_fields_admin( $old ) {

		$fields = ywccp_get_checkout_fields( 'shipping' );

		if( ! is_array( $fields ) || empty( $fields ) ) {
			return $old;
		}

		return ywccp_build_fields_array_admin( $fields, $old, 'shipping_' );
	}
}

if( ! function_exists( 'ywccp_add_address_formats' ) ) {
	/**
	 * Update address formats for all formatted address and all nations
	 *
	 * @param $formats array Array of available formats, indexed for nation code
	 * @return array Filtered array of available formats
	 * @since 1.0.0
	 * @author Francesco Licandro
	 */
	function ywccp_add_address_formats( $formats ) {

		$overwrite       = get_option( 'ywccp-override-formatted-addresses', 'no' ) == 'yes';
		$new_replacement = ywccp_get_fields_localisation_address_formats( 'all' );

		foreach ( $formats as $country => &$value ) {
			$overwrite ? $value = $new_replacement : $value .= $new_replacement;
		}

		return $formats;
	}
}

if( ! function_exists( 'ywccp_update_formatted_billing_address_order' ) ) {
	/**
	 * Adds field to formatted address for order's admin view
	 *
	 * @access public
	 *
	 * @param $billing_fields array Array of fields to be used in formatted address
	 * @param \WC_Order Order object
	 *
	 * @return array Array of filtered fields
	 * @since 1.0.0
	 */
	function ywccp_update_formatted_billing_address_order( $billing_fields, $order ) {

        // get address replacement
        $replacement = ywccp_get_address_replacement( 'billing', $order );

		return array_merge( $billing_fields, $replacement );
	}
}

if( ! function_exists( 'ywccp_update_formatted_shipping_address_order' ) ) {
	/**
	 * Adds field to formatted address for order's admin view
	 *
	 * @access public
	 *
	 * @param $shipping_fields array Array of fields to be used in formatted address
	 * @param \WC_Order Order object
	 *
	 * @return array Array of filtered fields
	 * @since 1.0.0
	 */
	function ywccp_update_formatted_shipping_address_order( $shipping_fields, $order ) {

        // get address replacement
        $replacement = ywccp_get_address_replacement( 'shipping', $order );

        return array_merge( $shipping_fields, $replacement );
	}
}

if( ! function_exists( 'ywccp_customer_get_billing' ) ) {
	/**
	 * Filter customer billing address
	 *
	 * @since 1.1.0
	 * @author Francesco Licandro
	 * @param array $value
	 * @param object $customer \WC_Customer
	 * @return array
	 */
	function ywccp_customer_get_billing( $value, $customer ) {
		return ywccp_customer_get_address( $value, $customer );
	}
}

if( ! function_exists( 'ywccp_customer_get_shipping' ) ) {
	/**
	 * Filter customer shipping address
	 *
	 * @since 1.1.0
	 * @author Francesco Licandro
	 * @param array $value
	 * @param object $customer \WC_Customer
	 * @return array
	 */
	function ywccp_customer_get_shipping( $value, $customer ) {
		return ywccp_customer_get_address( $value, $customer, 'shipping' );
	}
}

if( ! function_exists( 'ywccp_update_address_replacement' ) ) {
	/**
	 * Update address replacement for all site address formats
	 *
	 * @access public
	 *
	 * @param $replacements array Array of available replacements
	 * @param $args array Array of arguments to use in replacements
	 *
	 * @return array Filtered array of replacements
	 * @since 1.0.0
	 */
	function ywccp_update_address_replacement( $replacements, $args ) {

		$fields = ywccp_get_fields_localisation_address_formats( 'all', true );

		if( empty( $fields ) ) {
			return $replacements;
		}

		foreach ( (array) $fields as $value ) {
			if( isset( $replacements['{'.$value.'}'] ) ) {
				continue;
			}
			$replacements['{'.$value.'}'] = isset( $args[$value] ) ? $args[$value] : '';
		}

		return $replacements;
	}
}

if( ! function_exists( 'ywccp_add_additional_fields' ) ) {
	/**
	 * Add additional fields to checkout form
	 *
	 * @author Francesco Licandro
	 * @since 1.0.0
	 * @param $fields
	 * @return array
	 */
	function ywccp_add_additional_fields( $fields ) {

		$fields_new = ywccp_get_checkout_fields( 'additional' );

		if ( empty( $fields_new ) || ! isset( $fields['order'] ) ) {
			return $fields;
		}
		// remove disabled
		foreach ( $fields_new as $key => &$value ) {
			if ( isset( $value['enabled'] ) && ! $value['enabled'] ) {
				unset( $fields_new[ $key ] );
			}
		}

		$fields['order'] = $fields_new;

		return $fields;
	}
}

if( ! function_exists( 'ywccp_add_additional_fields_meta' ) ) {
	/**
	 * Add order meta for additional fields
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param mixed $order \ WC_Order or order id
	 * @param array $posted
	 */
	function ywccp_add_additional_fields_meta( $order, $posted ) {

		// get additional fields key
		$fields         = ywccp_get_checkout_fields( 'additional' );
		$default_keys   = ywccp_get_default_fields_key( 'additional' );
		! $order instanceof WC_Order && $order = wc_get_order( $order );

		foreach ( $fields as $key => $field ) {
			if( in_array( $key, $default_keys ) || empty( $posted[$key] ) ){
				continue;
			}

			if( ywccp_is_2_7() ) {
				yit_set_prop( $order, $key, $posted[$key] );
			}
			else {
				update_post_meta( $order->id, $key, $posted[$key] );
			}
		}
	}
}

if( ! function_exists( 'ywccp_email_additional_fields_list' ) ) {
	/**
	 * Add the additional fields list on order email
	 *
	 * @since 1.0.0
	 * @author Francesco Licandro
	 * @param object $order
	 * @param boolean $sent_to_admin
	 * @param boolean $plain_text
	 * @param $email
	 */
	function ywccp_email_additional_fields_list( $order, $sent_to_admin, $plain_text, $email = false ) {

		$fields = ywccp_get_custom_fields( 'additional' );

		// build template content
		$content = array();
		foreach ( $fields as $key => $field ) {
			// check if value exists for order
			//$value = get_post_meta( $order->id, $key, true );
			$value = yit_get_prop( $order, $key, true );

			if( $value && $field['show_in_email'] ) {
				$content[$key] = array(
					'label' => $field['label'],
					'value' => isset( $field['options'][$value] ) ? $field['options'][$value] : $value
				);
			}
		}
		
		if( empty( $content ) ) {
			return;
		}

		if( $plain_text ){
			wc_get_template( 'ywccp-additional-fields-list.php', array( 'fields' => $content ), '', YWCCP_TEMPLATE_PATH . '/email/plain/' );
		}
		else {
			wc_get_template( 'ywccp-additional-fields-list.php', array( 'fields' => $content ), '', YWCCP_TEMPLATE_PATH . '/email/' );
		}
	}
}

if( ! function_exists( 'ywccp_customer_order_csv_export_order_headers' ) ) {
	/**
	 * Add headers for customer order csv export plugins
	 *
	 * @since 1.0.3
	 * @author Francesco Licandro
	 * @param array $headers
	 * @param object $class WC_Customer_Order_CSV_Export_Generator
	 * @return array
	 */
	function ywccp_customer_order_csv_export_order_headers( $headers, $class ) {
		
		$custom_fields = ywccp_get_all_custom_fields();
		$csv_format = get_option( 'wc_customer_order_csv_export_order_format' );
		$use_label = ! in_array( $csv_format, array( 'legacy_import', 'import', 'default', 'default_one_row_per_item' ) );
		$new_headers = array();

		foreach ( $headers as $key => $value ){

			if( $key == 'billing_country' ) {
				foreach ( $custom_fields['billing'] as $key_custom => $value_custom ){
					$new_headers[ $key_custom ] = ( $use_label && ! empty( $value_custom['label'] ) ) ? 'Billing ' . $value_custom['label'] : $key_custom;
				}
			}
			elseif( $key == 'shipping_country' ) {
				foreach ( $custom_fields['shipping'] as $key_custom => $value_custom ){
					$new_headers[ $key_custom ] = ( $use_label && ! empty( $value_custom['label'] ) ) ? 'Shipping ' . $value_custom['label'] : $key_custom;
				}
			}
			elseif( $key == 'customer_note' ) {
				foreach ( $custom_fields['additional'] as $key_custom => $value_custom ){
					$new_headers[ $key_custom ] = ( $use_label && ! empty( $value_custom['label'] ) ) ? $value_custom['label'] : $key_custom;
				}
			}

			$new_headers[ $key ] = $value;
		}

		return $new_headers;
	}
}

if( ! function_exists( 'ywccp_customer_order_csv_export_order_row' ) ) {
	/**
	 * Modify order row for CSV export
	 * 
	 * @since 1.0.3
	 * @author Francesco Licandro
	 * @param array $order_data
	 * @param object $order
	 * @param object $class WC_Customer_Order_CSV_Export_Generator
	 * @return array
	 */
	function ywccp_customer_order_csv_export_order_row( $order_data, $order, $class ) {
		$custom_fields = ywccp_get_all_custom_fields();

		foreach ( $custom_fields as $section => $fields ){
			foreach ( $fields as $key => $options ) {
				$meta_key = ( $section == 'additional' ) ? $key : '_'.$key;
				//$order_data[ $key ] = get_post_meta( $order->id, $meta_key, true );
				$order_data[ $key ] = yit_get_prop( $order, $meta_key, true );
			}
		}

		return $order_data;
	}
}

if( ! function_exists( 'ywccp_filter_wpml_strings' ) ) {
	/**
	 * Filter strings for WPML
	 * 
	 * @since 1.0.0
	 * @param $fields
	 * @return array
	 * @author Francesco Licandro
	 */
	function ywccp_filter_wpml_strings( $fields ){

		foreach( $fields as $section => &$field ) {
			if( $section == 'account' ) {
				continue;
			}
			foreach( $field as $key => &$single ) {
				$single = ywccp_field_filter_wpml_strings( $key, $single );
			}
		}
		
		return $fields;
	}
}