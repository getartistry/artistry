<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "number".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'number', 'cp_v2_number_settings_field' );
}

/**
 * Function Name: cp_v2_number_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_number_settings_field( $name, $settings, $value ) {
	$input_name     = $name;
	$default        = isset( $value ) ? $value : '';
	$default_values = isset( $settings['default_value'] ) ? $settings['default_value'] : array( 700, 320 );

	// If user set value larger than default max value then it will override and set max to user defined value.
	$max     = isset( $settings['max'] ) ? $settings['max'] : '';
	$min     = isset( $settings['min'] ) ? $settings['min'] : '';
	$m_value = '';
	$m_max   = '';
	$d_max   = '';

	if ( is_array( $max ) ) {
		if ( is_array( $value ) && $value[0] > $max[0] ) {
			$max = $value;
		}
	}

	if ( is_array( $default ) ) {
		$value   = $default[0];
		$m_value = $default[1];
	} else {
		$value   = $default;
		$m_value = $default;
	}

	if ( is_array( $max ) ) {
		$d_max = $max[0];
		$m_max = $max[1];
	} else {
		$d_max = $max;
		$m_max = $max;
	}

	$type      = isset( $settings['type'] ) ? $settings['type'] : '';
	$class     = isset( $settings['class'] ) ? $settings['class'] : '';
	$map_style = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';
	$suffix    = isset( $settings['suffix'] ) ? $settings['suffix'] : '';

	$output = '<div class="cp-number-field"><input type="number" data-default-val="' . json_encode( $default_values ) . '"  data-mapstyle="' . htmlspecialchars( $map_style ) . '"  data-mobile-max="' . $m_max . '" id="cp_' . $input_name . '" class="form-control cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" value="' . $value . '" data-mobile-max="' . $m_max . '" data-min="' . $min . '" data-max="' . $d_max . '" data-unit="' . $suffix . '" min="' . $min . '" max="' . $d_max . '" data-type="number" data-default="' . $m_value . '" /><label class="align-right  for="' . $input_name . '">' . $suffix . '</label></div>';
	return $output;
}
