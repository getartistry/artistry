<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db034_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->hiddencheckbox(__FILE__); ?>   
<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
<style>#wtfdivi034-submit { display:inline; }</style>
Import plugin settings from file: <input name="uploaded_file" id="wtfdivi034-filename" type="file" /> <?php submit_button('Upload Settings', 'secondary', 'wtfdivi034-submit', false); ?> 
<script>
jQuery(function($){
	$("#wtfdivi034-submit").click(function(){ if (!confirm('Are you sure you want to overwrite the current settings?')) { $("#wtfdivi034-filename").val(''); return false;};});
	$("#submit").click(function(){$("#wtfdivi034-filename").val('');});
});
</script><?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('developer-export', 'db034_add_setting');