<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "textfield".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'cp_checkbox', 'cp_v2_field_checkbox_settings' );
}

/**
 * Function Name: cp_v2_field_checkbox_settings.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $sections string parameter.
 * @param string $value string parameter.
 * @param string $default_value string parameter.
 */
function cp_v2_field_checkbox_settings( $name, $settings, $sections, $value, $default_value ) {
	$data_json = array(
		'id'         => $name,
		'title'      => $settings['title'],
		'sections'   => $sections,
		'resize'     => $settings['resize'],
		'has_editor' => isset( $settings['editor'] ) ? true : false,
	);

	$data = json_encode( $data_json );

	$input_name = $name;

	$output  = "<div class='fields-panel'>";
	$output .= "<div class='cp-droppable-item list-group-item draggable' data-type='cp_checkbox' data-title='" . $settings['title'] . "' data-value='" . $settings['value'] . "' data-json='" . $data . "' data-resize='" . $settings['resize'] . "'>
        <div class='cp-panel-content-icon'><i class='dashicons dashicons-yes'></i></div>
        <div class='cp-element-title-wrapper'><span class='cp-element-title'>" . __( 'Check Box', 'convertpro' ) . "</span></div>
        <input type='checkbox' style='display:none;' class='cp-customizer-target cp-checkbox-field' />
    </div>";
	$output .= '</div>';

	return $output;
}
