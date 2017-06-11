<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db072_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/change-divi-filterable-portfolio-grid-thumbnail-sizes/'); 
	$plugin->checkbox(__FILE__); ?> Grid layout default image sizes:
<table style="margin-left:50px">
<tr><td>Images per row:</td><td id="wtfdivi016-count" onchange="
var width=jQuery('#wtfdivi068-width input');
var count=jQuery(this).find('input'); 
count.val(Math.max(1,count.val()));
var newmax=Math.floor(1080/count.val());
width.attr('max',newmax);
width.val(Math.min(width.val(),newmax)-1);
width.val(parseInt(width.val())+1);
width.change();">
<?php $plugin->numberpicker(__FILE__, 'imagescount', 4, 1); ?></td></tr>
<tr><td>Image width:</td><td id="wtfdivi068-width"><?php $plugin->numberpicker(__FILE__, 'imagewidth', 225, 0); ?>px</td></tr>
<tr><td>Image height:</td><td><?php $plugin->numberpicker(__FILE__, 'imageheight', 169, 0); ?>px</td></tr>
</table>
<script>jQuery("#wtfdivi016-count").change();</script>
	<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-portfoliofiltered', 'db072_add_setting');