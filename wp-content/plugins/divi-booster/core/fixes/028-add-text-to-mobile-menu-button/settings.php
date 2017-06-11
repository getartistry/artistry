<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db028_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/adding-text-before-the-divi-menu-button/'); 
	$plugin->checkbox(__FILE__); ?> Add text before menu button (mobiles): <?php $plugin->textpicker(__FILE__, 'menubuttontext'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-mobile', 'db028_add_setting');

