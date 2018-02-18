<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db140_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/open-image-links-within-posts-in-a-lightbox/'); 
	$plugin->checkbox(__FILE__); ?> Open linked images in a lightbox<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('posts', 'db140_add_setting');	