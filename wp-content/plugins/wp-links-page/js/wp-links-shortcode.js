(function($) {
jQuery(document).ready(function($) {
$(window).load(function() {
	
	$('#wplp-sb #cat').attr('disabled', 'disabled');
	$('#wplp-sb #cat').attr('multiple', 'multiple');
	
	//radios
	$('#wplp-sb .radio-i label:not(.pro), #wplp-sb .radio-no-i label:not(.pro)').click(function() {
			$(this).css('background-color', '#008ec2');	
			$(this).css('color', '#ffffff');
			$(this).siblings('label').css('background-color', '#e5e5e5');	
			$(this).siblings('label').css('color', 'inherit');
	});
	
	//checkboxes
	$('#wplp-sb .checks label:not(.pro)').click(function() {
		if ($(this).children('input').is(':checked')) {
			$(this).css('background-color', '#008ec2');	
			$(this).css('color', '#ffffff');
		} else {
			$(this).css('background-color', '#e5e5e5');	
			$(this).css('color', 'inherit');
		}
	});
	
	$('#wplp-sb #tabs-1 label input').change(function() {
		if ($(this).val() == 'grid') {
			$('.grid').show();	
		} else {
			$('.grid').hide();
		}
		
		if ($(this).val() == 'carousel') {
			$('.carousel').show();	
			$('.not-carousel').hide();	
		} else {
			$('.carousel').hide();
			$('.not-carousel').show();	
		}
	});
	
	$('#wplp-sb input, #wplp-sb select').change(function() {
		var sc = '[wp_links_page';
		
		if (typeof $('input[name=wplp-display]:checked').val() != 'undefined') {
		sc += ' display="'+$('input[name=wplp-display]:checked').val()+'"';
		}
		
		if (typeof $('input[name=wplp-columns]:checked').val() != 'undefined') {
		sc += ' cols="'+$('input[name=wplp-columns]:checked').val()+'"';
		}
		
		if (typeof $('input[name=wplp-image-size]:checked').val() != 'undefined') {
		sc += ' img_size="'+$('input[name=wplp-image-size]:checked').val()+'"';
		}
		
		if (typeof $('input[name=wplp-desc]:checked').val() != 'undefined') {
		sc += ' desc="'+$('input[name=wplp-desc]:checked').val()+'"';
		}
		
		sc += ']';
		$('#final-shortcode').val(sc);
	});
	
});
}); 
})(jQuery);