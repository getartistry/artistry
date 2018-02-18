<?php

/**
 * Customizer Settings
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


global $dce_customizer_sections;

foreach ( $dce_customizer_sections as $section => $values ) {
	$panel = $values[0];
	if ( $panel ) {
		require_once( 'settings/' . $panel . '/' . $section . '-settings.php' );
	}
}

 