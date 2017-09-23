<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($){
	
	var olddays = $('.et_pb_countdown_timer .days .value');
	
	// Clone the days and hide the original. 
	// - Wraps new days element in a span to prevent Divi from updating it
	olddays.each(function(){
		var oldday = $(this);
		oldday.after(oldday.clone());
		oldday.next().wrap('<span></span>');
	}).hide();
	
	// Update the clone each second, removing the trailing zero
	(function update_days() {
		olddays.each(function(){
			var oldday = $(this);
			var days = oldday.html();
			if (days.substr(0,1) == '0') { days = days.slice(1); }
			oldday.next().find('.value').html(days);
		});
		setTimeout(function(){ update_days(); }, 1000);
	})()

});