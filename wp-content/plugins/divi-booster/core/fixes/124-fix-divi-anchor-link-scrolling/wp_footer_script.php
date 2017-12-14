<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

?>
document.addEventListener('DOMContentLoaded', function(event){ 

	if (window.location.hash) {
		
		setTimeout(function(){
			
			if (window.hasOwnProperty('et_location_hash_style')) { // ET scroll fix enabled
				window.db_location_hash_style = window.et_location_hash_style;
				
			} else { // ET scroll fix not enabled
				
				// Start at top of page
				window.scrollTo(0, 0);
				
				// Prevent default scroll to anchor by hiding the target element
				var db_hash_elem = document.getElementById(window.location.hash.substring(1));
				window.db_location_hash_style = db_hash_elem.style.display;
				db_hash_elem.style.display = 'none';
			}
		
			// After a short delay, display the element and scroll to it
			setTimeout(function(){
				var elem = jQuery(window.location.hash);
				elem.css('display', window.db_location_hash_style);
				et_pb_smooth_scroll(elem, false, 800);
			}, 700);
			
		}, 0);
	}
});
<?php 
