<?php

/**
 * Customizer Sections for the Custom Modules panel
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


/**
 * Custom Modules sections
 */

function dce_customizer_sections_custom_modules( $wp_customize ) {

	$panel = 'custom-modules';
	$hide_section = 'hide-custom';

	global $dce_customizer_sections;

	foreach ( $dce_customizer_sections as $section => $values ) {

		if ( $panel !== $values[0] ) {
			continue;
		}

		if ( $section == 'hide-custom' ) {
				$section_string = 'hide_custom';
			} elseif ( $section == 'custom-fw-headers' ) {
				$section_string = 'custom_fullwidth_header';
			} elseif ( $section == 'custom-sidebars' ) {
				$section_string = 'custom_sidebar_module';
			} else {
				$section_string = rtrim( str_replace( '-', '_', $section ), 's' );
		}

		$section_name = 'dce_' . $section_string;
		$section_title = $values[4];
		$section_description = $values[5];
		$panel_name = 'dce_' . str_replace( '-', '_', $panel ) . '_panel';
		$count = ( isset( $count ) ) ? $count++ : 1;

		if ( $hide_section === $section ) {

				if ( dce_enable( 'section_' . $section_string ) ) {

					$wp_customize->add_section( $section_name, array(
						'title'			=>	$section_title,
						'description'	=>	$section_description,
						'capability'	=>	'edit_theme_options',
						'panel'			=>	$panel_name,
						'priority'		=>	$count,
					) );

				}

			} else {

				$custom_selectors_no_alias = dce_get_custom_selectors( $section_string, 'no_alias' );

				if ( $custom_selectors_no_alias ) {

					foreach ( $custom_selectors_no_alias as $custom_selector ) {

						if ( dce_enable( 'section_' . $custom_selector ) ) {
							if (  ! get_theme_mod( 'dce_hide_settings_' . $custom_selector, false ) ) {
								
								$count++;
								$selector_alias = dce_get_selector_alias( $custom_selector );
								$custom_title = ucwords( str_replace( array('_', '-'), ' ', $selector_alias ) );

								$wp_customize->add_section( 'dce_' . $custom_selector, array(
									'title'			=> $custom_title,
									'description'	=> $section_description . ' <b>' . $selector_alias . '</b>',
									'capability'	=> 'edit_theme_options',
									'panel'			=> $panel_name,
									'priority'		=>	$count,
								) );

							}
						}
					}
				}

		}

	}

}

add_action( 'customize_register', 'dce_customizer_sections_custom_modules' );

 