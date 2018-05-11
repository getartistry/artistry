<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "radio".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'radio', 'cp_v2_radio_button_settings_field' );
}

/**
 * Function Name: cp_v2_txt_link_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_radio_button_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$options    = isset( $settings['options'] ) ? $settings['options'] : '';
	$output     = '';
	$n          = 0;
	foreach ( $options as $text_val => $val ) {
		if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
			$text_val = $val;
		}
		$text_val = esc_attr( $text_val );
		$checked  = '';
		if ( '' !== $value && (string) $val === (string) $value ) {
			$checked = ' checked="checked"';
		}
		$output .= '<input type="radio" name="' . $input_name . '" value="' . $val . '" id="cp_' . $input_name . '_' . $n . '" class="form-control cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . '" ' . $checked . '> <label for="cp_' . $input_name . '_' . $n . '">' . $text_val . '</label>';
		$n++;
	}
	return $output;
}
