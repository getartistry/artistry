<?php

/**
 * Divi Children Engine custom scripts
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


global $dce_customizer_sections;

foreach ( $dce_customizer_sections as $section => $values ) {
	$scripts = $values[2];
	$section_string = str_replace( '-', '_', $section );
	if ( $scripts ) {
		$panel = $values[0];
		if ( $panel ) {
				$panel .= '/';
			} else {
				$panel = 'general-settings/';
		}
		require_once( 'scripts/' . $panel . $section . '-scripts.php' );
	}
}
