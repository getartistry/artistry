<?php
/**
 * Transparent Header - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_ext_sticky_header_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_ext_sticky_header_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	/**
	 * Set colors
	 *
	 * If colors extension is_active then get color from it.
	 * Else set theme default colors.
	 */
	$stick_header            = astra_get_option_meta( 'stick-header-meta' );
	$stick_header_main_meta  = astra_get_option_meta( 'header-main-stick-meta' );
	$stick_header_above_meta = astra_get_option_meta( 'header-above-stick-meta' );
	$stick_header_below_meta = astra_get_option_meta( 'header-below-stick-meta' );

	$stick_header_main  = astra_get_option( 'header-main-stick' );
	$stick_header_above = astra_get_option( 'header-above-stick' );
	$stick_header_below = astra_get_option( 'header-below-stick' );

	$sticky_header_logo_width = astra_get_option( 'sticky-header-logo-width' );
	// Old Log Width Option that we are no loginer using it our theme.
	$header_logo_width            = astra_get_option( 'ast-header-logo-width' );
	$header_responsive_logo_width = astra_get_option( 'ast-header-responsive-logo-width' );

	$sticky_header_bg_opc = astra_get_option( 'sticky-header-bg-opc' );

	$site_layout = astra_get_option( 'site-layout' );

	$header_color_site_title = '#222';
	$text_color              = astra_get_option( 'text-color' );
	$link_color              = astra_get_option( 'link-color' );
	// Header Break Point.
	$header_break_point = astra_header_break_point();

	if ( ! $stick_header_main && ! $stick_header_above && ! $stick_header_below && ( 'disabled' !== $stick_header && empty( $stick_header ) && ( empty( $stick_header_above_meta ) || empty( $stick_header_below_meta ) || empty( $stick_header_main_meta ) ) ) ) {
		return $dynamic_css;
	}

	$parse_css = '';

	// Desktop Sticky Header Logo width.
	$css_output = array(
		'#masthead .site-logo-img .sticky-custom-logo .astra-logo-svg, .site-logo-img .sticky-custom-logo .astra-logo-svg, .ast-sticky-main-shrink .ast-sticky-shrunk .site-logo-img .astra-logo-svg' => array(
			'width' => astra_get_css_value( $sticky_header_logo_width['desktop'], 'px' ),
		),
		'.site-logo-img .sticky-custom-logo img' => array(
			'max-width' => astra_get_css_value( $sticky_header_logo_width['desktop'], 'px' ),
		),
	);
	$parse_css .= astra_parse_css( $css_output );

	// Tablet Sticky Header Logo width.
	$tablet_css_output = array(
		'#masthead .site-logo-img .sticky-custom-logo .astra-logo-svg, .site-logo-img .sticky-custom-logo .astra-logo-svg, .ast-sticky-main-shrink .ast-sticky-shrunk .site-logo-img .astra-logo-svg' => array(
			'width' => astra_get_css_value( $sticky_header_logo_width['tablet'], 'px' ),
		),
		'.site-logo-img .sticky-custom-logo img' => array(
			'max-width' => astra_get_css_value( $sticky_header_logo_width['tablet'], 'px' ),
		),
	);
	$parse_css        .= astra_parse_css( $tablet_css_output, '', '768' );

	// Mobile Sticky Header Logo width.
	$mobile_css_output = array(
		'#masthead .site-logo-img .sticky-custom-logo .astra-logo-svg, .site-logo-img .sticky-custom-logo .astra-logo-svg, .ast-sticky-main-shrink .ast-sticky-shrunk .site-logo-img .astra-logo-svg' => array(
			'width' => astra_get_css_value( $sticky_header_logo_width['mobile'], 'px' ),
		),
		'.site-logo-img .sticky-custom-logo img' => array(
			'max-width' => astra_get_css_value( $sticky_header_logo_width['mobile'], 'px' ),
		),
	);
	$parse_css        .= astra_parse_css( $mobile_css_output, '', '543' );

	// Theme Main Logo width option for responsive devices.
	if ( is_array( $header_responsive_logo_width ) ) {
		/* Responsive main logo width */
		$responsive_logo_output = array(
			'#masthead .site-logo-img .astra-logo-svg' => array(
				'max-width' => astra_get_css_value( $header_responsive_logo_width['desktop'], 'px' ),
			),
		);
		$parse_css             .= astra_parse_css( $responsive_logo_output );

		$responsive_logo_output_tablet = array(
			'#masthead .site-logo-img .astra-logo-svg' => array(
				'max-width' => astra_get_css_value( $header_responsive_logo_width['tablet'], 'px' ),
			),
		);
		$parse_css                    .= astra_parse_css( $responsive_logo_output_tablet, '', '768' );

		$responsive_logo_output_mobile = array(
			'#masthead .site-logo-img .astra-logo-svg' => array(
				'max-width' => astra_get_css_value( $header_responsive_logo_width['mobile'], 'px' ),
			),
		);
		$parse_css                    .= astra_parse_css( $responsive_logo_output_mobile, '', '543' );
	} else {
		/* Old main logo width */
		$logo_output = array(
			'#masthead .site-logo-img .astra-logo-svg' => array(
				'width' => astra_get_css_value( $header_logo_width, 'px' ),
			),
		);
			/* Parse CSS from array() */
		$parse_css .= astra_parse_css( $logo_output );
	}

	// Compatible with header full width.
	$header_break_point = astra_header_break_point();
	$astra_header_width = astra_get_option( 'header-main-layout-width' );

	/* Width for Header */
	if ( 'content' != $astra_header_width ) {
		$general_global_responsive = array(
			'#ast-fixed-header .ast-container' => array(
				'max-width'     => '100%',
				'padding-left'  => '35px',
				'padding-right' => '35px',
			),
		);

		/* Parse CSS from array()*/
		$parse_css .= astra_parse_css( $general_global_responsive, $header_break_point );
	}

	if ( ! Astra_Ext_Extension::is_active( 'colors-and-background' ) ) {
		$css_output = array(
			'#ast-fixed-header .main-header-bar .site-title a, #ast-fixed-header .main-header-bar .site-title a:focus, #ast-fixed-header .main-header-bar .site-title a:hover, #ast-fixed-header .main-header-bar .site-title a:visited, .main-header-bar.ast-sticky-active .site-title a, .main-header-bar.ast-sticky-active .site-title a:focus, .main-header-bar.ast-sticky-active .site-title a:hover, .main-header-bar.ast-sticky-active .site-title a:visited' => array(
				'color' => esc_attr( $header_color_site_title ),
			),
			'#ast-fixed-header .main-header-bar .site-description, .main-header-bar.ast-sticky-active .site-description' => array(
				'color' => esc_attr( $text_color ),
			),

			'#ast-fixed-header .main-header-menu > li.current-menu-item > a, #ast-fixed-header .main-header-menu >li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu > li.current_page_item > a, .main-header-bar.ast-sticky-active .main-header-menu > li.current-menu-item > a, .main-header-bar.ast-sticky-active .main-header-menu >li.current-menu-ancestor > a, .main-header-bar.ast-sticky-active .main-header-menu > li.current_page_item > a' => array(
				'color' => esc_attr( $link_color ),
			),

			'#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu > li > a, #ast-fixed-header  .ast-masthead-custom-menu-items .slide-search .search-submit, #ast-fixed-header .ast-masthead-custom-menu-items, #ast-fixed-header .ast-masthead-custom-menu-items a, .main-header-bar.ast-sticky-active, .main-header-bar.ast-sticky-active .main-header-menu > li > a, .main-header-bar.ast-sticky-active  .ast-masthead-custom-menu-items .slide-search .search-submit, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items a' => array(
				'color' => esc_attr( $text_color ),
			),
			'#ast-fixed-header .main-header-menu a:hover, #ast-fixed-header .main-header-menu li:hover > a, #ast-fixed-header .main-header-menu li.focus > a, .main-header-bar.ast-sticky-active .main-header-menu li:hover > a, .main-header-bar.ast-sticky-active .main-header-menu li.focus > a' => array(
				'color' => esc_attr( $link_color ),
			),
			'#ast-fixed-header .main-header-menu .ast-masthead-custom-menu-items a:hover, #ast-fixed-header .main-header-menu li:hover > .ast-menu-toggle, #ast-fixed-header .main-header-menu li.focus > .ast-menu-toggle,.main-header-bar.ast-sticky-active .main-header-menu li:hover > .ast-menu-toggle,.main-header-bar.ast-sticky-active .main-header-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $link_color ),
			),
		);

		/* Parse CSS from array() */
		$parse_css .= astra_parse_css( $css_output );
	}

	$colors = array(
		'header-main'  => ( Astra_Ext_Extension::is_active( 'colors-and-background' ) ) ? astra_get_option( 'header-bg-color' ) : '#ffffff',
		'primary-menu' => ( Astra_Ext_Extension::is_active( 'colors-and-background' ) ) ? astra_get_option( 'primary-menu-bg-color' ) : '',
	);

	if ( ( Astra_Ext_Extension::is_active( 'header-sections' ) ) ) {
		$below_header_bg_obj   = astra_get_option( 'below-header-bg-obj' );
		$colors['header-supp'] = isset( $below_header_bg_obj['background-color'] ) ? $below_header_bg_obj['background-color'] : '#414042';
	} else {
		$colors['header-supp'] = '#414042';
	}

	if ( ( Astra_Ext_Extension::is_active( 'header-sections' ) ) ) {
		$above_header_bg_obj  = astra_get_option( 'above-header-bg-obj' );
		$colors['header-top'] = isset( $above_header_bg_obj['background-color'] ) ? $above_header_bg_obj['background-color'] : '';
	} else {
		$colors['header-top'] = '#ffffff';
	}

	$colors['header-main']  = ( '' != $colors['header-main'] ) ? $colors['header-main'] : '#ffffff';
	$colors['primary-menu'] = ( '' != $colors['primary-menu'] ) ? $colors['primary-menu'] : '';
	$colors['header-top']   = ( '' != $colors['header-top'] ) ? $colors['header-top'] : '#ffffff';
	$colors['header-supp']  = ( '' != $colors['header-supp'] ) ? $colors['header-supp'] : '#414042';

	/**
	 * Set RGBA color if transparent header is active
	 */
	$colors['header-main'] = ( ! empty( $colors['header-main'] ) ) ? astra_hex2rgba( astra_rgba2hex( $colors['header-main'] ), $sticky_header_bg_opc ) : '';

	if ( '' !== $colors['primary-menu'] ) {
		$colors['primary-menu'] = ( ! empty( $colors['primary-menu'] ) ) ? astra_hex2rgba( astra_rgba2hex( $colors['primary-menu'] ), $sticky_header_bg_opc ) : '';
	}

	$colors['header-top']  = ( ! empty( $colors['header-top'] ) ) ? astra_hex2rgba( astra_rgba2hex( $colors['header-top'] ), $sticky_header_bg_opc ) : '';
	$colors['header-supp'] = ( ! empty( $colors['header-supp'] ) ) ? astra_hex2rgba( astra_rgba2hex( $colors['header-supp'] ), $sticky_header_bg_opc ) : '';

	/**
	 * Generate Dynamic CSS
	 */

	$css = '';

	$css .= '.ast-transparent-header #ast-fixed-header .main-header-bar, #ast-fixed-header .main-header-bar, .ast-transparent-header .main-header-bar.ast-sticky-active, .main-header-bar.ast-sticky-active, #ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field, #ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field:focus {';
	$css .= 'background-color:' . esc_attr( $colors['header-main'] ) . ';';
	$css .= '}';
	$css .= '#ast-fixed-header .main-header-bar .main-header-menu, .main-header-bar.ast-sticky-active .main-header-menu, .ast-header-break-point #ast-fixed-header .main-header-menu, #ast-fixed-header .ast-masthead-custom-menu-items {';
	$css .= 'background-color:' . esc_attr( $colors['primary-menu'] ) . ';';
	$css .= '}';

	$primary_nav = astra_get_option( 'disable-primary-nav' );
	if ( $primary_nav ) {
		$css .= '.main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items {';
		$css .= 'background-color:' . esc_attr( $colors['primary-menu'] ) . ';';
		$css .= '}';
	}
	if ( Astra_Ext_Extension::is_active( 'header-sections' ) ) {
		$main_stick  = astra_get_option( 'header-main-stick' );
		$below_stick = astra_get_option( 'header-below-stick' );
		if ( 1 == $main_stick && 1 == $below_stick ) {
			$css .= '.ast-stick-primary-below-wrapper.ast-sticky-active .main-header-bar, .ast-stick-primary-below-wrapper.ast-sticky-active .ast-masthead-custom-menu-items .ast-inline-search .search-field, .ast-stick-primary-below-wrapper.ast-sticky-active .ast-masthead-custom-menu-items .ast-inline-search .search-field:focus {';
			$css .= 'background-color:' . esc_attr( $colors['header-main'] ) . ';';
			$css .= '}';
			$css .= '.ast-stick-primary-below-wrapper.ast-sticky-active .main-header-bar .main-header-menu, .main-header-bar.ast-sticky-active .main-header-menu, .ast-header-break-point .ast-stick-primary-below-wrapper.ast-sticky-active .main-header-menu, .ast-stick-primary-below-wrapper.ast-sticky-active .ast-masthead-custom-menu-items {';
			$css .= 'background-color:' . esc_attr( $colors['primary-menu'] ) . ';';
			$css .= '}';
			$css .= '.ast-stick-primary-below-wrapper.ast-sticky-active .ast-below-header, .ast-stick-primary-below-wrapper.ast-sticky-active .ast-below-header .ast-search-menu-icon .search-field {';
			$css .= 'background-color:' . esc_attr( $colors['header-supp'] ) . ';';
			$css .= '}';
		}
	}

	if ( Astra_Ext_Extension::is_active( 'header-sections' ) ) {
		$css .= '.ast-sticky-active .ast-above-header, .ast-above-header.ast-sticky-active, .ast-sticky-active .ast-above-header .ast-search-menu-icon .search-field, .ast-above-header.ast-sticky-active .ast-search-menu-icon .search-field {';
		$css .= 'background-color:' . esc_attr( $colors['header-top'] ) . ';';
		$css .= 'visibility:' . esc_attr( 'visible' ) . ';';
		$css .= '}';

		$css .= '.ast-sticky-active .ast-below-header, .ast-below-header.ast-sticky-active, .ast-below-header-wrap .ast-sticky-active .ast-search-menu-icon .search-field {';
		$css .= 'background-color:' . esc_attr( $colors['header-supp'] ) . ';';
		$css .= 'visibility:' . esc_attr( 'visible' ) . ';';
		$css .= '}';
	}

	$page_width = '100%';
	if ( Astra_Ext_Extension::is_active( 'site-layouts' ) ) {
		if ( 'ast-box-layout' == $site_layout ) {
			$page_width = astra_get_option( 'site-layout-box-width' ) . 'px';
		}

		if ( 'ast-padded-layout' == $site_layout ) {

			$padded_layout_padding = astra_get_option( 'site-layout-padded-pad' );

			/**
			 * Padded layout Desktop Spacing
			 */
			$padded_layout_spacing = array(
				'#ast-fixed-header' => array(
					'top'    => astra_responsive_spacing( $padded_layout_padding, 'top', 'desktop' ),
					'left'   => astra_responsive_spacing( $padded_layout_padding, 'left', 'desktop' ),
					'margin' => esc_attr( 0 ),
				),
			);
			/**
			 * Padded layout Tablet Spacing
			 */
			$tablet_padded_layout_spacing = array(
				'#ast-fixed-header' => array(
					'top'    => astra_responsive_spacing( $padded_layout_padding, 'top', 'tablet' ),
					'left'   => astra_responsive_spacing( $padded_layout_padding, 'left', 'tablet' ),
					'margin' => esc_attr( 0 ),
				),
			);

			/**
			 * Padded layout Mobile Spacing
			 */
			$mobile_padded_layout_spacing = array(
				'#ast-fixed-header' => array(
					'top'    => astra_responsive_spacing( $padded_layout_padding, 'top', 'mobile' ),
					'left'   => astra_responsive_spacing( $padded_layout_padding, 'left', 'mobile' ),
					'margin' => esc_attr( 0 ),
				),
			);

			$parse_css .= astra_parse_css( $padded_layout_spacing );
			$parse_css .= astra_parse_css( $tablet_padded_layout_spacing, '', '768' );
			$parse_css .= astra_parse_css( $mobile_padded_layout_spacing, '', '544' );
		}
	}

	$css       .= '.ast-above-header > div, .main-header-bar > div, .ast-below-header > div {';
	$css       .= '-webkit-transition: all 0.2s linear;';
	$css       .= 'transition: all 0.2s linear;';
	$css       .= '}';
	$css       .= '.ast-above-header, .main-header-bar, .ast-below-header {';
	$css       .= 'max-width:' . esc_attr( $page_width ) . ';';
	$css       .= '-webkit-transition: all 0.2s linear, max-width 0s;transition: all 0.2s linear, max-width 0s;';
	$css       .= '}';
	$parse_css .= $css;

	return $dynamic_css .= $parse_css;

}
