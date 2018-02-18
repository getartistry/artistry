<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($){
	if($('#sidebar').length) {
		if ($('body.et_right_sidebar').length) {
			$('body').addClass('db_sidebar_collapsible db_right_sidebar_collapsible');
			$('#main-content').prepend(
				$('<span id="db_hide_sidebar" title="Toggle Sidebar"></span>').click(function(){
					$('body').toggleClass('et_right_sidebar et_full_width_page');
					$('#sidebar').toggle();
				})
			);
			$('body').addClass('db_collapsible_sidebar');
		} else if ($('body.et_left_sidebar').length) { 
			$('body').addClass('db_sidebar_collapsible db_left_sidebar_collapsible');
			$('#main-content').prepend(
				$('<span id="db_hide_sidebar" title="Toggle Sidebar"></span>').click(function(){
					$('body').toggleClass('et_left_sidebar et_full_width_page');
					$('#sidebar').toggle();
				})
			);
		}
	}
});