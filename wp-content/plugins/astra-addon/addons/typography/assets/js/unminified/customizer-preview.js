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

	astra_responsive_font_size( 'astra-settings[font-size-primary-menu]', '.main-navigation, .ast-header-sections-navigation, .ast-above-header-menu-items, .ast-below-header-menu-items' );
	astra_responsive_font_size( 'astra-settings[font-size-primary-dropdown-menu]', '.main-header-menu > li > .sub-menu:first-of-type, .main-header-menu > li > .children:first-of-type, .ast-header-sections-navigation li > .sub-menu:first-of-type, .ast-above-header-menu-items li > .sub-menu:first-of-type, .ast-below-header-menu-items li > .sub-menu:first-of-type, .main-header-menu > li > .astra-full-megamenu-wrapper:first-of-type' );
	astra_responsive_font_size( 'astra-settings[font-size-post-meta]', '.entry-meta, .read-more' );
	astra_responsive_font_size( 'astra-settings[font-size-post-pagination]', '.ast-pagination .page-numbers, .ast-pagination .page-navigation' );
	astra_responsive_font_size( 'astra-settings[font-size-widget-title]', '.secondary .widget-title' );
	astra_responsive_font_size( 'astra-settings[font-size-widget-content]', '.secondary .widget > *:not(.widget-title)' );
	astra_responsive_font_size( 'astra-settings[font-size-footer-content]', '.ast-small-footer' );


	astra_css( 'astra-settings[line-height-primary-menu]', 'line-height', '.main-header-bar' );
	astra_css( 'astra-settings[line-height-primary-dropdown-menu]', 'line-height', '.main-header-bar .main-header-bar-navigation .sub-menu, .main-header-bar .main-header-bar-navigation .children, .ast-header-sections-navigation .sub-menu, .ast-above-header-menu-items .sub-menu, .ast-below-header-menu-items .sub-menu' );
	
	astra_css( 'astra-settings[line-height-archive-summary-title]', 'line-height', '.ast-archive-description .ast-archive-title' );
	astra_css( 'astra-settings[text-transform-archive-summary-title]', 'text-transform', '.ast-archive-description .ast-archive-title' );

	astra_css( 'astra-settings[line-height-page-title]', 'line-height', '.blog .entry-title, .archive .entry-title, .search .entry-title' );
	astra_css( 'astra-settings[text-transform-page-title]', 'text-transform', '.blog .entry-title, .blog .entry-title a, .archive .entry-title, .archive .entry-title a, .search .entry-title, .search .entry-title a' );


	astra_css( 'astra-settings[line-height-entry-title]', 'line-height', '.ast-single-post .entry-title, .page-title' );
	astra_css( 'astra-settings[text-transform-entry-title]', 'text-transform', '.ast-single-post .entry-title, .page-title' );

	astra_css( 'astra-settings[line-height-post-meta]', 'line-height', '.entry-meta, .read-more' );
	astra_css( 'astra-settings[text-transform-post-meta]', 'text-transform', '.entry-meta, .read-more' );

	astra_css( 'astra-settings[text-transform-post-pagination]', 'text-transform', '.ast-pagination .page-numbers, .ast-pagination .page-navigation' );

	astra_css( 'astra-settings[line-height-footer-content]', 'line-height', '.ast-small-footer' );
	astra_css( 'astra-settings[text-transform-footer-content]', 'text-transform', '.ast-small-footer' );

	astra_css( 'astra-settings[line-height-site-title]', 'line-height', '.site-title a' );
	astra_css( 'astra-settings[text-transform-site-title]', 'text-transform', '.site-title a' );

	astra_css( 'astra-settings[line-height-site-tagline]', 'line-height', '.site-header .site-description' );
	astra_css( 'astra-settings[text-transform-site-tagline]', 'text-transform', '.site-header .site-description' );

	astra_css( 'astra-settings[text-transform-primary-menu]', 'text-transform', '.main-header-bar .main-header-bar-navigation' );

	astra_css( 'astra-settings[text-transform-primary-dropdown-menu]', 'text-transform', '.main-header-bar .main-header-bar-navigation .sub-menu, .main-header-bar .main-header-bar-navigation .children, .ast-header-sections-navigation .sub-menu, .ast-above-header-menu-items .sub-menu, .ast-below-header-menu-items .sub-menu' );

	astra_css( 'astra-settings[line-height-widget-title]', 'line-height', '.secondary .widget-title' );
	astra_css( 'astra-settings[text-transform-widget-title]', 'text-transform', '.secondary .widget-title' );

	astra_css( 'astra-settings[line-height-widget-content]', 'line-height', '.secondary .widget > *:not(.widget-title)' );
	astra_css( 'astra-settings[text-transform-widget-content]', 'text-transform', '.secondary .widget > *:not(.widget-title)' );

	// Check if anchors should be loaded in the CSS for headings.	
	if (true == astraCustomizer.includeAnchorsInHeadindsCss) {
		astra_css('astra-settings[line-height-h1]', 'line-height', 'h1, .entry-content h1, .entry-content h1 a');
		astra_css('astra-settings[text-transform-h1]', 'text-transform', 'h1, .entry-content h1, .entry-content h1 a');

		astra_css('astra-settings[line-height-h2]', 'line-height', 'h2, .entry-content h2, .entry-content h2 a');
		astra_css('astra-settings[text-transform-h2]', 'text-transform', 'h2, .entry-content h2, .entry-content h2 a');

		astra_css('astra-settings[line-height-h3]', 'line-height', 'h3, .entry-content h3, .entry-content h3 a');
		astra_css('astra-settings[text-transform-h3]', 'text-transform', 'h3, .entry-content h3, .entry-content h3 a');

		astra_css('astra-settings[line-height-h4]', 'line-height', 'h4, .entry-content h4, .entry-content h4 a');
		astra_css('astra-settings[text-transform-h4]', 'text-transform', 'h4, .entry-content h4, .entry-content h4 a');

		astra_css('astra-settings[line-height-h5]', 'line-height', 'h5, .entry-content h5, .entry-content h5 a');
		astra_css('astra-settings[text-transform-h5]', 'text-transform', 'h5, .entry-content h5, .entry-content h5 a');

		astra_css('astra-settings[line-height-h6]', 'line-height', 'h6, .entry-content h6, .entry-content h6 a');
		astra_css('astra-settings[text-transform-h6]', 'text-transform', 'h6, .entry-content h6, .entry-content h6 a');
	} else {
		astra_css('astra-settings[line-height-h1]', 'line-height', 'h1, .entry-content h1');
		astra_css('astra-settings[text-transform-h1]', 'text-transform', 'h1, .entry-content h1');

		astra_css('astra-settings[line-height-h2]', 'line-height', 'h2, .entry-content h2');
		astra_css('astra-settings[text-transform-h2]', 'text-transform', 'h2, .entry-content h2');

		astra_css('astra-settings[line-height-h3]', 'line-height', 'h3, .entry-content h3');
		astra_css('astra-settings[text-transform-h3]', 'text-transform', 'h3, .entry-content h3');

		astra_css('astra-settings[line-height-h4]', 'line-height', 'h4, .entry-content h4');
		astra_css('astra-settings[text-transform-h4]', 'text-transform', 'h4, .entry-content h4');

		astra_css('astra-settings[line-height-h5]', 'line-height', 'h5, .entry-content h5');
		astra_css('astra-settings[text-transform-h5]', 'text-transform', 'h5, .entry-content h5');

		astra_css('astra-settings[line-height-h6]', 'line-height', 'h6, .entry-content h6');
		astra_css('astra-settings[text-transform-h6]', 'text-transform', 'h6, .entry-content h6');
	}

	astra_css( 'astra-settings[text-transform-button]', 'text-transform', 'button, .ast-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' );
	astra_responsive_font_size( 'astra-settings[font-size-button]', 'button, .ast-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' );
	
	astra_css( 'astra-settings[outside-menu-line-height]', 'line-height', '.ast-masthead-custom-menu-items, .ast-masthead-custom-menu-items *' );

} )( jQuery );
