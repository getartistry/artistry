jQuery(document).ready(function(){
	var switch_btn = jQuery(".cp-switch-btn");
	jQuery(document).on('click', '.cp-switch-btn', function(e){
		var id 			= jQuery(this).data('id'),
			switch_input = jQuery(this).parents(".cp-switch-wrapper").find("#"+id),
			name 		= switch_input.attr('name'),
			property    = switch_input.attr('data-css-property') || '',
			value 		= switch_input.val();

		if( value == 1 || value == '1' ) {
			jQuery(this).parents(".cp-switch-wrapper").find("#"+id).attr('value','0');
			value = 0;
		} else {
			jQuery(this).parents(".cp-switch-wrapper").find("#"+id).attr('value','1');
			value = 1;
		}

		jQuery("#"+id).trigger('change');
		
		jQuery(document).trigger('cpro_switch_change', [switch_input.selector, name, value, property, 'field'] );
	});
});