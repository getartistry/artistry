<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db075_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/change-the-divi-header-font-size/'); 
	$plugin->checkbox(__FILE__); ?> Menu font size: <?php $plugin->numberpicker(__FILE__, 'menufontsize', 14); ?>px<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db075_add_setting');