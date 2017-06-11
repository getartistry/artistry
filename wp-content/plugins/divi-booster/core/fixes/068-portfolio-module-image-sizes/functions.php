<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

// remove image height cropping
function db068_filter_portfolio_height($height) {
	global $wtfdivi;
	if ($height==284) { // standard porfolio module
		list($name, $option) = $wtfdivi->get_setting_bases(__FILE__);
		return @$option['imageheight']; 
	}
}
add_filter('et_pb_portfolio_image_height', 'db068_filter_portfolio_height');

// remove image width cropping
function db068_filter_portfolio_width($width) {
	global $wtfdivi; 
	if ($width==400) { // standard porfolio module
		list($name, $option) = $wtfdivi->get_setting_bases(__FILE__);
		return @$option['imagewidth']; 
	}
}
add_filter('et_pb_portfolio_image_width', 'db068_filter_portfolio_width');