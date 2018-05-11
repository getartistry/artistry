jQuery(document).on( "change", ".cp-font-param", function(e, data ) {

	var font_type =  jQuery(this).find(':selected').closest('optgroup').attr('label');
	var for_element = jQuery(this).attr("for");
	var font_weights = jQuery(this).find(':selected').data('weight');

	var id = jQuery("#"+for_element).find('.cp-target').attr('id');

	// if it hs tinymce editor
	if( typeof id !== 'undefined' && jQuery("#"+id).hasClass("tinymce") ) {

		jQuery(document).trigger( 'cp_font_change', [ id ] );
	}

	if( ( typeof data !== 'undefined' && data.set_options !== false ) || typeof data == 'undefined' ) {

		if( typeof font_weights !== 'undefined' ) {
		 	var font_weights_array =  font_weights.split(",");
			var weight_options     =  '';

			jQuery.each( font_weights_array, function(index, val) {

				if ( val == 'Inherit'  ) {
					label = 'Inherit';
				}else{
					label = val;
				}
				
				weight_options += "<option value='"+ val +"'>"+label+"</option>";
			});
		}	

		jQuery("select[for="+ for_element +"].cp-font-weights").html(weight_options);	
	}

	var font_family = jQuery(this).val();
	var font_weight = jQuery("select[for="+ for_element +"].cp-font-weights").val();

	if( font_type == 'Google' ) {

		var font_string = '';

		// Generate font string to pass to google font APIs
		if( "Inherit" != font_weight ) {
			font_string = font_family + ":" + font_weight;	
		} else {
			font_string = font_family;	
		}
		
		var font_id		= font_string.replace(" ", "-");

		var google_font_url = "//fonts.googleapis.com/css?family="+ font_string;

		jQuery("head").append("<link id='"+font_id+"' type='text/css' rel='stylesheet' href='"+google_font_url+"' /> ");
				
		fonts = generate_font_list( font_family, font_weight, for_element );	
		jQuery("#cp_fonts_list").val( JSON.stringify(fonts) );
	}

	jQuery("#cp_"+for_element).val( font_family + ":" + font_weight );	
	jQuery("#cp_"+for_element).trigger("change");
	

});

jQuery(document).on( "change", ".cp-font-weights", function() {
	var for_element = jQuery(this).attr("for");
	var id = jQuery("#"+for_element).find('.cp-target').attr('id');

	// if it hs tinymce editor
	if( typeof id !== 'undefined' && jQuery("#"+id).hasClass("tinymce") ) {
		jQuery(document).trigger( 'cp_font_change', [ id ] );
	}

	jQuery("select[for="+ for_element +"].cp-font-param").trigger('change', [{set_options:false}]);
});

function generate_font_list( font_family, font_weight, for_element  ) {

	var existing_fonts = jQuery("#cp_fonts_list").val();
	var fonts = {};

	if( existing_fonts == '' ) {	
		var font_prop = {};
		
		font_prop['family'] =  font_family;
		font_prop['weight'] =  font_weight;

		fonts[for_element] = font_prop; 
	} else {

		fonts = JSON.parse( existing_fonts );

		var font_prop = {};
		font_prop['family'] =  font_family;
		font_prop['weight'] =  font_weight;

		fonts[for_element] = font_prop; 
	}

	return fonts;

}