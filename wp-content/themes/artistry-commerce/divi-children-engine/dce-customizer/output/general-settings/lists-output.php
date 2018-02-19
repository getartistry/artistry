<?php

/**
 * Customizer output: Lists section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */




function dce_lists_output() {

	$dce_output = '';

	$ul_customize = get_theme_mod( 'dce_lists_ul_customize', 0 );
	$ol_customize = get_theme_mod( 'dce_lists_ol_customize', 0 );

	if ( $ul_customize ) {

		$ul_selectors = ' #left-area ul, .entry-content ul, .comment-content ul, body.et-pb-preview #main-content .container ul';
		$ul_left_padding = get_theme_mod( 'dce_lists_ul_left_padding', 1 );
		$ul_line_height = get_theme_mod( 'dce_lists_ul_line_height', 1.9 );
		$ul_top_padding = get_theme_mod( 'dce_lists_ul_top_padding', 0 );
		$ul_bottom_padding = get_theme_mod( 'dce_lists_ul_bottom_padding', 23 );
		$ul_style_type = get_theme_mod( 'dce_lists_ul_style_type', 'disc' );
		$ul_change_font_size = get_theme_mod( 'dce_lists_ul_change_font_size', 0 );
		if ( $ul_change_font_size ) {
				$ul_font_size = ' font-size: ' . get_theme_mod( 'dce_lists_ul_font_size', dce_get_divi_option( 'body_font_size', 14 ) ) . 'px;';
			} else {
				$ul_font_size = '';
		}

		$dce_output .= $ul_selectors . ' {' . $ul_font_size . ' line-height:' . $ul_line_height . 'em; padding-left: ' . $ul_left_padding . 'em; padding-top: ' . $ul_top_padding . 'px; padding-bottom: ' . $ul_bottom_padding . 'px; list-style-type: ' . $ul_style_type . ';}' . "\n";

	}

	if ( $ol_customize ) {

		$ol_selectors = ' #left-area ol, .entry-content ol, .comment-content ol, body.et-pb-preview #main-content .container ol';
		$ol_left_padding = get_theme_mod( 'dce_lists_ol_left_padding', 0 );
		$ol_line_height = get_theme_mod( 'dce_lists_ol_line_height', 1.9 );
		$ol_top_padding = get_theme_mod( 'dce_lists_ol_top_padding', 0 );
		$ol_bottom_padding = get_theme_mod( 'dce_lists_ol_bottom_padding', 23 );
		$ol_style_type = get_theme_mod( 'dce_lists_ol_style_type', 'decimal' );
		$ol_change_font_size = get_theme_mod( 'dce_lists_ol_change_font_size', 0 );
		if ( $ol_change_font_size ) {
				$ol_font_size = ' font-size: ' . get_theme_mod( 'dce_lists_ol_font_size', dce_get_divi_option( 'body_font_size', 14 ) ) . 'px;';
			} else {
				$ol_font_size = '';
		}

		$dce_output .= $ol_selectors . ' {' . $ol_font_size . ' line-height:' . $ol_line_height . 'em; padding-left: ' . $ol_left_padding . 'em; padding-top: ' . $ol_top_padding . 'px; padding-bottom: ' . $ol_bottom_padding . 'px; list-style-type: ' . $ol_style_type . ';}' . "\n";

	}

	return $dce_output;

}
