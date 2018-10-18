<?php
/**
 * Colors & Background - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_ext_header_sections_colors_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_ext_header_sections_colors_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$header_bg_obj           = astra_get_option( 'header-bg-obj-responsive' );
	$desktop_header_bg_color = isset( $header_bg_obj['desktop']['background-color'] ) ? $header_bg_obj['desktop']['background-color'] : '';
	$tablet_header_bg_color  = isset( $header_bg_obj['tablet']['background-color'] ) ? $header_bg_obj['tablet']['background-color'] : '';
	$mobile_header_bg_color  = isset( $header_bg_obj['mobile']['background-color'] ) ? $header_bg_obj['mobile']['background-color'] : '';

	$disable_primary_nav = astra_get_option( 'disable-primary-nav' );
	$above_header_merged = astra_get_option( 'above-header-merge-menu' );
	$below_header_merged = astra_get_option( 'below-header-merge-menu' );

	$primary_menu_bg_image = astra_get_option( 'primary-menu-bg-obj-responsive' );

	$primary_menu_color      = astra_get_option( 'primary-menu-color-responsive' );
	$primary_menu_h_bg_color = astra_get_option( 'primary-menu-h-bg-color-responsive' );
	$primary_menu_h_color    = astra_get_option( 'primary-menu-h-color-responsive' );
	$primary_menu_a_bg_color = astra_get_option( 'primary-menu-a-bg-color-responsive' );
	$primary_menu_a_color    = astra_get_option( 'primary-menu-a-color-responsive' );

	$primary_submenu_b_color    = astra_get_option( 'primary-submenu-b-color' );
	$primary_submenu_bg_color   = astra_get_option( 'primary-submenu-bg-color-responsive' );
	$primary_submenu_color      = astra_get_option( 'primary-submenu-color-responsive' );
	$primary_submenu_h_bg_color = astra_get_option( 'primary-submenu-h-bg-color-responsive' );
	$primary_submenu_h_color    = astra_get_option( 'primary-submenu-h-color-responsive' );
	$primary_submenu_a_bg_color = astra_get_option( 'primary-submenu-a-bg-color-responsive' );
	$primary_submenu_a_color    = astra_get_option( 'primary-submenu-a-color-responsive' );

	$header_break_point = astra_header_break_point(); // Header Break Point.

	$parse_css = '';

	/**
	 * Merge Header Section when there is no primary menu
	 */
	if ( $disable_primary_nav && ( $above_header_merged || $below_header_merged ) ) {
		// Desktop.
		$desktop_colors = array(

			/**
			 * Primary Menu merge with Above Header & Below Header menu
			 */
			'.ast-header-sections-navigation li.current-menu-item > a, .ast-above-header-menu-items li.current-menu-item > a,.ast-below-header-menu-items li.current-menu-item > a,.ast-header-sections-navigation li.current-menu-ancestor > a, .ast-above-header-menu-items li.current-menu-ancestor > a,.ast-below-header-menu-items li.current-menu-ancestor > a' => array(
				'color'            => esc_attr( $primary_menu_a_color['desktop'] ),
				'background-color' => esc_attr( $primary_menu_a_bg_color['desktop'] ),
			),
			'.main-header-menu a:hover, .ast-header-custom-item a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a, .ast-header-break-point .ast-header-sections-navigation a:hover, .ast-header-break-point .ast-header-sections-navigation a:focus' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color['desktop'] ),
				'color'            => esc_attr( $primary_menu_h_color['desktop'] ),
			),
			'.ast-header-sections-navigation li:hover > .ast-menu-toggle, .ast-header-sections-navigation li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_menu_h_color['desktop'] ),
			),

			'.ast-header-sections-navigation, .ast-header-sections-navigation a, .ast-above-header-menu-items,.ast-above-header-menu-items a,.ast-below-header-menu-items, .ast-below-header-menu-items a' => array(
				'color' => esc_attr( $primary_menu_color['desktop'] ),
			),

			'.ast-header-sections-navigation .ast-inline-search form' => array(
				'border-color' => esc_attr( $primary_menu_color['desktop'] ),
			),

			/**
			 * Primary Submenu
			 */
			'.ast-header-sections-navigation .sub-menu a, .ast-above-header-menu-items .sub-menu a, .ast-below-header-menu-items .sub-menu a' => array(
				'color' => esc_attr( $primary_submenu_color['desktop'] ),
			),
			'.ast-header-sections-navigation .sub-menu a:hover, .ast-above-header-menu-items .sub-menu a:hover, .ast-below-header-menu-items .sub-menu a:hover' => array(
				'color'            => esc_attr( $primary_submenu_h_color['desktop'] ),
				'background-color' => esc_attr( $primary_submenu_h_bg_color['desktop'] ),
			),
			'.ast-header-sections-navigation .sub-menu li:hover > .ast-menu-toggle, .ast-header-sections-navigation .sub-menu li:focus > .ast-menu-toggle, .ast-above-header-menu-items .sub-menu li:hover > .ast-menu-toggle, .ast-below-header-menu-items .sub-menu li:hover > .ast-menu-toggle, .ast-above-header-menu-items .sub-menu li:focus > .ast-menu-toggle, .ast-below-header-menu-items .sub-menu li:focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_submenu_h_color['desktop'] ),
			),
			'.ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
				'color'            => esc_attr( $primary_submenu_a_color['desktop'] ),
				'background-color' => esc_attr( $primary_submenu_a_bg_color['desktop'] ),
			),
			'.ast-header-sections-navigation div > li > ul' => array(
				'border-color' => esc_attr( $primary_submenu_b_color ),
			),
			'.main-navigation .sub-menu, .ast-header-break-point .main-header-menu ul, .ast-header-sections-navigation div > li > ul, .ast-above-header-menu-items li > ul, .ast-below-header-menu-items li > ul' => array(
				'background-color' => esc_attr( $primary_submenu_bg_color['desktop'] ),
			),
		);

		// Tablet.
		$tablet_colors = array(

			/**
			 * Primary Menu merge with Above Header & Below Header menu
			 */
			'.ast-header-sections-navigation li.current-menu-item > a, .ast-above-header-menu-items li.current-menu-item > a,.ast-below-header-menu-items li.current-menu-item > a,.ast-header-sections-navigation li.current-menu-ancestor > a, .ast-above-header-menu-items li.current-menu-ancestor > a,.ast-below-header-menu-items li.current-menu-ancestor > a' => array(
				'color'            => esc_attr( $primary_menu_a_color['tablet'] ),
				'background-color' => esc_attr( $primary_menu_a_bg_color['tablet'] ),
			),
			'.main-header-menu a:hover, .ast-header-custom-item a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a, .ast-header-break-point .ast-header-sections-navigation a:hover, .ast-header-break-point .ast-header-sections-navigation a:focus' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color['tablet'] ),
				'color'            => esc_attr( $primary_menu_h_color['tablet'] ),
			),
			'.ast-header-sections-navigation li:hover > .ast-menu-toggle, .ast-header-sections-navigation li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_menu_h_color['tablet'] ),
			),

			'.ast-header-sections-navigation, .ast-header-sections-navigation a, .ast-above-header-menu-items,.ast-above-header-menu-items a,.ast-below-header-menu-items, .ast-below-header-menu-items a' => array(
				'color' => esc_attr( $primary_menu_color['tablet'] ),
			),

			'.ast-header-sections-navigation .ast-inline-search form' => array(
				'border-color' => esc_attr( $primary_menu_color['tablet'] ),
			),

			/**
			 * Primary Submenu
			 */
			'.ast-header-sections-navigation .sub-menu a, .ast-above-header-menu-items .sub-menu a, .ast-below-header-menu-items .sub-menu a' => array(
				'color' => esc_attr( $primary_submenu_color['tablet'] ),
			),
			'.ast-header-sections-navigation .sub-menu a:hover, .ast-above-header-menu-items .sub-menu a:hover, .ast-below-header-menu-items .sub-menu a:hover' => array(
				'color'            => esc_attr( $primary_submenu_h_color['tablet'] ),
				'background-color' => esc_attr( $primary_submenu_h_bg_color['tablet'] ),
			),
			'.ast-header-sections-navigation .sub-menu li:hover > .ast-menu-toggle, .ast-header-sections-navigation .sub-menu li:focus > .ast-menu-toggle, .ast-above-header-menu-items .sub-menu li:hover > .ast-menu-toggle, .ast-below-header-menu-items .sub-menu li:hover > .ast-menu-toggle, .ast-above-header-menu-items .sub-menu li:focus > .ast-menu-toggle, .ast-below-header-menu-items .sub-menu li:focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_submenu_h_color['tablet'] ),
			),
			'.ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
				'color'            => esc_attr( $primary_submenu_a_color['tablet'] ),
				'background-color' => esc_attr( $primary_submenu_a_bg_color['tablet'] ),
			),
			'.main-navigation .sub-menu, .ast-header-break-point .main-header-menu ul, .ast-header-sections-navigation div > li > ul, .ast-above-header-menu-items li > ul, .ast-below-header-menu-items li > ul' => array(
				'background-color' => esc_attr( $primary_submenu_bg_color['tablet'] ),
			),
		);

		// Mobile.
		$mobile_colors = array(

			/**
			 * Primary Menu merge with Above Header & Below Header menu
			 */
			'.ast-header-sections-navigation li.current-menu-item > a, .ast-above-header-menu-items li.current-menu-item > a,.ast-below-header-menu-items li.current-menu-item > a,.ast-header-sections-navigation li.current-menu-ancestor > a, .ast-above-header-menu-items li.current-menu-ancestor > a,.ast-below-header-menu-items li.current-menu-ancestor > a' => array(
				'color'            => esc_attr( $primary_menu_a_color['mobile'] ),
				'background-color' => esc_attr( $primary_menu_a_bg_color['mobile'] ),
			),
			'.main-header-menu a:hover, .ast-header-custom-item a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a, .ast-header-break-point .ast-header-sections-navigation a:hover, .ast-header-break-point .ast-header-sections-navigation a:focus' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color['mobile'] ),
				'color'            => esc_attr( $primary_menu_h_color['mobile'] ),
			),
			'.ast-header-sections-navigation li:hover > .ast-menu-toggle, .ast-header-sections-navigation li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_menu_h_color['mobile'] ),
			),

			'.ast-header-sections-navigation, .ast-header-sections-navigation a, .ast-above-header-menu-items,.ast-above-header-menu-items a,.ast-below-header-menu-items, .ast-below-header-menu-items a' => array(
				'color' => esc_attr( $primary_menu_color['mobile'] ),
			),

			'.ast-header-sections-navigation .ast-inline-search form' => array(
				'border-color' => esc_attr( $primary_menu_color['mobile'] ),
			),

			/**
			 * Primary Submenu
			 */
			'.ast-header-sections-navigation .sub-menu a, .ast-above-header-menu-items .sub-menu a, .ast-below-header-menu-items .sub-menu a' => array(
				'color' => esc_attr( $primary_submenu_color['mobile'] ),
			),
			'.ast-header-sections-navigation .sub-menu a:hover, .ast-above-header-menu-items .sub-menu a:hover, .ast-below-header-menu-items .sub-menu a:hover' => array(
				'color'            => esc_attr( $primary_submenu_h_color['mobile'] ),
				'background-color' => esc_attr( $primary_submenu_h_bg_color['mobile'] ),
			),
			'.ast-header-sections-navigation .sub-menu li:hover > .ast-menu-toggle, .ast-header-sections-navigation .sub-menu li:focus > .ast-menu-toggle, .ast-above-header-menu-items .sub-menu li:hover > .ast-menu-toggle, .ast-below-header-menu-items .sub-menu li:hover > .ast-menu-toggle, .ast-above-header-menu-items .sub-menu li:focus > .ast-menu-toggle, .ast-below-header-menu-items .sub-menu li:focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_submenu_h_color['mobile'] ),
			),
			'.ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
				'color'            => esc_attr( $primary_submenu_a_color['mobile'] ),
				'background-color' => esc_attr( $primary_submenu_a_bg_color['mobile'] ),
			),
			'.main-navigation .sub-menu, .ast-header-break-point .main-header-menu ul, .ast-header-sections-navigation div > li > ul, .ast-above-header-menu-items li > ul, .ast-below-header-menu-items li > ul' => array(
				'background-color' => esc_attr( $primary_submenu_bg_color['mobile'] ),
			),
		);

		// Desktop.
		if ( '' != $primary_menu_bg_image['desktop']['background-color'] || '' != $primary_menu_bg_image['desktop']['background-image'] ) {
			// If primary menu background color is set then only apply it to the Merged menu.
			$desktop_colors['.ast-header-break-point .ast-header-sections-navigation'] = astra_get_background_obj( $primary_menu_bg_image['desktop'] );
		} else {
			// If primary menu background color is not set then only apply Header background color to the Merged menu.
			$desktop_colors['.ast-header-break-point .ast-header-sections-navigation, .ast-header-break-point .ast-above-header-menu-items, .ast-header-break-point .ast-below-header-menu-items'] = array(
				'background-color' => esc_attr( $desktop_header_bg_color ),
			);
		}

		// Tablet.
		if ( '' != $primary_menu_bg_image['tablet']['background-color'] || '' != $primary_menu_bg_image['tablet']['background-image'] ) {
			// If primary menu background color is set then only apply it to the Merged menu.
			$tablet_colors['.ast-header-break-point .ast-header-sections-navigation'] = astra_get_background_obj( $primary_menu_bg_image['tablet'] );
		} else {
			// If primary menu background color is not set then only apply Header background color to the Merged menu.
			$tablet_colors['.ast-header-break-point .ast-header-sections-navigation, .ast-header-break-point .ast-above-header-menu-items, .ast-header-break-point .ast-below-header-menu-items'] = array(
				'background-color' => esc_attr( $tablet_header_bg_color ),
			);
		}

		// mobile.
		if ( '' != $primary_menu_bg_image['mobile']['background-color'] || '' != $primary_menu_bg_image['mobile']['background-image'] ) {
			// If primary menu background color is set then only apply it to the Merged menu.
			$mobile_colors['.ast-header-break-point .ast-header-sections-navigation'] = astra_get_background_obj( $primary_menu_bg_image['mobile'] );
		} else {
			// If primary menu background color is not set then only apply Header background color to the Merged menu.
			$mobile_colors['.ast-header-break-point .ast-header-sections-navigation, .ast-header-break-point .ast-above-header-menu-items, .ast-header-break-point .ast-below-header-menu-items'] = array(
				'background-color' => esc_attr( $mobile_header_bg_color ),
			);
		}

		/* Parse CSS from array() */
		$parse_css .= astra_parse_css( $desktop_colors );
		$parse_css .= astra_parse_css( $tablet_colors, '', '768' );
		$parse_css .= astra_parse_css( $mobile_colors, '', '544' );
	}

	return $dynamic_css . $parse_css;
}
