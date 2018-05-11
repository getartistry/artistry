<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "slider".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'slider', 'cp_v2_slider_settings_field' );
}

add_action( 'admin_enqueue_scripts', 'cp_v2_slider_admin_scripts' );

/**
 * Function Name: cp_v2_slider_admin_scripts.
 * Function Description: cp_v2_slider_admin_scripts.
 *
 * @param string $hook string parameter.
 */
function cp_v2_slider_admin_scripts( $hook ) {
	$dev_mode   = get_option( 'cp_dev_mode' );
	$is_cp_page = strpos( $hook, 'convetplug-v2' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-slider' );
	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-slider', plugins_url( 'slider.js', __FILE__ ), array(), '1.0.0', true );
		wp_enqueue_style( 'cp-jquery-ui', plugins_url( 'jquery-ui.css', __FILE__ ) );
		wp_enqueue_style( 'cp-slider', plugins_url( 'slider.css', __FILE__ ) );
	}
}

/**
 * Function Name: cp_v2_slider_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_slider_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$min        = isset( $settings['min'] ) ? $settings['min'] : '';
	$default    = isset( $settings['value'] ) ? $settings['value'] : '';
	$step       = isset( $settings['step'] ) ? $settings['step'] : '';
	$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : 'px';

	// If user set value larger than default max value then it will override and set max to user defined value.
	$max = isset( $settings['max'] ) ? $settings['max'] : '';

	if ( isset( $settings['description'] ) && '' !== $settings['description'] ) {
		$tooltip_class = 'with-tooltip';
	} else {
		$tooltip_class = '';
	}

	$map_style = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';

	$uid     = uniqid();
	$output  = '<div class="cp-setting-block cp-slider-block">';
	$output .= '<div class="slider-input ' . $tooltip_class . '"><input data-type="slider" data-mapstyle="' . htmlspecialchars( $map_style ) . '" id="cp_' . $input_name . '_' . $uid . '" type="number"  step="' . $step . '" class="form-control cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" value="' . $value . '" data-max="' . $max . '" data-min="' . $min . '" min="' . $min . '" max="' . $max . '" data-unit="' . $suffix . '" data-step="' . $step . '" >';
	$output .= '<label class="align-right slider-label ' . $tooltip_class . '" for="' . $input_name . '">' . $suffix . '</label>';
	$output .= '</div>';
	$output .= '<div id="slider_' . $input_name . '_' . $uid . '" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ' . $input_name . ' ' . $type . ' ' . $class . '"><a class="ui-slider-handle ui-state-default" href="#"></a><span class="range-quantity" ></span></div></div>';
	return $output;
}
