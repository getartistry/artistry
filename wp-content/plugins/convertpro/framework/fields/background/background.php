<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "background".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'background', 'cp_v2_background_settings_field' );
}

add_action( 'admin_enqueue_scripts', 'framework_background_admin_styles' );

/**
 * Function Name: framework_background_admin_styles.
 * Function Description: framework_background_admin_styles.
 *
 * @param string $hook string parameter.
 */
function framework_background_admin_styles( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );
	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-background-script', CP_FRAMEWORK_URI . '/fields/background/cp-background.min.js', array(), '1.0.0', true );
	}
}

/**
 * Function Name: cp_v2_background_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_background_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$options    = isset( $settings['options'] ) ? $settings['options'] : '';

	$bg         = explode( '|', $value );
	$repeat_val = isset( $bg[0] ) ? $bg[0] : '';
	$pos_val    = isset( $bg[1] ) ? $bg[1] : '';
	$size_val   = isset( $bg[2] ) ? $bg[2] : '';

	$background_repeat = array(
		__( 'Repeat', 'convertpro' )    => 'repeat',
		__( 'No Repeat', 'convertpro' ) => 'no-repeat',
		__( 'X Repeat', 'convertpro' )  => 'repeat-x',
		__( 'Y Repeat', 'convertpro' )  => 'repeat-y',
	);

	$background_position = array(
		__( 'Center', 'convertpro' ) => 'center',
		__( 'Left', 'convertpro' )   => 'left',
		__( 'Right', 'convertpro' )  => 'right',
	);

	$background_size = array(
		__( 'Contain', 'convertpro' ) => 'contain',
		__( 'Cover', 'convertpro' )   => 'cover',
		__( 'Default', 'convertpro' ) => 'auto',
	);

	$output    = '';
	$map_style = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';

	// Background input field.
	$output = '<input type="hidden" id="cp_' . $input_name . '" data-mapstyle="' . htmlspecialchars( $map_style ) . '" class="form-control cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" data-type="bg_properties" value="' . $value . '" />';

	$output .= '<div class="cp-bgimage-container">';

	// Background Repeat.
	$output .= '<div class="cp-bgimage-wrap"><label for="cp_bg_repeat">' . __( 'Background Repeat', 'convertpro' ) . '</label>';
	$bg_rpt  = 'rpt';
	$output .= '<p><select id="cp_' . $input_name . '_' . $bg_rpt . '" class="cp_' . $input_name . ' cp-input" >';
	foreach ( $background_repeat as $title => $val ) {
		$selected = ( $val == $repeat_val ) ? "selected='selected'" : '';
		$output  .= '<option value="' . $val . '" ' . $selected . '>' . $title . '</option>';
	}
	$output .= '</select></p></div>';

	// Background Position.
	$output .= '<div class="cp-bgimage-wrap"><label for="cp_' . $input_name . '">' . __( 'Background Position', 'convertpro' ) . '</label>';
	$bg_pos  = 'pos';
	$output .= '<p><select id="cp_' . $input_name . '_' . $bg_pos . '" class="cp_' . $input_name . ' cp-input" >';
	foreach ( $background_position as $title => $val ) {
		$selected = ( $val == $pos_val ) ? "selected='selected'" : '';
		$output  .= '<option value="' . $val . '" ' . $selected . '>' . $title . '</option>';
	}
	$output .= '</select></p></div>';

	// Background Size.
	$bg_size = 'size';
	$output .= '<div class="cp-bgimage-wrap"><label for="cp_' . $input_name . '_' . $bg_size . '">' . __( 'Background Size', 'convertpro' ) . '</label>';
	$output .= '<p><select id="cp_' . $input_name . '_' . $bg_size . '" class="cp_' . $input_name . ' cp-input" >';
	foreach ( $background_size as $title => $val ) {
		$selected = ( $val == $size_val ) ? "selected='selected'" : '';
		$output  .= '<option value="' . $val . '" ' . $selected . '>' . $title . '</option>';
	}
	$output .= '</select></p></div>';
	$output .= '</div>';

	return $output;
}

