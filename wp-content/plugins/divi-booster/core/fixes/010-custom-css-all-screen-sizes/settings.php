<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db010_add_setting($plugin) { 
	$plugin->setting_start(); 
	$divi_options = get_option('et_divi'); 
	?>
<input type="checkbox" value="1" checked="checked" disabled/> Custom CSS as defined in <a href="<?php echo (is_divi24())?'admin.php?page=et_divi_options':'themes.php?page=core_functions.php'; ?>">Divi Custom CSS</a>: <br>
<div style="width:100%; margin-bottom:16px; box-sizing:border-box; padding-right:30px;"><textarea style="width:100%; box-sizing:border-box; min-height:100px; margin:2px 0px 2px 26px; overflow-y:hidden; line-height:1.4em; padding-top:0.3em;" placeholder="No custom CSS found in ePanel" cols="80" rows="6" disabled><?php echo htmlentities(@$divi_options['divi_custom_css']); ?></textarea></div><?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('customcss', 'db010_add_setting');
