<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "textfield".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'cp_close_text', 'cp_close_text_settings_field' );
}

/**
 * Function Name: cp_close_text_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $sections string parameter.
 * @param string $value string parameter.
 * @param string $default_value string parameter.
 */
function cp_close_text_settings_field( $name, $settings, $sections, $value, $default_value ) {
	$data_json = array(
		'id'       => $name,
		'title'    => $settings['title'],
		'sections' => $sections,
		'resize'   => $settings['resize'],
	);

	$data = json_encode( $data_json );

	$input_name = $name;

	$output = "<div class='fields-panel'>
    <div class='cp-droppable-item list-group-item draggable' data-type='cp_close_text' data-title='" . $settings['title'] . "' data-value='" . $settings['value'] . "' data-json='" . $data . "' data-resize='" . $settings['resize'] . "'><div class='cp-panel-content-icon'><h4> Close </h4></div>
    	<div class='cp-element-title-wrapper'>
    	   <span class='cp-element-title'>" . __( 'Close Text', 'convertpro' ) . '</span>
        </div>
    </div>
</div>';

	return $output;
}
