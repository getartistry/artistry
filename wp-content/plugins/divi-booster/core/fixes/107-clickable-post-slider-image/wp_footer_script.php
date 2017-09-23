<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($){
	$('.et_pb_post_slider .et_pb_slide').click(function(){
		var url=$(this).find('.et_pb_more_button').attr('href');
		if (url) {
			document.location = url;
		}
	});
	$('.et_pb_post_slider .et_pb_slide .et_pb_more_button').closest('.et_pb_slide').css('cursor','pointer');
});