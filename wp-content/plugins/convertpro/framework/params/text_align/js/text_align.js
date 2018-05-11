/**
 *
 * Text Align Param
 *
 */
 ;(function ( $, window, document, undefined ) {
  'use strict'; 
	$.fn.cp_text_align = function() { 
		var wrapper = jQuery(this);
		var rm_container = jQuery(".cp-text-align-holder");
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

			$this.find('input.cp-text_align').trigger('change');
			jQuery(document).trigger('cp-text_align-change', [r] );

			var value = $this.find('input:radio').val();
			var elem = $this.find('input:radio').attr('name');
			wrapper.attr('value', value);
			wrapper.trigger('change');
		});
	}
})( jQuery, window, document );
