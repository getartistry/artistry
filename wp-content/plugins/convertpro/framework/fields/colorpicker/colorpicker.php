<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "colorpicker".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'colorpicker', 'cp_v2_color_picker_settings_field' );
}
add_action( 'admin_enqueue_scripts', 'cp_v2_framework_color_picker_admin_styles' );

/**
 * Function Name: cp_v2_framework_color_picker_admin_styles.
 * Function Description: cp_v2_framework_color_picker_admin_styles.
 *
 * @param string $hook string parameter.
 */
function cp_v2_framework_color_picker_admin_styles( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );

	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-colorpicker-script', CP_FRAMEWORK_URI . '/fields/colorpicker/cp-color-picker.min.js', array(), '1.0.0', true );
		wp_enqueue_style( 'cp-colorpicker-style', CP_FRAMEWORK_URI . '/fields/colorpicker/cp-color-picker.min.css' );
	}
}

/**
 * Function Name: cp_v2_color_picker_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_color_picker_settings_field( $name, $settings, $value ) {
	$input_name    = $name;
	$type          = isset( $settings['type'] ) ? $settings['type'] : '';
	$class         = isset( $settings['class'] ) ? $settings['class'] : '';
	$map_style     = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';
	$default_color = isset( $settings['default'] ) ? $settings['default'] : '#fff';

	$output = '<p><input type="text" data-type="colorpicker" data-mapstyle="' . htmlspecialchars( $map_style ) . '" id="cp_' . $input_name . '" data-default-color="' . $default_color . '" class="cs-wp-color-picker cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" value="' . $value . '" />
		<div class="colorpicker-spacing"></div></p>';
	return $output;
}
