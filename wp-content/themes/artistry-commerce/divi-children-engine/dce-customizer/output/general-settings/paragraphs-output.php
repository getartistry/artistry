<?php

/**
 * Customizer output: Paragraphs section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */



function dce_paragraphs_output() {

	$dce_output = '';
	
	$customize = get_theme_mod( 'dce_paragraph_customize', 0 );

	if ( $customize ) {

		$line_height = get_theme_mod( 'dce_paragraph_line_height', 1.7 );
		$bottom_padding = get_theme_mod( 'dce_paragraph_bottom_padding', 1 );
		$change_font_size = get_theme_mod( 'dce_paragraph_change_font_size', 0 );
		if ( $change_font_size ) {
				$font_size = ' font-size: ' . get_theme_mod( 'dce_paragraph_font_size', dce_get_divi_option( 'body_font_size', 14 ) ) . 'px;';
			} else {
				$font_size = '';
		}
		$text_align = get_theme_mod( 'dce_paragraph_text_align', 'left' );

		$dce_output .= ' p {' . $font_size . ' text-align: ' . $text_align . '; line-height:' . $line_height . 'em; padding-bottom: ' . $bottom_padding . 'em;}' . "\n";

	}

	return $dce_output;

}
