<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db089_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-left-placed-divi-blurb-icons-larger/'); 
	$plugin->checkbox(__FILE__); ?> Make left-placed blurb icons bigger<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-blurb', 'db089_add_setting');