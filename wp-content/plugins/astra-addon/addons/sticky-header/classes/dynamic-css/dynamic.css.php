<?php
/**
 * Transparent Header - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_ext_sticky_header_dynamic_css', 30 );

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

	$sticky_header_style   = astra_get_option( 'sticky-header-style' );
	$sticky_hide_on_scroll = astra_get_option( 'sticky-hide-on-scroll' );

	$sticky_header_logo_width = astra_get_option( 'sticky-header-logo-width' );
	// Old Log Width Option that we are no loginer using it our theme.
	$header_logo_width            = astra_get_option( 'ast-header-logo-width' );
	$header_responsive_logo_width = astra_get_option( 'ast-header-responsive-logo-width' );

	$site_layout = astra_get_option( 'site-layout' );

	$header_color_site_title = '#222';
	$text_color              = astra_get_option( 'text-color' );
	$link_color              = astra_get_option( 'link-color' );

	$sticky_header_logo_different = astra_get_option( 'different-sticky-logo' );
	$sticky_header_logo           = astra_get_option( 'sticky-header-logo' );

	// Compatible with header full width.
	$header_break_point = astra_header_break_point();
	$astra_header_width = astra_get_option( 'header-main-layout-width' );

	// Sticky Header Background colors.
	$text_color = astra_get_option( 'text-color' );
	$link_color = astra_get_option( 'link-color' );

	$desktop_sticky_header_bg_color = astar( astra_get_option( 'sticky-header-bg-color-responsive' ), 'desktop', '#ffffff' );
	$tablet_sticky_header_bg_color  = astar( astra_get_option( 'sticky-header-bg-color-responsive' ), 'tablet' );
	$mobile_sticky_header_bg_color  = astar( astra_get_option( 'sticky-header-bg-color-responsive' ), 'mobile' );

	$sticky_header_menu_bg_color = astra_get_option( 'sticky-header-menu-bg-color-responsive' );

	$desktop_sticky_header_color_site_title = astar( astra_get_option( 'sticky-header-color-site-title-responsive' ), 'desktop', '#222' );
	$tablet_sticky_header_color_site_title  = astar( astra_get_option( 'sticky-header-color-site-title-responsive' ), 'tablet' );
	$mobile_sticky_header_color_site_title  = astar( astra_get_option( 'sticky-header-color-site-title-responsive' ), 'mobile' );

	$sticky_header_color_h_site_title = astra_get_option( 'sticky-header-color-h-site-title-responsive' );

	$desktop_sticky_header_color_site_tagline = astar( astra_get_option( 'sticky-header-color-site-tagline-responsive' ), 'desktop', $text_color );
	$tablet_sticky_header_color_site_tagline  = astar( astra_get_option( 'sticky-header-color-site-tagline-responsive' ), 'tablet' );
	$mobile_sticky_header_color_site_tagline  = astar( astra_get_option( 'sticky-header-color-site-tagline-responsive' ), 'mobile' );

	$desktop_sticky_primary_menu_color = astar( astra_get_option( 'sticky-header-menu-color-responsive' ), 'desktop', $text_color );
	$tablet_sticky_primary_menu_color  = astar( astra_get_option( 'sticky-header-menu-color-responsive' ), 'tablet' );
	$mobile_sticky_primary_menu_color  = astar( astra_get_option( 'sticky-header-menu-color-responsive' ), 'mobile' );

	$desktop_sticky_primary_menu_h_color = astar( astra_get_option( 'sticky-header-menu-h-color-responsive' ), 'desktop', $link_color );
	$tablet_sticky_primary_menu_h_color  = astar( astra_get_option( 'sticky-header-menu-h-color-responsive' ), 'tablet' );
	$mobile_sticky_primary_menu_h_color  = astar( astra_get_option( 'sticky-header-menu-h-color-responsive' ), 'mobile' );

	$sticky_header_menu_h_a_bg_color = astra_get_option( 'sticky-header-menu-h-a-bg-color-responsive' );

	$sticky_header_submenu_bg_color      = astra_get_option( 'sticky-header-submenu-bg-color-responsive' );
	$sticky_primary_submenu_color        = astra_get_option( 'sticky-header-submenu-color-responsive' );
	$sticky_primary_submenu_h_color      = astra_get_option( 'sticky-header-submenu-h-color-responsive' );
	$sticky_primary_submenu_h_a_bg_color = astra_get_option( 'sticky-header-submenu-h-a-bg-color-responsive' );

	$sticky_header_content_section_text_color   = astra_get_option( 'sticky-header-content-section-text-color-responsive' );
	$sticky_header_content_section_link_color   = astra_get_option( 'sticky-header-content-section-link-color-responsive' );
	$sticky_header_content_section_link_h_color = astra_get_option( 'sticky-header-content-section-link-h-color-responsive' );

	if ( ! $stick_header_main && ! $stick_header_above && ! $stick_header_below && ( 'disabled' !== $stick_header && empty( $stick_header ) && ( empty( $stick_header_above_meta ) || empty( $stick_header_below_meta ) || empty( $stick_header_main_meta ) ) ) ) {
		return $dynamic_css;
	}

	$parse_css = '';

	/**
	 * Sticky Header
	 *
	 * [1]. Apply default colors from theme for sticky header.
	 * [2]. Hide Sticky Header logo if Sticky Header logo is not enabled.
	 * [3]. Sticky Header Logo responsive widths.
	 * [4]. Compatible with Header Width.
	 * [5]. Stciky Header & Sticky Header Primary menu background color.
	 */

	/**
	 * [1]. Apply default colors from theme for sticky header.
	 */
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

			'#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu > li > a, #ast-fixed-header .ast-masthead-custom-menu-items, #ast-fixed-header .ast-masthead-custom-menu-items a, .main-header-bar.ast-sticky-active, .main-header-bar.ast-sticky-active .main-header-menu > li > a, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items a' => array(
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

	/**
	 * [2]. Hide Sticky Header logo if Sticky Header logo is not enabled.
	 */
	if ( '0' === $sticky_header_logo_different && '' != $sticky_header_logo ) {
		$css_output = array(
			'.ast-sticky-active .site-logo-img .custom-logo' => array(
				'display' => 'none',
			),
		);
		$parse_css .= astra_parse_css( $css_output );
	}

	/**
	 * [3]. Sticky Header Logo responsive widths
	 */
		// Desktop Sticky Header Logo width.
		$desktop_css_output = array(
			'#masthead .site-logo-img .sticky-custom-logo .astra-logo-svg, .site-logo-img .sticky-custom-logo .astra-logo-svg, .ast-sticky-main-shrink .ast-sticky-shrunk .site-logo-img .astra-logo-svg' => array(
				'width' => astra_get_css_value( $sticky_header_logo_width['desktop'], 'px' ),
			),
			'.site-logo-img .sticky-custom-logo img' => array(
				'max-width' => astra_get_css_value( $sticky_header_logo_width['desktop'], 'px' ),
			),
		);
		$parse_css         .= astra_parse_css( $desktop_css_output );

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
			'#masthead .site-logo-img .astra-logo-svg, .ast-header-break-point #ast-fixed-header .site-logo-img .custom-logo-link img ' => array(
				'max-width' => astra_get_css_value( $header_responsive_logo_width['desktop'], 'px' ),
			),
		);
		$parse_css             .= astra_parse_css( $responsive_logo_output );

		$responsive_logo_output_tablet = array(
			'#masthead .site-logo-img .astra-logo-svg, .ast-header-break-point #ast-fixed-header .site-logo-img .custom-logo-link img ' => array(
				'max-width' => astra_get_css_value( $header_responsive_logo_width['tablet'], 'px' ),
			),
		);
		$parse_css                    .= astra_parse_css( $responsive_logo_output_tablet, '', '768' );

		$responsive_logo_output_mobile = array(
			'#masthead .site-logo-img .astra-logo-svg, .ast-header-break-point #ast-fixed-header .site-logo-img .custom-logo-link img ' => array(
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

	/**
	 * [4]. Compatible with Header Width
	 */
	if ( 'content' != $astra_header_width ) {

		$general_global_responsive = array(
			'#ast-fixed-header .ast-container' => array(
				'max-width'     => '100%',
				'padding-left'  => '35px',
				'padding-right' => '35px',
			),
		);
		$padding_below_breakpoint  = array(
			'#ast-fixed-header .ast-container' => array(
				'padding-left'  => '20px',
				'padding-right' => '20px',
			),
		);

		/* Parse CSS from array()*/
		$parse_css .= astra_parse_css( $general_global_responsive );
		$parse_css .= astra_parse_css( $padding_below_breakpoint, '', $header_break_point );
	}

	/**
	 * [5]. Stciky Header & Sticky Header Primary menu colors.
	 */

	if ( 'none' === $sticky_header_style && ! $sticky_hide_on_scroll ) {
		$desktop_css_output = array(
			/**
			 * Header
			 */
			'.ast-primary-sticky-header-active .site-title a, .ast-primary-sticky-header-active .site-title a:focus, .ast-primary-sticky-header-active .site-title a:hover, .ast-primary-sticky-header-active .site-title a:visited' => array(
				'color' => esc_attr( $desktop_sticky_header_color_site_title ),
			),
			'.ast-primary-sticky-header-active .site-header .site-title a:hover'           => array(
				'color' => esc_attr( $sticky_header_color_h_site_title['desktop'] ),
			),
			'.ast-primary-sticky-header-active .site-header .site-description'             => array(
				'color' => esc_attr( $desktop_sticky_header_color_site_tagline ),
			),
			'.ast-transparent-header.ast-primary-sticky-header-active .main-header-bar, .ast-primary-sticky-header-active .main-header-bar, .ast-primary-sticky-header-active.ast-header-break-point .main-header-bar'                      => array(
				'background-color' => esc_attr( $desktop_sticky_header_bg_color ),
			),
			/**
			 * Primary Header Menu
			 */
				'.ast-primary-sticky-header-active .main-header-menu' => array(
					'background-color' => esc_attr( $sticky_header_menu_bg_color['desktop'] ),
				),
			'.ast-primary-sticky-header-active .main-header-menu li.current-menu-item > a, .ast-primary-sticky-header-active .main-header-menu li.current-menu-ancestor > a, .ast-primary-sticky-header-active .main-header-menu li.current_page_item > a' => array(
				'color'            => esc_attr( $desktop_sticky_primary_menu_h_color ),
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['desktop'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu a:hover, .ast-header-custom-item a:hover, .ast-primary-sticky-header-active .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['desktop'] ),
				'color'            => esc_attr( $desktop_sticky_primary_menu_h_color ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-primary-sticky-header-active .main-header-menu li:hover > .ast-menu-toggle, .ast-primary-sticky-header-active .main-header-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $desktop_sticky_primary_menu_h_color ),
			),

			'.ast-primary-sticky-header-active .main-header-menu, .ast-primary-sticky-header-active .main-header-menu a, .ast-primary-sticky-header-active .ast-header-custom-item, .ast-header-custom-item a, .ast-primary-sticky-header-active li.ast-masthead-custom-menu-items, .ast-primary-sticky-header-active li.ast-masthead-custom-menu-items a' => array(
				'color' => esc_attr( $desktop_sticky_primary_menu_color ),
			),

			'.ast-primary-sticky-header-active .ast-masthead-custom-menu-items .ast-inline-search form' => array(
				'border-color' => esc_attr( $desktop_sticky_primary_menu_color ),
			),
			/**
			 * Primary Submenu
			 */
				'.ast-primary-sticky-header-active .main-navigation ul ul, .ast-header-break-point.ast-primary-sticky-header-active .main-header-menu ul' => array(
					'background-color' => esc_attr( $sticky_header_submenu_bg_color['desktop'] ),
				),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu, .ast-primary-sticky-header-active .main-header-menu .sub-menu a, .ast-primary-sticky-header-active .main-header-menu .children a' => array(
				'color' => esc_attr( $sticky_primary_submenu_color['desktop'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu a:hover, .ast-primary-sticky-header-active .main-header-menu .children a:hover, .ast-primary-sticky-header-active .main-header-menu .sub-menu li:hover > a, .ast-primary-sticky-header-active .main-header-menu .children li:hover > a, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.focus > a, .ast-primary-sticky-header-active .main-header-menu .children li.focus > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['desktop'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['desktop'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $sticky_primary_submenu_h_color['desktop'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu li.current-menu-item > a, .ast-primary-sticky-header-active .main-header-menu .children li.current_page_item > a, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-primary-sticky-header-active .main-header-menu .children li.current_page_ancestor > a, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.current_page_item > a, .ast-primary-sticky-header-active .main-header-menu .children li.current_page_item > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['desktop'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['desktop'] ),
			),

			// Content Section text color.
			'.ast-primary-sticky-header-active div.ast-masthead-custom-menu-items, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget-title' => array(
				'color' => esc_attr( $sticky_header_content_section_text_color['desktop'] ),
			),
			// Content Section link color.
			'.ast-primary-sticky-header-active div.ast-masthead-custom-menu-items a, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget a' => array(
				'color' => esc_attr( $sticky_header_content_section_link_color['desktop'] ),
			),
			// Content Section link hover color.
			'.ast-primary-sticky-header-active div.ast-masthead-custom-menu-items a:hover, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget a:hover' => array(
				'color' => esc_attr( $sticky_header_content_section_link_h_color['desktop'] ),
			),
		);
		$tablet_css_output = array(

			/**
			 * Header
			 */
			'.ast-primary-sticky-header-active .site-title a, .ast-primary-sticky-header-active .site-title a:focus, .ast-primary-sticky-header-active .site-title a:hover, .ast-primary-sticky-header-active .site-title a:visited' => array(
				'color' => esc_attr( $tablet_sticky_header_color_site_title ),
			),
			'.ast-primary-sticky-header-active .site-header .site-title a:hover'           => array(
				'color' => esc_attr( $sticky_header_color_h_site_title['tablet'] ),
			),
			'.ast-primary-sticky-header-active .site-header .site-description'             => array(
				'color' => esc_attr( $tablet_sticky_header_color_site_tagline ),
			),
			'.ast-transparent-header.ast-primary-sticky-header-active .main-header-bar, .ast-primary-sticky-header-active .main-header-bar, .ast-primary-sticky-header-active.ast-header-break-point .main-header-bar'                      => array(
				'background-color' => esc_attr( $tablet_sticky_header_bg_color ),
			),
			/**
			 * Primary Header Menu
			 */
				'.ast-primary-sticky-header-active .main-header-menu' => array(
					'background-color' => esc_attr( $sticky_header_menu_bg_color['tablet'] ),
				),
			'.ast-primary-sticky-header-active .main-header-menu li.current-menu-item > a, .ast-primary-sticky-header-active .main-header-menu li.current-menu-ancestor > a, .ast-primary-sticky-header-active .main-header-menu li.current_page_item > a' => array(
				'color'            => esc_attr( $tablet_sticky_primary_menu_h_color ),
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['tablet'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu a:hover, .ast-header-custom-item a:hover, .ast-primary-sticky-header-active .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['tablet'] ),
				'color'            => esc_attr( $tablet_sticky_primary_menu_h_color ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-primary-sticky-header-active .main-header-menu li:hover > .ast-menu-toggle, .ast-primary-sticky-header-active .main-header-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $tablet_sticky_primary_menu_h_color ),
			),

			'.ast-primary-sticky-header-active .main-header-menu, .ast-primary-sticky-header-active .main-header-menu a, .ast-primary-sticky-header-active .ast-header-custom-item, .ast-header-custom-item a, .ast-primary-sticky-header-active li.ast-masthead-custom-menu-items, .ast-primary-sticky-header-active li.ast-masthead-custom-menu-items a' => array(
				'color' => esc_attr( $tablet_sticky_primary_menu_color ),
			),

			'.ast-primary-sticky-header-active .ast-masthead-custom-menu-items .ast-inline-search form' => array(
				'border-color' => esc_attr( $tablet_sticky_primary_menu_color ),
			),
			/**
			 * Primary Submenu
			 */
				'.ast-primary-sticky-header-active .main-navigation ul ul, .ast-header-break-point.ast-primary-sticky-header-active .main-header-menu ul' => array(
					'background-color' => esc_attr( $sticky_header_submenu_bg_color['tablet'] ),
				),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu, .ast-primary-sticky-header-active .main-header-menu .sub-menu a, .ast-primary-sticky-header-active .main-header-menu .children a' => array(
				'color' => esc_attr( $sticky_primary_submenu_color['tablet'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu a:hover, .ast-primary-sticky-header-active .main-header-menu .children a:hover, .ast-primary-sticky-header-active .main-header-menu .sub-menu li:hover > a, .ast-primary-sticky-header-active .main-header-menu .children li:hover > a, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.focus > a, .ast-primary-sticky-header-active .main-header-menu .children li.focus > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['tablet'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['tablet'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $sticky_primary_submenu_h_color['tablet'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu li.current-menu-item > a, .ast-primary-sticky-header-active .main-header-menu .children li.current_page_item > a, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-primary-sticky-header-active .main-header-menu .children li.current_page_ancestor > a, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.current_page_item > a, .ast-primary-sticky-header-active .main-header-menu .children li.current_page_item > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['tablet'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['tablet'] ),
			),

			// Content Section text color.
			'.ast-primary-sticky-header-active div.ast-masthead-custom-menu-items, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget-title' => array(
				'color' => esc_attr( $sticky_header_content_section_text_color['tablet'] ),
			),
			// Content Section link color.
			'.ast-primary-sticky-header-active div.ast-masthead-custom-menu-items a, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget a' => array(
				'color' => esc_attr( $sticky_header_content_section_link_color['tablet'] ),
			),
			// Content Section link hover color.
			'.ast-primary-sticky-header-active div.ast-masthead-custom-menu-items a:hover, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget a:hover' => array(
				'color' => esc_attr( $sticky_header_content_section_link_h_color['tablet'] ),
			),
		);
		$mobile_css_output = array(

			/**
			 * Header
			 */
				'.ast-primary-sticky-header-active .site-title a, .ast-primary-sticky-header-active .site-title a:focus, .ast-primary-sticky-header-active .site-title a:hover, .ast-primary-sticky-header-active .site-title a:visited' => array(
					'color' => esc_attr( $mobile_sticky_header_color_site_title ),
				),
			'.ast-primary-sticky-header-active .site-header .site-title a:hover'           => array(
				'color' => esc_attr( $sticky_header_color_h_site_title['mobile'] ),
			),
			'.ast-primary-sticky-header-active .site-header .site-description'             => array(
				'color' => esc_attr( $mobile_sticky_header_color_site_tagline ),
			),
			'.ast-transparent-header.ast-primary-sticky-header-active .main-header-bar, .ast-primary-sticky-header-active .main-header-bar, .ast-primary-sticky-header-active.ast-header-break-point .main-header-bar'                      => array(
				'background-color' => esc_attr( $mobile_sticky_header_bg_color ),
			),
			/**
			 * Primary Header Menu
			 */
				'.ast-primary-sticky-header-active .main-header-menu' => array(
					'background-color' => esc_attr( $sticky_header_menu_bg_color['mobile'] ),
				),
			'.ast-primary-sticky-header-active .main-header-menu li.current-menu-item > a, .ast-primary-sticky-header-active .main-header-menu li.current-menu-ancestor > a, .ast-primary-sticky-header-active .main-header-menu li.current_page_item > a' => array(
				'color'            => esc_attr( $mobile_sticky_primary_menu_h_color ),
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['mobile'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu a:hover, .ast-header-custom-item a:hover, .ast-primary-sticky-header-active .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['mobile'] ),
				'color'            => esc_attr( $mobile_sticky_primary_menu_h_color ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-primary-sticky-header-active .main-header-menu li:hover > .ast-menu-toggle, .ast-primary-sticky-header-active .main-header-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $mobile_sticky_primary_menu_h_color ),
			),

			'.ast-primary-sticky-header-active .main-header-menu, .ast-primary-sticky-header-active .main-header-menu a, .ast-primary-sticky-header-active .ast-header-custom-item, .ast-header-custom-item a, .ast-primary-sticky-header-active li.ast-masthead-custom-menu-items, .ast-primary-sticky-header-active li.ast-masthead-custom-menu-items a' => array(
				'color' => esc_attr( $mobile_sticky_primary_menu_color ),
			),

			'.ast-primary-sticky-header-active .ast-masthead-custom-menu-items .ast-inline-search form' => array(
				'border-color' => esc_attr( $mobile_sticky_primary_menu_color ),
			),
			/**
			 * Primary Submenu
			 */
				'.ast-primary-sticky-header-active .main-navigation ul ul, .ast-header-break-point.ast-primary-sticky-header-active .main-header-menu ul' => array(
					'background-color' => esc_attr( $sticky_header_submenu_bg_color['mobile'] ),
				),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu, .ast-primary-sticky-header-active .main-header-menu .sub-menu a, .ast-primary-sticky-header-active .main-header-menu .children a' => array(
				'color' => esc_attr( $sticky_primary_submenu_color['mobile'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu a:hover, .ast-primary-sticky-header-active .main-header-menu .children a:hover, .ast-primary-sticky-header-active .main-header-menu .sub-menu li:hover > a, .ast-primary-sticky-header-active .main-header-menu .children li:hover > a, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.focus > a, .ast-primary-sticky-header-active .main-header-menu .children li.focus > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['mobile'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['mobile'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $sticky_primary_submenu_h_color['mobile'] ),
			),
			'.ast-primary-sticky-header-active .main-header-menu .sub-menu li.current-menu-item > a, .ast-primary-sticky-header-active .main-header-menu .children li.current_page_item > a, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-primary-sticky-header-active .main-header-menu .children li.current_page_ancestor > a, .ast-primary-sticky-header-active .main-header-menu .sub-menu li.current_page_item > a, .ast-primary-sticky-header-active .main-header-menu .children li.current_page_item > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['mobile'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['mobile'] ),
			),

			// Content Section text color.
			'.ast-primary-sticky-header-active div.ast-masthead-custom-menu-items, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget-title' => array(
				'color' => esc_attr( $sticky_header_content_section_text_color['mobile'] ),
			),
			// Content Section link color.
			'.ast-primary-sticky-header-active div.ast-masthead-custom-menu-items a, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget a' => array(
				'color' => esc_attr( $sticky_header_content_section_link_color['mobile'] ),
			),
			// Content Section link hover color.
			'.ast-primary-sticky-header-active div.ast-masthead-custom-menu-items a:hover, .ast-primary-sticky-header-active div.ast-masthead-custom-menu-items .widget a:hover' => array(
				'color' => esc_attr( $sticky_header_content_section_link_h_color['mobile'] ),
			),
		);
	} else {
		// Only when Fixed Header Merkup added.
		$desktop_css_output = array(

			/**
			 * Header
			 */
			'#ast-fixed-header .site-title a, #ast-fixed-header .site-title a:focus, #ast-fixed-header .site-title a:hover, #ast-fixed-header .site-title a:visited' => array(
				'color' => esc_attr( $desktop_sticky_header_color_site_title ),
			),
			'#ast-fixed-header .site-header .site-title a:hover' => array(
				'color' => esc_attr( $sticky_header_color_h_site_title['desktop'] ),
			),
			'#ast-fixed-header .site-header .site-description' => array(
				'color' => esc_attr( $desktop_sticky_header_color_site_tagline ),
			),
			'.ast-transparent-header #ast-fixed-header .main-header-bar, #ast-fixed-header .main-header-bar, #ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field, #ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field:focus' => array(
				'background-color' => esc_attr( $desktop_sticky_header_bg_color ),
			),
			/**
			 * Primary Header Menu
			 */
				'#ast-fixed-header .main-header-menu' => array(
					'background-color' => esc_attr( $sticky_header_menu_bg_color['desktop'] ),
				),
			'#ast-fixed-header .main-header-menu li.current-menu-item > a, #ast-fixed-header .main-header-menu li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu li.current_page_item > a' => array(
				'color'            => esc_attr( $desktop_sticky_primary_menu_h_color ),
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['desktop'] ),
			),
			'#ast-fixed-header .main-header-menu a:hover, .ast-header-custom-item a:hover, #ast-fixed-header .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['desktop'] ),
				'color'            => esc_attr( $desktop_sticky_primary_menu_h_color ),
			),
			'#ast-fixed-header .main-header-menu .ast-masthead-custom-menu-items a:hover, #ast-fixed-header .main-header-menu li:hover > .ast-menu-toggle, #ast-fixed-header .main-header-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $desktop_sticky_primary_menu_h_color ),
			),

			'#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu a, #ast-fixed-header .ast-header-custom-item, .ast-header-custom-item a, #ast-fixed-header li.ast-masthead-custom-menu-items, #ast-fixed-header li.ast-masthead-custom-menu-items a' => array(
				'color' => esc_attr( $desktop_sticky_primary_menu_color ),
			),

			'#ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search form' => array(
				'border-color' => esc_attr( $desktop_sticky_primary_menu_color ),
			),
			/**
			 * Primary Submenu
			 */
				'#ast-fixed-header .main-navigation ul ul, .ast-header-break-point#ast-fixed-header .main-header-menu ul' => array(
					'background-color' => esc_attr( $sticky_header_submenu_bg_color['desktop'] ),
				),
			'#ast-fixed-header .main-header-menu .sub-menu, #ast-fixed-header .main-header-menu .sub-menu a, #ast-fixed-header .main-header-menu .children a' => array(
				'color' => esc_attr( $sticky_primary_submenu_color['desktop'] ),
			),
			'#ast-fixed-header .main-header-menu .sub-menu a:hover, #ast-fixed-header .main-header-menu .children a:hover, #ast-fixed-header .main-header-menu .sub-menu li:hover > a, #ast-fixed-header .main-header-menu .children li:hover > a, #ast-fixed-header .main-header-menu .sub-menu li.focus > a, #ast-fixed-header .main-header-menu .children li.focus > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['desktop'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['desktop'] ),
			),
			'#ast-fixed-header .main-header-menu .sub-menu li:hover > .ast-menu-toggle, #ast-fixed-header .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $sticky_primary_submenu_h_color['desktop'] ),
			),
			'#ast-fixed-header .main-header-menu .sub-menu li.current-menu-item > a, #ast-fixed-header .main-header-menu .children li.current_page_item > a, #ast-fixed-header .main-header-menu .sub-menu li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu .children li.current_page_ancestor > a, #ast-fixed-header .main-header-menu .sub-menu li.current_page_item > a, #ast-fixed-header .main-header-menu .children li.current_page_item > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['desktop'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['desktop'] ),
			),
			// Content Section text color.
			'#ast-fixed-header div.ast-masthead-custom-menu-items, #ast-fixed-header div.ast-masthead-custom-menu-items .widget, #ast-fixed-header div.ast-masthead-custom-menu-items .widget-title' => array(
				'color' => esc_attr( $sticky_header_content_section_text_color['desktop'] ),
			),
			// Content Section link color.
			'#ast-fixed-header div.ast-masthead-custom-menu-items a, #ast-fixed-header div.ast-masthead-custom-menu-items .widget a' => array(
				'color' => esc_attr( $sticky_header_content_section_link_color['desktop'] ),
			),
			// Content Section link hover color.
			'#ast-fixed-header div.ast-masthead-custom-menu-items a:hover, #ast-fixed-header div.ast-masthead-custom-menu-items .widget a:hover' => array(
				'color' => esc_attr( $sticky_header_content_section_link_h_color['desktop'] ),
			),
		);
		$tablet_css_output = array(

			/**
			 * Header
			 */
			'#ast-fixed-header .site-title a, #ast-fixed-header .site-title a:focus, #ast-fixed-header .site-title a:hover, #ast-fixed-header .site-title a:visited' => array(
				'color' => esc_attr( $tablet_sticky_header_color_site_title ),
			),
			'#ast-fixed-header .site-header .site-title a:hover' => array(
				'color' => esc_attr( $sticky_header_color_h_site_title['tablet'] ),
			),
			'#ast-fixed-header .site-header .site-description' => array(
				'color' => esc_attr( $tablet_sticky_header_color_site_tagline ),
			),
			'.ast-transparent-header #ast-fixed-header .main-header-bar, #ast-fixed-header .main-header-bar, #ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field, #ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field:focus' => array(
				'background-color' => esc_attr( $tablet_sticky_header_bg_color ),
			),
			/**
			 * Primary Header Menu
			 */
				'#ast-fixed-header .main-header-menu' => array(
					'background-color' => esc_attr( $sticky_header_menu_bg_color['tablet'] ),
				),
			'#ast-fixed-header .main-header-menu li.current-menu-item > a, #ast-fixed-header .main-header-menu li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu li.current_page_item > a' => array(
				'color'            => esc_attr( $tablet_sticky_primary_menu_h_color ),
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['tablet'] ),
			),
			'#ast-fixed-header .main-header-menu a:hover, .ast-header-custom-item a:hover, #ast-fixed-header .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['tablet'] ),
				'color'            => esc_attr( $tablet_sticky_primary_menu_h_color ),
			),
			'#ast-fixed-header .main-header-menu .ast-masthead-custom-menu-items a:hover, #ast-fixed-header .main-header-menu li:hover > .ast-menu-toggle, #ast-fixed-header .main-header-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $tablet_sticky_primary_menu_h_color ),
			),

			'#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu a, #ast-fixed-header .ast-header-custom-item, .ast-header-custom-item a, #ast-fixed-header li.ast-masthead-custom-menu-items, #ast-fixed-header li.ast-masthead-custom-menu-items a' => array(
				'color' => esc_attr( $tablet_sticky_primary_menu_color ),
			),

			'#ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search form' => array(
				'border-color' => esc_attr( $tablet_sticky_primary_menu_color ),
			),
			/**
			 * Primary Submenu
			 */
				'#ast-fixed-header .main-navigation ul ul, .ast-header-break-point#ast-fixed-header .main-header-menu ul' => array(
					'background-color' => esc_attr( $sticky_header_submenu_bg_color['tablet'] ),
				),
			'#ast-fixed-header .main-header-menu .sub-menu, #ast-fixed-header .main-header-menu .sub-menu a, #ast-fixed-header .main-header-menu .children a' => array(
				'color' => esc_attr( $sticky_primary_submenu_color['tablet'] ),
			),
			'#ast-fixed-header .main-header-menu .sub-menu a:hover, #ast-fixed-header .main-header-menu .children a:hover, #ast-fixed-header .main-header-menu .sub-menu li:hover > a, #ast-fixed-header .main-header-menu .children li:hover > a, #ast-fixed-header .main-header-menu .sub-menu li.focus > a, #ast-fixed-header .main-header-menu .children li.focus > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['tablet'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['tablet'] ),
			),
			'#ast-fixed-header .main-header-menu .sub-menu li:hover > .ast-menu-toggle, #ast-fixed-header .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $sticky_primary_submenu_h_color['tablet'] ),
			),
			'#ast-fixed-header .main-header-menu .sub-menu li.current-menu-item > a, #ast-fixed-header .main-header-menu .children li.current_page_item > a, #ast-fixed-header .main-header-menu .sub-menu li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu .children li.current_page_ancestor > a, #ast-fixed-header .main-header-menu .sub-menu li.current_page_item > a, #ast-fixed-header .main-header-menu .children li.current_page_item > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['tablet'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['tablet'] ),
			),
			// Content Section text color.
			'#ast-fixed-header div.ast-masthead-custom-menu-items, #ast-fixed-header div.ast-masthead-custom-menu-items .widget, #ast-fixed-header div.ast-masthead-custom-menu-items .widget-title' => array(
				'color' => esc_attr( $sticky_header_content_section_text_color['tablet'] ),
			),
			// Content Section link color.
			'#ast-fixed-header div.ast-masthead-custom-menu-items a, #ast-fixed-header div.ast-masthead-custom-menu-items .widget a' => array(
				'color' => esc_attr( $sticky_header_content_section_link_color['tablet'] ),
			),
			// Content Section link hover color.
			'#ast-fixed-header div.ast-masthead-custom-menu-items a:hover, #ast-fixed-header div.ast-masthead-custom-menu-items .widget a:hover' => array(
				'color' => esc_attr( $sticky_header_content_section_link_h_color['tablet'] ),
			),
		);
		$mobile_css_output = array(

			/**
			 * Header
			 */
			'#ast-fixed-header .site-title a, #ast-fixed-header .site-title a:focus, #ast-fixed-header .site-title a:hover, #ast-fixed-header .site-title a:visited' => array(
				'color' => esc_attr( $mobile_sticky_header_color_site_title ),
			),
			'#ast-fixed-header .site-header .site-title a:hover' => array(
				'color' => esc_attr( $sticky_header_color_h_site_title['mobile'] ),
			),
			'#ast-fixed-header .site-header .site-description' => array(
				'color' => esc_attr( $mobile_sticky_header_color_site_tagline ),
			),
			'.ast-transparent-header #ast-fixed-header .main-header-bar, #ast-fixed-header .main-header-bar, #ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field, #ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search .search-field:focus' => array(
				'background-color' => esc_attr( $mobile_sticky_header_bg_color ),
			),
			/**
			 * Primary Header Menu
			 */
				'#ast-fixed-header .main-header-menu' => array(
					'background-color' => esc_attr( $sticky_header_menu_bg_color['mobile'] ),
				),
			'#ast-fixed-header .main-header-menu li.current-menu-item > a, #ast-fixed-header .main-header-menu li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu li.current_page_item > a' => array(
				'color'            => esc_attr( $mobile_sticky_primary_menu_h_color ),
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['mobile'] ),
			),
			'#ast-fixed-header .main-header-menu a:hover, .ast-header-custom-item a:hover, #ast-fixed-header .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
				'background-color' => esc_attr( $sticky_header_menu_h_a_bg_color['mobile'] ),
				'color'            => esc_attr( $mobile_sticky_primary_menu_h_color ),
			),
			'#ast-fixed-header .main-header-menu .ast-masthead-custom-menu-items a:hover, #ast-fixed-header .main-header-menu li:hover > .ast-menu-toggle, #ast-fixed-header .main-header-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $mobile_sticky_primary_menu_h_color ),
			),

			'#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu a, #ast-fixed-header .ast-header-custom-item, .ast-header-custom-item a, #ast-fixed-header li.ast-masthead-custom-menu-items, #ast-fixed-header li.ast-masthead-custom-menu-items a' => array(
				'color' => esc_attr( $mobile_sticky_primary_menu_color ),
			),

			'#ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search form' => array(
				'border-color' => esc_attr( $mobile_sticky_primary_menu_color ),
			),
			/**
			 * Primary Submenu
			 */
				'#ast-fixed-header .main-navigation ul ul, .ast-header-break-point#ast-fixed-header .main-header-menu ul' => array(
					'background-color' => esc_attr( $sticky_header_submenu_bg_color['mobile'] ),
				),
			'#ast-fixed-header .main-header-menu .sub-menu, #ast-fixed-header .main-header-menu .sub-menu a, #ast-fixed-header .main-header-menu .children a' => array(
				'color' => esc_attr( $sticky_primary_submenu_color['mobile'] ),
			),
			'#ast-fixed-header .main-header-menu .sub-menu a:hover, #ast-fixed-header .main-header-menu .children a:hover, #ast-fixed-header .main-header-menu .sub-menu li:hover > a, #ast-fixed-header .main-header-menu .children li:hover > a, #ast-fixed-header .main-header-menu .sub-menu li.focus > a, #ast-fixed-header .main-header-menu .children li.focus > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['mobile'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['mobile'] ),
			),
			'#ast-fixed-header .main-header-menu .sub-menu li:hover > .ast-menu-toggle, #ast-fixed-header .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $sticky_primary_submenu_h_color['mobile'] ),
			),
			'#ast-fixed-header .main-header-menu .sub-menu li.current-menu-item > a, #ast-fixed-header .main-header-menu .children li.current_page_item > a, #ast-fixed-header .main-header-menu .sub-menu li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu .children li.current_page_ancestor > a, #ast-fixed-header .main-header-menu .sub-menu li.current_page_item > a, #ast-fixed-header .main-header-menu .children li.current_page_item > a' => array(
				'color'            => esc_attr( $sticky_primary_submenu_h_color['mobile'] ),
				'background-color' => esc_attr( $sticky_primary_submenu_h_a_bg_color['mobile'] ),
			),
			// Content Section text color.
			'#ast-fixed-header div.ast-masthead-custom-menu-items, #ast-fixed-header div.ast-masthead-custom-menu-items .widget, #ast-fixed-header div.ast-masthead-custom-menu-items .widget-title' => array(
				'color' => esc_attr( $sticky_header_content_section_text_color['mobile'] ),
			),
			// Content Section link color.
			'#ast-fixed-header div.ast-masthead-custom-menu-items a, #ast-fixed-header div.ast-masthead-custom-menu-items .widget a' => array(
				'color' => esc_attr( $sticky_header_content_section_link_color['mobile'] ),
			),
			// Content Section link hover color.
			'#ast-fixed-header div.ast-masthead-custom-menu-items a:hover, #ast-fixed-header div.ast-masthead-custom-menu-items .widget a:hover' => array(
				'color' => esc_attr( $sticky_header_content_section_link_h_color['mobile'] ),
			),
		);
	}

	if ( false === astra_pro_sticky_header_submenu_below_header_fix() ) :
		$submenu_below_header = array(
			'.ast-sticky-main-shrink .ast-sticky-shrunk .main-header-bar' => array(
				'padding-top'    => '0.5em',
				'padding-bottom' => '0.5em',
			),
			'.ast-sticky-main-shrink .ast-sticky-shrunk .main-header-bar .ast-site-identity' => array(
				'padding-top'    => '0',
				'padding-bottom' => '0',
			),
		);

		$parse_css .= astra_parse_css( $submenu_below_header );
	endif;

		/* Parse CSS from array() */
		$parse_css .= astra_parse_css( $desktop_css_output );
		$parse_css .= astra_parse_css( $tablet_css_output, '', '768' );
		$parse_css .= astra_parse_css( $mobile_css_output, '', '544' );

	return $dynamic_css .= $parse_css;

}


/**
 * Check backwards compatibility CSS for loading submenu below the header needs to be added.
 *
 * @since 1.6.0
 * @return boolean true if CSS should be included, False if not.
 */
function astra_pro_sticky_header_submenu_below_header_fix() {

	if ( false == astra_get_option( 'submenu-below-header', true ) &&
		false === apply_filters(
			'astra_submenu_below_header_fix',
			false
		) ) {

			return false;
	} else {

		return true;
	}

}
