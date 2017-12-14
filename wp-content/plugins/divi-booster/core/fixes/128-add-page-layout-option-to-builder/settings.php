<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db128_add_setting($plugin) {  
	$plugin->setting_start(); 
	//$plugin->techlink('https://divibooster.com/changing-hiding-the-select-page-centered-menu-text/'); 
	$plugin->checkbox(__FILE__); ?> Add Page Layout option on Divi Builder pages / supported CPTs<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('pagebuilder-divi', 'db128_add_setting');

