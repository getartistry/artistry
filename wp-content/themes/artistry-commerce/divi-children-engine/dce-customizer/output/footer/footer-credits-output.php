<?php

/**
 * Customizer output: Footer Credits section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_footer_credits_output() {

	$dce_output = '';

	$color = get_theme_mod( 'dce_footer_credits_separator_color', '#666666' );
	$separator_color = ( $color != '#666666' ) ? ' color:' . $color . ';' : '';

	$weight = get_theme_mod( 'dce_footer_credits_separator_font_weight', '700' );
	$separator_weight = ( $weight != '700' ) ? ' font-weight:' . $weight . ';' : '';
	
	$dce_output .= ' .dce-credits-separator {padding: 0 ' . get_theme_mod( 'dce_footer_credits_separator_padding', 3 ) . 'px;' . $separator_color . $separator_weight  . '}' . "\n";

	if ( get_theme_mod( 'dce_footer_credits_customize_linkcolor', '1' ) ) {
		$divi_text_color = dce_get_divi_option( 'bottom_bar_text_color', '#666666' );
		$link_color = get_theme_mod( 'dce_footer_credits_linkcolor', $divi_text_color );
		$dce_output .= ' #footer-info a {color:' . $link_color . ';}' . "\n";
	}

	$linkweight = get_theme_mod( 'dce_footer_credits_linkweight', 700 );
	if ( $linkweight != 700) {
		$dce_output .= ' #footer-info a {font-weight: ' . $linkweight . ';}' . "\n";
	}

	return $dce_output;

}
