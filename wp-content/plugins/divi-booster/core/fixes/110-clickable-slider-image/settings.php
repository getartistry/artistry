<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db110_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-divi-slider-module-image-into-a-clickable-link/'); 
	$plugin->checkbox(__FILE__); ?> Make slide image link to URL (NB: requires slide to have a button)
	<p style="margin-left: 30px;clear:both;"><strong>Update: You can now set the slide Background Link URL in the slider module's slide settings (no need to add a button).</strong></p>
	<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-slider', 'db110_add_setting');