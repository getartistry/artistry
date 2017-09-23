<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
(function(){
	// Override the addClass to prevent fixed header class from being added
    var addclass = jQuery.fn.addClass;
    jQuery.fn.addClass = function(){
        var result = addclass.apply(this, arguments);
		jQuery('#main-header').removeClass('et-fixed-header');
        return result;
    }
})();
jQuery(function($){
	$('#main-header').removeClass('et-fixed-header');
});
