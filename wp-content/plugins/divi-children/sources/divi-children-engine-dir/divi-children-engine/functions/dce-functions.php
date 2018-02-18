<?php

/**
 * Divi Children Engine auxiliary functions
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */



/**
 * Remove Divi Customizer panels and sections to reorder them.
 */

function dce_modify_divi_customizer_structure( $wp_customize ) {
	$wp_customize->remove_panel( 'et_divi_general_settings' );
	$wp_customize->remove_panel( 'et_divi_header_panel' );
	$wp_customize->remove_panel( 'et_divi_footer_panel' );
	$wp_customize->remove_panel( 'et_divi_buttons_settings' );
	$wp_customize->remove_panel( 'et_divi_blog_settings' );
	$wp_customize->remove_panel( 'et_divi_mobile' );
	$wp_customize->remove_section( 'et_color_schemes' );
	dce_reorder_divi_customizer_structure( $wp_customize );
}
add_action( 'customize_register', 'dce_modify_divi_customizer_structure', 100 );


/**
 * Display Divi Customizer panels and sections after the child theme specific panels and sections.
 */

function dce_reorder_divi_customizer_structure( $wp_customize ) {

	$wp_customize->add_panel( 'et_divi_general_settings' , array(
		'title'		=> __( 'General Settings', 'Divi' ),
		'priority'	=> 100,
	) );
	
	$wp_customize->add_panel( 'et_divi_header_panel', array(
	    'title'		=> __( 'Header & Navigation', 'Divi' ),
	    'priority'	=> 101,
	) );

	$wp_customize->add_panel( 'et_divi_footer_panel' , array(
		'title'		=> __( 'Footer', 'Divi' ),
		'priority'	=> 102,
	) );

	$wp_customize->add_panel( 'et_divi_buttons_settings' , array(
		'title'		=> __( 'Buttons', 'Divi' ),
		'priority'	=> 103,
	) );	

	$wp_customize->add_panel( 'et_divi_blog_settings' , array(
		'title'		=> __( 'Blog', 'Divi' ),
		'priority'	=> 104,
	) );	

	$wp_customize->add_panel( 'et_divi_mobile' , array(
		'title'		=> __( 'Mobile Styles', 'Divi' ),
		'priority'	=> 105,
	) );

	$wp_customize->add_section( 'et_color_schemes' , array(
		'title'       => __( 'Color Schemes', 'Divi' ),
		'priority'    => 106,
		'description' => __( 'Note: Color settings set above should be applied to the Default color scheme.', 'Divi' ),
	) );	
	
}


/**
 * Get an array containing the existing DCE custom selectors of a particular type which are set to "on" (if third argument is missing), including alias instead of original selector if second argument is false.
 */

function dce_get_custom_selectors( $type, $no_alias = null, $all = null ) {
	$custom_selectors = false;
	$custom_selector_1 = $type . '_1';
	$selector_1_mod = get_theme_mod( $custom_selector_1 );
	if ( ( $selector_1_mod == 'on' ) OR ( $selector_1_mod == 'off' ) ) {
		$count = 1;
		if ( ! $no_alias ) {
			$custom_selector_1 = dce_get_selector_alias( $custom_selector_1 );
		}
		if ( ( $all  == 'all' ) OR ( $selector_1_mod  == 'on' ) ) {
			$custom_selectors = array ( $custom_selector_1 );
		}
		while ( true ) {
			$count++;
			$custom_selector_n = $type . '_' . $count;
			$selector_n_mod = get_theme_mod( $custom_selector_n );
			if ( ( $selector_n_mod == 'on' ) OR ( $selector_n_mod == 'off' ) ) {
					if ( ! $no_alias ) {
						$custom_selector_n = dce_get_selector_alias( $custom_selector_n );
					}
					if ( ( $all  == 'all' ) OR ( $selector_n_mod  == 'on' ) ) {					
						$custom_selectors[] = $custom_selector_n;
					}
				} else {
					break;		
			}
		}
	}
	return $custom_selectors;
}


/**
 * Get the alias of an existing DCE custom selector
 */

function dce_get_selector_alias( $selector ) {
	$selector_modname = $selector.'_alias';
	$alias = get_theme_mod( $selector_modname );
	if( $alias ) {
		$selector = $alias;
	}
	return $selector;
}
 

/**
 * Get an array containing the active DCE custom selectors (original or alias if an alias was defined) of a particular type, with the real keys used for thememods numbering in the database.
 */
 
function dce_get_custom_selectors_realkeys( $type ) {
	$custom_selectors = dce_get_custom_selectors( $type );
	$custom_selectors_all = dce_get_custom_selectors( $type, false, 'all' );
	if ($custom_selectors) {
			foreach ( $custom_selectors as $value ) {
				$key = array_search( $value, $custom_selectors_all );
				if ( false !== $key ) {
					$realkey = $key + 1;
					$custom_selectors_realkeys[$realkey] = $value;
				}
			}
			return $custom_selectors_realkeys;
		} else {
			return false;
	}
}


/**
 * Control function to enable DCE features or Customizer parts like panels, sections or settings
 */

function dce_enable( $part ) {
	global $dce_locked;
	$theme_mod = 'dce_disable_' . $part;
	if ( ! $dce_locked ) {
			$enable = true;
		} elseif ( ! get_theme_mod( $theme_mod, false ) ) {
			$enable = true;
		} else {
			$enable = false;
	}
	return $enable;
}


/**
 * Check if Production Mode is set to display a warning message at the top of a Customizer section
 */

function check_production_mode( $section_file, $key = NULL ) {

	if ( 'production' !== get_theme_mod( 'dce_css_output_source', 'customize' ) ) {

			return false;

		} else {

			if ( !$key ) {
					$section = 'dce_' . str_replace ( '-' , '_' , basename( $section_file, '-settings.php' ) );
				} else {
					if ( 0 === strpos( basename( $section_file ), 'custom-sidebars' ) ) {
							$section = 'dce_' . str_replace ( '-' , '_' , basename( $section_file, 's-settings.php' ) ) . '_module_' . $key;
						} else {
							$section = 'dce_' . str_replace ( '-' , '_' , basename( $section_file, 's-settings.php' ) ) . '_' . $key;
					}
			}

			Kirki::add_field( 'dce', array(
				'type'		=> 'custom',
				'settings'	=> 'pmw_' . $section,
				'section'	=> $section,
				'default'	=> dce_production_mode_warning( $section ),
				'priority'	=> 1,
			) );
			
	}

}
 

/**
 * Production Mode output warning message for Customizer sections
 */

function dce_production_mode_warning( $section ) {

$group_title_style = 'style="margin: 0 -20px -19px -20px;"';
	$no_group_title_style = 'style="margin: 0 -20px 20px -20px;"';
$section_with_description_style = 'style="margin: 0 -20px 20px -20px;"';

	if ( $section == 'dce_live_custom_css' ) {

			$style = 'style="margin-top:0;"';

		} elseif ( ( $section == 'dce_heading_styles' ) OR ( $section == 'dce_paragraphs' ) OR ( $section == 'dce_lists' ) OR ( $section == 'dce_top_header' ) OR ( $section == 'dce_main_header' ) ) {

			$style = $section_with_description_style;

		} elseif ( $section == 'dce_main_sidebar' ) {

			$style = $group_title_style;

		} elseif ( 0 === strpos( $section , 'dce_custom_' ) ) {

			$style = $group_title_style;

		} else {

			$style = '';

	}

	$warning = '<div class="production-warning"' . $style . '>';
	$warning .= __( 'Output Mode is set to "Production Mode". If you want to modify any of your child theme settings with the Customizer, you should switch to "Development Mode" and then hit the "Save & Publish" button.', 'divi-children-engine' );
	$warning .= '</div>';

	return $warning;

}


/**
 * Saves the last version of the Divi Children Engine used to modify the theme.
 */

function dce_last_version_save() {
	set_theme_mod( 'dce_last_version_used', DCE_VERSION );
}
add_action('customize_save_after', 'dce_last_version_save', 100);


/**
 * Get the version of Divi being used.
 */

function dce_get_divi_version() {
	$divi_theme = wp_get_theme( 'Divi' );
	$divi_version = $divi_theme->get( 'Version' );
	return $divi_version;
}


/**
 * Adds a substring to each selector on a string of CSS selectors separated by commas
 */

function dce_selector_string_adder( $string, $substring ) {
	$array = explode( ',', $string );
	$result = array();
	foreach ( $array as $key => $value ) {
		$result[$key] = $value . $substring;
	}
	$result = implode(',', $result);
	return $result;
}


/**
 * Calculates top position of right arrow of Divi buttons depending on the button text size 
 */

function dce_divi_button_arrow_top_position( $button_size ) {
	if ( $button_size < 18 ) {
			$arrow_top = '0';
		} elseif( $button_size < 22 ) {
			$arrow_top = '1px';
		} elseif( $button_size < 24 ) {
			$arrow_top = '2px';
		} elseif( $button_size < 25 ) {
			$arrow_top = '3px';
		} elseif( $button_size < 28 ) {
			$arrow_top = '4px';
		} elseif( $button_size < 29 ) {
			$arrow_top = '5px';
		} elseif( $button_size < 30 ) {
			$arrow_top = '6px';
		} elseif( $button_size < 34 ) {
			$arrow_top = '7px';
		} elseif( $button_size < 38 ) {
			$arrow_top = '8px';
		} else {
			$arrow_top = strval( 15 + ( ( $button_size - 50 ) / 2.66 ) ) .'px';
	}
	return $arrow_top;
}

