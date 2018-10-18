<?php
/**
 * Above Header - Dyanamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_ext_above_header_dynamic_css' );

/**
 * Dynamic CSS funtion
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dyanamic CSS Filters.
 * @return string
 */
function astra_ext_above_header_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$above_header_layout = astra_get_option( 'above-header-layout' );

	if ( 'disabled' == $above_header_layout ) {
		return $dynamic_css;
	}

	$parse_css = '';

	$height       = astra_get_option( 'above-header-height' );
	$border_width = astra_get_option( 'above-header-divider' );
	$border_color = astra_get_option( 'above-header-divider-color' );

	$theme_color            = astra_get_option( 'theme-color' );
	$theme_text_color       = astra_get_option( 'text-color' );
	$theme_link_color       = astra_get_option( 'link-color' );
	$theme_link_hover_color = astra_get_option( 'link-h-color' );

	$above_header_text_color = astra_get_option( 'above-header-text-color-responsive' );

	$desktop_above_header_link_color = astar( astra_get_option( 'above-header-link-color-responsive' ), 'desktop', $theme_link_color );
	$tablet_above_header_link_color  = astar( astra_get_option( 'above-header-link-color-responsive' ), 'tablet' );
	$mobile_above_header_link_color  = astar( astra_get_option( 'above-header-link-color-responsive' ), 'mobile' );

	$desktop_above_header_link_h_color = astar( astra_get_option( 'above-header-link-h-color-responsive' ), 'desktop', $theme_link_hover_color );
	$tablet_above_header_link_h_color  = astar( astra_get_option( 'above-header-link-h-color-responsive' ), 'tablet' );
	$mobile_above_header_link_h_color  = astar( astra_get_option( 'above-header-link-h-color-responsive' ), 'mobile' );

	$above_header_bg_obj = astra_get_option( 'above-header-bg-obj-responsive' );
	$desktop_background  = isset( $above_header_bg_obj['desktop']['background-color'] ) ? $above_header_bg_obj['desktop']['background-color'] : '';
	$tablet_background   = isset( $above_header_bg_obj['tablet']['background-color'] ) ? $above_header_bg_obj['tablet']['background-color'] : '';
	$mobile_background   = isset( $above_header_bg_obj['mobile']['background-color'] ) ? $above_header_bg_obj['mobile']['background-color'] : '';

	$above_header_menu_bg_obj = astra_get_option( 'above-header-menu-bg-obj-responsive' );

	$above_header_menu_color           = astra_get_option( 'above-header-menu-color-responsive' );
	$above_header_menu_h_color         = astra_get_option( 'above-header-menu-h-color-responsive' );
	$above_header_menu_h_bg_color      = astra_get_option( 'above-header-menu-h-bg-color-responsive' );
	$above_header_menu_active_color    = astra_get_option( 'above-header-menu-active-color-responsive' );
	$above_header_menu_active_bg_color = astra_get_option( 'above-header-menu-active-bg-color-responsive' );

	$above_header_submenu_text_color      = astra_get_option( 'above-header-submenu-text-color-responsive' );
	$above_header_submenu_bg_color        = astra_get_option( 'above-header-submenu-bg-color-responsive' );
	$above_header_submenu_hover_color     = astra_get_option( 'above-header-submenu-hover-color-responsive' );
	$above_header_submenu_bg_hover_color  = astra_get_option( 'above-header-submenu-bg-hover-color-responsive' );
	$above_header_submenu_active_color    = astra_get_option( 'above-header-submenu-active-color-responsive' );
	$above_header_submenu_active_bg_color = astra_get_option( 'above-header-submenu-active-bg-color-responsive' );

	$above_header_submenu_border       = astra_get_option( 'above-header-submenu-border' );
	$above_header_submenu_item_border  = astra_get_option( 'above-header-submenu-item-border' );
	$above_header_submenu_item_b_color = astra_get_option( 'above-header-submenu-item-b-color', '#eaeaea' );
	$above_header_submenu_border_color = astra_get_option( 'above-header-submenu-border-color', $theme_color );

	$font_family    = astra_get_option( 'above-header-font-family' );
	$font_weight    = astra_get_option( 'above-header-font-weight' );
	$font_size      = astra_get_option( 'above-header-font-size' );
	$text_transform = astra_get_option( 'above-header-text-transform' );

	// Above header submenu typography.
	$font_size_above_header_dropdown      = astra_get_option( 'font-size-above-header-dropdown-menu' );
	$font_family_above_header_dropdown    = astra_get_option( 'font-family-above-header-dropdown-menu' );
	$font_weight_above_header_dropdown    = astra_get_option( 'font-weight-above-header-dropdown-menu' );
	$text_transform_above_header_dropdown = astra_get_option( 'text-transform-above-header-dropdown-menu' );

	// Header Break Point.
	$header_break_point = astra_header_break_point();

	$max_height = '26px';
	$padding    = '';
	if ( '' != $height && 30 < $height ) {
		$max_height = ( $height - 6 ) . 'px';
	}

	if ( 60 > $height ) {
		$padding = '.35em';
	}

	/**
	 * [1]. Above Header General options
	 * [2]. Above Header Responsive Typography
	 * [3]. Above Header Responsive Colors
	 */

	/**
	 * [1]. Above Header General options
	 */
	$common_css_output = array(

		'.ast-above-header'                => array(
			'border-bottom-width' => astra_get_css_value( $border_width, 'px' ),
			'border-bottom-color' => esc_attr( $border_color ),
			'line-height'         => astra_get_css_value( $height, 'px' ),
		),
		'.ast-above-header-menu, .ast-above-header .user-select' => array(
			'font-family'    => astra_get_css_value( $font_family, 'font' ),
			'font-weight'    => astra_get_css_value( $font_weight, 'font' ),
			'font-size'      => astra_responsive_font( $font_size, 'desktop' ),
			'text-transform' => esc_attr( $text_transform ),
		),
		/**
		 * Above Header Dropdown Navigation
		 */
		'.ast-above-header-menu .sub-menu' => array(
			'font-family'    => astra_get_css_value( $font_family_above_header_dropdown, 'font' ),
			'font-weight'    => astra_get_css_value( $font_weight_above_header_dropdown, 'font' ),
			'font-size'      => astra_responsive_font( $font_size_above_header_dropdown, 'desktop' ),
			'text-transform' => esc_attr( $text_transform_above_header_dropdown ),
			// Box Shadow.
			'box-shadow'     => '0 5px 20px rgba(0,0,0,0.06)',
		),
		'.ast-header-break-point .ast-above-header-merged-responsive .ast-above-header' => array(
			'border-bottom-width' => astra_get_css_value( $border_width, 'px' ),
			'border-bottom-color' => esc_attr( $border_color ),
		),

		'.ast-above-header .ast-search-menu-icon .search-field' => array(
			'max-height'     => esc_attr( $max_height ),
			'padding-top'    => esc_attr( $padding ),
			'padding-bottom' => esc_attr( $padding ),
		),

		'.ast-above-header-section-wrap'   => array(
			'min-height' => astra_get_css_value( $height, 'px' ),
		),
		'.ast-above-header-menu .sub-menu, .ast-above-header-menu .sub-menu a, .ast-above-header-menu .astra-full-megamenu-wrapper' => array(
			'border-color' => esc_attr( $above_header_submenu_border_color ),
		),
		'.ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget-title' => array(
			'color' => esc_attr( $theme_text_color ),
		),
		'.ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select a' => array(
			'color' => esc_attr( $theme_link_color ),
		),
	);

	/**
	 * [2]. Above Header General options
	 */
	$tablet_typography_css = array(
		'.ast-above-header-menu, .ast-above-header .user-select' => array(
			'font-size' => astra_responsive_font( $font_size, 'tablet' ),
		),
		'.ast-above-header-menu .sub-menu' => array(
			'font-size' => astra_responsive_font( $font_size_above_header_dropdown, 'mobile' ),
		),
	);

	$mobile_typography_css = array(
		'.ast-above-header-menu, .ast-above-header .user-select' => array(
			'font-size' => astra_responsive_font( $font_size, 'mobile' ),
		),
		'.ast-above-header-menu .sub-menu' => array(
			'font-size' => astra_responsive_font( $font_size_above_header_dropdown, 'tablet' ),
		),
	);

	/**
	 * [3]. Above Header Responsive Colors
	 */
	$desktop_colors = array(
		'.ast-above-header'                             => astra_get_background_obj( $above_header_bg_obj['desktop'] ),
		'.ast-header-break-point .ast-above-header-merged-responsive .ast-above-header' => array(
			'background-color' => esc_attr( $desktop_background ),
		),
		'.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul' => array(
			'background-color' => esc_attr( $desktop_background ),
		),
		'.ast-above-header-menu,.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul.ast-above-header-menu' => astra_get_background_obj( $above_header_menu_bg_obj['desktop'] ),

		'.ast-above-header-section .user-select, .ast-above-header-section .widget, .ast-above-header-section .widget-title' => array(
			'color' => esc_attr( $above_header_text_color['desktop'] ),
		),

		'.ast-above-header-section .user-select a, .ast-above-header-section .widget a' => array(
			'color' => esc_attr( $desktop_above_header_link_color ),
		),

		'.ast-above-header-section .search-field:focus' => array(
			'border-color' => esc_attr( $desktop_above_header_link_color ),
		),

		'.ast-above-header-section .user-select a:hover, .ast-above-header-section .widget a:hover' => array(
			'color' => esc_attr( $desktop_above_header_link_h_color ),
		),

		'.ast-above-header-navigation a'                => array(
			'color' => esc_attr( $above_header_menu_color['desktop'] ),
		),

		'.ast-above-header-navigation li:hover > a'     => array(
			'color'            => esc_attr( $above_header_menu_h_color['desktop'] ),
			'background-color' => esc_attr( $above_header_menu_h_bg_color['desktop'] ),
		),

		'.ast-above-header-navigation li.current-menu-item > a, .ast-above-header-navigation li.current-menu-ancestor > a' => array(
			'color'            => esc_attr( $above_header_menu_active_color['desktop'] ),
			'background-color' => esc_attr( $above_header_menu_active_bg_color['desktop'] ),
		),

		/**
		 * Below Header Dropdown Navigation
		 */
		'.ast-above-header-menu .sub-menu li:hover > a, .ast-above-header-menu .sub-menu li:focus > a, .ast-above-header-menu .sub-menu li.focus > a,.ast-above-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color'            => esc_attr( $above_header_submenu_hover_color['desktop'] ),
			'background-color' => esc_attr( $above_header_submenu_bg_hover_color['desktop'] ),
		),
		'.ast-above-header-menu .sub-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $above_header_submenu_active_color['desktop'] ),
		),
		'.ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-above-header-menu .sub-menu li.current-menu-item:hover > a, .ast-above-header-menu .sub-menu li.current-menu-item:focus > a, .ast-above-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color'            => esc_attr( $above_header_submenu_active_color['desktop'] ),
			'background-color' => esc_attr( $above_header_submenu_active_bg_color['desktop'] ),
		),
		'.ast-above-header-menu .sub-menu'              => array(
			'background-color' => esc_attr( $above_header_submenu_bg_color['desktop'] ),
		),
		'.ast-above-header-menu .sub-menu, .ast-above-header-menu .sub-menu a' => array(
			'color' => esc_attr( $above_header_submenu_text_color['desktop'] ),
		),
	);

	$tablet_colors = array(
		'.ast-above-header'                             => astra_get_background_obj( $above_header_bg_obj['tablet'] ),
		'.ast-header-break-point .ast-above-header-merged-responsive .ast-above-header' => array(
			'background-color' => esc_attr( $tablet_background ),
		),
		'.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul' => array(
			'background-color' => esc_attr( $tablet_background ),
		),
		'.ast-above-header-menu,.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul.ast-above-header-menu' => astra_get_background_obj( $above_header_menu_bg_obj['tablet'] ),
		'.ast-above-header-section .user-select, .ast-above-header-section .widget, .ast-above-header-section .widget-title' => array(
			'color' => esc_attr( $above_header_text_color['tablet'] ),
		),

		'.ast-above-header-section .user-select a, .ast-above-header-section .widget a' => array(
			'color' => esc_attr( $tablet_above_header_link_color ),
		),

		'.ast-above-header-section .search-field:focus' => array(
			'border-color' => esc_attr( $tablet_above_header_link_color ),
		),

		'.ast-above-header-section .user-select a:hover, .ast-above-header-section .widget a:hover' => array(
			'color' => esc_attr( $tablet_above_header_link_h_color ),
		),

		'.ast-above-header-navigation a'                => array(
			'color' => esc_attr( $above_header_menu_color['tablet'] ),
		),

		'.ast-above-header-navigation li:hover > a'     => array(
			'color'            => esc_attr( $above_header_menu_h_color['tablet'] ),
			'background-color' => esc_attr( $above_header_menu_h_bg_color['tablet'] ),
		),

		'.ast-above-header-navigation li.current-menu-item > a, .ast-above-header-navigation li.current-menu-ancestor > a' => array(
			'color'            => esc_attr( $above_header_menu_active_color['tablet'] ),
			'background-color' => esc_attr( $above_header_menu_active_bg_color['tablet'] ),
		),

		/**
		 * Below Header Dropdown Navigation
		 */
		'.ast-above-header-menu .sub-menu li:hover > a, .ast-above-header-menu .sub-menu li:focus > a, .ast-above-header-menu .sub-menu li.focus > a,.ast-above-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color'            => esc_attr( $above_header_submenu_hover_color['tablet'] ),
			'background-color' => esc_attr( $above_header_submenu_bg_hover_color['tablet'] ),
		),
		'.ast-above-header-menu .sub-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $above_header_submenu_active_color['tablet'] ),
		),
		'.ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-above-header-menu .sub-menu li.current-menu-item:hover > a, .ast-above-header-menu .sub-menu li.current-menu-item:focus > a, .ast-above-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color'            => esc_attr( $above_header_submenu_active_color['tablet'] ),
			'background-color' => esc_attr( $above_header_submenu_active_bg_color['tablet'] ),
		),
		'.ast-above-header-menu .sub-menu'              => array(
			'background-color' => esc_attr( $above_header_submenu_bg_color['tablet'] ),
		),
		'.ast-above-header-menu .sub-menu, .ast-above-header-menu .sub-menu a' => array(
			'color' => esc_attr( $above_header_submenu_text_color['tablet'] ),
		),
	);

	$mobile_colors = array(
		'.ast-above-header'                             => astra_get_background_obj( $above_header_bg_obj['mobile'] ),
		'.ast-header-break-point .ast-above-header-merged-responsive .ast-above-header' => array(
			'background-color' => esc_attr( $mobile_background ),
		),
		'.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul' => array(
			'background-color' => esc_attr( $mobile_background ),
		),
		'.ast-above-header-menu,.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul.ast-above-header-menu' => astra_get_background_obj( $above_header_menu_bg_obj['mobile'] ),
		'.ast-above-header-section .user-select, .ast-above-header-section .widget, .ast-above-header-section .widget-title' => array(
			'color' => esc_attr( $above_header_text_color['mobile'] ),
		),

		'.ast-above-header-section .user-select a, .ast-above-header-section .widget a' => array(
			'color' => esc_attr( $mobile_above_header_link_color ),
		),

		'.ast-above-header-section .search-field:focus' => array(
			'border-color' => esc_attr( $mobile_above_header_link_color ),
		),

		'.ast-above-header-section .user-select a:hover, .ast-above-header-section .widget a:hover' => array(
			'color' => esc_attr( $mobile_above_header_link_h_color ),
		),

		'.ast-above-header-navigation a'                => array(
			'color' => esc_attr( $above_header_menu_color['mobile'] ),
		),

		'.ast-above-header-navigation li:hover > a'     => array(
			'color'            => esc_attr( $above_header_menu_h_color['mobile'] ),
			'background-color' => esc_attr( $above_header_menu_h_bg_color['mobile'] ),
		),

		'.ast-above-header-navigation li.current-menu-item > a, .ast-above-header-navigation li.current-menu-ancestor > a' => array(
			'color'            => esc_attr( $above_header_menu_active_color['mobile'] ),
			'background-color' => esc_attr( $above_header_menu_active_bg_color['mobile'] ),
		),

		/**
		 * Below Header Dropdown Navigation
		 */
		'.ast-above-header-menu .sub-menu li:hover > a, .ast-above-header-menu .sub-menu li:focus > a, .ast-above-header-menu .sub-menu li.focus > a,.ast-above-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color'            => esc_attr( $above_header_submenu_hover_color['mobile'] ),
			'background-color' => esc_attr( $above_header_submenu_bg_hover_color['mobile'] ),
		),
		'.ast-above-header-menu .sub-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-above-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $above_header_submenu_active_color['mobile'] ),
		),
		'.ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-above-header-menu .sub-menu li.current-menu-item:hover > a, .ast-above-header-menu .sub-menu li.current-menu-item:focus > a, .ast-above-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color'            => esc_attr( $above_header_submenu_active_color['mobile'] ),
			'background-color' => esc_attr( $above_header_submenu_active_bg_color['mobile'] ),
		),
		'.ast-above-header-menu .sub-menu'              => array(
			'background-color' => esc_attr( $above_header_submenu_bg_color['mobile'] ),
		),
		'.ast-above-header-menu .sub-menu, .ast-above-header-menu .sub-menu a' => array(
			'color' => esc_attr( $above_header_submenu_text_color['mobile'] ),
		),
	);

	// Common options of Above Header.
	$parse_css .= astra_parse_css( $common_css_output );

	// Above Header Responsive Typography.
	$parse_css .= astra_parse_css( $tablet_typography_css, '', '768' );
	$parse_css .= astra_parse_css( $mobile_typography_css, '', '544' );

	// Above Header Responsive Colors.
	$parse_css .= astra_parse_css( $desktop_colors );
	$parse_css .= astra_parse_css( $tablet_colors, '', '768' );
	$parse_css .= astra_parse_css( $mobile_colors, '', '544' );

	/**
	 * Hide the default naviagtion markup for responsive devices.
	 * Once class .ast-header-break-point is added to the body below CSS will be override by the
	 * .ast-header-break-point class
	 */
	$astra_navigation = array(
		'.ast-above-header-navigation,.ast-above-header-hide-on-mobile .ast-above-header-wrap' => array(
			'display' => esc_attr( 'none' ),
		),
	);
	$parse_css       .= astra_parse_css( $astra_navigation, '', $header_break_point );

	// Above header border.
	$border = array(
		'.ast-desktop .ast-above-header-menu.submenu-with-border .sub-menu a' => array(
			'border-bottom-width' => ( true == $above_header_submenu_item_border ) ? '1px' : '0px',
			'border-style'        => 'solid',
			'border-color'        => esc_attr( $above_header_submenu_item_b_color ),
		),
		'.ast-desktop .ast-above-header-menu.submenu-with-border .sub-menu .sub-menu' => array(
			'top' => ( isset( $above_header_submenu_border['top'] ) && '' != $above_header_submenu_border['top'] ) ? astra_get_css_value( '-' . $above_header_submenu_border['top'], 'px' ) : '',
		),
		'.ast-desktop .ast-above-header-menu.submenu-with-border .sub-menu' => array(
			'border-top-width'    => astra_get_css_value( $above_header_submenu_border['top'], 'px' ),
			'border-left-width'   => astra_get_css_value( $above_header_submenu_border['left'], 'px' ),
			'border-right-width'  => astra_get_css_value( $above_header_submenu_border['right'], 'px' ),
			'border-bottom-width' => astra_get_css_value( $above_header_submenu_border['bottom'], 'px' ),
			'border-style'        => 'solid',
		),
	);

	// Submenu items goes outside?
	$submenu_border_for_left_align_menu = array(
		'.ast-above-header-menu ul li.ast-left-align-sub-menu:hover > ul, .ast-above-header-menu ul li.ast-left-align-sub-menu.focus > ul' => array(
			'margin-left' => ( ( isset( $above_header_submenu_border['left'] ) && '' != $above_header_submenu_border['left'] ) || isset( $above_header_submenu_border['right'] ) && '' != $above_header_submenu_border['right'] ) ? astra_get_css_value( '-' . ( $above_header_submenu_border['left'] + $above_header_submenu_border['right'] ), 'px' ) : '',
		),
	);

	/* Parse CSS from array() */
	$parse_css .= astra_parse_css( $border );
	// Submenu items goes outside?
	$parse_css .= astra_parse_css( $submenu_border_for_left_align_menu, '769' );

	// Add Inline style.
	return $dynamic_css . $parse_css;
}
