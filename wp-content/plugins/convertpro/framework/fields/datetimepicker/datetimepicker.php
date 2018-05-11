<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "datetimepicker".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'datetimepicker', 'cp_v2_datetime_picker_settings_field' );
}

add_action( 'admin_enqueue_scripts', 'cp_v2_framework_datepicker_admin_styles' );

/**
 * Function Name: cp_v2_framework_datepicker_admin_styles.
 * Function Description: cp_v2_framework_datepicker_admin_styles.
 *
 * @param string $hook string parameter.
 */
function cp_v2_framework_datepicker_admin_styles( $hook ) {

	$cp_page  = strpos( $hook, CP_PRO_SLUG );
	$dev_mode = get_option( 'cp_dev_mode' );
	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-datetime-script', plugins_url( 'datetimepicker.js', __FILE__ ), array( 'cp-datetimepicker-script' ), '1.0.0', true );
	}

}

/**
 * Function Name: cp_v2_datetime_picker_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_datetime_picker_settings_field( $name, $settings, $value ) {
	$input_name        = $name;
	$type              = isset( $settings['type'] ) ? $settings['type'] : '';
	$class             = isset( $settings['class'] ) ? $settings['class'] : '';
	$timezone_settings = get_option( 'convert_plug_settings' );
	$date              = current_time( 'm/d/Y h:i A' );

	$output  = '';
	$output .= ' <input type="hidden" id="cp_currenttime" class="form-control cp_currenttime" value="' . esc_attr( $date ) . '" />';
	$output .= ' <input type="hidden" id="cp_timezone_name" class="form-control cp_timezone" value="wordpress" />';

	$output .= '<div class="form-group cp-datetime-picker">
                    <div class="input-group date" id="' . $input_name . '">
                      <input type="text" id="cp_' . $input_name . '" data-default-date="' . $value . '"  name="' . $input_name . '"  class="form-control cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '"  value="' . $value . '" />
                      <span class="input-group-addon"><span class="dashicons dashicons-clock"></span></span> </div>
                </div>';
	return $output;
}

