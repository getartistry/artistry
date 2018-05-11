<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "number".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'text-align', 'cp_v2_textalign_settings_field' );
	add_action( 'admin_enqueue_scripts', 'cp_v2_textalign_admin_scripts' );
}

/**
 * Function Name: cp_v2_textalign_admin_scripts.
 * Function Description: cp_v2_textalign_admin_scripts.
 *
 * @param string $hook string parameter.
 */
function cp_v2_textalign_admin_scripts( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );
	wp_enqueue_script( 'jquery' );
	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-text-align-script', plugins_url( 'text-align.js', __FILE__ ), array(), '1.0.0', true );
		wp_enqueue_style( 'cp-text-align-style', plugins_url( 'text-align.css', __FILE__ ) );
	}
}

/**
 * Function Name: cp_v2_textalign_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_textalign_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$value      = htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$options    = isset( $settings['options'] ) ? $settings['options'] : '';
	$map_style  = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';

	$output = '<input type="hidden" data-mapstyle="' . htmlspecialchars( $map_style ) . '" name="' . $input_name . '" id="' . $input_name . '" value="' . $value . '" class="' . $class . ' cp-input cp-text-align-field" data-type="' . $type . '" />
		<div class="cp-text-align-field-container">';
	foreach ( $options as $key => $val ) {
		if ( 'justify' != $val ) {
				$dash_value = 'align' . $val;
		} else {
			$dash_value = $val;
		}

		if ( $key == $value ) {
			$output .= '<div class="cp-text-align-holder-field selected-text" >';
			$output .= '<input type="radio" value="' . $val . '" data-id="cp_' . $name . '" class="form-control cp-input cp-text_align ' . $name . '" checked= "checked" >';
			$output .= '<label class="cp-radio-control-field"><span class="cp-radio-control-field ' . $name . '-' . $key . '"><i class="dashicons dashicons-editor-' . $dash_value . '"></i></span></label>';
			$output .= '</div>';
		} else {
			$output .= '<div class="cp-text-align-holder-field" >';
			$output .= '<input type="radio" value="' . $val . '" data-id="cp_' . $name . '" class="form-control cp-input cp-text_align ' . $name . '" >';
			$output .= '<label class="cp-radio-control-field"><span class="cp-radio-control-field ' . $name . '-' . $key . '"><i class="dashicons dashicons-editor-' . $dash_value . '"></i></span></label>';
			$output .= '</div>';
		}
	}

	$output .= '</div>';
	return $output;
}
