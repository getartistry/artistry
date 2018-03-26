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

	/**
	 * Footer Widgets background color opacity
	 */
	wp.customize( 'astra-settings[footer-adv-bg-color-opac]', function( setting ) {
		setting.bind( function( bg_color_opac ) {
			if ( bg_color_opac == '' ) {
				wp.customize.preview.send( 'refresh' );

			} else {
				var bg_color     = wp.customize( 'astra-settings[footer-adv-bg-color]' ).get();
				var dynamicStyle = '.footer-adv-overlay {background-color: ' + astra_hex2rgba( bg_color, bg_color_opac ) + '}';
				astra_add_dynamic_css( 'footer-adv-bg-color-opac', dynamicStyle );
			}

		} );
	} );

	wp.customize( 'astra-settings[footer-adv-bg-img]', function( setting ) {
		setting.bind( function( bg_img ) {

			if ( bg_img == '') {
				wp.customize.preview.send( 'refresh' );
			} else {

				var bg_color      = wp.customize( 'astra-settings[footer-adv-bg-color]' ).get();
				var bg_color_opac = (typeof wp.customize( 'astra-settings[footer-adv-bg-color-opac]' ) != 'undefined') ? wp.customize( 'astra-settings[footer-adv-bg-color-opac]' ).get() : '';
				if ( bg_color_opac && bg_color ) {
					var dynamicStyle  = '.footer-adv-overlay { background-color: ' + astra_hex2rgba( bg_color,bg_color_opac ) + ';}';
				}
				else{
					var dynamicStyle  = '.footer-adv-overlay { background-color: ' + bg_color + ';}';
				}
					dynamicStyle += '.footer-adv { background-image: url(' + bg_img + '); }';
				astra_add_dynamic_css( 'footer-adv-bg-img', dynamicStyle );
			}

		} );
	} );

	/**
	 * Footer Widgets Padding
	 */
	astra_responsive_spacing( 'astra-settings[footer-adv-area-padding]','.footer-adv-overlay', 'padding', ['top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[footer-adv-area-padding]','.footer-adv .ast-container', 'padding', ['right', 'left' ] );

	astra_css( 'astra-settings[footer-adv-wgt-title-color]', 'color', '.footer-adv .widget-title, .footer-adv .widget-title a' );
	astra_css( 'astra-settings[footer-adv-text-color]', 'color', '.footer-adv' );
	astra_css( 'astra-settings[footer-adv-bg-size]', 'background-size', '.footer-adv' );
	astra_css( 'astra-settings[footer-adv-bg-pos]', 'background-position', '.footer-adv' );
	astra_css( 'astra-settings[footer-adv-bg-attac]', 'background-attachment', '.footer-adv' );
	astra_css( 'astra-settings[footer-adv-bg-repeat]', 'background-repeat', '.footer-adv' );

	astra_responsive_font_size( 'astra-settings[footer-adv-wgt-title-font-size]', '.footer-adv .widget-title, .footer-adv .widget-title a.rsswidget, .ast-no-widget-row .widget-title' );
	astra_responsive_font_size( 'astra-settings[footer-adv-wgt-content-font-size]', '.footer-adv .widget > *:not(.widget-title)' );

	astra_css( 'astra-settings[footer-adv-wgt-title-line-height]', 'line-height', '.footer-adv .widget-title, .footer-adv .widget-title a.rsswidget, .ast-no-widget-row .widget-title' );
	astra_css( 'astra-settings[footer-adv-wgt-title-text-transform]', 'text-transform', '.footer-adv .widget-title, .footer-adv .widget-title a.rsswidget, .ast-no-widget-row .widget-title' );

	astra_css( 'astra-settings[footer-adv-wgt-content-line-height]', 'line-height', '.footer-adv .widget > *:not(.widget-title)' );
	astra_css( 'astra-settings[footer-adv-wgt-content-text-transform]', 'text-transform', '.footer-adv .widget > *:not(.widget-title)' );

} )( jQuery );
