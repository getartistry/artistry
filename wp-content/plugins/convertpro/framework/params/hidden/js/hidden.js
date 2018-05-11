/**
 *
 * Icon Param
 *
 */
 ;(function ( $, window, document, undefined ) {
  'use strict'; 
	$.fn.cp_hidden = function() { 
		jQuery(this).closest('.cp-edit-panel-field').css('display','none');
	}
})( jQuery, window, document );