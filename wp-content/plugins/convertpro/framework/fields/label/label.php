<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "section".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'label', 'cp_v2_label_settings_field' );
}

/**
 * Function Name: cp_v2_label_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_label_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$title      = isset( $settings['label'] ) ? $settings['label'] : '';
	$output     = '<div class="cp-edit-param-title cp-label-wrapper">';
	$output    .= '<label class="cp-edit-param-label">';
	$output    .= $title;
	$output    .= '</label>';
	$output    .= '</div>';
	return $output;
}
