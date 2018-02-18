<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db095_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-secondary-header-text-and-icon-hover-colors/');

	// Get the current secondary nav (non-hover) color
	$detect_legacy_secondary_nav_color = et_get_option('secondary_nav_text_color', 'Light');
	if ( $detect_legacy_secondary_nav_color == 'Light' ) {
		$legacy_secondary_nav_color = '#ffffff';
	} else {
		$legacy_secondary_nav_color = 'rgba(0,0,0,0.7)';
	}
	$non_hover_col = et_get_option('secondary_nav_text_color_new', $legacy_secondary_nav_color);
	
	// Add opacity to non-hover color to give default hover color
	// convert from hex to rgba
	if (preg_match("/^#?([0-9a-f]{3,6})$/", $non_hover_col, $matches)) { 
		$hex = $matches[1];
		list($r,$g,$b) = str_split($hex,(strlen($hex)==6)?2:1);
		$r=hexdec($r); $g=hexdec($g); $b=hexdec($b);
		
		// Update the option with the rgba form of the color
		$default_hover_col = "rgba($r,$g,$b,0.7)";
	} else {
		$default_hover_col = $non_hover_col;
	}
	
	$plugin->checkbox(__FILE__); ?> Link hover color: <?php $plugin->colorpicker(__FILE__, 'hovercol', $default_hover_col, true);  
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-top', 'db095_add_setting');