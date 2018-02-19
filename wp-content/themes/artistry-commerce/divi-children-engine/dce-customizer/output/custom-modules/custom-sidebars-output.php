<?php

/**
 * Customizer output - Divi modules with custom selectors: Custom Sidebar modules output
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_custom_sidebars_output() {

	$dce_output = '';

	$custom_sidebar_modules_realkeys = dce_get_custom_selectors_realkeys( 'custom_sidebar_module' );
	
	if ( $custom_sidebar_modules_realkeys ) {
	
		foreach ($custom_sidebar_modules_realkeys as $key => $value) {

			$sidebar_background = get_theme_mod( 'dce_csb_background_' . $key, 'none' );
			if ( 'none' == $sidebar_background ) {
					$sidepadding = get_theme_mod( 'dce_csb_sidepadding_' . $key, 30 );
					if ( 30 != $sidepadding ) {
						$dce_output .= ' #' . $value . '.et_pb_widget_area_right {padding-left: ' . $sidepadding . 'px;}' . "\n";
						$dce_output .= ' #' . $value . '.et_pb_widget_area_left {padding-right: ' . $sidepadding . 'px;}' . "\n";
					}
				} else {
					$hor_padding = get_theme_mod( 'dce_csb_hor_padding_' . $key, 30 );
					$vert_padding = get_theme_mod( 'dce_csb_vert_padding_' . $key, 30 );
					$padding = ' padding: ' . $vert_padding . 'px ' . $hor_padding . 'px;';
					if ( 'image' == $sidebar_background ) {
						$background = ' background-image: url(' . esc_url( get_theme_mod( 'dce_csb_background_image_' . $key, '' ) ) . '); background-repeat: no-repeat; background-size: cover; background-position: center;';
					}
					if ( 'color' == $sidebar_background ) {
						$background = ' background-color: ' . get_theme_mod( 'dce_csb_background_color_' . $key, '#ffffff' ) . ';';
					}
					$dce_output .= ' #' . $value . ' {' . $background . $padding . ' margin-bottom: ' . get_theme_mod( 'dce_csb_bottom_margin', 30 ) . 'px;}' . "\n";
			}

			$dce_output .= ' #' . $value . ' .et_pb_widget {margin-bottom: ' .  get_theme_mod( 'dce_csb_widget_bottommargin_' . $key, 30 ) .'px;}' . "\n";

			$boxed_title = get_theme_mod( 'dce_csb_boxed_title_' . $key, 'default' );
			if ( 'boxed' == $boxed_title ) {
				$title_backcolor = get_theme_mod( 'dce_csb_boxed_title_backcolor_' . $key, '#eeeeee' );
				$title_vertpadding = get_theme_mod( 'dce_csb_boxed_title_vertpadding_' . $key, 10 );
				$title_horpadding = get_theme_mod( 'dce_csb_boxed_title_horpadding_' . $key, 10 );
				$title_bottommargin = get_theme_mod( 'dce_csb_boxed_title_bottommargin_' . $key, 10 );
				$dce_output .= ' #' . $value . ' h4.widgettitle {background:' . $title_backcolor . '; padding: ' . $title_vertpadding . 'px ' . $title_horpadding . 'px; margin-bottom: ' . $title_bottommargin . 'px;}' . "\n";
			}

			$dce_output .= ' #' . $value . ' li a:hover, #' . $value . ' .textwidget a:hover {color:' . get_theme_mod( 'dce_csb_widgethover_color_' . $key, '#82c0c7' ) . '!important;}' . "\n";

			$widget_lists = get_theme_mod( 'dce_csb_widget_lists_' . $key, 'default' );

			if ( 'custom' == $widget_lists ) {
				$elements_type = get_theme_mod( 'dce_csb_widget_lists_type_' . $key, 'bullets' );
				$bullets_color = get_theme_mod( 'dce_csb_bullets_color_' . $key, '#666666' );
				$elements_bkgndcolor = get_theme_mod( 'dce_csb_widget_lists_bkgndcolor_' . $key, '#f4f4f4' );
				if ( 'bullets' == $elements_type ) {
						$dce_output .= ' #' . $value . ' li {padding: 0 0 4px 14px; position: relative;}' . "\n";
						$dce_output .= ' #' . $value . ' li:before {color:' . $bullets_color . ' !important; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; border-style: solid; border-width: 3px; content: ""; position: absolute; top: 9px;  left: 0;}' . "\n";
					} elseif ( 'squares' == $elements_type ) {
						$dce_output .= ' #' . $value . ' li {padding: 0 0 4px 14px; position: relative;}' . "\n";
						$dce_output .= ' #' . $value . ' li:before {color:' . $bullets_color . ' !important; -moz-border-radius: 0; -webkit-border-radius: 0; border-radius: 0; border-style: solid; border-width: 3px; content: ""; position: absolute; top: 9px;  left: 0;}' . "\n";
					} elseif ( 'arrows' == $elements_type ) {
						$dce_output .= ' #' . $value . ' li {padding: 0 0 4px 14px; position: relative; }' . "\n";
						$dce_output .= ' #' . $value . ' li:before {color:' . $bullets_color . ' !important; font-family: "ETmodules"; content: "\45"; font-size: 18px; position: absolute; top: 0px;  left: -5px;}' . "\n";
					} elseif ( 'line' == $elements_type ) {
						$dce_output .= ' #' . $value . ' li {padding: 0px 0px 0px 10px; position: relative; margin: 14px 0; border-color:' . $bullets_color . '; border-left-style: solid; border-left-width: 3px;}' . "\n";
					} elseif ( 'background' == $elements_type ) {
						$dce_output .= ' #' . $value . ' li {padding: 6px 10px 6px 10px; position: relative; margin: 10px 0; background: ' . $elements_bkgndcolor . ';}' . "\n";
					} elseif ( 'line-background' == $elements_type ) {
						$dce_output .= ' #' . $value . ' li {padding: 6px 10px 6px 10px; position: relative; margin: 14px 0; border-color:' . $bullets_color . '; border-left-style: solid; border-left-width: 3px; background: ' . $elements_bkgndcolor . ';}' . "\n";	
				}
			}
		
		}
	
	}

	return $dce_output;

}