<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'txt-link', 'cp_v2_txt_link_settings_field' );
}

/**
 * Function Name: cp_v2_txt_link_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_txt_link_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$link       = isset( $settings['link'] ) ? $settings['link'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$output     = '<div class="link-title ' . $class . '">';
	$output    .= $link;
	$output    .= '</div>';
	return $output;
}
