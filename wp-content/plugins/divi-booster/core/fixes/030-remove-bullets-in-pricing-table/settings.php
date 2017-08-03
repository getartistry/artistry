<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db030_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/hide-bullet-points-on-divi-pricing-tables/');
	$plugin->checkbox(__FILE__); ?> Hide bullet points in pricing tables<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db030_add_setting');