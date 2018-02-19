<?php

/**
 * Customizer Panels
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


/**
 * Customizer Panels
 */
 
function dce_customizer_panels( $wp_customize ) {
	
	global $dce_customizer_panels;

	global $divi_child_name;

	$divi_child_name = wp_get_theme()->get( 'Name' );

	if ( strlen ( $divi_child_name ) > 16 ) {
		$divi_child_name = 'Child Theme';
	}

	$priority = 1;

	foreach ( $dce_customizer_panels as $panel => $values ) {

		if ( $values ) {
			
			$part = 'panel_' . str_replace( '-', '_', $panel );
			
			if ( dce_enable( $part ) ) {
			
				$panel_string = $values[0];

				$panel_title = $divi_child_name . ' - ' . $values[1];

				$wp_customize->add_panel( $panel_string, array(
					'title'          => $panel_title,
					'description'    => '',
					'capability'     => 'edit_theme_options',
					'priority'       => $priority,
				) );
				$priority++;

			}
		
		}
		
	}

}

add_action( 'customize_register', 'dce_customizer_panels' );

