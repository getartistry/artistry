<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "dropdown".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'font', 'font_settings_field' );
	add_action( 'admin_enqueue_scripts', 'framework_font_admin_scripts' );
}

/**
 * Function Name: framework_font_admin_scripts.
 * Function Description: framework_font_admin_scripts.
 *
 * @param string $hook string parameter.
 */
function framework_font_admin_scripts( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );
	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-font-script', CP_FRAMEWORK_URI . '/fields/font/font.js', array(), '1.0.0', true );
	}
}

/**
 * Function Name: font_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function font_settings_field( $name, $settings, $value ) {
	$input_name       = $name;
	$type             = isset( $settings['type'] ) ? $settings['type'] : '';
	$class            = isset( $settings['class'] ) ? $settings['class'] : '';
	$font_weights_arr = '';
	$map_style        = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';

	$font_options = cp_Framework::$fonts;

	$font_values     = explode( ':', $value );
	$sel_font_family = $font_values[0];
	$sel_font_weight = isset( $font_values[1] ) ? $font_values[1] : '';

	$output = '<input data-mapstyle="' . htmlspecialchars( $map_style ) . '" type="hidden" id="cp_' . $input_name . '" name="' . $input_name . '" class="cp-input" value="' . $value . '" >';

	$output .= '<div class="cp-field-font">';

	$output .= '<select for="' . $input_name . '"  class="form-control  cp-font-param ' . $input_name . ' ' . $type . '" >';

	foreach ( $font_options as $key => $font ) {
		$output .= "<optgroup label='" . $key . "'>";

		foreach ( $font as $font_family => $font_weights ) {

			if ( 'inherit' == $font_family ) {
				$font_family_label = 'Inherit from Global Settings';
			} else {
				$font_family_label = $font_family;
			}

			$selected = $sel_font_family == $font_family ? 'selected=selected' : '';
			$output  .= "<option value='" . $font_family . "' " . $selected . " data-weight='" . implode( ',', $font_weights ) . "'>" . ucfirst( $font_family_label ) . '</option>';

			if ( '' !== $selected ) {
				$font_weights_arr = $font_weights;
			}
		}

		$output .= '</optgroup>';
	}

	$output .= '</select>';

	$output .= '<select for="' . $input_name . '" class="cp-font-weights">';

	if ( '' !== $font_weights_arr ) {
		foreach ( $font_weights_arr as $weight ) {
			$selected = $sel_font_weight == $weight ? 'selected=selected' : '';

			if ( 'Inherit' == $weight ) {
				$weight_label = 'Inherit';
			} else {
				$weight_label = $weight;
			}

			$output .= '<option ' . $selected . " value='" . $weight . "'>" . $weight_label . '</option>';
		}
	}

	$output .= '</select>';

	$output .= '</div>';

	return $output;
}
