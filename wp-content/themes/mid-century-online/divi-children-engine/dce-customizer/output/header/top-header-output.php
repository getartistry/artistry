<?php

/**
 * Customizer output: Top Header section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_top_header_output() {

	$dce_output = '';

	$layout = get_theme_mod( 'dce_top_header_layout', 'default' );
	$menu_align = get_theme_mod( 'dce_top_header_menu_align', 'right' );
	$contact_right = get_theme_mod( 'dce_top_header_contact_right', '' );
	$social_right = get_theme_mod( 'dce_top_header_social_right', '' );
		
	if ( 'centered' === $layout ) {
			$dce_output .= ' #et-info {float: none !important; text-align: center;}' . "\n";
		} elseif ( 'reversed' === $layout ) {
			$dce_output .= ' #top-header .et-social-icons {float: left; padding-right: 20px; margin-left: -20px;}' . "\n";
			if ( $contact_right ) {
				$dce_output .= ' #et-info {width: 100%;}' . "\n";
				$dce_output .= ' #et-info-phone {float: right; margin-right: 0;}' . "\n";
				$dce_output .= ' #et-info-email {float: right; margin-right: 13px;}' . "\n";
			}
		} elseif ( $social_right ) {
			$dce_output .= ' #et-info {width: 100%;}' . "\n";
			$dce_output .= ' #top-header .et-social-icons {float: right;}' . "\n";
	}

	if ( 'right' !== $menu_align ) {
		$dce_output .= ' #et-secondary-menu {float:none; text-align: ' . $menu_align . '}' . "\n";
		if ( ( 'left' === $menu_align ) AND ( 'centered' != $layout ) AND ! $contact_right AND ! $social_right ) {
			$dce_output .= ' #et-secondary-nav {margin-left: ' . get_theme_mod( 'dce_top_header_menu_leftmargin', 25 ) . 'px;}' . "\n";
		}
	}

	$menu_spacing = get_theme_mod( 'dce_top_header_menu_spacing', 15 );
	if ( $menu_spacing !== 15 ) {
		$dce_output .= ' #et-secondary-nav li {margin-right: ' . $menu_spacing . 'px !important;}' . "\n";
	}
	$dce_output .= ' #et-secondary-nav li:last-child {margin-right: 0 !important;}' . "\n";

	return $dce_output;

}
