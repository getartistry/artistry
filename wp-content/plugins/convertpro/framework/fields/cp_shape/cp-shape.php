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
	cp_add_input_type( 'cp_shape', 'cp_shape_settings_field' );
}

/**
 * Function Name: cp_shape_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $sections string parameter.
 * @param string $value string parameter.
 * @param string $default_value string parameter.
 */
function cp_shape_settings_field( $name, $settings, $sections, $value, $default_value ) {
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

	$output  = "<div class='fields-panel'>";
	$output .= "<div class='list-group-item' style='display: none;' data-preset='cp-shapes' data-type='cp_shape' data-value='" . $settings['value'] . "' data-json='" . $data . "'></div>";

	if ( isset( $settings['presets'] ) && is_array( $settings['presets'] ) && count( $settings['presets'] ) > 0 ) {

		foreach ( $settings['presets'] as $key => $file ) {

			$file_path    = plugin_dir_path( __FILE__ ) . 'presets/' . $file['name'] . '.svg';
			$shape_file   = fopen( $file_path, 'r' ) or die( 'Invalid Shape Preset' );
			$file_content = fread( $shape_file, filesize( $file_path ) );

			$preset_settings = $file['preset_setting'];

			if ( isset( $preset_settings['shape_width'] ) ) {
				$file_content = str_replace( '{{stroke_width}}', $preset_settings['shape_width']['value'], $file_content );
				$file_content = str_replace( '{{stroke_half_width}}', ( $preset_settings['shape_width']['value'] / 2 ), $file_content );
				$file_content = str_replace( '{{stroke_dasharray}}', ( 2 * $preset_settings['shape_width']['value'] ), $file_content );
				$file_content = str_replace( '{{stroke_dasharray_06}}', ( 3 * $preset_settings['shape_width']['value'] ) . ', ' . ( 3 * $preset_settings['shape_width']['value'] ), $file_content );
			}

			$output .= '<div class="cp_element_drager_wrap cp_shape ' . $file['name'] . ' ' . $file['section'] . '">';
			$output .= "<div class='cp-shapes cp-preset-field cp-element-container draggable' data-preset='" . str_replace( '_', '-', $file['name'] ) . "' data-tags='" . $file['tags'] . "' data-content='" . $file_content . "' data-type='cp_shape' data-value='" . $settings['value'] . "' data-resize='" . $settings['resize'] . "'>" . str_replace( '{{shape_color}}', '#666666', $file_content ) . '</div>';
			$output .= '</div>';

			fclose( $shape_file );

		}
	} else {
		$output .= __( 'No Shapes Found', 'convertpro' );
	}

	$output .= '</div>';

	return $output;
}
