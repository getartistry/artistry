<?php

/**
 * Customizer output: Main Sidebar section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_main_sidebar_output() {

	$dce_output = '';

	$content_spacing = get_theme_mod( 'dce_sidebar_content_spacing', 5.5 );
	if ( $content_spacing != 5.5 ) {
		$dce_output .= '@media only screen and (min-width: 981px) {' . "\n";
		$dce_output .= ' .et_right_sidebar #left-area {padding-right: ' . $content_spacing . '%;}' . "\n";
		$dce_output .= ' .et_left_sidebar #left-area {padding-left: ' . $content_spacing . '%;}' . "\n";
		$dce_output .= '}' . "\n";
	}

	$vertical_divider = get_theme_mod( 'dce_sidebar_vertical_divider', 'initial' );
	if ( 'none' === $vertical_divider ) {
		 $dce_output .= ' #main-content .container:before {display:none;}' . "\n";
	}

	$sidebar_background = get_theme_mod( 'dce_sidebar_background', 'none' );
	if ( 'none' == $sidebar_background ) {
			$sidepadding = get_theme_mod( 'dce_sidebar_sidepadding', 30 );
			if ( 30 != $sidepadding ) {
				$dce_output .= ' .et_right_sidebar #sidebar {padding-left: ' . $sidepadding . 'px;}' . "\n";
				$dce_output .= ' .et_left_sidebar #sidebar {padding-right: ' . $sidepadding . 'px;}' . "\n";
			}
		} else {
			$hor_padding = get_theme_mod( 'dce_sidebar_hor_padding', 30 );
			$vert_padding = get_theme_mod( 'dce_sidebar_vert_padding', 30 );
			$padding = ' padding: ' . $vert_padding . 'px ' . $hor_padding . 'px;';
			if ( 'image' == $sidebar_background ) {
				$background = ' background-image: url(' . esc_url( get_theme_mod( 'dce_sidebar_background_image', '' ) ) . '); background-repeat: no-repeat; background-size: cover; background-position: center;';
			}
			if ( 'color' == $sidebar_background ) {
				$background = ' background-color: ' . get_theme_mod( 'dce_sidebar_background_color', '#ffffff' ) . ';';
			}
			$dce_output .= ' .et_right_sidebar #sidebar, .et_left_sidebar #sidebar {' . $background . $padding . ' margin-bottom: ' . get_theme_mod( 'dce_sidebar_bottom_margin', 30 ) . 'px;}' . "\n";
	}

	$boxed_title = get_theme_mod( 'dce_sidebar_boxed_title', 'default' );
	if ( 'boxed' == $boxed_title ) {
		$title_backcolor = get_theme_mod( 'dce_sidebar_boxed_title_backcolor', '#eeeeee' );
		$title_vertpadding = get_theme_mod( 'dce_sidebar_boxed_title_vertpadding', 10 );
		$title_horpadding = get_theme_mod( 'dce_sidebar_boxed_title_horpadding', 10 );
		$title_bottommargin = get_theme_mod( 'dce_sidebar_boxed_title_bottommargin', 10 );
		$dce_output .= ' #sidebar h4.widgettitle {background:' . $title_backcolor . '; padding: ' . $title_vertpadding . 'px ' . $title_horpadding . 'px; margin-bottom: ' . $title_bottommargin . 'px;}' . "\n";
	}

	$widget_bottommargin = get_theme_mod( 'dce_sidebar_widget_bottommargin', 30 );
	if ( $widget_bottommargin != 30 ) {
		$dce_output .= ' .et_right_sidebar #sidebar .et_pb_widget, .et_left_sidebar #sidebar .et_pb_widget {margin-bottom: ' . $widget_bottommargin .'px;}' . "\n";
	}

	$default_h1_size = dce_get_divi_option( 'body_header_size', 30 );
	if ( 30 != $default_h1_size ) {
			$default_title_size = round( $default_h1_size * 0.60 );
		} else {
			$default_title_size = 18;
	}
	$default_h_color = dce_get_divi_option( 'header_color', '#666666' );
	$default_h_height = dce_get_divi_option( 'body_header_height', 1 );
	$title_size = get_theme_mod( 'dce_sidebar_title_size', $default_title_size );
	$title_color = get_theme_mod( 'dce_sidebar_title_color', $default_h_color );
	$title_height = get_theme_mod( 'dce_sidebar_title_height', $default_h_height );
	$title_weight = get_theme_mod( 'dce_sidebar_title_weight', 500 );
	$title_uppercase = ( get_theme_mod( 'dce_sidebar_title_uppercase', '' ) ) ? ' text-transform: uppercase;' : ' text-transform: none;';
	$title_italics = ( get_theme_mod( 'dce_sidebar_title_italics', '' ) ) ? ' font-style: italic;' : ' font-style: normal;';
	$dce_output .= ' #sidebar h4.widgettitle {font-size: ' . $title_size . 'px; color: ' . $title_color . '; line-height: ' . $title_height . 'em; font-weight: ' . $title_weight . ';' . $title_uppercase . $title_italics . '}' . "\n";

	$widgettext_size = get_theme_mod( 'dce_sidebar_widgettext_size', dce_get_divi_option( 'body_font_size', 14 ) );
	$widgettext_color = get_theme_mod( 'dce_sidebar_widgettext_color', dce_get_divi_option( 'font_color', '#666666' ) );
	$widgettext_height = get_theme_mod( 'dce_sidebar_widgettext_height', dce_get_divi_option( 'body_font_height', 1.7 ) );
	$dce_output .= ' #sidebar li, #sidebar .textwidget, #sidebar li a {font-size:' . $widgettext_size . 'px; color: ' . $widgettext_color . '; line-height: ' . $widgettext_height . 'em;}' . "\n";
	$dce_output .= ' #sidebar li, #sidebar .textwidget, #sidebar li a {font-size:' . $widgettext_size . 'px; color: ' . $widgettext_color . ';}' . "\n";

	$dce_output .= ' #sidebar li a:hover, #sidebar .textwidget a:hover {color:' . get_theme_mod( 'dce_sidebar_widgethover_color', '#82c0c7' ) . '!important;}' . "\n";

	$widget_lists = get_theme_mod( 'dce_sidebar_widget_lists', 'default' );
	if ( 'custom' == $widget_lists ) {
		$elements_type = get_theme_mod( 'dce_sidebar_widget_lists_type', 'bullets' );
		$bullets_color = get_theme_mod( 'dce_sidebar_bullets_color', get_theme_mod( 'dce_sidebar_title_color', $default_h_color ) );
		$elements_bkgndcolor = get_theme_mod( 'dce_sidebar_widget_lists_bkgndcolor', '#f4f4f4' );
		$top_pos = round ( ( 9 * ( $widgettext_height / 1.7 ) ) + ( $widgettext_size - 14 ) );
		if ( 'bullets' == $elements_type ) {
				$dce_output .= ' #sidebar li {padding: 0 0 4px 14px; position: relative;}' . "\n";
				// $dce_output .= ' #sidebar li:before {color:' . $bullets_color . '; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; border-style: solid; border-width: 3px; content: ""; position: absolute; top: 9px;  left: 0;}' . "\n";
				$dce_output .= ' #sidebar li:before {color:' . $bullets_color . '; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; border-style: solid; border-width: 3px; content: ""; position: absolute; top: '.$top_pos.'px;  left: 0;}' . "\n";
			} elseif ( 'squares' == $elements_type ) {
				$dce_output .= ' #sidebar li {padding: 0 0 4px 14px; position: relative;}' . "\n";
				// $dce_output .= ' #sidebar li:before {color:' . $bullets_color . '; -moz-border-radius: 0; -webkit-border-radius: 0; border-radius: 0; border-style: solid; border-width: 3px; content: ""; position: absolute; top: 9px;  left: 0;}' . "\n";
				$dce_output .= ' #sidebar li:before {color:' . $bullets_color . '; -moz-border-radius: 0; -webkit-border-radius: 0; border-radius: 0; border-style: solid; border-width: 3px; content: ""; position: absolute; top: '.$top_pos.'px;  left: 0;}' . "\n";
			} elseif ( 'arrows' == $elements_type ) {
				$dce_output .= ' #sidebar li {padding: 0 0 4px 14px; position: relative; }' . "\n";
				$dce_output .= ' #sidebar li:before {color:' . $bullets_color . '; font-family: "ETmodules"; content: "\45"; font-size: 18px; position: absolute; top: 0px;  left: -5px;}' . "\n";
			} elseif ( 'line' == $elements_type ) {
				$dce_output .= ' #sidebar li {padding: 0px 0px 0px 10px; position: relative; margin: 14px 0; border-color:' . $bullets_color . '; border-left-style: solid; border-left-width: 3px;}' . "\n";
			} elseif ( 'background' == $elements_type ) {
				$dce_output .= ' #sidebar li {padding: 6px 10px 6px 10px; position: relative; margin: 10px 0; background: ' . $elements_bkgndcolor . ';}' . "\n";
			} elseif ( 'line-background' == $elements_type ) {
				$dce_output .= ' #sidebar li {padding: 6px 10px 6px 10px; position: relative; margin: 14px 0; border-color:' . $bullets_color . '; border-left-style: solid; border-left-width: 3px; background: ' . $elements_bkgndcolor . ';}' . "\n";	
		}
	}

	return $dce_output;

}
