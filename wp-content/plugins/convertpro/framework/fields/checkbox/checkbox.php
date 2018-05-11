<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "checkbox".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'checkbox', 'cp_v2_checkbox_settings_field' );
}

add_action( 'admin_enqueue_scripts', 'cp_v2_framework_checkbox_admin_styles' );

/**
 * Function Name: cp_v2_framework_checkbox_admin_styles.
 * Function Description: cp_v2_framework_checkbox_admin_styles.
 *
 * @param string $hook string parameter.
 */
function cp_v2_framework_checkbox_admin_styles( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );

	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-checkbox-script', CP_FRAMEWORK_URI . '/fields/checkbox/cp-checkbox.min.js', array(), '1.0.0', true );
	}
}
/**
 * Function Name: cp_v2_checkbox_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_checkbox_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$options    = isset( $settings['options'] ) ? $settings['options'] : '';
	$output     = '';
	$n          = 0;
	$values     = explode( '|', $value );
	$output    .= '<p><input type="hidden" name="' . $input_name . '" value="' . $value . '" id="cp_' . $input_name . '" class="form-control cp-input cp-' . $type . ' ' . $input_name . '"></p>';
	foreach ( $options as $text_val => $val ) {
		if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
			$text_val = $val;
		}
		$checked = '';
		if ( '' !== $value && in_array( $val, $values ) ) {
			$checked = ' checked="checked"';
		}
		$output .= '<div class="checkbox">
    				<p><label><input type="checkbox" value="' . $val . '" id="cp_' . $input_name . '_' . $n . '" class="cp-' . $type . ' cp_' . $input_name . '" ' . $checked . ' name ="cp_' . $input_name . '">' . $text_val . '</label></p></div>';
		$n++;
	}
	return $output;
}

