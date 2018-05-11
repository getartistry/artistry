<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "multiinput".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'multiinput', 'multiinput_settings_field' );
	add_action( 'admin_enqueue_scripts', 'framework_multiinput_admin_styles' );
}

/**
 * Function Name: framework_multiinput_admin_styles.
 * Function Description: framework multiinput admin styles.
 *
 * @param string $hook string parameter.
 */
function framework_multiinput_admin_styles( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );
	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-multiinput-script', plugins_url( 'multiinput.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'cp-multiinput-style', plugins_url( 'multiinput.css', __FILE__ ) );
	}
}

/**
 * Function Name: cp_v2_txt_link_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function multiinput_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$options    = isset( $settings['options'] ) ? $settings['options'] : '';

	$suffix    = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
	$min       = isset( $settings['min'] ) ? $settings['min'] : '';
	$max       = isset( $settings['max'] ) ? $settings['max'] : '';
	$map_style = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';

	$output = '<div class="cp-multiinput-container" data-id="' . $input_name . '" data-units="' . $suffix . '">
					<div class="cp-fields-param-units">
						<span class="cp-units cp-unit-px">' . __( 'PX', 'convertpro' ) . '</span>
						<span class="cp-units cp-unit-em">' . __( 'EM', 'convertpro' ) . '</span>
						<span class="cp-units cp-unit-per">' . __( '%', 'convertpro' ) . '</span>
					</div>
					<input type="hidden" data-type="multiinput" data-mapstyle="' . htmlspecialchars( $map_style ) . '" name="' . $input_name . '" id="' . $input_name . '" value="' . $value . '" min="' . $min . '" max="' . $max . '" class="form-control cp-input cp-select ' . $input_name . ' ' . $type . ' "  for="" data-type="' . $type . '" />
					<div>
						<div class="cp-multiinput-param-field-wrap">
							<input type="number" class="cp-multiinput-param-fields multiinput-top" min="0" data-multiinput="0" />
							<span>' . __( 'TOP', 'convertpro' ) . '</span>
						</div><div class="cp-multiinput-param-field-wrap">
							<input type="number" class="cp-multiinput-param-fields multiinput-right" min="0" data-multiinput="1" />
							<span>' . __( 'RIGHT', 'convertpro' ) . '</span>
						</div><div class="cp-multiinput-param-field-wrap">
							<input type="number" class="cp-multiinput-param-fields multiinput-bottom" min="0" data-multiinput="2" />
							<span>' . __( 'BOTTOM', 'convertpro' ) . '</span>
						</div><div class="cp-multiinput-param-field-wrap">
							<input type="number" class="cp-multiinput-param-fields multiinput-left" min="0" data-multiinput="3" />
							<span>' . __( 'LEFT', 'convertpro' ) . '</span>
						</div><div class="cp-multiinput-param-field-wrap">
							<button class="cp-multiinput-toggle">
								<span class="cp-multiinput-linked"><i class="dashicons dashicons-admin-links"></i></span>
								<span class="cp-multiinput-unlinked"><i class="dashicons dashicons-editor-unlink"></i></span>
							</button>
						</div>
					</div>
					</div></p>';
	return $output;
}
