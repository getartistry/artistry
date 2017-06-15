<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($){
	$('.et_pb_accordion .et_pb_toggle_title').click(function(){
		var $toggle = $(this).closest('.et_pb_toggle');
		if (!$toggle.hasClass('et_pb_accordion_toggling')) {
			var $accordion = $toggle.closest('.et_pb_accordion');
			if ($toggle.hasClass('et_pb_toggle_open')) {
				$accordion.addClass('et_pb_accordion_toggling');
				$toggle.find('.et_pb_toggle_content').slideToggle(700, function() { 
					$toggle.removeClass('et_pb_toggle_open').addClass('et_pb_toggle_close'); 
					
				});
			}
			setTimeout(function(){ $accordion.removeClass('et_pb_accordion_toggling'); }, 750);
		}
	});
});