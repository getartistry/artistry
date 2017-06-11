<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($){
	
	var wtfdivi054_featured = $('body.single article.has-post-thumbnail .et_post_meta_wrapper img:nth-of-type(1)');
		
	if (wtfdivi054_featured.length) { 
		wtfdivi054_adjust_margin();
		$(window).resize(function(){ wtfdivi054_adjust_margin(); });	
	}
	
	function wtfdivi054_adjust_margin() { 
		$('#content-area').css('margin-top', wtfdivi054_featured.height()); 
	}
});
