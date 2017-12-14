<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db138_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-footer-content-width-on-mobiles/'); 
	$plugin->checkbox(__FILE__); ?> Set footer content width:<?php
	$plugin->numberpicker(__FILE__, 'mobilewidth', 80); ?>%<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('footer', 'db138_add_setting');

