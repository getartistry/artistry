<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "textfield".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'cp_heading', 'cp_heading_settings_field' );
}

/**
 * Function Name: cp_v2_label_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $sections string parameter.
 * @param string $value string parameter.
 * @param string $default_value string parameter.
 */
function cp_heading_settings_field( $name, $settings, $sections, $value, $default_value ) {
	$data_json = array(
		'id'         => $name,
		'title'      => $settings['title'],
		'sections'   => $sections,
		'resize'     => $settings['resize'],
		'has_editor' => isset( $settings['editor'] ) ? true : false,
	);

		$tags = isset( $settings['tags'] ) ? $settings['tags'] : false;

		$data = json_encode( $data_json );

		$input_name = $name;

		$output = "<div class='fields-panel'>
    <div class='cp-droppable-item list-group-item draggable' data-type='cp_heading' data-title='" . $settings['title'] . "' data-value='" . $settings['value'] . "' data-json='" . $data . "' data-resize='" . $settings['resize'] . "'>
        <div class='cp-panel-content-icon'><h2> H </h2></div>
    	<div class='cp-element-title-wrapper'>
    	   <span class='cp-element-title'>" . __( 'Heading', 'convertpro' ) . '</span>
        </div>
    </div>';

						$output .= '</div>';

		return $output;
}
