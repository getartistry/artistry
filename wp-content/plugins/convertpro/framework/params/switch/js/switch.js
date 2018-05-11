/**
 *
 * Switch Param
 *
 */
 ;(function ( $, window, document, undefined ) {
  'use strict';   
	$.fn.cp_switch_param = function() { 
		var container = jQuery(this);

		//set default values to switch
		jQuery.each( container, function(index, val) {
			var val = jQuery(this).find(".cp-edit-modal-data").val();
			var id = jQuery(this).find(".cp-edit-modal-data").attr("id");
			if( val == 'true' ){
				jQuery(this).find("#cp_"+id).attr("checked","checked");	
				jQuery(this).find("#cp_"+id+"_btn").attr("checked","checked");
			}
		});
	}

	//click event on switch button
	jQuery(document).ready(function($){
		jQuery(document).on('click', '.cp-cp-switch-btn', function(e){			
			var id      = jQuery(this).data('id');
			var value   = jQuery(this).parents(".cp-switch-wrapper").find("#"+id).val();	
			var wrapper = jQuery(this).parents(".cp-edit-panel-field").find("#"+id);
			if( value == 1 || value == '1' || value == 'true' ) {
				jQuery(this).parents(".cp-switch-wrapper").find("#"+id).attr('value','0');
				wrapper.attr('value', false);
			} else {
				jQuery(this).parents(".cp-switch-wrapper").find("#"+id).attr('value','1');
				wrapper.attr('value', true);
			}		
			
			//change values for input			
			wrapper.first().trigger('change');
		});
	});

})( jQuery, window, document );