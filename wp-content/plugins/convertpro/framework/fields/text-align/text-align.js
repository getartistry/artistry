/**
 *
 * Text Align Param
 *
 */
 ;(function ( $, window, document, undefined ) {
  'use strict'; 
	$.fn.cp_text_align = function() { 
		var wrapper = jQuery(this);
		var rm_container = jQuery(".cp-text-align-holder-field");
		rm_container.click(function(){
			var $this = jQuery(this);
			
			jQuery.each(rm_container,function(index,element){
				jQuery(this).removeClass('selected-text');
			});

			$this.addClass('selected-text');
			rm_container.find('input:radio').removeAttr('checked');	
			$this.find('input:radio').attr('checked', true);		
			
			var r = $this.find('input:radio');
			r.attr('checked', true);

			// $this.find('input.cp-text_align').trigger('change');
			jQuery(document).trigger('cp-text_align-change', [r] );

			var value = $this.find('input:radio').val();
			var elem = $this.find('input:radio').attr('name');
			wrapper.parent().find( ".cp-text-align-field" ).val(value).trigger('change');

		});
	}
	jQuery(".cp-text-align-field-container").cp_text_align();

})( jQuery, window, document );
