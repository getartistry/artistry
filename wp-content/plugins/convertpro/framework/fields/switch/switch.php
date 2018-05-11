<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "switch".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'switch', 'cp_v2_switch_settings_field' );
}

/**
 * Function Name: cp_v2_switch_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_switch_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$on         = isset( $settings['on'] ) ? $settings['on'] : 'ON';
	$off        = isset( $settings['off'] ) ? $settings['off'] : 'OFF';
	$checked    = ( $value ) ? 'checked="checked"' : '';
	$uniq       = uniqid();
	$map_style  = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';
	$output     = '<div class="cp-switch-wrapper">';

	$output .= '<input type="text" data-type="switch" data-mapstyle="' . htmlspecialchars( $map_style ) . '" id="cp_' . $input_name . '" class="form-control cp-input cp-switch-input ' . $class . '" name="' . $input_name . '" value="' . $value . '" />';
	$output .= '<input type="checkbox" ' . $checked . ' id="cp_' . $input_name . '_btn_' . $uniq . '" class="ios-toggle cp-switch-input switch-checkbox cp-' . $type . ' ' . $class . '" value="' . $value . '"   >';
	$output .= '<label class="cp-switch-btn checkbox-label" data-on="' . $on . '"  data-off="' . $off . '" data-id="cp_' . $input_name . '" for="cp_' . $input_name . '_btn_' . $uniq . '">
				</label>';
	$output .= '</div>';
	return $output;
}
