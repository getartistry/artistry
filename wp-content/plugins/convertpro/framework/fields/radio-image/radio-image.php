<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "radio-image".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'radio-image', 'cp_v2_radio_image_settings_field' );
}

add_action( 'admin_enqueue_scripts', 'cp_v2_radio_image_scripts' );

/**
 * Function Name: cp_v2_radio_image_scripts.
 * Function Description: cp_v2_radio_image_scripts.
 *
 * @param string $hook string parameter.
 */
function cp_v2_radio_image_scripts( $hook ) {
	$cp_page  = strpos( $hook, CP_PRO_SLUG );
	$dev_mode = get_option( 'cp_dev_mode' );

	if ( false !== $cp_page && '1' == $dev_mode ) {
		wp_enqueue_style( 'cp-radio-image', plugins_url( 'radio-image.css', __FILE__ ) );
		wp_enqueue_script( 'cp-radio-image', plugins_url( 'radio-image.js', __FILE__ ), array(), '1.0.0', true );
	}
}

/**
 * Function Name: cp_v2_radio_image_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_radio_image_settings_field( $name, $settings, $value ) {
	$input_name  = $name;
	$type        = isset( $settings['type'] ) ? $settings['type'] : '';
	$class       = isset( $settings['class'] ) ? $settings['class'] : '';
	$options     = isset( $settings['options'] ) ? $settings['options'] : '';
	$max_width   = isset( $settings['width'] ) ? $settings['width'] : '';
	$image_title = isset( $settings['imagetitle'] ) ? $settings['imagetitle'] : '';

	$output    = '';
	$n         = 0;
	$img_title = '';

	foreach ( $options as $key => $img ) {
		$checked = '';
		$cls     = '';
		if ( '' !== $value && (string) $key === (string) $value ) {
			$checked = ' checked="checked"';
			$cls     = 'selected';
		}
		if ( '' !== $image_title ) {
			$description = $image_title[ "title-$n" ];
			$img_title   = 'title = "' . $description . '"';
		}
		$output .= '<div class="cp-radio-image-holder ' . $cls . '">';

		$output .= '<input type="radio" name="' . $input_name . '" value="' . $key . '" data-id="cp_' . $input_name . '" class="form-control cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . '" ' . $checked . '> <label for="cp_' . $key . '_' . $n . '" class="cp-radio-control"><img style="max-width: ' . $max_width . ';" class="cp-radio-control ' . $input_name . '-' . $key . '" src="' . $img . '" ' . $img_title . '/></label>';

		$output .= '</div>';
		$n++;
	}
	return '<div class="cp-radio-image-wrapper">' . $output . '</div>';
}
