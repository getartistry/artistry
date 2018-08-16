<?php
/**
 * Mobile Header - Dynamic CSS
 *
 * @package Astra Addon
 */

/**
 * Mobile Header options.
 */
add_filter( 'astra_dynamic_css', 'astra_ext_mobile_header_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_ext_mobile_header_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$menu_style            = astra_get_option( 'mobile-menu-style' );
	$flayout_sidebar_width = apply_filters( 'astra_flayout_sidebar_width', 325 );
	$primary_menu_spacing  = astra_get_option( 'primary-menu-spacing' );

	$theme_color                          = astra_get_option( 'theme-color' );
	$link_color                           = astra_get_option( 'link-color', $theme_color );
	$text_color                           = astra_get_option( 'text-color' );
	$btn_bg_color                         = astra_get_option( 'button-bg-color', $theme_color );
	$mobile_header_toggle_btn_style_color = astra_get_option( 'mobile-header-toggle-btn-style-color', $btn_bg_color );
	$mobile_header_toggle_btn_style       = astra_get_option( 'mobile-header-toggle-btn-style' );

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

	$disable_primary_menu = astra_get_option( 'disable-primary-nav' );
	$merge_above_header   = astra_get_option( 'above-header-merge-menu' );
	$merge_below_header   = astra_get_option( 'below-header-merge-menu' );

	$header_bg_obj           = astra_get_option( 'header-bg-obj-responsive' );
	$desktop_header_bg_color = isset( $header_bg_obj['desktop']['background-color'] ) ? $header_bg_obj['desktop']['background-color'] : '';
	$tablet_header_bg_color  = isset( $header_bg_obj['tablet']['background-color'] ) ? $header_bg_obj['tablet']['background-color'] : '';
	$mobile_header_bg_color  = isset( $header_bg_obj['mobile']['background-color'] ) ? $header_bg_obj['mobile']['background-color'] : '';

	$mobile_header_close_desktop_color = astar( astra_get_option( 'primary-menu-color-responsive' ), 'desktop', $text_color );
	$mobile_header_close_tablet_color  = astar( astra_get_option( 'primary-menu-color-responsive' ), 'tablet' );
	$mobile_header_close_mobile_color  = astar( astra_get_option( 'primary-menu-color-responsive' ), 'mobile' );

	$mobile_header_menu_all_border = astra_get_option( 'mobile-header-menu-all-border' );
	$mobile_header_menu_b_color    = astra_get_option( 'mobile-header-menu-b-color', '#dadada' );

	$header_break_point = astra_header_break_point(); // Header Break Point.

	$css_output = '';

	/**
	 * Responsive Colors options
	 * [1]. Primary Menu Responsive Colors general
	 * [2]. Primary Menu Responsive Colors only for Full Screen menu style
	 * [3]. Primary Menu Responsive Colors only for Flyout menu style
	 */

	/**
	 * Responsive Colors options
	 * [1]. Primary Menu Responsive Colors general
	 */
	$desktop_colors = array(

		// Header Background Image.
		'.ast-header-break-point .main-header-bar'       => astra_get_background_obj( $header_bg_obj['desktop'] ),
		'.ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-sections-navigation' => astra_get_background_obj( $primary_menu_bg_image['desktop'] ),
		'.ast-header-break-point .ast-primary-menu-disabled .ast-above-header-menu-items, .ast-header-break-point .ast-primary-menu-disabled .ast-below-header-menu-items' => astra_get_background_obj( $primary_menu_bg_image['desktop'] ),
		'.ast-header-break-point .ast-primary-menu-disabled .ast-below-header-menu-items' => astra_get_background_obj( $primary_menu_bg_image['desktop'] ),
		// Primary Header Menu color.
		'.ast-header-break-point .main-header-menu, .ast-header-break-point .main-header-menu a, .ast-header-break-point .main-header-menu li.focus > .ast-menu-toggle, .ast-header-break-point .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-header-break-point .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-header-break-point .main-header-menu .current_page_item > .ast-menu-toggle, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-custom-item a, .ast-header-break-point .ast-masthead-custom-menu-items .slide-search .search-submit, .ast-header-break-point .ast-masthead-custom-menu-items, .ast-header-break-point .ast-masthead-custom-menu-items a, .ast-header-break-point .ast-masthead-custom-menu-items .ast-inline-search form .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select,.ast-header-break-point .ast-above-header-menu-items, .ast-header-break-point .ast-above-header-menu-items a, .ast-header-break-point .ast-below-header-menu-items, .ast-header-break-point .ast-below-header-menu-items a, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget-title' => array(
			'color' => esc_attr( $primary_menu_color['desktop'] ),
		),
		'.ast-header-break-point .ast-masthead-custom-menu-items .ast-inline-search form' => array(
			'border-color' => esc_attr( $primary_menu_color['desktop'] ),
		),
		// Primary Menu Hover colors.
		'.ast-header-break-point .main-header-menu a:hover, .ast-header-break-point .ast-header-custom-item a:hover, .ast-header-break-point .main-header-menu li:hover > a, .ast-header-break-point .main-header-menu li.focus > a' => array(
			'color' => esc_attr( $primary_menu_h_color['desktop'] ),
		),
		'.ast-header-break-point .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-header-break-point .main-header-menu li:hover > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_menu_h_color['desktop'] ),
		),
		// Primary Menu Hover Bg color.
		'.ast-header-break-point .main-header-menu a:hover, .ast-header-break-point .ast-header-custom-item a:hover, .ast-header-break-point .main-header-menu li:hover > a, .ast-header-break-point .main-header-menu li.focus > a' => array(
			'background-color' => esc_attr( $primary_menu_h_bg_color['desktop'] ),
		),
		// Primary Menu Active color.
		'.ast-header-break-point .main-header-menu li.current-menu-item > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.current_page_item > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu li.current_page_item > a' => array(
			'color' => esc_attr( $primary_menu_a_color['desktop'] ),
		),
		// Primary menu Active Bg color.
		'.ast-header-break-point .main-header-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu li.current_page_item > a' => array(
			'background-color' => esc_attr( $primary_menu_a_bg_color['desktop'] ),
		),

		// Submenu link color.
		'.ast-header-break-point .main-header-menu .sub-menu, .ast-header-break-point .main-header-menu .sub-menu a, .ast-header-break-point .main-header-menu .children a, .ast-header-break-point .ast-header-sections-navigation .sub-menu a, .ast-header-break-point .ast-above-header-menu-items .sub-menu a, .ast-header-break-point .ast-below-header-menu-items .sub-menu a' => array(
			'color' => esc_attr( $primary_submenu_color['desktop'] ),
		),
		'.ast-header-break-point .main-header-menu ul a' => array(
			'color' => esc_attr( $primary_submenu_color['desktop'] ),
		),
		// Submenu Background color.
		'.ast-header-break-point .main-header-menu .sub-menu, .ast-header-break-point .main-header-menu .children, .ast-header-break-point .ast-header-sections-navigation .sub-menu, .ast-header-break-point .ast-above-header-menu-items .sub-menu, .ast-header-break-point .ast-below-header-menu-items .sub-menu' => array(
			'background-color' => esc_attr( $primary_submenu_bg_color['desktop'] ),
		),
		// Submenu hover color.
		'.ast-header-break-point .main-header-menu ul a:hover, .ast-header-break-point .main-header-menu ul a:focus' => array(
			'color' => esc_attr( $primary_submenu_h_color['desktop'] ),
		),
		// Submenu hover bg color.
		'.ast-header-break-point .main-header-menu .sub-menu a:hover, .ast-header-break-point .main-header-menu .children a:hover, .ast-header-break-point .main-header-menu .sub-menu li:hover > a, .ast-header-break-point .main-header-menu .children li:hover > a, .ast-header-break-point .main-header-menu .sub-menu li.focus > a, .ast-header-break-point .main-header-menu .children li.focus > a' => array(
			'background-color' => esc_attr( $primary_submenu_h_bg_color['desktop'] ),
		),
		// Submenu active color.
		'.ast-header-break-point .main-header-menu .sub-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu .children li.current_page_ancestor > a, .ast-header-break-point .main-header-menu .sub-menu li.current_page_item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
			'color' => esc_attr( $primary_submenu_a_color['desktop'] ),
		),
		// Submenu active bg color.
		'.ast-header-break-point .main-header-menu .sub-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu .children li.current_page_ancestor > a, .ast-header-break-point .main-header-menu .sub-menu li.current_page_item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
			'background-color' => esc_attr( $primary_submenu_a_bg_color['desktop'] ),
		),

		// Primary Menu Bg color when Above & Below Header is merged and Primary menu is disabled.
		'.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap .ast-above-header-menu-items, .ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap .ast-below-header-menu-items' => astra_get_background_obj( $primary_menu_bg_image['desktop'] ),

	);
	$tablet_colors = array(
		// Header Background Image.
		'.ast-header-break-point .main-header-bar'       => astra_get_background_obj( $header_bg_obj['tablet'] ),

		'.ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-sections-navigation' => astra_get_background_obj( $primary_menu_bg_image['tablet'] ),
		'.ast-header-break-point .ast-primary-menu-disabled .ast-above-header-menu-items, .ast-header-break-point .ast-primary-menu-disabled .ast-below-header-menu-items' => astra_get_background_obj( $primary_menu_bg_image['tablet'] ),
		'.ast-header-break-point .ast-primary-menu-disabled .ast-below-header-menu-items' => astra_get_background_obj( $primary_menu_bg_image['tablet'] ),
		// Primary Header Menu color.
		'.ast-header-break-point .main-header-menu, .ast-header-break-point .main-header-menu a, .ast-header-break-point .main-header-menu li.focus > .ast-menu-toggle, .ast-header-break-point .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-header-break-point .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-header-break-point .main-header-menu .current_page_item > .ast-menu-toggle, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-custom-item a, .ast-header-break-point .ast-masthead-custom-menu-items .slide-search .search-submit, .ast-header-break-point .ast-masthead-custom-menu-items, .ast-header-break-point .ast-masthead-custom-menu-items a, .ast-header-break-point .ast-masthead-custom-menu-items .ast-inline-search form .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select,.ast-header-break-point .ast-above-header-menu-items, .ast-header-break-point .ast-above-header-menu-items a, .ast-header-break-point .ast-below-header-menu-items, .ast-header-break-point .ast-below-header-menu-items a, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget-title' => array(
			'color' => esc_attr( $primary_menu_color['tablet'] ),
		),
		'.ast-header-break-point .ast-masthead-custom-menu-items .ast-inline-search form' => array(
			'border-color' => esc_attr( $primary_menu_color['tablet'] ),
		),
		// Primary Menu Hover colors.
		'.ast-header-break-point .main-header-menu a:hover, .ast-header-break-point .ast-header-custom-item a:hover, .ast-header-break-point .main-header-menu li:hover > a, .ast-header-break-point .main-header-menu li.focus > a' => array(
			'color' => esc_attr( $primary_menu_h_color['tablet'] ),
		),
		'.ast-header-break-point .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-header-break-point .main-header-menu li:hover > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_menu_h_color['tablet'] ),
		),
		// Primary Menu Hover Bg color.
		'.ast-header-break-point .main-header-menu a:hover, .ast-header-break-point .ast-header-custom-item a:hover, .ast-header-break-point .main-header-menu li:hover > a, .ast-header-break-point .main-header-menu li.focus > a' => array(
			'background-color' => esc_attr( $primary_menu_h_bg_color['tablet'] ),
		),
		// Primary Menu Active color.
		'.ast-header-break-point .main-header-menu li.current-menu-item > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.current_page_item > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu li.current_page_item > a' => array(
			'color' => esc_attr( $primary_menu_a_color['tablet'] ),
		),
		// Primary menu Active Bg color.
		'.ast-header-break-point .main-header-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu li.current_page_item > a' => array(
			'background-color' => esc_attr( $primary_menu_a_bg_color['tablet'] ),
		),

		// Submenu link color.
		'.ast-header-break-point .main-header-menu .sub-menu, .ast-header-break-point .main-header-menu .sub-menu a, .ast-header-break-point .main-header-menu .children a, .ast-header-break-point .ast-header-sections-navigation .sub-menu a, .ast-header-break-point .ast-above-header-menu-items .sub-menu a, .ast-header-break-point .ast-below-header-menu-items .sub-menu a' => array(
			'color' => esc_attr( $primary_submenu_color['tablet'] ),
		),
		'.ast-header-break-point .main-header-menu ul a' => array(
			'color' => esc_attr( $primary_submenu_color['tablet'] ),
		),
		// Submenu Background color.
		'.ast-header-break-point .main-header-menu .sub-menu,.ast-header-break-point .main-header-menu .children, .ast-header-break-point .ast-header-sections-navigation .sub-menu, .ast-header-break-point .ast-above-header-menu-items .sub-menu, .ast-header-break-point .ast-below-header-menu-items .sub-menu' => array(
			'background-color' => esc_attr( $primary_submenu_bg_color['tablet'] ),
		),
		// Submenu hover color.
		'.ast-header-break-point .main-header-menu ul a:hover, .ast-header-break-point .main-header-menu ul a:focus' => array(
			'color' => esc_attr( $primary_submenu_h_color['tablet'] ),
		),
		// Submenu hover bg color.
		'.ast-header-break-point .main-header-menu .sub-menu a:hover, .ast-header-break-point .main-header-menu .children a:hover, .ast-header-break-point .main-header-menu .sub-menu li:hover > a, .ast-header-break-point .main-header-menu .children li:hover > a, .ast-header-break-point .main-header-menu .sub-menu li.focus > a, .ast-header-break-point .main-header-menu .children li.focus > a' => array(
			'background-color' => esc_attr( $primary_submenu_h_bg_color['tablet'] ),
		),
		// Submenu active color.
		'.ast-header-break-point .main-header-menu .sub-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu .children li.current_page_ancestor > a, .ast-header-break-point .main-header-menu .sub-menu li.current_page_item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
			'color' => esc_attr( $primary_submenu_a_color['tablet'] ),
		),
		// Submenu active bg color.
		'.ast-header-break-point .main-header-menu .sub-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu .children li.current_page_ancestor > a, .ast-header-break-point .main-header-menu .sub-menu li.current_page_item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
			'background-color' => esc_attr( $primary_submenu_a_bg_color['tablet'] ),
		),

		// Primary Menu Bg color when Above & Below Header is merged and Primary menu is disabled.
		'.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap .ast-above-header-menu-items, .ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap .ast-below-header-menu-items' => astra_get_background_obj( $primary_menu_bg_image['tablet'] ),
	);
	$mobile_colors = array(
		// Header Background Image.
		'.ast-header-break-point .main-header-bar'       => astra_get_background_obj( $header_bg_obj['mobile'] ),
		'.ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-sections-navigation' => astra_get_background_obj( $primary_menu_bg_image['mobile'] ),
		'.ast-header-break-point .ast-primary-menu-disabled .ast-above-header-menu-items, .ast-header-break-point .ast-primary-menu-disabled .ast-below-header-menu-items' => astra_get_background_obj( $primary_menu_bg_image['mobile'] ),
		'.ast-header-break-point .ast-primary-menu-disabled .ast-below-header-menu-items' => astra_get_background_obj( $primary_menu_bg_image['mobile'] ),
		// Primary Header Menu color.
		'.ast-header-break-point .main-header-menu, .ast-header-break-point .main-header-menu a, .ast-header-break-point .main-header-menu li.focus > .ast-menu-toggle, .ast-header-break-point .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-header-break-point .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-header-break-point .main-header-menu .current_page_item > .ast-menu-toggle, .ast-header-break-point .ast-header-custom-item, .ast-header-break-point .ast-header-custom-item a, .ast-header-break-point .ast-masthead-custom-menu-items .slide-search .search-submit, .ast-header-break-point .ast-masthead-custom-menu-items, .ast-header-break-point .ast-masthead-custom-menu-items a, .ast-header-break-point .ast-masthead-custom-menu-items .ast-inline-search form .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select,.ast-header-break-point .ast-above-header-menu-items, .ast-header-break-point .ast-above-header-menu-items a, .ast-header-break-point .ast-below-header-menu-items, .ast-header-break-point .ast-below-header-menu-items a, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget, .ast-header-break-point .ast-below-header-merged-responsive .below-header-user-select .widget-title' => array(
			'color' => esc_attr( $primary_menu_color['mobile'] ),
		),
		'.ast-header-break-point .ast-masthead-custom-menu-items .ast-inline-search form' => array(
			'border-color' => esc_attr( $primary_menu_color['mobile'] ),
		),
		// Primary Menu Hover colors.
		'.ast-header-break-point .main-header-menu a:hover, .ast-header-break-point .ast-header-custom-item a:hover, .ast-header-break-point .main-header-menu li:hover > a, .ast-header-break-point .main-header-menu li.focus > a' => array(
			'color' => esc_attr( $primary_menu_h_color['mobile'] ),
		),
		'.ast-header-break-point .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-header-break-point .main-header-menu li:hover > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_menu_h_color['mobile'] ),
		),
		// Primary Menu Hover Bg color.
		'.ast-header-break-point .main-header-menu a:hover, .ast-header-break-point .ast-header-custom-item a:hover, .ast-header-break-point .main-header-menu li:hover > a, .ast-header-break-point .main-header-menu li.focus > a' => array(
			'background-color' => esc_attr( $primary_menu_h_bg_color['mobile'] ),
		),
		// Primary Menu Active color.
		'.ast-header-break-point .main-header-menu li.current-menu-item > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.current_page_item > .ast-menu-toggle, .ast-header-break-point .main-header-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu li.current_page_item > a' => array(
			'color' => esc_attr( $primary_menu_a_color['mobile'] ),
		),
		// Primary menu Active Bg color.
		'.ast-header-break-point .main-header-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu li.current_page_item > a' => array(
			'background-color' => esc_attr( $primary_menu_a_bg_color['mobile'] ),
		),

		// Submenu link color.
		'.ast-header-break-point .main-header-menu .sub-menu, .ast-header-break-point .main-header-menu .sub-menu a, .ast-header-break-point .main-header-menu .children a, .ast-header-break-point .ast-header-sections-navigation .sub-menu a, .ast-header-break-point .ast-above-header-menu-items .sub-menu a, .ast-header-break-point .ast-below-header-menu-items .sub-menu a' => array(
			'color' => esc_attr( $primary_submenu_color['mobile'] ),
		),
		'.ast-header-break-point .main-header-menu ul a' => array(
			'color' => esc_attr( $primary_submenu_color['mobile'] ),
		),
		// Submenu Background color.
		'.ast-header-break-point .main-header-menu .sub-menu, .ast-header-break-point .main-header-menu .children, .ast-header-break-point .ast-header-sections-navigation .sub-menu, .ast-header-break-point .ast-above-header-menu-items .sub-menu, .ast-header-break-point .ast-below-header-menu-items .sub-menu' => array(
			'background-color' => esc_attr( $primary_submenu_bg_color['mobile'] ),
		),
		// Submenu hover color.
		'.ast-header-break-point .main-header-menu ul a:hover, .ast-header-break-point .main-header-menu ul a:focus' => array(
			'color' => esc_attr( $primary_submenu_h_color['mobile'] ),
		),
		// Submenu hover bg color.
		'.ast-header-break-point .main-header-menu .sub-menu a:hover, .ast-header-break-point .main-header-menu .children a:hover, .ast-header-break-point .main-header-menu .sub-menu li:hover > a, .ast-header-break-point .main-header-menu .children li:hover > a, .ast-header-break-point .main-header-menu .sub-menu li.focus > a, .ast-header-break-point .main-header-menu .children li.focus > a' => array(
			'background-color' => esc_attr( $primary_submenu_h_bg_color['mobile'] ),
		),
		// Submenu active color.
		'.ast-header-break-point .main-header-menu .sub-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu .children li.current_page_ancestor > a, .ast-header-break-point .main-header-menu .sub-menu li.current_page_item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
			'color' => esc_attr( $primary_submenu_a_color['mobile'] ),
		),
		// Submenu active bg color.
		'.ast-header-break-point .main-header-menu .sub-menu li.current-menu-item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-header-break-point .main-header-menu .children li.current_page_ancestor > a, .ast-header-break-point .main-header-menu .sub-menu li.current_page_item > a, .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
			'background-color' => esc_attr( $primary_submenu_a_bg_color['mobile'] ),
		),

		// Primary Menu Bg color when Above & Below Header is merged and Primary menu is disabled.
		'.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap .ast-above-header-menu-items, .ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap .ast-below-header-menu-items' => astra_get_background_obj( $primary_menu_bg_image['mobile'] ),
	);
	/* Parse CSS from array() */
	$css_output .= astra_parse_css( $desktop_colors );
	$css_output .= astra_parse_css( $tablet_colors, '', '768' );
	$css_output .= astra_parse_css( $mobile_colors, '', '544' );

	/**
	 * Responsive Colors options
	 * [2]. Primary Menu Responsive Colors only for Full Screen menu style
	 */
	if ( 'fullscreen' == $menu_style ) {
			$desktop_colors = array(
				'.ast-fullscreen-menu-overlay .main-header-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .main-header-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .main-header-menu li.current_page_item > a' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .main-header-menu li.current-menu-item:hover, .ast-fullscreen-menu-overlay .main-header-menu li.current-menu-item, .ast-fullscreen-menu-overlay .main-header-menu li.current-menu-ancestor, .ast-fullscreen-menu-overlay .main-header-menu li.current_page_item' => array(
					'background-color' => esc_attr( $primary_menu_a_bg_color['desktop'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-custom-item li:hover, .ast-fullscreen-menu-overlay .main-header-menu li:hover, .ast-fullscreen-menu-overlay .main-header-menu li.focus, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation li:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation li:focus' => array(
					'background-color' => esc_attr( $primary_menu_h_bg_color['desktop'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu a:hover,.ast-fullscreen-menu-overlay .ast-header-custom-item a:hover,.ast-fullscreen-menu-overlay .main-header-menu li:hover > a,.ast-fullscreen-menu-overlay .main-header-menu li.focus > a,.ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation a:hover,.ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation a:focus' => array(
					'background-color' => 'transparent',
					'color'            => esc_attr( $primary_menu_h_color['desktop'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu a:hover, .ast-fullscreen-menu-overlay .main-header-menu .children a:hover, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .children li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.focus > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.focus > a, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu a:hover' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu a:hover, .ast-fullscreen-menu-overlay .main-header-menu .children a:hover, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .children li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.focus > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.focus > a, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu a:hover' => array(
					'background-color' => 'transparent',
					'color'            => esc_attr( $primary_submenu_h_color['desktop'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu li:hover, .ast-fullscreen-menu-overlay .main-header-menu .children li:hover, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.focus, .ast-fullscreen-menu-overlay .main-header-menu .children li.focus, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li.focus, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu li:hover, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu li.focus' => array(
					'background-color' => esc_attr( $primary_submenu_h_bg_color['desktop'] ),
				),

				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_ancestor > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current_page_item > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
					'background-color' => 'transparent',
					'color'            => esc_attr( $primary_submenu_a_color['desktop'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-ancestor, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_ancestor, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current_page_item, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu li.current-menu-item' => array(
					'background-color' => esc_attr( $primary_submenu_a_bg_color['desktop'] ),
				),
				// Primary Menu Hover Bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-custom-item a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li:hover > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.focus > a' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-custom-item a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.focus' => array(
					'background-color' => esc_attr( $primary_menu_h_bg_color['desktop'] ),
				),
				// Primary menu Active Bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.current_page_item > a' => array(
					'background-color' => 'transparent',
				),
				// Submenu Background color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-custom-item a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.focus > a' => array(
					'background-color' => 'transparent',
				),
				// Submenu hover bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu ul li:hover' => array(
					'background-color' => esc_attr( $primary_submenu_h_bg_color['desktop'] ),
				),
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu ul a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu ul a:focus' => array(
					'background-color' => 'transparent',
				),
				// Submenu active bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_ancestor > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_ancestor, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item' => array(
					'background-color' => esc_attr( $primary_submenu_a_bg_color['desktop'] ),
				),
				'.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar-navigation .close' => array(
					'color' => esc_attr( $mobile_header_close_desktop_color ),
				),
			);
			// Fullscreen background color if Header Background color is set.
			$desktop_colors['.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation,.ast-fullscreen-menu-enable.ast-header-break-point.admin-bar.ast-admin-bar-visible .ast-primary-menu-disabled .ast-header-custom-item .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $header_bg_obj['desktop'] );
			// Fullscreen background color if Primary Menu Background color is set.
		if ( '' !== $primary_menu_bg_image['desktop']['background-image'] || '' !== $primary_menu_bg_image['desktop']['background-color'] ) {
			$desktop_colors['.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation,.ast-fullscreen-menu-enable.ast-header-break-point.admin-bar.ast-admin-bar-visible .ast-primary-menu-disabled .ast-header-custom-item .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $primary_menu_bg_image['desktop'] );
		}

			$tablet_colors = array(
				'.ast-fullscreen-menu-overlay .main-header-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .main-header-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .main-header-menu li.current_page_item > a' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .main-header-menu li.current-menu-item:hover, .ast-fullscreen-menu-overlay .main-header-menu li.current-menu-item, .ast-fullscreen-menu-overlay .main-header-menu li.current-menu-ancestor, .ast-fullscreen-menu-overlay .main-header-menu li.current_page_item' => array(
					'background-color' => esc_attr( $primary_menu_a_bg_color['tablet'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-custom-item li:hover, .ast-fullscreen-menu-overlay .main-header-menu li:hover, .ast-fullscreen-menu-overlay .main-header-menu li.focus, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation li:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation li:focus' => array(
					'background-color' => esc_attr( $primary_menu_h_bg_color['tablet'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu a:hover,.ast-fullscreen-menu-overlay .ast-header-custom-item a:hover,.ast-fullscreen-menu-overlay .main-header-menu li:hover > a,.ast-fullscreen-menu-overlay .main-header-menu li.focus > a,.ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation a:hover,.ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation a:focus' => array(
					'background-color' => 'transparent',
					'color'            => esc_attr( $primary_menu_h_color['tablet'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu a:hover, .ast-fullscreen-menu-overlay .main-header-menu .children a:hover, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .children li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.focus > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.focus > a, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu a:hover' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu a:hover, .ast-fullscreen-menu-overlay .main-header-menu .children a:hover, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .children li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.focus > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.focus > a, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu a:hover' => array(
					'background-color' => 'transparent',
					'color'            => esc_attr( $primary_submenu_h_color['tablet'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu li:hover, .ast-fullscreen-menu-overlay .main-header-menu .children li:hover, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.focus, .ast-fullscreen-menu-overlay .main-header-menu .children li.focus, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li.focus, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu li:hover, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu li.focus' => array(
					'background-color' => esc_attr( $primary_submenu_h_bg_color['tablet'] ),
				),

				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_ancestor > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current_page_item > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
					'background-color' => 'transparent',
					'color'            => esc_attr( $primary_submenu_a_color['tablet'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-ancestor, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_ancestor, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current_page_item, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu li.current-menu-item' => array(
					'background-color' => esc_attr( $primary_submenu_a_bg_color['tablet'] ),
				),
				// Primary Menu Hover Bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-custom-item a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li:hover > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.focus > a' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-custom-item a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.focus' => array(
					'background-color' => esc_attr( $primary_menu_h_bg_color['tablet'] ),
				),
				// Primary menu Active Bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.current_page_item > a' => array(
					'background-color' => 'transparent',
				),
				// Submenu Background color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-custom-item a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.focus > a' => array(
					'background-color' => 'transparent',
				),
				// Submenu hover bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu ul li:hover' => array(
					'background-color' => esc_attr( $primary_submenu_h_bg_color['tablet'] ),
				),
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu ul a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu ul a:focus' => array(
					'background-color' => 'transparent',
				),
				// Submenu active bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_ancestor > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_ancestor, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item' => array(
					'background-color' => esc_attr( $primary_submenu_a_bg_color['tablet'] ),
				),
				'.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar-navigation .close' => array(
					'color' => esc_attr( $mobile_header_close_tablet_color ),
				),
			);

			// Fullscreen background color if Header Background color is set.
			$tablet_colors['.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation,.ast-fullscreen-menu-enable.ast-header-break-point.admin-bar.ast-admin-bar-visible .ast-primary-menu-disabled .ast-header-custom-item .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $header_bg_obj['tablet'] );
			// Fullscreen background color if Primary Menu Background color is set.
		if ( '' !== $primary_menu_bg_image['tablet']['background-image'] || '' !== $primary_menu_bg_image['tablet']['background-color'] ) {
			$tablet_colors['.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation,.ast-fullscreen-menu-enable.ast-header-break-point.admin-bar.ast-admin-bar-visible .ast-primary-menu-disabled .ast-header-custom-item .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $primary_menu_bg_image['tablet'] );
		}

			$mobile_colors = array(
				'.ast-fullscreen-menu-overlay .main-header-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .main-header-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .main-header-menu li.current_page_item > a' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .main-header-menu li.current-menu-item:hover, .ast-fullscreen-menu-overlay .main-header-menu li.current-menu-item, .ast-fullscreen-menu-overlay .main-header-menu li.current-menu-ancestor, .ast-fullscreen-menu-overlay .main-header-menu li.current_page_item' => array(
					'background-color' => esc_attr( $primary_menu_a_bg_color['mobile'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-custom-item li:hover, .ast-fullscreen-menu-overlay .main-header-menu li:hover, .ast-fullscreen-menu-overlay .main-header-menu li.focus, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation li:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation li:focus' => array(
					'background-color' => esc_attr( $primary_menu_h_bg_color['mobile'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu a:hover,.ast-fullscreen-menu-overlay .ast-header-custom-item a:hover,.ast-fullscreen-menu-overlay .main-header-menu li:hover > a,.ast-fullscreen-menu-overlay .main-header-menu li.focus > a,.ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation a:hover,.ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation a:focus' => array(
					'background-color' => 'transparent',
					'color'            => esc_attr( $primary_menu_h_color['mobile'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu a:hover, .ast-fullscreen-menu-overlay .main-header-menu .children a:hover, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .children li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.focus > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.focus > a, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu a:hover' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu a:hover, .ast-fullscreen-menu-overlay .main-header-menu .children a:hover, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .children li:hover > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.focus > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.focus > a, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu a:hover, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu a:hover' => array(
					'background-color' => 'transparent',
					'color'            => esc_attr( $primary_submenu_h_color['mobile'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu li:hover, .ast-fullscreen-menu-overlay .main-header-menu .children li:hover, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.focus, .ast-fullscreen-menu-overlay .main-header-menu .children li.focus, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li.focus, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu li:hover, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu li.focus' => array(
					'background-color' => esc_attr( $primary_submenu_h_bg_color['mobile'] ),
				),

				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_ancestor > a, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current_page_item > a, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
					'background-color' => 'transparent',
					'color'            => esc_attr( $primary_submenu_a_color['mobile'] ),
				),
				'.ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current-menu-ancestor, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_ancestor, .ast-fullscreen-menu-overlay .main-header-menu .sub-menu li.current_page_item, .ast-fullscreen-menu-overlay .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-sections-navigation .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-above-header-menu-items .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-below-header-menu-items .sub-menu li.current-menu-item' => array(
					'background-color' => esc_attr( $primary_submenu_a_bg_color['mobile'] ),
				),
				// Primary Menu Hover Bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-custom-item a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li:hover > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.focus > a' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-custom-item a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.focus' => array(
					'background-color' => esc_attr( $primary_menu_h_bg_color['mobile'] ),
				),
				// Primary menu Active Bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.current_page_item > a' => array(
					'background-color' => 'transparent',
				),
				// Submenu Background color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-custom-item a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu li.focus > a' => array(
					'background-color' => 'transparent',
				),
				// Submenu hover bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu ul li:hover' => array(
					'background-color' => esc_attr( $primary_submenu_h_bg_color['mobile'] ),
				),
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu ul a:hover, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu ul a:focus' => array(
					'background-color' => 'transparent',
				),
				// Submenu active bg color.
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_ancestor > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
					'background-color' => 'transparent',
				),
				'.ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current-menu-ancestor, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_ancestor, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .sub-menu li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-break-point .main-header-menu .children li.current_page_item, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-header-sections-navigation .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-above-header-menu-items .sub-menu li.current-menu-item, .ast-fullscreen-menu-overlay .ast-header-break-point .ast-below-header-menu-items .sub-menu li.current-menu-item' => array(
					'background-color' => esc_attr( $primary_submenu_a_bg_color['mobile'] ),
				),
				'.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar-navigation .close' => array(
					'color' => esc_attr( $mobile_header_close_mobile_color ),
				),
			);
			// Fullscreen background color if Header Background color is set.
			$mobile_colors['.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation,.ast-fullscreen-menu-enable.ast-header-break-point.admin-bar.ast-admin-bar-visible .ast-primary-menu-disabled .ast-header-custom-item .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $header_bg_obj['mobile'] );
			// Fullscreen background color if Primary Menu Background color is set.
		if ( '' !== $primary_menu_bg_image['mobile']['background-image'] || '' !== $primary_menu_bg_image['mobile']['background-color'] ) {
			$mobile_colors['.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation,.ast-fullscreen-menu-enable.ast-header-break-point.admin-bar.ast-admin-bar-visible .ast-primary-menu-disabled .ast-header-custom-item .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $primary_menu_bg_image['mobile'] );
		}

			/* Parse CSS from array() */
			$css_output .= astra_parse_css( $desktop_colors );
			$css_output .= astra_parse_css( $tablet_colors, '', '768' );
			$css_output .= astra_parse_css( $mobile_colors, '', '544' );

			/**
			 * Border only for resopnsive devices
			 */
		if ( '' !== $mobile_header_menu_all_border['top'] || '' !== $mobile_header_menu_all_border['right'] || '' !== $mobile_header_menu_all_border['bottom'] || '' !== $mobile_header_menu_all_border['left'] ) {
			$mobile_header_border = array(
				'.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu > .menu-item, .ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu > .page_item' => array(
					'border-right-width' => astra_get_css_value( $mobile_header_menu_all_border['right'], 'px' ),
					'border-left-width'  => astra_get_css_value( $mobile_header_menu_all_border['left'], 'px' ),
					'border-style'       => 'solid',
					'border-color'       => esc_attr( $mobile_header_menu_b_color ),
				),
				'.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu > .menu-item:not(:first-child):not(:last-child), .ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu > .page_item:not(:first-child):not(:last-child)' => array(
					'border-top-width'    => ( ! empty( $mobile_header_menu_all_border['top'] ) && ! empty( $mobile_header_menu_all_border['bottom'] ) ) ? astra_calc_spacing( $mobile_header_menu_all_border['top'] . 'px', '/', '2' ) : astra_get_css_value( $mobile_header_menu_all_border['top'], 'px' ),
					'border-bottom-width' => ( ! empty( $mobile_header_menu_all_border['bottom'] ) && ! empty( $mobile_header_menu_all_border['top'] ) ) ? astra_calc_spacing( $mobile_header_menu_all_border['bottom'] . 'px', '/', '2' ) : astra_get_css_value( $mobile_header_menu_all_border['bottom'], 'px' ),
					'border-style'        => 'solid',
					'border-color'        => esc_attr( $mobile_header_menu_b_color ),
				),
				'.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu .menu-item:first-child, .ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu .page_item:first-child' => array(
					'border-top-width'    => astra_get_css_value( $mobile_header_menu_all_border['top'], 'px' ),
					'border-bottom-width' => ( ! empty( $mobile_header_menu_all_border['bottom'] ) && ! empty( $mobile_header_menu_all_border['top'] ) ) ? astra_calc_spacing( $mobile_header_menu_all_border['bottom'] . 'px', '/', '2' ) : astra_get_css_value( $mobile_header_menu_all_border['bottom'], 'px' ),
					'border-style'        => 'solid',
					'border-color'        => esc_attr( $mobile_header_menu_b_color ),
				),
				'.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu .menu-item:last-child, .ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu .page_item:last-child' => array(
					'border-top-width'    => ( ! empty( $mobile_header_menu_all_border['top'] ) && ! empty( $mobile_header_menu_all_border['bottom'] ) ) ? astra_calc_spacing( $mobile_header_menu_all_border['top'] . 'px', '/', '2' ) : astra_get_css_value( $mobile_header_menu_all_border['top'], 'px' ),
					'border-bottom-width' => astra_get_css_value( $mobile_header_menu_all_border['bottom'], 'px' ),
					'border-style'        => 'solid',
					'border-color'        => esc_attr( $mobile_header_menu_b_color ),
				),
				'.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu .menu-item.ast-submenu-expanded .sub-menu .menu-item, .ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu .page_item.ast-submenu-expanded .children .page_item' => array(
					'border-top-width'    => astra_get_css_value( $mobile_header_menu_all_border['top'], 'px' ),
					'border-bottom-width' => 0,
					'border-style'        => 'solid',
					'border-color'        => esc_attr( $mobile_header_menu_b_color ),
				),
				'.ast-fullscreen-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu .ast-masthead-custom-menu-items' => array(
					'border-top-width'    => ( ! empty( $mobile_header_menu_all_border['top'] ) && ! empty( $mobile_header_menu_all_border['bottom'] ) ) ? astra_calc_spacing( $mobile_header_menu_all_border['top'] . 'px', '/', '2' ) : astra_get_css_value( $mobile_header_menu_all_border['top'], 'px' ),
					'border-bottom-width' => astra_get_css_value( $mobile_header_menu_all_border['bottom'], 'px' ),
					'border-right-width'  => astra_get_css_value( $mobile_header_menu_all_border['right'], 'px' ),
					'border-left-width'   => astra_get_css_value( $mobile_header_menu_all_border['left'], 'px' ),
					'border-style'        => 'solid',
					'border-color'        => esc_attr( $mobile_header_menu_b_color ),
				),
			);
			$css_output          .= astra_parse_css( $mobile_header_border );
		}
	} elseif ( 'no-toggle' == $menu_style ) {

		// Border only for responsive devices.
		if ( '' !== $mobile_header_menu_all_border['top'] || '' !== $mobile_header_menu_all_border['right'] || '' !== $mobile_header_menu_all_border['bottom'] || '' !== $mobile_header_menu_all_border['left'] ) {
			$mobile_header_border = array(
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar-navigation ul li' => array(
					'border-top-width'   => astra_get_css_value( $mobile_header_menu_all_border['top'], 'px' ),
					'border-left-width'  => astra_get_css_value( $mobile_header_menu_all_border['left'], 'px' ),
					'border-right-width' => astra_get_css_value( $mobile_header_menu_all_border['right'], 'px' ),
					'border-color'       => esc_attr( $mobile_header_menu_b_color ),
				),
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-navigation > ul > li, .ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar-navigation .sub-menu li:last-child' => array(
					'border-bottom-width' => astra_get_css_value( $mobile_header_menu_all_border['bottom'], 'px' ),
				),
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-navigation > ul.ast-above-header-menu > li:last-child, .ast-no-toggle-menu-enable.ast-header-break-point .main-navigation > ul > li:last-child' => array(
					'border-right-width' => astra_get_css_value( $mobile_header_menu_all_border['right'], 'px' ),
					'border-color'       => esc_attr( $mobile_header_menu_b_color ),
					'border-style'       => 'solid',
				),
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar-navigation ul > li:first-child' => array(
					'border-top-width' => astra_get_css_value( $mobile_header_menu_all_border['top'], 'px' ),
					'border-color'     => esc_attr( $mobile_header_menu_b_color ),
				),
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-navigation > ul > li:first-child, .ast-no-toggle-menu-enable.ast-header-break-point .main-navigation > ul > li' => array(
					'border-right-width' => astra_get_css_value( $mobile_header_menu_all_border['right'], 'px' ),
					'border-color'       => esc_attr( $mobile_header_menu_b_color ),
				),
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-navigation > ul > li' => array(
					'margin-right' => '-' . astra_get_css_value( $mobile_header_menu_all_border['right'], 'px' ),
				),
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-navigation > ul > li > .sub-menu' => array(
					'margin-left' => '-' . astra_get_css_value( $mobile_header_menu_all_border['left'], 'px' ),
				),

			);
			$css_output .= astra_parse_css( $mobile_header_border );
		}

		/**
		 * Responsive Colors options
		 * [1]. Primary Menu Responsive Colors general
		 */
		$desktop_colors = array(
			// Primary Menu Hover colors.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li:hover > a, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li:hover > .ast-menu-toggle, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.focus > a' => array(
				'color' => esc_attr( $primary_menu_h_color['desktop'] ),
			),
			// Primary Menu Hover Bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li:not(.ast-masthead-custom-menu-items):hover, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color['desktop'] ),
			),
			// Submenu hover bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover' => array(
				'background-color' => esc_attr( $primary_submenu_h_bg_color['desktop'] ),
			),
			// Submenu hover color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover > .ast-menu-toggle,.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover > a' => array(
				'color' => esc_attr( $primary_submenu_h_color['desktop'] ),
			),
			// Primary Menu Active colors.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > a, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > .ast-menu-toggle, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover > a, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_menu_a_color['desktop'] ),
			),
			// Primary Menu Active Bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover' => array(
				'background-color' => esc_attr( $primary_menu_a_bg_color['desktop'] ),
			),
			// Primary Submenu Active colors.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > .ast-menu-toggle,.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li.current-menu-item > a' => array(
				'color' => esc_attr( $primary_submenu_a_color['desktop'] ),
			),
			// Primary Submenu Active Bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li.current-menu-item' => array(
				'background-color' => esc_attr( $primary_submenu_a_bg_color['desktop'] ),
			),
		);
		$tablet_colors = array(
			// Primary Menu Hover colors.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li:hover > a, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li:hover > .ast-menu-toggle, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.focus > a' => array(
				'color' => esc_attr( $primary_menu_h_color['tablet'] ),
			),
			// Primary Menu Hover Bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li:not(.ast-masthead-custom-menu-items):hover, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color['tablet'] ),
			),
			// Submenu hover bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover' => array(
				'background-color' => esc_attr( $primary_submenu_h_bg_color['tablet'] ),
			),
			// Submenu hover color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover > .ast-menu-toggle,.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover > a' => array(
				'color' => esc_attr( $primary_submenu_h_color['tablet'] ),
			),
			// Primary Menu Active colors.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > a, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > .ast-menu-toggle, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover > a, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_menu_a_color['tablet'] ),
			),
			// Primary Menu Active Bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover' => array(
				'background-color' => esc_attr( $primary_menu_a_bg_color['tablet'] ),
			),
			// Primary Submenu Active colors.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > .ast-menu-toggle,.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li.current-menu-item > a' => array(
				'color' => esc_attr( $primary_submenu_a_color['tablet'] ),
			),
			// Primary Submenu Active Bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li.current-menu-item' => array(
				'background-color' => esc_attr( $primary_submenu_a_bg_color['tablet'] ),
			),
		);
		$mobile_colors = array(
			// Primary Menu Hover colors.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li:hover > a, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li:hover > .ast-menu-toggle, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.focus > a' => array(
				'color' => esc_attr( $primary_menu_h_color['mobile'] ),
			),
			// Primary Menu Hover Bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li:not(.ast-masthead-custom-menu-items):hover, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color['mobile'] ),
			),
			// Submenu hover bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover' => array(
				'background-color' => esc_attr( $primary_submenu_h_bg_color['mobile'] ),
			),
			// Submenu hover color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover > .ast-menu-toggle,.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li:hover > a' => array(
				'color' => esc_attr( $primary_submenu_h_color['mobile'] ),
			),
			// Primary Menu Active colors.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > a, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > .ast-menu-toggle, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover > a, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_menu_a_color['mobile'] ),
			),
			// Primary Menu Active Bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover' => array(
				'background-color' => esc_attr( $primary_menu_a_bg_color['mobile'] ),
			),
			// Primary Submenu Active colors.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu li.current-menu-item > .ast-menu-toggle,.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li.current-menu-item > a' => array(
				'color' => esc_attr( $primary_submenu_a_color['mobile'] ),
			),
			// Primary Submenu Active Bg color.
			'.ast-header-break-point.ast-no-toggle-menu-enable .main-header-menu .sub-menu li.current-menu-item' => array(
				'background-color' => esc_attr( $primary_submenu_a_bg_color['mobile'] ),
			),
		);
		/* Parse CSS from array() */
		$css_output .= astra_parse_css( $desktop_colors );
		$css_output .= astra_parse_css( $tablet_colors, '', '768' );
		$css_output .= astra_parse_css( $mobile_colors, '', '544' );
	} else {
		/**
		 * Border only for responsive devices
		 */
		if ( '' !== $mobile_header_menu_all_border['top'] || '' !== $mobile_header_menu_all_border['right'] || '' !== $mobile_header_menu_all_border['bottom'] || '' !== $mobile_header_menu_all_border['left'] ) {
			$mobile_header_border = array(
				'.ast-header-break-point .main-header-bar .main-header-bar-navigation .main-header-menu' => array(
					'border-top-width'   => astra_get_css_value( $mobile_header_menu_all_border['top'], 'px' ),
					'border-left-width'  => astra_get_css_value( $mobile_header_menu_all_border['left'], 'px' ),
					'border-right-width' => astra_get_css_value( $mobile_header_menu_all_border['right'], 'px' ),
					'border-color'       => esc_attr( $mobile_header_menu_b_color ),
				),
				'.ast-header-break-point .main-navigation ul li a' => array(
					'border-bottom-width' => astra_get_css_value( $mobile_header_menu_all_border['bottom'], 'px' ),
					'border-color'        => esc_attr( $mobile_header_menu_b_color ),
					'border-style'        => esc_attr( 'solid' ),
				),
				'.ast-header-break-point li.ast-masthead-custom-menu-items' => array(
					'border-bottom-width' => astra_get_css_value( $mobile_header_menu_all_border['bottom'], 'px' ),
					'border-color'        => esc_attr( $mobile_header_menu_b_color ),
					'border-style'        => esc_attr( 'solid' ),
					'margin-top'          => esc_attr( '0' ),
					'margin-bottom'       => esc_attr( '0' ),
				),
			);
			$css_output          .= astra_parse_css( $mobile_header_border );
		}
	}

	/**
	 * Responsive Colors options
	 * [3]. Primary Menu Responsive Colors only for Flyout menu style
	 */
	if ( 'flyout' == $menu_style ) {
		$desktop_colors = array(
			'.ast-flyout-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation #site-navigation,  .ast-flyout-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap' => array(
				'width' => astra_get_css_value( $flayout_sidebar_width, 'px' ),
			),
			'.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .close' => array(
				'color' => esc_attr( $mobile_header_close_desktop_color ),
			),
		);
		// Flyout background color if Header Background color is set.
		$desktop_colors['.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation, .ast-flyout-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $header_bg_obj['desktop'] );
		// Flyout background color if Primary Menu Background color is set.
		if ( '' !== $primary_menu_bg_image['desktop']['background-image'] || '' !== $primary_menu_bg_image['desktop']['background-color'] ) {
			$desktop_colors['.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation, .ast-flyout-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $primary_menu_bg_image['desktop'] );
		}

		$tablet_colors = array(
			'.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .close' => array(
				'color' => esc_attr( $mobile_header_close_tablet_color ),
			),
		);
		// Flyout background color if Header Background color is set.
		$tablet_colors['.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation, .ast-flyout-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $header_bg_obj['tablet'] );
		// Flyout background color if Primary Menu Background color is set.
		if ( '' !== $primary_menu_bg_image['tablet']['background-image'] || '' !== $primary_menu_bg_image['tablet']['background-color'] ) {
			$tablet_colors['.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation, .ast-flyout-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $primary_menu_bg_image['tablet'] );
		}

		$mobile_colors = array(
			'.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .close' => array(
				'color' => esc_attr( $mobile_header_close_mobile_color ),
			),
		);
		// Flyout background color if Header Background color is set.
		$mobile_colors['.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation, .ast-flyout-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $header_bg_obj['mobile'] );
		// Flyout background color if Primary Menu Background color is set.
		if ( '' !== $primary_menu_bg_image['mobile']['background-image'] || '' !== $primary_menu_bg_image['mobile']['background-color'] ) {
			$mobile_colors['.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation, .ast-flyout-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap'] = astra_get_background_obj( $primary_menu_bg_image['mobile'] );
		}

		/* Parse CSS from array() */
		$css_output .= astra_parse_css( $desktop_colors );
		$css_output .= astra_parse_css( $tablet_colors, '', '768' );
		$css_output .= astra_parse_css( $mobile_colors, '', '544' );
	}

	/**
	 * Responsive Colors options
	 * [4]. Below Header Menu Responsive Colors only for No Toggle menu style
	 */
	if ( 'no-toggle' == $menu_style ) {

		$link_colors = array(
			'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-menu li:hover' => array(
				'color' => esc_attr( $link_color ),
			),
		);
		$css_output .= astra_parse_css( $link_colors );

		/**
		 * Responsive Colors options
		 * [4]. Below Header Menu Responsive Colors general
		 */
		$desktop_colors = array(
			// Mobile Below Header menu hover bg color.
			'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-menu li:hover > .ast-menu-toggle' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color['desktop'] ),
			),
			'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-menu, .ast-no-toggle-menu-enable.ast-header-break-point .ast-header-custom-item, .ast-no-toggle-menu-enable.ast-header-break-point .ast-header-sections-navigation' => array(
				'background-color' => esc_attr( $primary_menu_bg_image['desktop']['background-color'] ),
			),
			'.ast-no-toggle-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-above-header-menu-items' => array(
				'background-color' => esc_attr( $primary_menu_bg_image['desktop']['background-color'] ),
			),
			'.ast-no-toggle-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-below-header-menu-items' => array(
				'background-color' => esc_attr( $primary_menu_bg_image['desktop']['background-color'] ),
			),
		);

		$desktop_colors['.ast-header-break-point .main-header-menu'] =
				array(
					'background-color' => esc_attr( '#FFFFFF' ),
				);

		$tablet_colors = array(
			// Mobile Below Header menu hover bg color.
			'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-menu li:hover > .ast-menu-toggle' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color['tablet'] ),
			),
			'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-menu, .ast-no-toggle-menu-enable.ast-header-break-point .ast-header-custom-item, .ast-no-toggle-menu-enable.ast-header-break-point .ast-header-sections-navigation' => array(
				'background-color' => esc_attr( $primary_menu_bg_image['tablet']['background-color'] ),
			),
			'.ast-no-toggle-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-above-header-menu-items' => array(
				'background-color' => esc_attr( $primary_menu_bg_image['tablet']['background-color'] ),
			),
			'.ast-no-toggle-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-below-header-menu-items' => array(
				'background-color' => esc_attr( $primary_menu_bg_image['tablet']['background-color'] ),
			),
		);
		$mobile_colors = array(
			// Mobile Below Header menu hover bg color.
			'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-menu li:hover > .ast-menu-toggle' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color['mobile'] ),
			),
			'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-menu, .ast-no-toggle-menu-enable.ast-header-break-point .ast-header-custom-item, .ast-no-toggle-menu-enable.ast-header-break-point .ast-header-sections-navigation' => array(
				'background-color' => esc_attr( $primary_menu_bg_image['mobile']['background-color'] ),
			),
			'.ast-no-toggle-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-above-header-menu-items' => array(
				'background-color' => esc_attr( $primary_menu_bg_image['mobile']['background-color'] ),
			),
			'.ast-no-toggle-menu-enable.ast-header-break-point .ast-primary-menu-disabled .ast-below-header-menu-items' => array(
				'background-color' => esc_attr( $primary_menu_bg_image['mobile']['background-color'] ),
			),
		);

		/* Parse CSS from array() */
		$css_output .= astra_parse_css( $desktop_colors );
		$css_output .= astra_parse_css( $tablet_colors, '', '768' );
		$css_output .= astra_parse_css( $mobile_colors, '', '544' );

		if ( $primary_menu_spacing ) {
			$desktop_spacing = array(
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .page_item_has_children > .ast-menu-toggle, .ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar .main-header-menu > .page_item_has_children > .ast-menu-toggle, .ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar .main-header-menu > .menu-item-has-children > .ast-menu-toggle' => array(
					'padding-left'  => astra_responsive_spacing( $primary_menu_spacing, 'left', 'desktop' ),
					'padding-right' => astra_responsive_spacing( $primary_menu_spacing, 'right', 'desktop' ),
				),
			);
			$tablet_spacing  = array(
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .page_item_has_children > .ast-menu-toggle, .ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar .main-header-menu > .page_item_has_children > .ast-menu-toggle, .ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar .main-header-menu > .menu-item-has-children > .ast-menu-toggle' => array(
					'padding-left'  => astra_responsive_spacing( $primary_menu_spacing, 'left', 'tablet' ),
					'padding-right' => astra_responsive_spacing( $primary_menu_spacing, 'right', 'tablet' ),
				),
			);
			$mobile_spacing  = array(
				'.ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar .main-header-bar-navigation .page_item_has_children > .ast-menu-toggle, .ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar .main-header-menu > .page_item_has_children > .ast-menu-toggle, .ast-no-toggle-menu-enable.ast-header-break-point .main-header-bar .main-header-menu > .menu-item-has-children > .ast-menu-toggle' => array(
					'padding-left'  => astra_responsive_spacing( $primary_menu_spacing, 'left', 'mobile' ),
					'padding-right' => astra_responsive_spacing( $primary_menu_spacing, 'right', 'mobile' ),
				),
			);
			$css_output     .= astra_parse_css( $desktop_spacing );
			$css_output     .= astra_parse_css( $tablet_spacing, '', '768' );
			$css_output     .= astra_parse_css( $mobile_spacing, '', '544' );
		}
	}

	/**
	 * Responsive Devices Border
	 * Border only for resopnsive devices When primay menu is desabled & Header section is merged
	 */
	if ( $disable_primary_menu && ( $merge_above_header || $merge_below_header ) ) {
		$mobile_header_border = array(
			'.ast-header-break-point .ast-primary-menu-disabled .ast-merge-header-navigation-wrap .ast-above-header-menu-items' => array(
				'border-top' => $mobile_header_menu_b_color,
			),
			'.ast-header-break-point .ast-above-header-menu-items li a,.ast-header-break-point .ast-above-header-navigation ul li a, .ast-header-break-point .ast-above-header-menu-items ul li a,.ast-header-break-point .ast-below-header-menu-items li a,.ast-header-break-point .ast-below-header-actual-nav ul li a, .ast-header-break-point .ast-below-header-menu-items ul li a, .ast-fullscreen-menu-enable.ast-header-break-point .ast-merge-header-navigation-wrap .ast-above-header-menu-items li a:before, .ast-fullscreen-menu-enable.ast-header-break-point .ast-merge-header-navigation-wrap .ast-above-header-menu-items li.menu-item-has-children a:before, .ast-fullscreen-menu-enable.ast-header-break-point .ast-merge-header-navigation-wrap .ast-below-header-menu-items li a:before, .ast-fullscreen-menu-enable.ast-header-break-point .ast-merge-header-navigation-wrap .ast-below-header-menu-items li.menu-item-has-children a:before' => array(
				'border-bottom' => $mobile_header_menu_b_color,
			),
		);
		$css_output          .= astra_parse_css( $mobile_header_border );
	}

	// Check Spacing is activated.
	if ( Astra_Ext_Extension::is_active( 'spacing' ) ) {
		/**
		 * Spacing for .main-header-bar. when click on the toggle menu button
		 */
		$header_spacing = astra_get_option( 'header-spacing' );

		$desktop_colors = array(
			// Header Spacing Top / Bottom Padding.
			'.ast-flyout-menu-enable.ast-main-header-nav-open .main-header-bar,.ast-fullscreen-menu-enable.ast-main-header-nav-open .main-header-bar' => array(
				'padding-bottom' => astra_responsive_spacing( $header_spacing, 'bottom', 'desktop' ),
			),
		);
		$tablet_colors  = array(
			// Header Spacing Top / Bottom Padding.
			'.ast-flyout-menu-enable.ast-main-header-nav-open .main-header-bar,.ast-fullscreen-menu-enable.ast-main-header-nav-open .main-header-bar' => array(
				'padding-bottom' => astra_responsive_spacing( $header_spacing, 'bottom', 'tablet' ),
			),
		);
		$mobile_colors  = array(
			// Header Spacing Top / Bottom Padding.
			'.ast-flyout-menu-enable.ast-main-header-nav-open .main-header-bar,.ast-fullscreen-menu-enable.ast-main-header-nav-open .main-header-bar' => array(
				'padding-bottom' => astra_responsive_spacing( $header_spacing, 'bottom', 'mobile' ),
			),
		);
		/* Parse CSS from array() */
		$css_output .= astra_parse_css( $desktop_colors );
		$css_output .= astra_parse_css( $tablet_colors, '', '768' );
		$css_output .= astra_parse_css( $mobile_colors, '', '544' );
	}
	return $dynamic_css . $css_output;
}
