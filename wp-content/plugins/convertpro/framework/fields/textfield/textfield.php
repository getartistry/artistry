<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "textfield".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'textfield', 'cp_v2_textfield_settings_field' );
}

/**
 * Function Name: cp_v2_textfield_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_textfield_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$value      = htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$map_style  = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';

	$output = '<p><input type="text" data-type="text" data-mapstyle="' . htmlspecialchars( $map_style ) . '"  id="cp_' . $input_name . '" class="form-control cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" value="' . $value . '" /></p>';
	return $output;
}
