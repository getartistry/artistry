<?php

/**
 * Customizer output: Main Header section (horizontal navigation only)
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_main_header_output() {

	if ( true !== et_get_option( 'vertical_nav', false ) ) {

		$dce_output = '';

		if ( 'none' == get_theme_mod( 'dce_main_header_border', '0 1px 0 rgba(0, 0, 0, 0.1)' ) ) {
			$dce_output .= ' #main-header {box-shadow:none; -webkit-box-shadow:none; -moz-box-shadow:none;}' . "\n";
		}

		if ( 'none' == get_theme_mod( 'dce_main_header_fixed_shadow', '0 0 7px rgba(0, 0, 0, 0.1)' ) ) {
			$dce_output .= ' #main-header.et-fixed-header {box-shadow:none !important; -webkit-box-shadow:none !important; -moz-box-shadow:none !important;}' . "\n";
		}

		$menu_spacing = get_theme_mod( 'dce_main_header_menu_spacing', 22 );
		if ( $menu_spacing != 22 ) {
			$dce_output .= ' #top-menu li {padding-right: ' . $menu_spacing . 'px;}' . "\n";
		}
		
		if ( 'underline' ==  get_theme_mod( 'dce_main_header_active_underline', 'none' ) ){
			$active_bottom_line_thickness = get_theme_mod( 'dce_main_header_active_underline_line', 3 );
			$active_bottom_line_color = get_theme_mod( 'dce_main_header_active_underline_color', et_get_option( 'accent_color', '#2ea3f2' ) );
			$active_bottom_padding_normal = 62 - $active_bottom_line_thickness - 33;
			$active_bottom_padding_shrunk = 43 - $active_bottom_line_thickness - 27;
			$dce_output .= ' #top-menu li.current-menu-ancestor > a, #top-menu li.current-menu-item > a, .et-fixed-header #top-menu li.current-menu-ancestor > a, .et-fixed-header #top-menu li.current-menu-item > a {border-style: solid;border-bottom-width: ' . $active_bottom_line_thickness . 'px; border-bottom-color: ' . $active_bottom_line_color . ';}' . "\n";
			$dce_output .= ' #top-menu li.current-menu-ancestor > a, #top-menu li.current-menu-item > a {padding-bottom: ' . $active_bottom_padding_normal . 'px;}' . "\n";						
			$dce_output .= ' .et-fixed-header #top-menu li.current-menu-ancestor > a, .et-fixed-header #top-menu li.current-menu-item > a {padding-bottom: ' . $active_bottom_padding_shrunk . 'px;}' . "\n";
		}

		return $dce_output;

	}

}
