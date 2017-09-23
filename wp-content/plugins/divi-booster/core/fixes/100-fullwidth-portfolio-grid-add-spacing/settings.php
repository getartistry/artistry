<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db100_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/add-space-between-fullwidth-portfolio-items/'); 
	$plugin->checkbox(__FILE__); ?> Add space between project images (grid view)<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-portfoliofullwidth', 'db100_add_setting');