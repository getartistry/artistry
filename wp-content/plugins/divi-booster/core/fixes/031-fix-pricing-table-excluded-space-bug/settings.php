<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db031_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/fix-pricing-table-excluded-features-spacing-issue/'); 
	$plugin->checkbox(__FILE__); ?> Fix pricing table excluded feature space issue<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-pricing', 'db031_add_setting');