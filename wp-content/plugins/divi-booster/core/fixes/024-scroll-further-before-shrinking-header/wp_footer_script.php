<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>
<?php 
$offset = isset($option['offset'])?$option['offset']:0;
$offset = intval($offset);
?>
jQuery(function($){
	
	var offset = <?php esc_html_e($offset); ?>;
	var $header = $('#main-header');
	
	// Override the addClass to prevent fixed header class from being added before offset reached
    var addclass = $.fn.addClass;
    $.fn.addClass = function(){
        var result = addclass.apply(this, arguments);
		if ($(window).scrollTop() < offset) {
			$header.removeClass('et-fixed-header');
		}
        return result;
    }

	// Remove fixed header class initially
	$header.removeClass('et-fixed-header');
	
	// Create waypoint which adds / removes fixed header class when offset reached
	$('body').waypoint({
		handler: function(d) {
			$header.toggleClass('et-fixed-header',(d==='down'));
		},
		offset: -offset
	});
	
});