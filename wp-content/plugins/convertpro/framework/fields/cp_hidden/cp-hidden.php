<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "textfield".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'cp_hidden', 'cp_hidden_settings_field' );
}

/**
 * Function Name: cp_v2_label_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $sections string parameter.
 * @param string $value string parameter.
 */
function cp_hidden_settings_field( $name, $settings, $sections, $value ) {
	$data_json = array(
		'id'       => $name,
		'title'    => $settings['title'],
		'sections' => $sections,
	);

	$data = htmlspecialchars( json_encode( $data_json ), ENT_QUOTES, 'UTF-8' );

	$input_name = $name;

		$output = "<div class='fields-panel cp-hidden'>
    <input type='hidden' value='" . $value . "' name=" . $input_name . ">
    <div class='list-group-item draggable' data-fieldtype='" . $input_name . "' data-title='" . $settings['title'] . "' data-json='" . $data . "'>" . __( 'Button', 'convertpro' ) . '</div>
</div>';

	return $output;
}
