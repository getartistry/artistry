<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db113_add_setting($plugin) {  
	$plugin->setting_start(); 
	if (divibooster_is_extra()) {
		$plugin->techlink('https://extrabooster.com/change-the-extra-logo-link/'); 
	} else {
		$plugin->techlink('https://divibooster.com/change-the-divi-logo-link/'); 
	}
	$plugin->checkbox(__FILE__); ?> Change logo link URL to: <?php $plugin->textpicker(__FILE__, 'logourl'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db113_add_setting');

