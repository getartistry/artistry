<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db107_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-divi-post-slider-module-image-into-a-clickable-link-to-the-post'); 
	$plugin->checkbox(__FILE__); ?> Make slide image link to post (NB: read more button must be enabled)<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-postslider', 'db107_add_setting');