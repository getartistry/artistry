<?php

/**
 * Customizer output: Live Custom CSS section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_live_custom_css_output() {

	$custom_css = get_theme_mod( 'dce_live_custom_css', '' );
	
	if ( $custom_css != '' ) {
		return $custom_css . "\n";
	}

}