<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

add_filter('gettext', 'db127_change_select_page_text', 20, 3);
function db127_change_select_page_text($text, $orig, $domain ) { 
	if ($orig == 'Select Page' and $domain == 'Divi') {
		global $wtfdivi;
		list($name, $option) = $wtfdivi->get_setting_bases(__FILE__); 
		return empty($option['selectpagetext'])?'':$option['selectpagetext']; 
	}
	return $text;
}
