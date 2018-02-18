<?php

/**
 * Divi Children Engine CSS output
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


$output_to_css = get_theme_mod( 'dce_css_output_source', 'customize' );
if ( $output_to_css == 'customize' ) {
		$output_function = 'dce_customizer_output_customize';
	} elseif ( $output_to_css == 'production') {
		$output_function = 'dce_customizer_output_production';
	} elseif ( $output_to_css == 'stylesheet') {
		return false;
}
if ( $output_function ) {
	add_action( 'wp_footer', $output_function, 100 );
}


/**
 * Outputs Customizer CSS for site under development.
 */
 
function dce_customizer_output_customize() {
	echo dce_customizer_output();
}


/**
 * Outputs Customizer CSS for finished site.
 */
 
function dce_customizer_output_production() {
	echo get_theme_mod( 'dce_css_output' );
}


/**
 * Saves Divi Children Engine CSS to generate Customizer output for the Production mode.
 */

function dce_customizer_output_save() {
	if ( get_theme_mod( 'dce_css_output_save' ) ) {
		$output = dce_customizer_output();
		set_theme_mod( 'dce_css_output', $output );
	}
}
add_action('customize_save_after', 'dce_customizer_output_save', 99);


/**
 * Generates Customizer controlled Divi Children Engine CSS.
 */

function dce_customizer_output() {
	$dce_output .= "\n" . '<!-- Child theme custom CSS created by Divi Children Engine - http://divi4u.com -->' . "\n";
	$dce_output .= '<style type="text/css" media="screen">' . "\n";
	$dce_output = dce_generate_css_rules( $dce_output );
	$dce_output .= '</style>' . "\n";
	$dce_output .= '<!-- End Child theme custom CSS -->' . "\n" . "\n";
	return $dce_output;
}


/**
 * Generates CSS rules from each Divi Children Engine Customizer section.
 */
 
function dce_generate_css_rules( $dce_output ) {
	global $dce_customizer_sections;
	foreach ( $dce_customizer_sections as $section => $values ) {
		$output = $values[1];
		$section_string = str_replace( '-', '_', $section );
		if ( $output ) {
			$panel = $values[0];
			if ( $panel ) {
					$panel .= '/';
				} else {
					$panel = 'general-settings/';
			}
			require_once( 'output/' . $panel . $section . '-output.php' );
			$output_function = 'dce_' . $section_string . '_output';
			$dce_output .= call_user_func( $output_function );
		}
	}
	return $dce_output;
}
