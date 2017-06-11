<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db102_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/woocommerce-show-4-products-per-row-in-divi/'); 
	$plugin->checkbox(__FILE__); ?> Make WooCommerce store display 4 items per row<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('plugins-woocommerce', 'db102_add_setting');