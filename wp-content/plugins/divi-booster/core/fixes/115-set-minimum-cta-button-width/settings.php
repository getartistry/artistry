<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db115_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/making-divi-buttons-the-same-size/'); 
	$plugin->checkbox(__FILE__); ?> Set minimum CTA button width:<?php
	$plugin->numberpicker(__FILE__, 'ctawidth', 180); ?> pixels<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('pagebuilder-divi', 'db115_add_setting');

