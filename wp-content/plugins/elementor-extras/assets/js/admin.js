(function($){

	var activetab = '';

	// Initiate Color Picker
	$('.wp-color-picker-field').wpColorPicker();

	// Switches option sections
	$('.ee-settings__group').hide();

	if ( typeof( localStorage ) !== 'undefined' ) {
		activetab = localStorage.getItem( "activetab" );
	}
	
	if( window.location.hash ) {

		activetab = window.location.hash;

		if ( typeof( localStorage ) !== 'undefined' ) {
			
			localStorage.setItem( "activetab", activetab );
		
		}                
	} 
	
	if ( activetab != '' && $( activetab ).length ) {
		
		$(activetab).fadeIn();
	
	} else {
	
		$('.ee-settings__group:first').fadeIn();
	
	}

	$('.ee-settings__group .collapsed').each( function() {

		$(this).find('input:checked').parent().parent().parent().nextAll().each(

			function() {

				if ( $(this).hasClass('last') ) {
					$(this).removeClass('hidden');
					return false;
				}

				$(this).filter('.hidden').removeClass('hidden');
			}

		);
	});

	if ( activetab != '' && $( activetab + '-tab' ).length ) {
		
		$( activetab + '-tab' ).addClass('nav-tab-active');
	
	} else {

		$('.ee-nav-tabs a:first').addClass('nav-tab-active');
	
	}
	
	$('.ee-nav-tabs a').click( function( e ) {

		if ( $(this).is( '.ee-nav-tabs__link' ) ) {
			return;
		}

		$('.ee-nav-tabs a').removeClass('nav-tab-active');

		$(this).addClass('nav-tab-active').blur();

		var clicked_group = $(this).attr('href');

		if ( typeof( localStorage ) !== 'undefined' ) {
			
			localStorage.setItem("activetab", $(this).attr('href'));
		
		}
		
		$('.ee-settings__group').hide();
		
		$(clicked_group).fadeIn();
		
		e.preventDefault();
	});

})(jQuery);