<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add presets for field.
require_once( 'presets.php' );

// Add new input type "textfield".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'cp_dual_color_shape', 'cp_dual_color_shape_settings_field' );
}

/**
 * Function Name: cp_dual_color_shape_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $sections string parameter.
 * @param string $value string parameter.
 * @param string $default_value string parameter.
 */
function cp_dual_color_shape_settings_field( $name, $settings, $sections, $value, $default_value ) {
	$data_json = array(
		'id'         => $name,
		'title'      => $settings['title'],
		'sections'   => $sections,
		'resize'     => $settings['resize'],
		'has_editor' => isset( $settings['editor'] ) ? true : false,
		'presets'    => $settings['presets'],
	);

	$data = json_encode( $data_json );

	$input_name = $name;

	$shape_color     = isset( $sections[0]['params'][0]['default_value'] ) ? $sections[0]['params'][0]['default_value'] : '#454545';
	$sec_shape_color = isset( $sections[0]['params'][1]['default_value'] ) ? $sections[0]['params'][1]['default_value'] : '#a9a9a9';

	$output  = "<div class='fields-panel'>";
	$output .= "<div class='list-group-item' style='display: none;' data-preset='cp-dual-color-shapes' data-type='cp_dual_color_shape' data-value='" . $settings['value'] . "' data-json='" . $data . "'></div>";

	if ( isset( $settings['presets'] ) && is_array( $settings['presets'] ) && count( $settings['presets'] ) > 0 ) {

		foreach ( $settings['presets'] as $key => $file ) {

			$file_path    = plugin_dir_path( __FILE__ ) . 'presets/' . $file['name'] . '.html';
			$shape_file   = fopen( $file_path, 'r' ) or die( 'Invalid Shape Preset' );
			$file_content = fread( $shape_file, filesize( $file_path ) );

			$file_content_prev = str_replace( '{{shape_color}}', $shape_color, $file_content );
			$file_content_prev = str_replace( '{{sec_shape_color}}', $sec_shape_color, $file_content_prev );
			$output           .= '<div class="cp_element_drager_wrap cp_dual_color_shape ' . $file['name'] . '">';
			$output           .= "<div class='cp-dual-color-shapes cp-preset-field cp-element-container draggable' data-title='" . $settings['title'] . "' data-preset='" . str_replace( '_', '-', $file['name'] ) . "' data-tags='" . $file['tags'] . "' data-content='" . $file_content . "' data-type='cp_dual_color_shape' data-value='" . $settings['value'] . "' data-resize='" . $settings['resize'] . "'>" . $file_content_prev . '</div>';
			$output           .= '</div>';

			fclose( $shape_file );

		}
	} else {
		$output .= __( 'No Shapes Found', 'convertpro' );
	}

	$output .= '</div>';

	return $output;
}
