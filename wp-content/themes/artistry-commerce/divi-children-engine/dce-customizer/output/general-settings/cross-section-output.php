<?php

/**
 * Customizer output: Cross-section output (output that applies to more than one Customizer section)
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


function dce_cross_section_output() {

	$dce_output = '';

	$single_icons = get_theme_mod( 'dce_post_postmeta_with_icons', 'default' );
	$blog_grid_icons = get_theme_mod( 'dce_blog_grid_postmeta_with_icons', 'default' );
	if ( ( 'icons' == $single_icons ) OR ( 'icons' == $blog_grid_icons ) ) {
		$dce_output .= ' .icon_tags, .icon_profile, .icon_chat, .icon_clipboard, .icon_calendar, .icon_refresh { font-family: "ETmodules"; speak: none; font-style: normal; font-weight: normal; font-variant: normal; text-transform: none; line-height: 1; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; font-size: 16px;}' . "\n";
		$dce_output .= ' .icon_tags:before {content: "\e07c";}' . "\n";
		$dce_output .= ' .icon_profile:before {content: "\e08a";}' . "\n";
		$dce_output .= ' .icon_chat:before {content: "\e066";}' . "\n";
		$dce_output .= ' .icon_clipboard:before {content: "\e0e6";}' . "\n";
		$dce_output .= ' .icon_calendar:before {content: "\e023";}' . "\n";
		$dce_output .= ' .icon_refresh:before {content: "\e02a";}' . "\n";
	}

	return $dce_output;

}
