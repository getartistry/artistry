<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db018_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/making-the-divi-box-layout-overlap-the-header/'); 
	$plugin->checkbox(__FILE__); ?> Make main content overlap header in box layout:
<table style="margin-left:50px">
<tr><td>Header height:</td><td><?php $plugin->numberpicker(__FILE__, 'headerheight', 120, 0); ?>px</td></tr>
<tr><td>Header color:</td><td><?php $plugin->colorpicker(__FILE__, 'headercol'); ?></td></tr>
<tr><td>Page background color:</td><td><?php $plugin->colorpicker(__FILE__, 'bgcol'); ?></td></tr>
</table>
<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('general-layout', 'db018_add_setting');