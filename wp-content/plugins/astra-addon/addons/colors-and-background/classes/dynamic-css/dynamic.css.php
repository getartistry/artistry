<?php
/**
 * Colors & Background - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_ext_colors_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_ext_colors_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$content_bg_obj        = astra_get_option( 'content-bg-obj' );
	$blog_layout           = astra_get_option( 'blog-layout' );
	$blog_grid             = astra_get_option( 'blog-grid' );
	$content_bg_color      = isset( $content_bg_obj['background-color'] ) ? $content_bg_obj['background-color'] : '';
	$content_bg_image      = isset( $content_bg_obj['background-image'] ) ? $content_bg_obj['background-image'] : '';
	$site_container_layout = astra_get_option( 'site-content-layout' );
	$link_color            = astra_get_option( 'link-color' );
	$h1_color              = astra_get_option( 'h1-color' );
	$h2_color              = astra_get_option( 'h2-color' );
	$h3_color              = astra_get_option( 'h3-color' );
	$h4_color              = astra_get_option( 'h4-color' );
	$h5_color              = astra_get_option( 'h5-color' );
	$h6_color              = astra_get_option( 'h6-color' );

	$header_bg_obj             = astra_get_option( 'header-bg-obj-responsive' );
	$desktop_header_bg_color   = isset( $header_bg_obj['desktop']['background-color'] ) ? $header_bg_obj['desktop']['background-color'] : '';
	$tablet_header_bg_color    = isset( $header_bg_obj['tablet']['background-color'] ) ? $header_bg_obj['tablet']['background-color'] : '';
	$mobile_header_bg_color    = isset( $header_bg_obj['mobile']['background-color'] ) ? $header_bg_obj['mobile']['background-color'] : '';
	$header_color_site_title   = astra_get_option( 'header-color-site-title' );
	$header_color_h_site_title = astra_get_option( 'header-color-h-site-title' );
	$header_color_site_tagline = astra_get_option( 'header-color-site-tagline' );

	$disable_primary_nav = astra_get_option( 'disable-primary-nav' );

	$primary_menu_bg_image = astra_get_option( 'primary-menu-bg-obj-responsive' );

	$primary_menu_color      = astra_get_option( 'primary-menu-color-responsive' );
	$primary_menu_h_bg_color = astra_get_option( 'primary-menu-h-bg-color-responsive' );
	$primary_menu_h_color    = astra_get_option( 'primary-menu-h-color-responsive' );
	$primary_menu_a_bg_color = astra_get_option( 'primary-menu-a-bg-color-responsive' );
	$primary_menu_a_color    = astra_get_option( 'primary-menu-a-color-responsive' );

	$primary_submenu_bg_color   = astra_get_option( 'primary-submenu-bg-color-responsive' );
	$primary_submenu_color      = astra_get_option( 'primary-submenu-color-responsive' );
	$primary_submenu_h_bg_color = astra_get_option( 'primary-submenu-h-bg-color-responsive' );
	$primary_submenu_h_color    = astra_get_option( 'primary-submenu-h-color-responsive' );
	$primary_submenu_a_bg_color = astra_get_option( 'primary-submenu-a-bg-color-responsive' );
	$primary_submenu_a_color    = astra_get_option( 'primary-submenu-a-color-responsive' );

	$entry_title_color = astra_get_option( 'entry-title-color' );
	$page_title_color  = astra_get_option( 'page-title-color' );

	$archive_summary_bg_color    = astra_get_option( 'archive-summary-box-bg-color' );
	$archive_summary_title_color = astra_get_option( 'archive-summary-box-title-color' );
	$archive_summary_text_color  = astra_get_option( 'archive-summary-box-text-color' );

	$post_meta_color        = astra_get_option( 'post-meta-color' );
	$post_meta_link_color   = astra_get_option( 'post-meta-link-color' );
	$post_meta_link_h_color = astra_get_option( 'post-meta-link-h-color' );

	$sidebar_wgt_title_color = astra_get_option( 'sidebar-widget-title-color' );
	$sidebar_text_color      = astra_get_option( 'sidebar-text-color' );
	$sidebar_link_color      = astra_get_option( 'sidebar-link-color' );
	$sidebar_link_h_color    = astra_get_option( 'sidebar-link-h-color' );
	$sidebar_bg_obj          = astra_get_option( 'sidebar-bg-obj' );

	$footer_color        = astra_get_option( 'footer-color' );
	$footer_link_color   = astra_get_option( 'footer-link-color' );
	$footer_link_h_color = astra_get_option( 'footer-link-h-color' );
	$header_break_point  = astra_header_break_point(); // Header Break Point.

	/**
	 * Normal Colors without reponsive option.
	 * [1]. Header Colors
	 * [2]. Content Colors
	 *      - Single Post / Page Title Colors
	 *      - Blog / Archive Title Colors
	 *      - Blog / Archive Meta Colors
	 * [3]. Sidebar Colors
	 * [4]. Footer Colors
	 *
	 * Responsive Colors options
	 * [1]. Header Responsive Background with Image.
	 * [2]. Primary Menu Responsive Colors
	 */

	/**
	 * Normal Colors without reponsive option.
	 * [1]. Header Colors
	 * [2]. Content Colors
	 *      - Single Post / Page Title Color
	 *      - Blog / Archive Title
	 *      - Blog / Archive Meta
	 * [3]. Sidebar Colors
	 * [4]. Footer
	 */
	$css_output = array(

		/**
		 * Content <h1> to <h6> headings
		 */
		'h1, .entry-content h1'                      => array(
			'color' => esc_attr( $h1_color ),
		),
		'h2, .entry-content h2'                      => array(
			'color' => esc_attr( $h2_color ),
		),
		'h3, .entry-content h3'                      => array(
			'color' => esc_attr( $h3_color ),
		),
		'h4, .entry-content h4'                      => array(
			'color' => esc_attr( $h4_color ),
		),
		'h5, .entry-content h5'                      => array(
			'color' => esc_attr( $h5_color ),
		),
		'h6, .entry-content h6'                      => array(
			'color' => esc_attr( $h6_color ),
		),

		/**
		 * Header
		 */

		'.site-title a, .site-title a:focus, .site-title a:hover, .site-title a:visited' => array(
			'color' => esc_attr( $header_color_site_title ),
		),
		'.site-header .site-title a:hover'           => array(
			'color' => esc_attr( $header_color_h_site_title ),
		),
		'.site-header .site-description'             => array(
			'color' => esc_attr( $header_color_site_tagline ),
		),

		/**
		 * Single Post / Page Title Color
		 */
		'.ast-single-post .entry-title, .page-title' => array(
			'color' => esc_attr( $entry_title_color ),
		),

		/**
		 * Sidebar
		 */
		'.sidebar-main'                              => astra_get_background_obj( $sidebar_bg_obj ),
		'.secondary .widget-title, .secondary .widget-title *' => array(
			'color' => esc_attr( $sidebar_wgt_title_color ),
		),
		'.secondary'                                 => array(
			'color' => esc_attr( $sidebar_text_color ),
		),
		'.secondary a'                               => array(
			'color' => esc_attr( $sidebar_link_color ),
		),
		'.secondary a:hover'                         => array(
			'color' => esc_attr( $sidebar_link_h_color ),
		),
		'.secondary .tagcloud a:hover, .secondary .tagcloud a.current-item' => array(
			'border-color'     => esc_attr( $sidebar_link_color ),
			'background-color' => esc_attr( $sidebar_link_color ),
		),
		'.secondary .calendar_wrap #today, .secondary a:hover + .post-count' => array(
			'background-color' => esc_attr( $sidebar_link_color ),
		),

		/**
		 * Blog / Archive Title
		 */
		'.entry-title a'                             => array(
			'color' => esc_attr( $page_title_color ),
		),

		/**
		 * Blog / Archive Meta
		 */
		'.read-more a:not(.ast-button):hover, .entry-meta a:hover, .entry-meta a:hover *' => array(
			'color' => esc_attr( $post_meta_link_h_color ),
		),
		'.entry-meta a, .entry-meta a *, .read-more a:not(.ast-button)' => array(
			'color' => esc_attr( $post_meta_link_color ),
		),

		'.entry-meta, .entry-meta *'                 => array(
			'color' => esc_attr( $post_meta_color ),
		),

		/**
		 * Footer
		 */
		'.ast-small-footer'                          => array(
			'color' => esc_attr( $footer_color ),
		),
		'.ast-small-footer a'                        => array(
			'color' => esc_attr( $footer_link_color ),
		),
		'.ast-small-footer a:hover'                  => array(
			'color' => esc_attr( $footer_link_h_color ),
		),
	);

	/* Parse CSS from array() */
	$css_output = astra_parse_css( $css_output );

	// Container Layout Colors.
		$separate_container_css = array(

			/**
			 * Archive Summary Background Color
			 */
			'.ast-separate-container .ast-archive-description' => array(
				'background-color' => esc_attr( $archive_summary_bg_color ),
			),

			'.ast-archive-description'             => array(
				'color' => esc_attr( $archive_summary_text_color ),
			),

			'.ast-archive-description .page-title' => array(
				'color' => esc_attr( $archive_summary_title_color ),
			),

			'.ast-separate-container .ast-article-single, .ast-separate-container .comment-respond,.ast-separate-container .ast-comment-list li, .ast-separate-container .ast-woocommerce-container, .ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container .ast-author-meta, .ast-separate-container .related-posts-title-wrapper, .ast-separate-container.ast-two-container #secondary .widget,.ast-separate-container .comments-count-wrapper, .ast-box-layout.ast-plain-container .site-content,.ast-padded-layout.ast-plain-container .site-content' => astra_get_background_obj( $content_bg_obj ),
		);
		// Blog Pro Layout Colors.
	if ( 'blog-layout-1' == $blog_layout && 1 != $blog_grid ) {
		$blog_layouts = array(
			'.ast-separate-container .blog-layout-1, .ast-separate-container .blog-layout-2, .ast-separate-container .blog-layout-3' => astra_get_background_obj( $content_bg_obj ),
		);
	} else {
		$blog_layouts = array(
			'.ast-separate-container .ast-article-post' => astra_get_background_obj( $content_bg_obj ),
		);
		$inner_layout = array(
			'.ast-separate-container .blog-layout-1, .ast-separate-container .blog-layout-2, .ast-separate-container .blog-layout-3' => array(
				'background-color' => 'transparent',
				'background-image' => 'none',
			),
		);
		$css_output  .= astra_parse_css( $inner_layout );
	}

		$css_output .= astra_parse_css( $blog_layouts );
		$css_output .= astra_parse_css( $separate_container_css );

	// Sidebar Foreground color.
	if ( ! empty( $sidebar_link_color ) ) {
		$sidebar_foreground = array(
			'.secondary .tagcloud a:hover, .secondary .tagcloud a.current-item' => array(
				'color' => astra_get_foreground_color( $sidebar_link_color ),
			),
			'.secondary .calendar_wrap #today' => array(
				'color' => astra_get_foreground_color( $sidebar_link_color ),
			),
		);
		$css_output        .= astra_parse_css( $sidebar_foreground );
	}

	/**
	 * Responsive Colors options
	 * [1]. Header Responsive Background with Image
	 * [2]. Primary Menu Responsive Colors
	 */
	$desktop_colors = array(

		/**
		 * Header
		 */
		'.main-header-bar' => astra_get_background_obj( $header_bg_obj['desktop'] ),
		/**
		 * Primary Menu
		 */
		'.main-header-menu, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item' => astra_get_background_obj( $primary_menu_bg_image['desktop'] ),
		'.main-header-menu .current-menu-item > a, .main-header-menu .current-menu-ancestor > a, .main-header-menu .current_page_item > a' => array(
			'color'            => esc_attr( $primary_menu_a_color['desktop'] ),
			'background-color' => esc_attr( $primary_menu_a_bg_color['desktop'] ),
		),
		'.main-header-menu a:hover, .ast-header-custom-item a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
			'background-color' => esc_attr( $primary_menu_h_bg_color['desktop'] ),
			'color'            => esc_attr( $primary_menu_h_color['desktop'] ),
		),
		'.main-header-menu .ast-masthead-custom-menu-items a:hover, .main-header-menu li:hover > .ast-menu-toggle, .main-header-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_menu_h_color['desktop'] ),
		),

		'.main-header-menu, .main-header-menu a, .ast-header-custom-item, .ast-header-custom-item a,  .ast-masthead-custom-menu-items, .ast-masthead-custom-menu-items a' => array(
			'color' => esc_attr( $primary_menu_color['desktop'] ),
		),

		/**
		 * Primary Submenu
		 */
		'.main-header-menu .sub-menu, .main-header-menu .sub-menu a, .main-header-menu .children a' => array(
			'color' => esc_attr( $primary_submenu_color['desktop'] ),
		),
		'.main-header-menu .sub-menu a:hover, .main-header-menu .children a:hover, .main-header-menu .sub-menu li:hover > a, .main-header-menu .children li:hover > a, .main-header-menu .sub-menu li.focus > a, .main-header-menu .children li.focus > a' => array(
			'color'            => esc_attr( $primary_submenu_h_color['desktop'] ),
			'background-color' => esc_attr( $primary_submenu_h_bg_color['desktop'] ),
		),
		'.main-header-menu .sub-menu li:hover > .ast-menu-toggle, .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_submenu_h_color['desktop'] ),
		),
		'.main-header-menu .sub-menu li.current-menu-item > a, .main-header-menu .children li.current_page_item > a, .main-header-menu .sub-menu li.current-menu-ancestor > a, .main-header-menu .children li.current_page_ancestor > a, .main-header-menu .sub-menu li.current_page_item > a, .main-header-menu .children li.current_page_item > a' => array(
			'color'            => esc_attr( $primary_submenu_a_color['desktop'] ),
			'background-color' => esc_attr( $primary_submenu_a_bg_color['desktop'] ),
		),
		'.main-navigation .sub-menu, .ast-header-break-point .main-header-menu ul' => array(
			'background-color' => esc_attr( $primary_submenu_bg_color['desktop'] ),
		),
	);

	$tablet_colors = array(
		/**
		 * Header
		 */
		'.main-header-bar' => astra_get_background_obj( $header_bg_obj['tablet'] ),

		/**
		 * Primary Menu
		 */
		'.main-header-menu, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item' => astra_get_background_obj( $primary_menu_bg_image['tablet'] ),
		'.main-header-menu .current-menu-item > a, .main-header-menu .current-menu-ancestor > a, .main-header-menu .current_page_item > a' => array(
			'color'            => esc_attr( $primary_menu_a_color['tablet'] ),
			'background-color' => esc_attr( $primary_menu_a_bg_color['tablet'] ),
		),
		'.main-header-menu a:hover, .ast-header-custom-item a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
			'background-color' => esc_attr( $primary_menu_h_bg_color['tablet'] ),
			'color'            => esc_attr( $primary_menu_h_color['tablet'] ),
		),
		'.main-header-menu .ast-masthead-custom-menu-items a:hover, .main-header-menu li:hover > .ast-menu-toggle, .main-header-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_menu_h_color['tablet'] ),
		),

		'.main-header-menu, .main-header-menu a, .ast-header-custom-item, .ast-header-custom-item a,  .ast-masthead-custom-menu-items, .ast-masthead-custom-menu-items a' => array(
			'color' => esc_attr( $primary_menu_color['tablet'] ),
		),

		/**
		 * Primary Submenu
		 */
		'.main-header-menu .sub-menu, .main-header-menu .sub-menu a, .main-header-menu .children a' => array(
			'color' => esc_attr( $primary_submenu_color['tablet'] ),
		),
		'.main-header-menu .sub-menu a:hover, .main-header-menu .children a:hover, .main-header-menu .sub-menu li:hover > a, .main-header-menu .children li:hover > a, .main-header-menu .sub-menu li.focus > a, .main-header-menu .children li.focus > a' => array(
			'color'            => esc_attr( $primary_submenu_h_color['tablet'] ),
			'background-color' => esc_attr( $primary_submenu_h_bg_color['tablet'] ),
		),
		'.main-header-menu .sub-menu li:hover > .ast-menu-toggle, .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_submenu_h_color['tablet'] ),
		),
		'.main-header-menu .sub-menu li.current-menu-item > a, .main-header-menu .children li.current_page_item > a, .main-header-menu .sub-menu li.current-menu-ancestor > a, .main-header-menu .children li.current_page_ancestor > a, .main-header-menu .sub-menu li.current_page_item > a, .main-header-menu .children li.current_page_item > a' => array(
			'color'            => esc_attr( $primary_submenu_a_color['tablet'] ),
			'background-color' => esc_attr( $primary_submenu_a_bg_color['tablet'] ),
		),
		'.main-navigation .sub-menu, .ast-header-break-point .main-header-menu ul' => array(
			'background-color' => esc_attr( $primary_submenu_bg_color['tablet'] ),
		),
	);
	$mobile_colors = array(
		/**
		 * Header
		 */
		'.main-header-bar' => astra_get_background_obj( $header_bg_obj['mobile'] ),

		/**
		 * Primary Menu
		 */
		'.main-header-menu, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item' => astra_get_background_obj( $primary_menu_bg_image['mobile'] ),
		'.main-header-menu .current-menu-item > a, .main-header-menu .current-menu-ancestor > a, .main-header-menu .current_page_item > a' => array(
			'color'            => esc_attr( $primary_menu_a_color['mobile'] ),
			'background-color' => esc_attr( $primary_menu_a_bg_color['mobile'] ),
		),
		'.main-header-menu a:hover, .ast-header-custom-item a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
			'background-color' => esc_attr( $primary_menu_h_bg_color['mobile'] ),
			'color'            => esc_attr( $primary_menu_h_color['mobile'] ),
		),
		'.main-header-menu .ast-masthead-custom-menu-items a:hover, .main-header-menu li:hover > .ast-menu-toggle, .main-header-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_menu_h_color['mobile'] ),
		),

		'.main-header-menu, .main-header-menu a, .ast-header-custom-item, .ast-header-custom-item a, .ast-masthead-custom-menu-items, .ast-masthead-custom-menu-items a' => array(
			'color' => esc_attr( $primary_menu_color['mobile'] ),
		),

		/**
		 * Primary Submenu
		 */
		'.main-header-menu .sub-menu, .main-header-menu .sub-menu a, .main-header-menu .children a' => array(
			'color' => esc_attr( $primary_submenu_color['mobile'] ),
		),
		'.main-header-menu .sub-menu a:hover, .main-header-menu .children a:hover, .main-header-menu .sub-menu li:hover > a, .main-header-menu .children li:hover > a, .main-header-menu .sub-menu li.focus > a, .main-header-menu .children li.focus > a' => array(
			'color'            => esc_attr( $primary_submenu_h_color['mobile'] ),
			'background-color' => esc_attr( $primary_submenu_h_bg_color['mobile'] ),
		),
		'.main-header-menu .sub-menu li:hover > .ast-menu-toggle, .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_submenu_h_color['mobile'] ),
		),
		'.main-header-menu .sub-menu li.current-menu-item > a, .main-header-menu .children li.current_page_item > a, .main-header-menu .sub-menu li.current-menu-ancestor > a, .main-header-menu .children li.current_page_ancestor > a, .main-header-menu .sub-menu li.current_page_item > a, .main-header-menu .children li.current_page_item > a' => array(
			'color'            => esc_attr( $primary_submenu_a_color['mobile'] ),
			'background-color' => esc_attr( $primary_submenu_a_bg_color['mobile'] ),
		),
		'.main-navigation .sub-menu, .ast-header-break-point .main-header-menu ul' => array(
			'background-color' => esc_attr( $primary_submenu_bg_color['mobile'] ),
		),
	);

	// Primary Menu Desabled.
	if ( $disable_primary_nav ) {
		// Set Primary Menu background color to the Custom Menu Item.
		$desktop_colors['.ast-header-break-point .ast-header-custom-item'] = astra_get_background_obj( $primary_menu_bg_image['desktop'] );
		$tablet_colors['.ast-header-break-point .ast-header-custom-item']  = astra_get_background_obj( $primary_menu_bg_image['tablet'] );
		$mobile_colors['.ast-header-break-point .ast-header-custom-item']  = astra_get_background_obj( $primary_menu_bg_image['mobile'] );
	}

	/* Parse CSS from array() */
	$css_output .= astra_parse_css( $desktop_colors );
	$css_output .= astra_parse_css( $tablet_colors, '', '768' );
	$css_output .= astra_parse_css( $mobile_colors, '', '544' );

	// All the primary menu bg color is not set then set the default header bg color to the primary menu for responsive devices.
	if ( '' == $primary_menu_bg_image['desktop']['background-color'] ) {
			$menu_bg_color = array(
				'.ast-header-break-point .main-header-menu' => array(
					'background-color' => esc_attr( $desktop_header_bg_color ),
				),
			);
		$css_output       .= astra_parse_css( $menu_bg_color );
	}
	if ( '' == $primary_menu_bg_image['tablet']['background-color'] ) {
			$menu_bg_color = array(
				'.ast-header-break-point .main-header-menu' => array(
					'background-color' => esc_attr( $tablet_header_bg_color ),
				),
			);
		$css_output       .= astra_parse_css( $menu_bg_color, '', '768' );
	}
	if ( '' == $primary_menu_bg_image['mobile']['background-color'] ) {
			$menu_bg_color = array(
				'.ast-header-break-point .main-header-menu' => array(
					'background-color' => esc_attr( $mobile_header_bg_color ),
				),
			);
		$css_output       .= astra_parse_css( $menu_bg_color, '', '544' );
	}

	return $dynamic_css . $css_output;
}
