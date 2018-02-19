<?php

/**
 * Customizer output: Main Footer section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_main_footer_output() {

	$dce_output = '';

	if ( 'custom' == get_theme_mod( 'dce_footer_columns_default', 'custom' ) ) {
		$dce_output .= '@media only screen and ( min-width: 1100px ) {' . "\n";
		$footer_columns_separation = get_theme_mod( 'dce_footer_columns_separation', 5 );
		$layout = get_theme_mod( 'dce_footer_columns_layout', 0 );
		if ( $layout == '0' ) {
			$footer_columns_width = ( 100 - ( 3 * $footer_columns_separation ) ) / 4;
			$dce_output .= ' #footer-widgets .footer-widget {width:' . $footer_columns_width . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(4) {margin-right: 0;}' . "\n";
		}
		if ( $layout == '1' ) {
			$footer_columns_width = ( 100 - ( 2 * $footer_columns_separation ) ) / 3;
			$dce_output .= ' #footer-widgets .footer-widget {width:' . $footer_columns_width . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(3) {margin-right: 0;}' . "\n";
		}
		if ( $layout == '2' ) {
			$footer_columns_width = ( 100 - $footer_columns_separation ) / 2;
			$dce_output .= ' #footer-widgets .footer-widget {width:' . $footer_columns_width . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(2) {margin-right: 0;}' . "\n";
		}
		if ( $layout == '3' ) {
			$footer_columns_width_total = 100 - ( 2 * $footer_columns_separation );
			$footer_columns_width_narrow = $footer_columns_width_total / ( 2 + get_theme_mod( 'dce_footer_columns_width_ratio', 2 ) );
			$footer_columns_width_wide = $footer_columns_width_narrow * get_theme_mod( 'dce_footer_columns_width_ratio', 2 );
			$dce_output .= ' #footer-widgets .footer-widget {width:' . $footer_columns_width_narrow . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(2) {width:' . $footer_columns_width_wide . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(3) {margin-right: 0;}' . "\n";
		}
		if ( $layout == '4' ) {
			$footer_columns_width_total = 100 - ( 2 * $footer_columns_separation );
			$footer_columns_width_narrow = $footer_columns_width_total / ( 2 + get_theme_mod( 'dce_footer_columns_width_ratio', 2 ) );
			$footer_columns_width_wide = $footer_columns_width_narrow * get_theme_mod( 'dce_footer_columns_width_ratio', 2 );			
			$dce_output .= ' #footer-widgets .footer-widget {width:' . $footer_columns_width_narrow . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(1) {width:' . $footer_columns_width_wide . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(3) {margin-right: 0;}' . "\n";
		}
		if ( $layout == '5' ) {
			$footer_columns_width_total = 100 - ( 2 * $footer_columns_separation );
			$footer_columns_width_narrow = $footer_columns_width_total / ( 2 + get_theme_mod( 'dce_footer_columns_width_ratio', 2 ) );
			$footer_columns_width_wide = $footer_columns_width_narrow * get_theme_mod( 'dce_footer_columns_width_ratio', 2 );			
			$dce_output .= ' #footer-widgets .footer-widget {width:' . $footer_columns_width_narrow . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(3) {width:' . $footer_columns_width_wide . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(3) {margin-right: 0;}' . "\n";
		}		
		if ( $layout == '6' ) {
			$footer_columns_width_total = 100 - $footer_columns_separation;
			$footer_columns_width_narrow = $footer_columns_width_total / ( 1 + get_theme_mod( 'dce_footer_columns_width_ratio', 2 ) );
			$footer_columns_width_wide = $footer_columns_width_narrow * get_theme_mod( 'dce_footer_columns_width_ratio', 2 );
			$dce_output .= ' #footer-widgets .footer-widget {width:' . $footer_columns_width_narrow . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(1) {width:' . $footer_columns_width_wide . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(2) {margin-right: 0;}' . "\n";
		}		
		if ( $layout == '7' ) {
			$footer_columns_width_total = 100 - $footer_columns_separation;
			$footer_columns_width_narrow = $footer_columns_width_total / ( 1 + get_theme_mod( 'dce_footer_columns_width_ratio', 2 ) );
			$footer_columns_width_wide = $footer_columns_width_narrow * get_theme_mod( 'dce_footer_columns_width_ratio', 2 );
			$dce_output .= ' #footer-widgets .footer-widget {width:' . $footer_columns_width_narrow . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(2) {width:' . $footer_columns_width_wide . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(2) {margin-right: 0;}' . "\n";
		}
		if ( $layout == '8' ) {
			$dce_output .= ' #footer-widgets .footer-widget { width:100%;margin-right:0;margin-left:0;}' . "\n";
		}
		if ( $layout == '9' ) {
			$footer_columns_width = ( 100 - ( 4 * $footer_columns_separation ) ) / 5;
			$dce_output .= ' #footer-widgets .footer-widget {width:' . $footer_columns_width . '%; margin-right: ' . $footer_columns_separation . '%;}' . "\n";
			$dce_output .= ' #footer-widgets .footer-widget:nth-child(5) {margin-right: 0;}' . "\n";
		}
		$dce_output .= ' #footer-widgets .footer-widget .et_pb_widget { width:100%}' . "\n";
		$dce_output .= '}' . "\n";
	}
	
	$top_padding = get_theme_mod( 'dce_main_footer_toppadding', 80 );
	if ( $top_padding != 80 ) {
		$dce_output .= ' #footer-widgets {padding-top:' . $top_padding . 'px;}' . "\n";
	}
	
	$bottom_margin = get_theme_mod( 'dce_footer_widget_bottommargin', 50 );
	if ( $bottom_margin != 50 ) {
		$dce_output .= ' .footer-widget {margin-bottom:' . $bottom_margin . 'px !important;}' . "\n";
	}

	$title_padding = strval( get_theme_mod( 'dce_main_footer_title_padding', 10 ) );
	if ( $title_padding != 10 ) {
		$dce_output .= ' .footer-widget .title {padding-bottom:' . $title_padding . 'px;}' . "\n";
	}
	
	$default_hover_color = et_get_option( 'accent_color', '#2ea3f2' );
	$hover_color = get_theme_mod( 'dce_main_footer_hovercolor', $default_hover_color );
	if ( $hover_color != $default_hover_color ) {
		$dce_output .= ' .footer-widget li a:hover, #footer-widgets .et_pb_widget li a:hover {color:' . $hover_color . '!important;}' . "\n";
	}
	
	$bullets = get_theme_mod( 'dce_main_footer_bullets', 1 );
	if  ( $bullets ) {
			$bullets_color = get_theme_mod( 'dce_main_footer_bulletscolor', et_get_option( 'accent_color', '#2ea3f2' ) );
			$bullets_type = get_theme_mod( 'dce_main_footer_bulletstype', 'bullets' );
			$dce_output .= ' .footer-widget li:before {border-color:' . $bullets_color . ';}' . "\n";
			if ( $bullets_type == 'squares' ) {
					$dce_output .= ' .footer-widget li:before {-moz-border-radius: 0!important; -webkit-border-radius: 0!important;  border-radius: 0!important;}' . "\n";
				} elseif ( $bullets_type == 'arrows' ) {
					$dce_output .= ' .footer-widget li:before {border-width:0!important; font-family: "ETmodules"; content: "\45"!important; font-size: 18px; position: absolute; top: 0px!important;  left: -5px!important; color:' . $bullets_color . ';}' . "\n";
				} elseif ( $bullets_type == 'line' ) {
					$dce_output .= ' .footer-widget li:before {display:none;}' . "\n";
					$dce_output .= ' .footer-widget li {padding: 0px 0px 0px 10px!important; position: relative; margin: 15px 0; border-color:' . $bullets_color . '; border-left-style: solid; border-left-width: 2px;}' . "\n";
			}
		} else {
			$dce_output .= ' .footer-widget li:before {display:none;}' . "\n";
			$dce_output .= ' .footer-widget li {padding-left: 0!important;}' . "\n";
	}

	return $dce_output;

}