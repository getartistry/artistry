/**
 *
 * Radio Image Param
 *
 */
 ;(function ( $, window, document, undefined ) {
  'use strict'; 
	$.fn.cp_radio_image = function() { 
		var wrapper = jQuery(this);
		var rm_container = jQuery(".cp-radio-image-holder");
		rm_container.click(function(){
			var $this = jQuery(this);
			jQuery.each(rm_container,function(index,element){
				jQuery(this).removeClass('selected');
			});
			$this.addClass('selected');
			rm_container.find('input:radio').removeAttr('checked');	
			$this.find('input:radio').attr('checked', true);		
			$this.find('input:radio').trigger('change');
			
			var r = $this.find('input:radio');
			r.attr('checked', true);

			$this.find('input.cp-radio_image').trigger('change');
			jQuery(document).trigger('cp-radio_image-change', [r] );

			var value = $this.find('input:radio').val();
			var elem = $this.find('input:radio').attr('name');
			wrapper.attr('value', value);
			wrapper.trigger('change');
			jQuery(document).trigger('radio_image_click',[elem,value]);
		});
	}

})( jQuery, window, document );
