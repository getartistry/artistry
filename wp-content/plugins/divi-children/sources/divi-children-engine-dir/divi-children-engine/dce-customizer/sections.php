<?php

/**
 * Customizer Sections
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


global $dce_customizer_panels;

foreach ( $dce_customizer_panels as $panel => $values ) {
	$section_file = 'sections/sections-' . $panel . '.php';
	require_once( $section_file );
}

 