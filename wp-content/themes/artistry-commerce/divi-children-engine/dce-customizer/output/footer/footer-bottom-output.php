<?php

/**
 * Customizer output: Footer Bottom section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_footer_bottom_output() {

	$dce_output .= '';

	$footer_bottom_layout = get_theme_mod( 'dce_footer_bottom_layout', 'default' );

	$top_padding = get_theme_mod( 'dce_footer_bottom_toppadding', 15 );
	$bottom_padding = get_theme_mod( 'dce_footer_bottom_bottompadding', 5 );
	if ( ( $top_padding != 15 ) OR ( $bottom_padding != 5 ) ) {
		$dce_output .= ' #footer-bottom {padding:' . $top_padding . 'px 0 '. $bottom_padding . 'px;}' . "\n";
	}
	
	if ( 'centered' == $footer_bottom_layout ) {

			$dce_output .= ' #footer-info, #footer-bottom .et-social-icons {float: none; text-align: center;}' . "\n";
			$dce_output .= ' #footer-bottom .et-social-icon:first-of-type {margin-left: 0 !important;}' . "\n";
			$dce_output .= ' #footer-bottom .et-social-icons {padding: ' . get_theme_mod( 'dce_footer_bottom_socialicons_bottompadding', 20 ) . 'px 0;}' . "\n";

		} elseif ( 'fullwidth' == get_theme_mod( 'dce_footer_bottom_fullwidth', 'default' ) ) {

			$dce_output .= ' #footer-bottom .container {width: 100%; max-width: 100%; padding: 0 ' . get_theme_mod( 'dce_footer_bottom_lateral_padding', 2 ) . '%;}' . "\n";

	}

	if ( 'reversed' == $footer_bottom_layout ) {
		$dce_output .= ' #footer-bottom .et-social-icons {float: left}' . "\n";
		$dce_output .= ' #footer-info {float: right}' . "\n";
		$dce_output .= ' #footer-bottom .et-social-icon:first-of-type {margin-left: 0 !important;}' . "\n";
		$dce_output .= ' #footer-bottom .et-social-icon:last-of-type {margin-right: 20 px;}' . "\n";
	}
	
	return $dce_output;

}