<?php
/**
 * Below Header - Dyanamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_ext_below_header_dynamic_css' );

/**
 * Dynamic CSS funtion
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dyanamic CSS Filters.
 * @return string
 */
function astra_ext_below_header_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	// set page width depending on site layout.
	$below_header_layout = astra_get_option( 'below-header-layout' );

	if ( 'disabled' == $below_header_layout ) {
		return $dynamic_css;
	}

	// Below Header - Height/Line-Height.
	$below_header_line_height = astra_get_option( 'below-header-height' );
	$below_header_border      = astra_get_option( 'below-header-separator' );

	// Background & Color.
	$link_color                  = astra_get_option( 'link-color' );
	$link_hover_color            = astra_get_option( 'link-h-color' );
	$text_color                  = astra_get_option( 'text-color' );
	$right_left_text_color       = astra_get_option( 'below-header-text-color' );
	$right_left_link_color       = astra_get_option( 'below-header-link-color', '#d6d6d6' );
	$right_left_link_hover_color = astra_get_option( 'below-header-link-hover-color', '#ffffff' );

	$below_header_obj = astra_get_option( 'below-header-bg-obj' );
	$below_header_bg  = isset( $below_header_obj['background-color'] ) ? $below_header_obj['background-color'] : '#414042';

	$below_header_border_color          = astra_get_option( 'below-header-bottom-border-color' );
	$below_header_menu_text             = astra_get_option( 'below-header-menu-text-color' );
	$below_header_menu_hover_color      = astra_get_option( 'below-header-menu-text-hover-color' );
	$below_header_menu_hover_bg_color   = astra_get_option( 'below-header-menu-bg-hover-color' );
	$below_header_menu_current_color    = astra_get_option( 'below-header-current-menu-text-color' );
	$below_header_menu_current_bg_color = astra_get_option( 'below-header-current-menu-bg-color' );

	$below_header_submenu_text_color      = astra_get_option( 'below-header-submenu-text-color' );
	$below_header_submenu_bg_color        = astra_get_option( 'below-header-submenu-bg-color' );
	$below_header_submenu_hover_color     = astra_get_option( 'below-header-submenu-hover-color' );
	$below_header_submenu_bg_hover_color  = astra_get_option( 'below-header-submenu-bg-hover-color' );
	$below_header_submenu_active_color    = astra_get_option( 'below-header-submenu-active-color' );
	$below_header_submenu_active_bg_color = astra_get_option( 'below-header-submenu-active-bg-color' );
	$below_header_submenu_border_color    = astra_get_option( 'below-header-submenu-border-color' );

	$font_size_below_header_content      = astra_get_option( 'font-size-below-header-content' );
	$font_family_below_header_content    = astra_get_option( 'font-family-below-header-content' );
	$font_weight_below_header_content    = astra_get_option( 'font-weight-below-header-content' );
	$text_transform_below_header_content = astra_get_option( 'text-transform-below-header-content' );

	$font_size_below_header_primary      = astra_get_option( 'font-size-below-header-primary-menu' );
	$font_family_below_header_primary    = astra_get_option( 'font-family-below-header-primary-menu' );
	$font_weight_below_header_primary    = astra_get_option( 'font-weight-below-header-primary-menu' );
	$text_transform_below_header_primary = astra_get_option( 'text-transform-below-header-primary-menu' );

	$font_size_below_header_dropdown      = astra_get_option( 'font-size-below-header-dropdown-menu' );
	$font_family_below_header_dropdown    = astra_get_option( 'font-family-below-header-dropdown-menu' );
	$font_weight_below_header_dropdown    = astra_get_option( 'font-weight-below-header-dropdown-menu' );
	$text_transform_below_header_dropdown = astra_get_option( 'text-transform-below-header-dropdown-menu' );
	$above_header_bg_obj                  = astra_get_option( 'above-header-bg-obj' );

	$max_height = '26px';
	$padding    = '';
	if ( '' != $below_header_line_height && 30 < $below_header_line_height ) {
		$max_height = ( $below_header_line_height - 8 ) . 'px';
	}

	if ( 60 > $below_header_line_height ) {
		$padding = '.35em';
	}

	$below_header_parse_css = '';

	/**
	 * Below Header
	 */
	$css_output = array(

		'.ast-below-header'                                => array(
			'border-bottom-width' => astra_get_css_value( $below_header_border, 'px' ),
			'border-bottom-color' => esc_attr( $below_header_border_color ),
			'line-height'         => astra_get_css_value( $below_header_line_height, 'px' ),
		),

		'.ast-below-header-section-wrap'                   => array(
			'min-height' => astra_get_css_value( $below_header_line_height, 'px' ),
		),

		'.below-header-user-select .ast-search-menu-icon .search-field' => array(
			'max-height'     => esc_attr( $max_height ),
			'padding-top'    => esc_attr( $padding ),
			'padding-bottom' => esc_attr( $padding ),
		),

		'.ast-below-header, .ast-below-header-wrap .ast-search-menu-icon .search-field, .ast-below-header-menu .sub-menu' => array(
			'background-color' => esc_attr( $below_header_bg ),
		),

		'.ast-header-break-point .ast-below-header-section-separated .ast-below-header-actual-nav' => array(
			'background-color' => esc_attr( $below_header_bg ),
		),

		/**
		 * Below Header Navigation
		 */
		'.ast-below-header-menu'                           => array(
			'font-family'    => astra_get_css_value( $font_family_below_header_primary, 'font' ),
			'font-weight'    => astra_get_css_value( $font_weight_below_header_primary, 'font' ),
			'font-size'      => astra_responsive_font( $font_size_below_header_primary, 'desktop' ),
			'text-transform' => esc_attr( $text_transform_below_header_primary ),
		),

		'.ast-below-header-menu, .ast-below-header-menu a' => array(
			'color' => esc_attr( $below_header_menu_text ),
		),

		'.ast-below-header-menu li:hover > a, .ast-below-header-menu li:focus > a, .ast-below-header-menu li.focus > a' => array(
			'color'            => esc_attr( $below_header_menu_hover_color ),
			'background-color' => esc_attr( $below_header_menu_hover_bg_color ),
		),

		'.ast-below-header-menu li.current-menu-ancestor > a, .ast-below-header-menu li.current-menu-item > a, .ast-below-header-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-below-header-menu li.current-menu-item > .ast-menu-toggle, .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-below-header-menu .sub-menu li.current-menu-item.focus > a, .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-below-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-below-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-below-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $below_header_menu_current_color ),
		),

		'.ast-below-header-menu li.current-menu-ancestor > a, .ast-below-header-menu li.current-menu-item > a, .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-below-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'background-color' => esc_attr( $below_header_menu_current_bg_color ),
		),

		/**
		 * Below Header Dropdown Navigation
		 */
		'.ast-below-header-menu .sub-menu'                 => array(
			'font-family'    => astra_get_css_value( $font_family_below_header_dropdown, 'font' ),
			'font-weight'    => astra_get_css_value( $font_weight_below_header_dropdown, 'font' ),
			'font-size'      => astra_responsive_font( $font_size_below_header_dropdown, 'desktop' ),
			'text-transform' => esc_attr( $text_transform_below_header_dropdown ),
		),

		'.ast-below-header-menu .sub-menu li:hover > a, .ast-below-header-menu .sub-menu li:focus > a, .ast-below-header-menu .sub-menu li.focus > a' => array(
			'color'            => esc_attr( $below_header_submenu_hover_color ),
			'background-color' => esc_attr( $below_header_submenu_bg_hover_color ),
		),

		'.ast-below-header-menu .sub-menu li.current-menu-ancestor > a, .ast-below-header-menu .sub-menu li.current-menu-item > a, .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-below-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color'            => esc_attr( $below_header_submenu_active_color ),
			'background-color' => esc_attr( $below_header_submenu_active_bg_color ),
		),

		'.ast-below-header-menu .sub-menu a'               => array(
			'background-color' => esc_attr( $below_header_submenu_bg_color ),
		),

		'.ast-below-header-menu .sub-menu, .ast-below-header-menu .sub-menu a' => array(
			'color'        => esc_attr( $below_header_submenu_text_color ),
			'border-color' => esc_attr( $below_header_submenu_border_color ),
		),

		/**
		 * Content Colors & Typography
		 */
		'.below-header-user-select'                        => array(
			'color'          => esc_attr( $right_left_text_color ),
			'font-family'    => astra_get_css_value( $font_family_below_header_content, 'font' ),
			'font-weight'    => astra_get_css_value( $font_weight_below_header_content, 'font' ),
			'font-size'      => astra_responsive_font( $font_size_below_header_content, 'desktop' ),
			'text-transform' => esc_attr( $text_transform_below_header_content ),
		),
		'.below-header-user-select .widget,.below-header-user-select .widget-title' => array(
			'color' => esc_attr( $right_left_text_color ),
		),

		'.below-header-user-select a, .below-header-user-select .widget a' => array(
			'color' => esc_attr( $right_left_link_color ),
		),

		'.below-header-user-select a:hover, .below-header-user-select .widget a:hover' => array(
			'color' => esc_attr( $right_left_link_hover_color ),
		),

		'.below-header-user-select input.search-field:focus, .below-header-user-select input.search-field.focus' => array(
			'border-color' => esc_attr( $right_left_link_color ),
		),
	);

	$below_header_parse_css .= astra_parse_css( $css_output );

	$css_output = array(
		'.ast-above-header' => astra_get_background_obj( $above_header_bg_obj ),
		'.ast-below-header' => astra_get_background_obj( $below_header_obj ),
	);

	$below_header_parse_css .= astra_parse_css( $css_output );

	$tablet_css = array(
		'.ast-below-header-menu'           => array(
			'font-size' => astra_responsive_font( $font_size_below_header_primary, 'tablet' ),
		),
		'.ast-below-header-menu .sub-menu' => array(
			'font-size' => astra_responsive_font( $font_size_below_header_dropdown, 'tablet' ),
		),
		'.below-header-user-select'        => array(
			'font-size' => astra_responsive_font( $font_size_below_header_content, 'tablet' ),
		),
	);

	$below_header_parse_css .= astra_parse_css( $tablet_css, '', '768' );

	$mobile_css = array(
		'.ast-below-header-menu'           => array(
			'font-size' => astra_responsive_font( $font_size_below_header_primary, 'mobile' ),
		),
		'.ast-below-header-menu .sub-menu' => array(
			'font-size' => astra_responsive_font( $font_size_below_header_dropdown, 'mobile' ),
		),
		'.below-header-user-select'        => array(
			'font-size' => astra_responsive_font( $font_size_below_header_content, 'mobile' ),
		),
	);

	$below_header_parse_css .= astra_parse_css( $mobile_css, '', '544' );

	// Add Inline style.
	return $dynamic_css .= $below_header_parse_css;
}

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

	$theme_text_color       = astra_get_option( 'text-color' );
	$theme_link_color       = astra_get_option( 'link-color' );
	$theme_link_hover_color = astra_get_option( 'link-h-color' );

	$color               = astra_get_option( 'above-header-text-color' );
	$link_color          = astra_get_option( 'above-header-link-color', $theme_link_color );
	$link_h_color        = astra_get_option( 'above-header-link-h-color', $theme_link_hover_color );
	$above_header_bg_obj = astra_get_option( 'above-header-bg-obj' );
	$background          = isset( $above_header_bg_obj['background-color'] ) ? $above_header_bg_obj['background-color'] : '';

	$menu_color           = astra_get_option( 'above-header-menu-color' );
	$menu_h_color         = astra_get_option( 'above-header-menu-h-color' );
	$menu_h_bg_color      = astra_get_option( 'above-header-menu-h-bg-color' );
	$menu_active_color    = astra_get_option( 'above-header-menu-active-color' );
	$menu_active_bg_color = astra_get_option( 'above-header-menu-active-bg-color' );

	$above_header_submenu_text_color      = astra_get_option( 'above-header-submenu-text-color' );
	$above_header_submenu_bg_color        = astra_get_option( 'above-header-submenu-bg-color' );
	$above_header_submenu_hover_color     = astra_get_option( 'above-header-submenu-hover-color' );
	$above_header_submenu_bg_hover_color  = astra_get_option( 'above-header-submenu-bg-hover-color' );
	$above_header_submenu_active_color    = astra_get_option( 'above-header-submenu-active-color' );
	$above_header_submenu_active_bg_color = astra_get_option( 'above-header-submenu-active-bg-color' );
	$above_header_submenu_border_color    = astra_get_option( 'above-header-submenu-border-color' );

	$font_family    = astra_get_option( 'above-header-font-family' );
	$font_weight    = astra_get_option( 'above-header-font-weight' );
	$font_size      = astra_get_option( 'above-header-font-size' );
	$text_transform = astra_get_option( 'above-header-text-transform' );

	$max_height = '26px';
	$padding    = '';
	if ( '' != $height && 30 < $height ) {
		$max_height = ( $height - 6 ) . 'px';
	}

	if ( 60 > $height ) {
		$padding = '.35em';
	}

	$css_output = array(

		'.ast-above-header'                             => array(
			'background-color'    => esc_attr( $background ),
			'border-bottom-width' => astra_get_css_value( $border_width, 'px' ),
			'border-bottom-color' => esc_attr( $border_color ),
			'line-height'         => astra_get_css_value( $height, 'px' ),
			'font-family'         => astra_get_css_value( $font_family, 'font' ),
			'font-weight'         => astra_get_css_value( $font_weight, 'font' ),
			'font-size'           => astra_responsive_font( $font_size, 'desktop' ),
			'text-transform'      => esc_attr( $text_transform ),
			'text-transform'      => esc_attr( $text_transform ),
		),
		'.ast-header-break-point .ast-above-header-merged-responsive .ast-above-header' => array(
			'background-color'    => esc_attr( $background ),
			'border-bottom-width' => astra_get_css_value( $border_width, 'px' ),
			'border-bottom-color' => esc_attr( $border_color ),
		),

		'.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation, .ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul' => array(
			'background-color' => esc_attr( $background ),
		),

		'.ast-above-header .ast-search-menu-icon .search-field' => array(
			'max-height'     => esc_attr( $max_height ),
			'padding-top'    => esc_attr( $padding ),
			'padding-bottom' => esc_attr( $padding ),
		),

		'.ast-above-header .ast-search-menu-icon .search-field, .ast-above-header .ast-search-menu-icon .search-field:focus' => array(
			'background-color' => esc_attr( $background ),
		),

		'.ast-above-header-section-wrap'                => array(
			'min-height' => astra_get_css_value( $height, 'px' ),
		),

		'.ast-above-header-section .user-select, .ast-above-header-section .widget, .ast-above-header-section .widget-title' => array(
			'color' => esc_attr( $color ),
		),

		'.ast-above-header-section .user-select a, .ast-above-header-section .widget a' => array(
			'color' => esc_attr( $link_color ),
		),

		'.ast-above-header-section .search-field:focus' => array(
			'border-color' => esc_attr( $link_color ),
		),

		'.ast-above-header-section .user-select a:hover, .ast-above-header-section .widget a:hover' => array(
			'color' => esc_attr( $link_h_color ),
		),

		'.ast-above-header-navigation a'                => array(
			'color' => esc_attr( $menu_color ),
		),

		'.ast-above-header-navigation li:hover > a'     => array(
			'color' => esc_attr( $menu_h_color ),
		),

		'.ast-above-header-navigation li:hover'         => array(
			'background-color' => esc_attr( $menu_h_bg_color ),
		),

		'.ast-above-header-navigation li.current-menu-item > a' => array(
			'color' => esc_attr( $menu_active_color ),
		),

		'.ast-above-header-navigation li.current-menu-item' => array(
			'background-color' => esc_attr( $menu_active_bg_color ),
		),
		'.ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget-title' => array(
			'color' => esc_attr( $theme_text_color ),
		),
		'.ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select a' => array(
			'color' => esc_attr( $theme_link_color ),
		),

		/**
		 * Below Header Dropdown Navigation
		 */
		'.ast-above-header-menu .sub-menu li:hover > a, .ast-above-header-menu .sub-menu li:focus > a, .ast-above-header-menu .sub-menu li.focus > a' => array(
			'color'            => esc_attr( $above_header_submenu_hover_color ),
			'background-color' => esc_attr( $above_header_submenu_bg_hover_color ),
		),

		'.ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-above-header-menu .sub-menu li.current-menu-item:hover > a, .ast-above-header-menu .sub-menu li.current-menu-item:focus > a, .ast-above-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color'            => esc_attr( $above_header_submenu_active_color ),
			'background-color' => esc_attr( $above_header_submenu_active_bg_color ),
		),

		'.ast-above-header-menu .sub-menu a'            => array(
			'background-color' => esc_attr( $above_header_submenu_bg_color ),
		),

		'.ast-above-header-menu .sub-menu, .ast-above-header-menu .sub-menu a' => array(
			'color'        => esc_attr( $above_header_submenu_text_color ),
			'border-color' => esc_attr( $above_header_submenu_border_color ),
		),

	);

	$parse_css .= astra_parse_css( $css_output );

	$tablet_css = array(
		'.ast-above-header' => array(
			'font-size' => astra_responsive_font( $font_size, 'tablet' ),
		),
	);

	$parse_css .= astra_parse_css( $tablet_css, '', '768' );

	$mobile_css = array(
		'.ast-above-header' => array(
			'font-size' => astra_responsive_font( $font_size, 'mobile' ),
		),
	);

	$parse_css .= astra_parse_css( $mobile_css, '', '544' );

	// Add Inline style.
	return $dynamic_css .= $parse_css;
}
