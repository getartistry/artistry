<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "cp_countdown".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'cp_countdown', 'cp_countdown_settings_field' );
}

// Added countdown scripts.
add_action( 'admin_enqueue_scripts', 'cp_countdown_admin_styles' );

/**
 * Function Name: cp_countdown_admin_styles.
 * Function Description: Function to handle Countdown Admin Styles.
 *
 * @param string $hook string parameter.
 */
function cp_countdown_admin_styles( $hook ) {

	$dev_mode = get_option( 'cp_dev_mode' );
	wp_enqueue_script( 'cp-countdown-plugin-script', CP_FRAMEWORK_URI . '/fields/cp_countdown/cp_countdown_plugin.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'cp-countdown', CP_FRAMEWORK_URI . '/fields/cp_countdown/cp_countdown.min.js', array(), '1.0.0', true );
	wp_enqueue_style( 'cpro-countdown-style', CP_FRAMEWORK_URI . '/fields/cp_countdown/cp-countdown-style.css' );
}

/**
 * Function Name: cp_countdown_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $sections string parameter.
 * @param string $value string parameter.
 * @param string $default_value string parameter.
 */
function cp_countdown_settings_field( $name, $settings, $sections, $value, $default_value ) {
	$data_json = array(
		'id'         => $name,
		'title'      => $settings['title'],
		'sections'   => $sections,
		'resize'     => $settings['resize'],
		'has_editor' => false,
	);

	$data = json_encode( $data_json );

	$input_name = $name;

	$output = "<div class='fields-panel'>
	    <div class='cp-droppable-item list-group-item draggable' data-type='cp_countdown' data-title='" . $settings['title'] . "' data-value='" . $settings['value'] . "' data-json='" . $data . "' data-resize='" . $settings['resize'] . "'><div class='cp-panel-content-icon'><i class='dashicons dashicons-clock'></i></div>
	        <div class='cp-element-title-wrapper'>
	        <span class='cp-element-title'>" . __( 'Countdown', 'convertpro' ) . '</span>
	        </div>
	    </div>
	</div>';

	return $output;
}
