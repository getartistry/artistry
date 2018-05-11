<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "number".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'numberfield', 'cp_v2_numberfield_settings_field' );
	add_action( 'admin_enqueue_scripts', 'cp_v2_numberfield_admin_scripts' );
}

/**
 * Function Name: cp_v2_numberfield_admin_scripts.
 * Function Description: cp_v2_numberfield_admin_scripts.
 *
 * @param string $hook string parameter.
 */
function cp_v2_numberfield_admin_scripts( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );
	wp_enqueue_script( 'jquery' );
	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-numberfield', plugins_url( 'numberfield.js', __FILE__ ), array(), '1.0.0', true );
	}
}

/**
 * Function Name: cp_v2_numberfield_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_numberfield_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$value      = htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
	$map_style  = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';
	$suffixes   = explode( ',', $suffix );

	ob_start();

	?>

	<div class="cp-numberfield-container cp-param-inner" data-id="cp_<?php echo $input_name; ?>" data-units="<?php echo $suffix; ?>">
		<div class="cp-fields-param-units number-field-param-units">
			<?php foreach ( $suffixes as $suffix ) { ?>	
				<span class="cp-units cp-unit-<?php echo $suffix; ?>"><?php echo strtoupper( $suffix ); ?></span>
			<?php } ?>
		</div>
		<input type="hidden" data-type="<?php echo $type; ?>" id="cp_<?php echo $input_name; ?>" data-mapstyle="<?php echo htmlspecialchars( $map_style ); ?>" class="form-control cp-input cp-<?php echo $type . ' ' . $input_name . ' ' . $type . ' ' . $class; ?>" value="<?php echo $value; ?>" name="<?php echo $input_name; ?>" />
		<input type="number" class="cp-number-param-temp" value="<?php echo $value; ?>" /></div>

	<?php

	$output = ob_get_clean();

	return $output;
}
