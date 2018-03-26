/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra Addon
 * @since  1.0.0
 */
( function( $ ) {

	astra_css_font_size( 'astra-settings[scroll-to-top-icon-size]', '#ast-scroll-top' );
	astra_css( 'astra-settings[scroll-to-top-icon-radius]', 'border-radius', '#ast-scroll-top', 'px' );
	astra_css( 'astra-settings[scroll-to-top-icon-color]', 'color', '#ast-scroll-top' );
	astra_css( 'astra-settings[scroll-to-top-icon-h-color]', 'color', '#ast-scroll-top:hover' );
	astra_css( 'astra-settings[scroll-to-top-icon-h-bg-color]', 'background-color', '#ast-scroll-top:hover' );

	// Scroll to top position.
	wp.customize( 'astra-settings[scroll-to-top-icon-position]', function( value ) {
		value.bind( function( position ) {
			jQuery("#ast-scroll-top").removeClass("ast-scroll-to-top-right ast-scroll-to-top-left");
			jQuery("#ast-scroll-top").addClass("ast-scroll-to-top-"+position);
		} );
	} );

	wp.customize( 'astra-settings[scroll-to-top-icon-bg-color]', function( value ) {
		value.bind( function( color ) {
			if (color == '') {
				wp.customize.preview.send( 'refresh' );
			}
			var dynamicStyle = '#ast-scroll-top{ background-color: ' + astra_hex2rgba( color,.8 ) + '}';
				astra_add_dynamic_css( 'scroll-to-top-icon-bg-color', dynamicStyle );
		} );
	} );

	wp.customize( 'astra-settings[scroll-to-top-icon-h-bg-color]', function( value ) {
		value.bind( function( bg_color ) {
			if (bg_color == '') {
				wp.customize.preview.send( 'refresh' );
			}
			var dynamicStyle = '#ast-scroll-top:hover{ background-color: ' + astra_hex2rgba( bg_color,.8 ) + '}';
			astra_add_dynamic_css( 'scroll-to-top-icon-h-bg-color', dynamicStyle );
		} );
	} );

} )( jQuery );
