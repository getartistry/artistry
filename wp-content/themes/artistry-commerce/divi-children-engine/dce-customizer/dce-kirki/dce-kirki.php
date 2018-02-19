<?php

/**
 * Kirki configuration, styles and modifications for the Divi Children Engine
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


/**
 * Load the Kirki Customizer toolkit from the Divi Children Engine
 */

if ( ! class_exists( 'Kirki' ) ) {
	require_once( dirname( __FILE__ ) . '/kirki/kirki.php' );
}


/**
 * Kirki Customizer Configuration
 */

function dce_kirki_configuration( $config ) {
	$args = array(
		'disable_loader' => true,
	);
	return wp_parse_args( $args, $config );
}
add_filter( 'kirki/config', 'dce_kirki_configuration' );


/**
 * Create a Kirki config instance that will be used by fields added via the static methods.
 */

Kirki::add_config( 'dce', array(
	'capability'    	=> 'edit_theme_options',
	'option_type'   	=> 'theme_mod',
	'disable_output'	=> true,
) );


/**
 * Enqueue the Divi Children Engine styles for the Customizer.
 */
 
function dce_customizer_styles() {

	$dce_styles = DCE_URL . 'dce-customizer/dce-kirki/css/dce-customizer.css';
	wp_enqueue_style( 'kirki-dce_customizer-css', $dce_styles, 'kirki-customizer-css' );

}
add_action( 'customize_controls_print_styles', 'dce_customizer_styles' );

