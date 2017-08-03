<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db088_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/using-pageloader-by-bonfire-with-divi/'); 
	$plugin->checkbox(__FILE__); ?> Fix <a href="http://codecanyon.net/item/pageloader-a-wp-preloader-with-content-slidein/6594364?ref=danmossop" target="_blank">PageLoader by Bonfire</a> page layout issues on Chrome<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('plugins-other', 'db088_add_setting');