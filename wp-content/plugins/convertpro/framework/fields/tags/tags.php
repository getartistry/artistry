<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "tags".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'tags', 'cp_v2_tags_settings_field' );
}


/**
 * Function Name: cp_v2_tags_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_tags_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$value      = htmlentities( $value );
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$output     = '<p><input type="hidden" id="cp_' . $input_name . '" class="form-control cp-input cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" value="' . $value . '" /></p>';
	return $output;
}

add_action( 'admin_enqueue_scripts', 'cp_v2_tags_footer', 99 );

/**
 * Function Name: cp_v2_tags_footer.
 * Function Description: cp_v2_tags_footer.
 *
 * @param string $hook string parameter.
 */
function cp_v2_tags_footer( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );

	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_style( 'cp-tagit-style', plugins_url( 'css/jquery.tagit.css', __FILE__ ) );
		wp_enqueue_script( 'cp-taggle', plugins_url( 'js/tag-it.js', __FILE__ ), false, false, array( 'jquery' ) );
		wp_enqueue_script( 'cp-tags', plugins_url( 'js/tags.js', __FILE__ ), false, false, array( 'cp-taggle' ) );
	}
}
