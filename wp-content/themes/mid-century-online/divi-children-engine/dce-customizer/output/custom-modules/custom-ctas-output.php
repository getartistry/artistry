<?php

/**
 * Customizer output - Divi modules with custom selectors: Custom Call to Action modules output
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_custom_ctas_output() {

	$dce_output = '';

	$custom_ctas_realkeys = dce_get_custom_selectors_realkeys( 'custom_cta' );
	
	if ($custom_ctas_realkeys) {
	
		foreach ($custom_ctas_realkeys as $key => $value) {
			
			$module_radius = get_theme_mod( 'dce_ccta_module_radius_' . $key, 0 );
			if ( 0 != $module_radius ) {
				$dce_output .= ' .' . $value . ' {-moz-border-radius: ' . $module_radius . 'px; -webkit-border-radius: ' . $module_radius . 'px; border-radius: ' . $module_radius . 'px;}' . "\n";
			}

			$description_background = get_theme_mod( 'dce_ccta_description_background_' . $key, 'default' );
			if ( 'background' == $description_background ) {
				$description_backcolor = 'background-color: ' . get_theme_mod( 'dce_ccta_description_backcolor_' . $key, '#eeeeee' ) . ';';
				$description_vertpadding = get_theme_mod( 'dce_ccta_description_vertpadding_' . $key, 20 );
				$description_horpadding = get_theme_mod( 'dce_ccta_description_horpadding_' . $key, 10 );
				$description_padding = ' padding: ' . $description_vertpadding . 'px ' . $description_horpadding . '%;';
				$radius = get_theme_mod( 'dce_ccta_description_radius_' . $key, 0 );
				if ( 0 != $radius ) {
					$description_radius = '-moz-border-radius: ' . $radius . 'px; -webkit-border-radius: ' . $radius . 'px; border-radius: ' . $radius . 'px;';
				}
			}
			$description_bottommargin = ' margin-bottom: ' . get_theme_mod( 'dce_ccta_description_bottommargin_' . $key, 0 ) . 'px;';
			$dce_output .= ' .' . $value . ' .et_pb_promo_description {' . $description_backcolor . $description_padding . $description_radius . $description_bottommargin .'}' . "\n";

			$title_background = get_theme_mod( 'dce_ccta_title_background_' . $key, 'default' );
			if ( 'background' == $title_background ) {
				$title_backcolor = 'background-color: ' . get_theme_mod( 'dce_ccta_title_backcolor_' . $key, '#eeeeee' ) . ';';
				$title_vertpadding = get_theme_mod( 'dce_ccta_title_vertpadding_' . $key, 20 );
				$title_horpadding = get_theme_mod( 'dce_ccta_title_horpadding_' . $key, 10 );
				$title_padding = ' padding: ' . $title_vertpadding . 'px ' . $title_horpadding . '%;';
				$radius = get_theme_mod( 'dce_ccta_title_radius_' . $key, 0 );
				if ( 0 != $radius ) {
					$title_radius = '-moz-border-radius: ' . $radius . 'px; -webkit-border-radius: ' . $radius . 'px; border-radius: ' . $radius . 'px;';
				}
			}
			$title_bottommargin = ' margin-bottom: ' . get_theme_mod( 'dce_ccta_title_bottommargin_' . $key, 0 ) . 'px;';
			$dce_output .= ' .' . $value . ' .et_pb_promo_description h2 {' . $title_backcolor . $title_padding . $title_radius . $title_bottommargin .'}' . "\n";

		}
	
	}

	return $dce_output;

}