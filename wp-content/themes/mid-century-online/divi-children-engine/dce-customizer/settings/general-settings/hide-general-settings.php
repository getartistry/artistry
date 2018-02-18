<?php

/**
 * Customizer controls: Divi Children Engine Settings Control (General Settings sections)
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_hide_general_settings( $fields ) {

	$panel = 'general-settings';
	$hide_section = 'hide-general';

	global $dce_customizer_sections;

	/* GROUP TITLE: Hide this sections: */
	$fields[] = array(
		'type'		=> 'custom',
		'settings'	=> 'gt_hgss',
		'label'		=> __( 'Hide this sections:', 'divi-children-engine' ),
		'section'	=> 'dce_hide_general',
		'priority'	=> 0,
	);

	foreach ( $dce_customizer_sections as $key => $value ) {

		if ( ( $panel !== $value[0] ) OR ( $hide_section === $key ) ) {
			continue;
		}
		
		// Avoid showing the "Main Header (horizontal navigation)" section hide option if vertical navigation was selected
		if ( ( 'main-header' === $key ) AND ( true === et_get_option( 'vertical_nav', false ) ) ) {
			continue;
		}
		
		$section_string = str_replace( '-', '_', $key );
		
		if ( ! dce_enable( 'section_' . $section_string ) ) {
			continue;
		}
		
		$count = ( isset( $count ) ) ? $count++ : 1;
		
		$fields[] = array(
			'type'		=>	'checkbox',
			'settings'	=>	'dce_hide_settings_' . $section_string,
			'label'		=>	$value[4],
			'section'	=>	'dce_hide_general',
			'default'	=>	0,
			'priority'	=>	$count,
		);
		
	}
	
	return $fields;
	
}
add_filter( 'kirki/fields', 'dce_hide_general_settings' );