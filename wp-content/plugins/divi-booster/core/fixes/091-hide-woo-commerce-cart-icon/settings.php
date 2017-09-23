<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db091_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/hide-woocommerce-icon-from-divi-header/'); 
	$plugin->checkbox(__FILE__); ?> Remove WooCommerce cart icon from header<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('plugins-woocommerce', 'db091_add_setting');