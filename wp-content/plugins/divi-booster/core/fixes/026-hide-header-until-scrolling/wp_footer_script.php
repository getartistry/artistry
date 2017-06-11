<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($) {

	var fadetime = 500;
	
	var mh = $('#main-header');
	var body = $('body');
	var width = parseFloat(body.css("width"));
	
	$(window).scroll(function(){
		if (width >= 964) { 
			if ($(this).scrollTop() > 1) {
				mh.fadeIn(fadetime);
			} else { 
				mh.fadeOut(fadetime);
			}
		} 
	});
	
	$(window).resize(function(){
		
		width = parseFloat(body.css("width"));
		
		if (width < 964 || $(this).scrollTop() > 1) {
			mh.show();
		} else { 
			mh.hide();
		}
	});
});