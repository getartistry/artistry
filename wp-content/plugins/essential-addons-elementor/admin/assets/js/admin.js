/**
 * Eael Admin Script
 *
 * @since  v1.0.0
 */

;( function( $ ) {
	'use strict';

	/**
	 * Eael Tabs
	 */
	$( '.eael-tabs li a' ).on( 'click', function(e) {
		e.preventDefault();
		$( '.eael-tabs li a' ).removeClass( 'active' );
		$(this).addClass( 'active' );
		var tab = $(this).attr( 'href' );
		$( '.eael-settings-tab' ).removeClass( 'active' );
		$( '.eael-settings-tabs' ).find( tab ).addClass( 'active' );
	});

	/**
	 * Save Button Reacting on Any Changes
	 */
	var headerSaveBtn = $( '.eael-header-bar .eael-btn' );
	var footerSaveBtn = $( '.eael-save-btn-wrap .eael-btn' );
	$('.eael-checkbox input[type="checkbox"]').on( 'click', function() {
		headerSaveBtn.addClass( 'save-now' );
		footerSaveBtn.addClass( 'save-now' );
	} );

	/**
	 * Saving Data With Ajax Request
	 */
	$( '.js-eael-settings-save' ).on( 'click', function(e) {
		e.preventDefault();
		$.ajax( {
			url: js_eael_pro_settings.ajaxurl,
			type: 'post',
			data: {
				action: 'save_settings_with_ajax',
				fields: $( 'form#eael-settings' ).serialize(),
			},
			success: function( response ) {
				swal({
					title: 'Settings Saved!',
					test: 'Click OK to continue',
					type: 'success',
				});
				headerSaveBtn.removeClass( 'save-now' );
				footerSaveBtn.removeClass( 'save-now' );
			},
			error: function() {
				swal(
				  'Oops...',
				  'Something went wrong!',
				  'error'
				);
			}
		} );
	} );
	$('#essential-addons-elementor-license-key').on('keypress', function(e) {
		if(e.which == 13) {
			$('.eael-license-activation-btn').click();
			return false;
		}
	});
} )( jQuery );
