<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db137_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/creating-a-distraction-free-edd-checkout-in-divi'); 
	$plugin->checkbox(__FILE__); ?> Hide unnecessary Divi components on checkout<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('plugins-edd', 'db137_add_setting');