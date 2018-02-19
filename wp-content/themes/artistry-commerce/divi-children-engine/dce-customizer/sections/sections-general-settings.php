<?php

/**
 * Customizer Sections for the General Settings panel
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


/**
 * General Settings sections
 */

function dce_customizer_sections_general_settings( $wp_customize ) {

	$panel = 'general-settings';
	$hide_section = 'hide-general';

	global $dce_customizer_sections;

	foreach ( $dce_customizer_sections as $section => $values ) {

		if ( $panel !== $values[0] ) {
			continue;
		}

		// Avoid showing the "Main Header (horizontal navigation)" section if vertical navigation was selected
		if ( ( 'main-header' === $section ) AND ( true === et_get_option( 'vertical_nav', false ) ) ) {
			continue;
		}

		$section_string = str_replace( '-', '_', $section );
		$part = 'section_' . $section_string;
		$hide = ( get_theme_mod( 'dce_hide_settings_' . $section_string, false ) ) ? true : false;

		if ( ! dce_enable( $part ) ) {
			continue;
		}

		if ( ( $hide_section !== $section ) AND $hide ) {
			continue;
		}

		$section_name = 'dce_' . $section_string;
		$section_title = $values[4];
		$section_description = $values[5];
		$panel_name = 'dce_' . str_replace( '-', '_', $panel ) . '_panel';
		$count = ( isset( $count ) ) ? $count++ : 1;

		$wp_customize->add_section( $section_name, array(
			'title'			=>	$section_title,
			'description'	=>	$section_description,
			'capability'	=>	'edit_theme_options',
			'panel'			=>	$panel_name,
			'priority'		=>	$count,
		) );		

	}

}

add_action( 'customize_register', 'dce_customizer_sections_general_settings' );

 