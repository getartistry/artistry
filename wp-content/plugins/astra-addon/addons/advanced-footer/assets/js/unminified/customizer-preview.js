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
	 * Footer Widgets Padding
	 */
	astra_responsive_spacing( 'astra-settings[footer-adv-area-padding]','.footer-adv-overlay', 'padding', ['top', 'bottom' ] );
	astra_responsive_spacing( 'astra-settings[footer-adv-area-padding]','.footer-adv .ast-container', 'padding', ['right', 'left' ] );

	astra_css( 'astra-settings[footer-adv-wgt-title-color]', 'color', '.footer-adv .widget-title, .footer-adv .widget-title a' );
	astra_css( 'astra-settings[footer-adv-text-color]', 'color', '.footer-adv' );

	astra_responsive_font_size( 'astra-settings[footer-adv-wgt-title-font-size]', '.footer-adv .widget-title, .footer-adv .widget-title a.rsswidget, .ast-no-widget-row .widget-title' );
	astra_responsive_font_size( 'astra-settings[footer-adv-wgt-content-font-size]', '.footer-adv .widget > *:not(.widget-title)' );

	astra_css( 'astra-settings[footer-adv-wgt-title-line-height]', 'line-height', '.footer-adv .widget-title, .footer-adv .widget-title a.rsswidget, .ast-no-widget-row .widget-title' );
	astra_css( 'astra-settings[footer-adv-wgt-title-text-transform]', 'text-transform', '.footer-adv .widget-title, .footer-adv .widget-title a.rsswidget, .ast-no-widget-row .widget-title' );

	astra_css( 'astra-settings[footer-adv-wgt-content-line-height]', 'line-height', '.footer-adv .widget > *:not(.widget-title)' );
	astra_css( 'astra-settings[footer-adv-wgt-content-text-transform]', 'text-transform', '.footer-adv .widget > *:not(.widget-title)' );

} )( jQuery );
