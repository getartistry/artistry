<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db073_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Add more social media icons (enter URL):<br>
	
<table style="float:left;margin-left:30px; margin-bottom: 10px">
<tr><td>LinkedIn</td><td><?php $plugin->textpicker(__FILE__, 'linkedin'); ?></td></tr>
<tr><td>YouTube</td><td><?php $plugin->textpicker(__FILE__, 'youtube'); ?></td></tr>
<tr><td>Pinterest</td><td><?php $plugin->textpicker(__FILE__, 'pinterest'); ?></td></tr>
<tr><td>Tumblr</td><td><?php $plugin->textpicker(__FILE__, 'tumblr'); ?></td></tr>
<tr><td>Instagram</td><td><?php $plugin->textpicker(__FILE__, 'instagram'); ?></td></tr>
</table>
<table style="float:left;margin-left:30px; margin-bottom: 10px">
<tr><td>Skype</td><td><?php $plugin->textpicker(__FILE__, 'skype'); ?></td></tr>
<tr><td>Flickr</td><td><?php $plugin->textpicker(__FILE__, 'flikr'); ?></td></tr>
<tr><td>MySpace</td><td><?php $plugin->textpicker(__FILE__, 'myspace'); ?></td></tr>
<tr><td>Vimeo</td><td><?php $plugin->textpicker(__FILE__, 'vimeo'); ?></td></tr>
</table>
	<p style="margin-left: 30px;clear:both;"><strong>Need another icon? You can now add 160+ social media icons via <a href="customize.php?autofocus[section]=divibooster-social-icons" target="_blank">the customizer</a></strong></p>
<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('general-icons', 'db073_add_setting');