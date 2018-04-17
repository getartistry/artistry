<?php
/**
 * Transparent Header - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_ext_transparent_header_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_ext_transparent_header_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	/**
	 * Set colors
	 *
	 * If colors extension is_active then get color from it.
	 * Else set theme default colors.
	 */
	$transparent_header_separator       = astra_get_option( 'transparent-header-main-sep' );
	$transparent_header_separator_color = astra_get_option( 'transparent-header-main-sep-color' );

	$transparent_header_logo_width = astra_get_option( 'transparent-header-logo-width' );

	$header_break_point = astra_header_break_point(); // Header Break Point.

	/**
	 * Generate Dynamic CSS
	 */

	$css = '';

	// Desktop Transparent Heder Logo Width.
	$css_output = array(
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo .astra-logo-svg' => array(
			'width' => astra_get_css_value( $transparent_header_logo_width['desktop'], 'px' ),
		),
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo img' => array(
			' max-width' => astra_get_css_value( $transparent_header_logo_width['desktop'], 'px' ),
		),
	);
	$css       .= astra_parse_css( $css_output );

	// Tablet Transparent Heder Logo Width.
	$tablet_css_output = array(
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo .astra-logo-svg' => array(
			'width' => astra_get_css_value( $transparent_header_logo_width['tablet'], 'px' ),
		),
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo img' => array(
			' max-width' => astra_get_css_value( $transparent_header_logo_width['tablet'], 'px' ),
		),
	);
	$css              .= astra_parse_css( $tablet_css_output, '', '768' );

	// Mobile Transparent Heder Logo Width.
	$mobile_css_output = array(
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo .astra-logo-svg' => array(
			'width' => astra_get_css_value( $transparent_header_logo_width['mobile'], 'px' ),
		),
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo img' => array(
			' max-width' => astra_get_css_value( $transparent_header_logo_width['mobile'], 'px' ),
		),
	);
	$css              .= astra_parse_css( $mobile_css_output, '', '543' );

	$css         .= '.ast-theme-transparent-header.ast-header-break-point .site-header {';
		$css     .= 'border-bottom-width:' . astra_get_css_value( $transparent_header_separator, 'px' ) . ';';
		$css     .= 'border-bottom-color:' . esc_attr( $transparent_header_separator_color ) . ';';
	$css         .= '}';
	$css         .= '@media (min-width: 769px) {';
		$css     .= '.ast-theme-transparent-header .main-header-bar {';
			$css .= 'border-bottom-width:' . astra_get_css_value( $transparent_header_separator, 'px' ) . ';';
			$css .= 'border-bottom-color:' . esc_attr( $transparent_header_separator_color ) . ';';
		$css     .= '}';
	$css         .= '}';

	return $dynamic_css .= $css;

}
