jQuery(document).ready( function() {

	jQuery( '#astra-pro-sites-license-form-btn' ).on( 'click', function() {
		jQuery( 'body' ).addClass('astra-license-form-open');

		setTimeout(function() {
			jQuery( '#TB_window' )
				.addClass('astra-license-form')
				.removeClass('thickbox-loading')
				.find('.inner').removeAttr('style');
		}, 100);
	});

});