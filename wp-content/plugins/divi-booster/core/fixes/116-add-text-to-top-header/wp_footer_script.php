<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); 

if (!empty($option['topheadertext'])) { ?>
	jQuery(function($){
		if (!$('#et-info').length) { $('#top-header .container').prepend('<div id="et-info"></div>'); }
		$('#et-info').prepend('<span id="db-info-text" style="margin:0 10px">'+<?php echo json_encode(@$option['topheadertext']); ?>+'</span>');
	});
<?php
}