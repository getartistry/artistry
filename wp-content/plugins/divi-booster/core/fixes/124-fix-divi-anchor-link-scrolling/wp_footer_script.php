<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

?>
document.addEventListener('DOMContentLoaded', function(event){ 

	if (window.location.hash) {
		// Start at top of page
		window.scrollTo(0, 0);
		
		// Prevent default scroll to anchor by hiding the target element
		var db_hash_elem = document.getElementById(window.location.hash.substring(1));
		window.db_location_hash_style = db_hash_elem.style.display;
		db_hash_elem.style.display = 'none';
		
		// After a short delay, display the element and scroll to it
		jQuery(function($){
			setTimeout(function(){
				$(window.location.hash).css('display', window.db_location_hash_style);
				et_pb_smooth_scroll($(window.location.hash), false, 800);
			}, 700);
		});		
	}
});
<?php 
