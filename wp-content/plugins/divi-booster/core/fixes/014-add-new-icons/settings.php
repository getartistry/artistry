<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db014_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/adding-custom-icons-to-divi/'); 
	$plugin->checkbox(__FILE__); ?> Add custom icons for use in modules [recommended size 96x96px]:<br>
<div style="margin:10px 30px">
<?php 
list($name, $option) = $plugin->get_setting_bases(__FILE__);
if (!isset($option['urlmax'])) { $option['urlmax']=0; }

for($i=0; $i<=$option['urlmax']; $i++) {
	if (!empty($option["url$i"])) {
		$plugin->imagepicker(__FILE__, "url$i"); 
		echo '<a href="javascript:;" onclick="jQuery(this).prev().find(\'input[type=url]\').val(\'\');jQuery(this).prev().hide();jQuery(this).hide();jQuery(this).next().hide();" style="text-decoration:none" title="Delete">X</a><br>';
	}
}
$option["urlmax"]+=(empty($option["url".$option["urlmax"]])?0:1);
$plugin->imagepicker(__FILE__, "url".$option['urlmax']); 
?> 
<input type="hidden" name="<?php echo $name; ?>[urlmax]" value="<?php echo $option["urlmax"]; ?>"/>  
</div>
<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('general-icons', 'db014_add_setting');