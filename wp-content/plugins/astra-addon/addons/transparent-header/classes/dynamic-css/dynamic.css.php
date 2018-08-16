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

	$transparent_header_inherit = astra_get_option( 'different-transparent-logo' );
	$transparent_header_logo    = astra_get_option( 'transparent-header-logo' );

	$transparent_bg_color           = astra_get_option( 'transparent-header-bg-color-responsive' );
	$transparent_color_site_title   = astra_get_option( 'transparent-header-color-site-title-responsive' );
	$transparent_color_h_site_title = astra_get_option( 'transparent-header-color-h-site-title-responsive' );
	$transparent_menu_bg_color      = astra_get_option( 'transparent-menu-bg-color-responsive' );
	$transparent_menu_color         = astra_get_option( 'transparent-menu-color-responsive' );
	$transparent_menu_h_color       = astra_get_option( 'transparent-menu-h-color-responsive' );
	$transparent_sub_menu_color     = astra_get_option( 'transparent-submenu-color-responsive' );
	$transparent_sub_menu_h_color   = astra_get_option( 'transparent-submenu-h-color-responsive' );
	$transparent_sub_menu_bg_color  = astra_get_option( 'transparent-submenu-bg-color-responsive' );

	$transparent_content_section_text_color   = astra_get_option( 'transparent-content-section-text-color-responsive' );
	$transparent_content_section_link_color   = astra_get_option( 'transparent-content-section-link-color-responsive' );
	$transparent_content_section_link_h_color = astra_get_option( 'transparent-content-section-link-h-color-responsive' );
	/**
	 * Generate Dynamic CSS
	 */

	$css = '';

	if ( '0' === $transparent_header_inherit && '' != $transparent_header_logo ) {
		$css_output = array(
			'.ast-theme-transparent-header .site-logo-img .custom-logo' => array(
				'display' => 'none',
			),
		);
		$css       .= astra_parse_css( $css_output );
	}

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

	/**
	 * Transparent Header Colors
	 */
	$transparent_header_desktop = array(

		'.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header .ast-header-break-point .main-header-menu' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .main-header-bar .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),

		'.ast-theme-transparent-header .ast-above-header, .ast-theme-transparent-header .ast-below-header' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),

		'.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited' => array(
			'color' => esc_attr( $transparent_color_site_title['desktop'] ),
		),
		'.ast-theme-transparent-header .site-header .site-title a:hover' => array(
			'color' => esc_attr( $transparent_color_h_site_title['desktop'] ),
		),

		'.ast-theme-transparent-header .site-header .site-description' => array(
			'color' => esc_attr( $transparent_color_site_title['desktop'] ),
		),

		'.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .ast-header-break-point .main-header-menu' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .main-header-menu ul.sub-menu' => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .main-header-menu ul.sub-menu li a,.ast-theme-transparent-header .main-header-menu ul.sub-menu li > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_color['desktop'] ),
		),
		'.ast-theme-transparent-header .main-header-menu ul.sub-menu a:hover,.ast-theme-transparent-header .main-header-menu ul.sub-menu li:hover > a, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.focus > a,.ast-theme-transparent-header .main-header-menu ul.sub-menu li.current-menu-item > a, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.current-menu-item > .ast-menu-toggle,.ast-theme-transparent-header .main-header-menu ul.sub-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['desktop'] ),
		),
		'.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .main-header-menu a, .ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-submit, .ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a,.ast-theme-transparent-header .main-header-menu li > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu li > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_color['desktop'] ),
		),
		'.ast-theme-transparent-header .main-header-menu li:hover > a, .ast-theme-transparent-header .main-header-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header .main-header-menu .focus > a, .ast-theme-transparent-header .main-header-menu .focus > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current-menu-item > a, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > a, .ast-theme-transparent-header .main-header-menu .current_page_item > a, .ast-theme-transparent-header .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current_page_item > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_h_color['desktop'] ),
		),
		// Content Section text color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget-title' => array(
			'color' => esc_attr( $transparent_content_section_text_color['desktop'] ),
		),
		// Content Section link color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color['desktop'] ),
		),
		// Content Section link hover color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color['desktop'] ),
		),
	);

	$transparent_header_tablet = array(

		'.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header .ast-header-break-point .main-header-menu' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .main-header-bar .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),

		'.ast-theme-transparent-header .ast-above-header, .ast-theme-transparent-header .ast-below-header' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),

		'.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited' => array(
			'color' => esc_attr( $transparent_color_site_title['tablet'] ),
		),
		'.ast-theme-transparent-header .site-header .site-title a:hover' => array(
			'color' => esc_attr( $transparent_color_h_site_title['tablet'] ),
		),

		'.ast-theme-transparent-header .site-header .site-description' => array(
			'color' => esc_attr( $transparent_color_site_title['tablet'] ),
		),

		'.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .ast-header-break-point .main-header-menu' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .main-header-menu ul.sub-menu' => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .main-header-menu ul.sub-menu li a,.ast-theme-transparent-header .main-header-menu ul.sub-menu li > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_color['tablet'] ),
		),
		'.ast-theme-transparent-header .main-header-menu ul.sub-menu a:hover,.ast-theme-transparent-header .main-header-menu ul.sub-menu li:hover > a, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.focus > a,.ast-theme-transparent-header .main-header-menu ul.sub-menu li.current-menu-item > a,.ast-theme-transparent-header .main-header-menu ul.sub-menu li.current-menu-item > .ast-menu-toggle,.ast-theme-transparent-header .main-header-menu ul.sub-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['tablet'] ),
		),
		'.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .main-header-menu a, .ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-submit, .ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a,.ast-theme-transparent-header .main-header-menu li > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu li > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_color['tablet'] ),
		),
		'.ast-theme-transparent-header .main-header-menu li:hover > a, .ast-theme-transparent-header .main-header-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header .main-header-menu .focus > a, .ast-theme-transparent-header .main-header-menu .focus > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current-menu-item > a, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > a, .ast-theme-transparent-header .main-header-menu .current_page_item > a, .ast-theme-transparent-header .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current_page_item > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_h_color['tablet'] ),
		),
		// Content Section text color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget-title' => array(
			'color' => esc_attr( $transparent_content_section_text_color['tablet'] ),
		),
		// Content Section link color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color['tablet'] ),
		),
		// Content Section link hover color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color['tablet'] ),
		),
	);

	$transparent_header_mobile = array(

		'.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header .ast-header-break-point .main-header-menu' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .main-header-bar .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),

		'.ast-theme-transparent-header .ast-above-header, .ast-theme-transparent-header .ast-below-header' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),

		'.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited' => array(
			'color' => esc_attr( $transparent_color_site_title['mobile'] ),
		),
		'.ast-theme-transparent-header .site-header .site-title a:hover' => array(
			'color' => esc_attr( $transparent_color_h_site_title['mobile'] ),
		),

		'.ast-theme-transparent-header .site-header .site-description' => array(
			'color' => esc_attr( $transparent_color_site_title['mobile'] ),
		),

		'.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .ast-header-break-point .main-header-menu' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .main-header-menu ul.sub-menu' => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .main-header-menu ul.sub-menu li a,.ast-theme-transparent-header .main-header-menu ul.sub-menu li > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_color['mobile'] ),
		),
		'.ast-theme-transparent-header .main-header-menu ul.sub-menu a:hover,.ast-theme-transparent-header .main-header-menu ul.sub-menu li:hover > a, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.focus > a,.ast-theme-transparent-header .main-header-menu ul.sub-menu li.current-menu-item > a,.ast-theme-transparent-header .main-header-menu ul.sub-menu li.current-menu-item > .ast-menu-toggle,.ast-theme-transparent-header .main-header-menu ul.sub-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.focus > .ast-menu-toggle,.ast-theme-transparent-header .main-header-menu ul.sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['mobile'] ),
		),
		'.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .main-header-menu a, .ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-submit, .ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a,.ast-theme-transparent-header .main-header-menu li > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu li > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_color['mobile'] ),
		),
		'.ast-theme-transparent-header .main-header-menu li:hover > a, .ast-theme-transparent-header .main-header-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header .main-header-menu .focus > a, .ast-theme-transparent-header .main-header-menu .focus > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current-menu-item > a, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > a, .ast-theme-transparent-header .main-header-menu .current_page_item > a, .ast-theme-transparent-header .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current_page_item > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_h_color['mobile'] ),
		),
		// Content Section text color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget-title' => array(
			'color' => esc_attr( $transparent_content_section_text_color['mobile'] ),
		),
		// Content Section link color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color['mobile'] ),
		),
		// Content Section link hover color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color['mobile'] ),
		),
	);

	/* Parse CSS from array() */
	$css .= astra_parse_css( $transparent_header_desktop );
	$css .= astra_parse_css( $transparent_header_tablet, '', '768' );
	$css .= astra_parse_css( $transparent_header_mobile, '', '544' );

	$css .= '.ast-theme-transparent-header.ast-header-break-point .site-header {';
	$css .= 'border-bottom-width:' . astra_get_css_value( $transparent_header_separator, 'px' ) . ';';
	$css .= 'border-bottom-color:' . esc_attr( $transparent_header_separator_color ) . ';';
	$css .= '}';
	$css .= '@media (min-width: 769px) {';
	$css .= '.ast-theme-transparent-header .main-header-bar {';
	$css .= 'border-bottom-width:' . astra_get_css_value( $transparent_header_separator, 'px' ) . ';';
	$css .= 'border-bottom-color:' . esc_attr( $transparent_header_separator_color ) . ';';
	$css .= '}';
	$css .= '}';

	return $dynamic_css .= $css;

}
