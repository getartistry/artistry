<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db082_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/change-divi-image-portfolio-grid-thumbnail-sizes/'); 
	$plugin->checkbox(__FILE__); ?> Make grid images fill the container<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-portfoliofiltered', 'db082_add_setting');