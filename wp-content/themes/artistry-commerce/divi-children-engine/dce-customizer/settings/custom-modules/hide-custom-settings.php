<?php

/**
 * Customizer controls: Divi Children Engine Settings Control (Custom Modules sections)
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_hide_custom_settings( $fields ) {

	$panel = 'custom-modules';
	$hide_section = 'hide-custom';

	global $dce_customizer_sections;

	$custom_settings_control = array();
	
	foreach ( $dce_customizer_sections as $key => $value ) {
	
		if ( ( $panel !== $value[0] ) OR ( $hide_section === $key ) ) {
			continue;
		}

		if ( $key == 'custom-fw-headers' ) {
				$section_string = 'custom_fullwidth_header';
			} elseif ( $key == 'custom-sidebars' ) {
				$section_string = 'custom_sidebar_module';
			} else {
				$section_string = rtrim( str_replace( '-', '_', $key ), 's' );
		}

		$custom_settings_control[$section_string] = dce_get_custom_selectors( $section_string, 'no_alias' );

	}	

	if ( ! empty( $custom_settings_control ) ) {

		/* GROUP TITLE: Hide this sections: */
		$fields[] = array(
			'type'		=> 'custom',
			'settings'	=> 'gt_hchs_' . $custom_selector,
			'label'		=> __( 'Hide this sections:', 'divi-children-engine' ),
			'section'	=> 'dce_hide_custom',
			'priority'	=> 0,
		);

		foreach ( $custom_settings_control as $custom_modules ) {

			$count = ( isset( $count ) ) ? $count++ : 1;
			
			if ( $custom_modules ) {

				foreach ( $custom_modules as $custom_selector ) {

					if ( ! dce_enable( 'section_' . $custom_selector ) ) {
						continue;
					}				

					$count++;
					$selector_alias = dce_get_selector_alias( $custom_selector );
					$custom_title = ucwords( str_replace( array('_', '-'), ' ', $selector_alias ) );
								
					/* Checkbox to hide each section */
					$fields[] = array(
						'type'		=>	'checkbox',
						'setting'	=>	'dce_hide_settings_' . $custom_selector,
						'label'		=>	$custom_title,
						'section'	=>	'dce_hide_custom',
						'default'	=>	0,
						'priority'	=>	$count,
					);

				}
			}
		}
	}

	return $fields;
	
}

add_filter( 'kirki/fields', 'dce_hide_custom_settings' );