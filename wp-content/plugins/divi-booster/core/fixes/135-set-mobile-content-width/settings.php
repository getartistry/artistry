<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db135_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-website-content-width-on-mobiles/'); 
	$plugin->checkbox(__FILE__); ?> Set mobile content width:<?php
	$plugin->numberpicker(__FILE__, 'mobilewidth', 80); ?>%<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('general-layout', 'db135_add_setting');

