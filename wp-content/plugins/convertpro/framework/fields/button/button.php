<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "textarea".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'button', 'cp_v2_button_settings_field' );
}

/**
 * Function Name: cp_v2_button_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_button_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$output     = '<p><button id="cp_' . $input_name . '" class="form-control cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" onclick="javascript:void(0)">' . stripslashes( $value ) . '</button></p>';
	return $output;
}
