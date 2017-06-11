<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db015_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-divi-header-phone-and-email-font-sizes/'); 
	$plugin->checkbox(__FILE__); ?> Contact info icon and text style:
<table style="margin-left:50px">
<tr><td>Text size:</td><td><?php $plugin->numberpicker(__FILE__, 'fontsize', 100, 0); ?>%</td></tr>
<tr><td>Text / icon color:</td><td><?php $plugin->colorpicker(__FILE__, 'col'); ?></td></tr>
<tr><td>Hover color:</td><td><?php $plugin->colorpicker(__FILE__, 'hovercol'); ?></td></tr>
<tr><td>Background color:</td><td><?php $plugin->colorpicker(__FILE__, 'bgcol'); ?></td></tr>
</table>
	<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db015_add_setting');	