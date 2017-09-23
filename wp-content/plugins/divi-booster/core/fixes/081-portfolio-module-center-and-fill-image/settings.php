<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db081_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Make grid images fill the container<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-portfolio', 'db081_add_setting');