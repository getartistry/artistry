<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db046_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/add-semi-transparent-background-to-divi-slider-text/'); 
	$plugin->checkbox(__FILE__); ?> Add background to slider text:
<table style="margin-left:50px">
<tr><td>Background color:</td><td><?php $plugin->colorpicker(__FILE__, 'bgcol', '#000'); ?></td></tr>
<tr><td>Opacity:</td><td><?php $plugin->numberpicker(__FILE__, 'opacity', 50); ?>%</td></tr>
</table>
	<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-slider', 'db046_add_setting');