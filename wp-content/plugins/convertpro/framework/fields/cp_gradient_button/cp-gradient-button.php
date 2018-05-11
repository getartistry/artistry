<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add presets to field.
require_once( 'presets.php' );

// Add new input type "textfield".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'cp_gradient_button', 'cp_gradient_button_settings_field' );
}

/**
 * Function Name: cp_gradient_button_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $sections string parameter.
 * @param string $value string parameter.
 * @param string $default_value string parameter.
 */
function cp_gradient_button_settings_field( $name, $settings, $sections, $value, $default_value ) {
	$data_json = array(
		'id'         => $name,
		'title'      => $settings['title'],
		'sections'   => $sections,
		'resize'     => $settings['resize'],
		'has_editor' => isset( $settings['editor'] ) ? true : false,
		'presets'    => $settings['presets'],
	);

	$tags = isset( $settings['tags'] ) ? $settings['tags'] : false;

	$data = json_encode( $data_json );

	$input_name = $name;

	$output = "<div class='fields-panel'>
    <div class='list-group-item draggable cp-hidden' data-type='cp_gradient_button' data-value='" . $settings['value'] . "' data-json='" . $data . "' data-resize='" . $settings['resize'] . "'><span class='dashicons dashicons-edit cp-hidden'></span>" . __( 'Button', 'convertpro' ) . '</div>';
	if ( isset( $settings['presets'] ) ) {
		$output .= cp_render_presets( 'cp_gradient_button', $settings['title'], $settings['presets'], $tags, $settings['resize'] );
	}

	$output .= '</div>';

	return $output;
}
