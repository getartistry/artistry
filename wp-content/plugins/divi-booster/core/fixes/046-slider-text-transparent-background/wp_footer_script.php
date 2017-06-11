<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($){ 

	/* Hide empty slide text */
	$('.et_pb_slide_description').filter(function(){
		if($.trim($(this).text()) == ''){ return true; }
	}).hide();

});
