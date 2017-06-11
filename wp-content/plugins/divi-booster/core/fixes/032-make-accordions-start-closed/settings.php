<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db032_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-divi-accordions-closed-by-default/'); ?> 
<?php $plugin->checkbox(__FILE__); ?> Make accordions start fully closed by default<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-accordion', 'db032_add_setting');