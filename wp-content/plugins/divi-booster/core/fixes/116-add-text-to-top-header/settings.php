<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db116_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/how-to-add-text-to-divi-top-header/'); 
	$plugin->checkbox(__FILE__); ?> Add text to top header (on left-hand side): <?php $plugin->textpicker(__FILE__, 'topheadertext'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-top', 'db116_add_setting');

