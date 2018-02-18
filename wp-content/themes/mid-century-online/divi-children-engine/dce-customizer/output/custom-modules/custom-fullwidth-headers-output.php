<?php

/**
 * Customizer output - Divi modules with custom selectors: Custom Fullwidth Header modules output
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_custom_fullwidth_headers_output() {

	$dce_output = '';

	$custom_fw_headers_realkeys = dce_get_custom_selectors_realkeys( 'custom_fullwidth_header' );
	
	if ($custom_fw_headers_realkeys) {
	
		foreach ( $custom_fw_headers_realkeys as $key => $value ) {

			$logo_top_margin = get_theme_mod( 'dce_cfwh_logo_topmargin_' . $key, 0 );
			$logo_bottom_margin = get_theme_mod( 'dce_cfwh_logo_bottommargin_' . $key, 0 );
			$dce_output .= ' .' . $value . ' .header-content img {margin-top: ' . $logo_top_margin . 'px; margin-bottom: ' . $logo_bottom_margin .'px;}' . "\n";

			$title_background = get_theme_mod( 'dce_cfwh_title_background' . $key, 'default' );
			if ( 'background' == $title_background ) {
				$title_backcolor = 'background-color: ' . get_theme_mod( 'dce_cfwh_title_backcolor_' . $key, '#eeeeee' ) . ';';
				$title_vertpadding = get_theme_mod( 'dce_cfwh_title_vertpadding_' . $key, 20 );
				$title_horpadding = get_theme_mod( 'dce_cfwh_title_horpadding_' . $key, 10 );
				$title_padding = ' padding: ' . $title_vertpadding . 'px ' . $title_horpadding . '%;';
				$radius = get_theme_mod( 'dce_cfwh_title_radius_' . $key, 0 );
				if ( 0 != $radius ) {
					$title_radius = '-moz-border-radius: ' . $radius . 'px; -webkit-border-radius: ' . $radius . 'px; border-radius: ' . $radius . 'px;';
				}
			}
			$title_height = ' line-height: ' . get_theme_mod( 'dce_cfwh_title_height_' . $key, 1 ) . 'em;';
			$title_bottommargin = ' margin-bottom: ' . get_theme_mod( 'dce_cfwh_title_bottommargin_' . $key, 0 ) . 'px;';
			$dce_output .= ' .' . $value . ' .header-content h1 {' . $title_backcolor . $title_padding . $title_radius . $title_height . $title_bottommargin .';}' . "\n";

			$subhead_background = get_theme_mod( 'dce_cfwh_subhead_background' . $key, 'default' );
			if ( 'background' == $subhead_background ) {
				$subhead_backcolor = 'background-color: ' . get_theme_mod( 'dce_cfwh_subhead_backcolor_' . $key, '#eeeeee' ) . ';';
				$subhead_vertpadding = get_theme_mod( 'dce_cfwh_subhead_vertpadding_' . $key, 20 );
				$subhead_horpadding = get_theme_mod( 'dce_cfwh_subhead_horpadding_' . $key, 10 );
				$subhead_padding = ' padding: ' . $subhead_vertpadding . 'px ' . $subhead_horpadding . '%;';
				$radius = get_theme_mod( 'dce_cfwh_subhead_radius_' . $key, 0 );
				if ( 0 != $radius ) {
					$subhead_radius = '-moz-border-radius: ' . $radius . 'px; -webkit-border-radius: ' . $radius . 'px; border-radius: ' . $radius . 'px;';
				}
			}
			$subhead_height = ' line-height: ' . get_theme_mod( 'dce_cfwh_subhead_height_' . $key, 1.7 ) . 'em;';
			$subhead_bottommargin = ' margin-bottom: ' . get_theme_mod( 'dce_cfwh_subhead_bottommargin_' . $key, 0 ) . 'px;';
			$dce_output .= ' .' . $value . ' .header-content .et_pb_fullwidth_header_subhead {' . $subhead_backcolor . $subhead_padding . $subhead_radius . $subhead_height . $subhead_bottommargin .';}' . "\n";

			$buttons_topmargin = get_theme_mod( 'dce_cfwh_buttons_topmargin_' . $key, 20 );
			$buttons_bottommargin = get_theme_mod( 'dce_cfwh_buttons_bottommargin_' . $key, 0 );
			$dce_output .= ' .' . $value . ' .header-content .et_pb_more_button {margin-top: ' . $buttons_topmargin .'px; margin-bottom: ' . $buttons_bottommargin .'px;}' . "\n";

			$buttons_separation = get_theme_mod( 'dce_cfwh_buttons_separation_' . $key, 'default' );
			if ( 'default' != $buttons_separation ) {
				if ( 'custom' == $buttons_separation ) {
					$dce_output .= '@media only screen and (min-width: 981px) {' . "\n";
					$dce_output .= ' .' . $value . ' .header-content .et_pb_button_two {margin-left: ' . get_theme_mod( 'dce_cfwh_buttons_separation_desktop_' . $key, 15 ) .'px;}' . "\n";
					$dce_output .= '}' . "\n";
					$dce_output .= '@media only screen and (min-width: 768px) and (max-width: 980px) {' . "\n";
					$dce_output .= ' .' . $value . ' .header-content .et_pb_button_two {margin-left: ' . get_theme_mod( 'dce_cfwh_buttons_separation_tablet_' . $key, 15 ) .'px;}' . "\n";
					$dce_output .= '}' . "\n";
				}
				if ( 'float' == $buttons_separation ) {
					$dce_output .= '@media only screen and (min-width: 981px) {' . "\n";
					$dce_output .= ' .' . $value . ' .header-content .et_pb_button_one {float: left;}' . "\n";
					$dce_output .= ' .' . $value . ' .header-content .et_pb_button_two {float: right; margin-left: 0;}' . "\n";
					$dce_output .= '}' . "\n";
				}
			}

			$buttons_separation = get_theme_mod( 'dce_cfwh_buttons_button2_nomargin' . $key, '' );
			if ( get_theme_mod( 'dce_cfwh_buttons_button2_nomargin' . $key, '' ) ) {
				$dce_output .= '@media only screen and (max-width: 767px) {' . "\n";
				$dce_output .= ' .' . $value . ' .header-content .et_pb_button_two {margin-left: 0;}' . "\n";
				$dce_output .= '}' . "\n";
			}

		}

	}

	return $dce_output;

}