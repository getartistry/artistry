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

	$content_bg_color      = astra_get_option( 'content-bg-color' );
	$site_container_layout = astra_get_option( 'site-content-layout' );
	$link_color            = astra_get_option( 'link-color' );
	$h1_color              = astra_get_option( 'h1-color' );
	$h2_color              = astra_get_option( 'h2-color' );
	$h3_color              = astra_get_option( 'h3-color' );
	$h4_color              = astra_get_option( 'h4-color' );
	$h5_color              = astra_get_option( 'h5-color' );
	$h6_color              = astra_get_option( 'h6-color' );

	$header_bg_color           = astra_get_option( 'header-bg-color' );
	$header_color_site_title   = astra_get_option( 'header-color-site-title' );
	$header_color_h_site_title = astra_get_option( 'header-color-h-site-title' );
	$header_color_site_tagline = astra_get_option( 'header-color-site-tagline' );

	$transparent_bg_color           = astra_get_option( 'transparent-header-bg-color' );
	$transparent_color_site_title   = astra_get_option( 'transparent-header-color-site-title' );
	$transparent_color_h_site_title = astra_get_option( 'transparent-header-color-h-site-title' );
	$transparent_menu_bg_color      = astra_get_option( 'transparent-menu-bg-color' );
	$transparent_menu_color         = astra_get_option( 'transparent-menu-color' );
	$transparent_menu_h_color       = astra_get_option( 'transparent-menu-h-color' );

	$primary_menu_bg_color   = astra_get_option( 'primary-menu-bg-color' );
	$primary_menu_color      = astra_get_option( 'primary-menu-color' );
	$primary_menu_h_bg_color = astra_get_option( 'primary-menu-h-bg-color' );
	$primary_menu_h_color    = astra_get_option( 'primary-menu-h-color' );
	$primary_menu_a_bg_color = astra_get_option( 'primary-menu-a-bg-color' );
	$primary_menu_a_color    = astra_get_option( 'primary-menu-a-color' );

	$primary_submenu_b_color    = astra_get_option( 'primary-submenu-b-color' );
	$primary_submenu_bg_color   = astra_get_option( 'primary-submenu-bg-color' );
	$primary_submenu_color      = astra_get_option( 'primary-submenu-color' );
	$primary_submenu_h_bg_color = astra_get_option( 'primary-submenu-h-bg-color' );
	$primary_submenu_h_color    = astra_get_option( 'primary-submenu-h-color' );
	$primary_submenu_a_bg_color = astra_get_option( 'primary-submenu-a-bg-color' );
	$primary_submenu_a_color    = astra_get_option( 'primary-submenu-a-color' );

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

	$footer_bg_color     = astra_get_option( 'footer-bg-color' );
	$footer_bg_color_opc = astra_get_option( 'footer-bg-color-opc' );
	$footer_bg_img       = astra_get_option( 'footer-bg-img' );
	$footer_bg_rep       = astra_get_option( 'footer-bg-rep' );
	$footer_bg_size      = astra_get_option( 'footer-bg-size' );
	$footer_bg_pos       = astra_get_option( 'footer-bg-pos' );
	$footer_bg_atch      = astra_get_option( 'footer-bg-atch' );
	$footer_color        = astra_get_option( 'footer-color' );
	$footer_link_color   = astra_get_option( 'footer-link-color' );
	$footer_link_h_color = astra_get_option( 'footer-link-h-color' );

	// Check Astra_Control_Color is exist in the theme.
	if ( version_compare( ASTRA_THEME_VERSION, '1.0.22', '<' ) ) {
		if ( empty( $footer_bg_img ) ) {
			$footer_bg_color_opc = 1;
		}

		if ( ! empty( $footer_bg_color ) && 0 != $footer_bg_color_opc ) {
			$footer_bg_color = astra_hex_to_rgba( $footer_bg_color, $footer_bg_color_opc );
		}
	}

	$css_output = array(

		/**
		 * Content <h1> to <h6> headings
		 */
		'h1, .entry-content h1'                            => array(
			'color' => esc_attr( $h1_color ),
		),
		'h2, .entry-content h2'                            => array(
			'color' => esc_attr( $h2_color ),
		),
		'h3, .entry-content h3'                            => array(
			'color' => esc_attr( $h3_color ),
		),
		'h4, .entry-content h4'                            => array(
			'color' => esc_attr( $h4_color ),
		),
		'h5, .entry-content h5'                            => array(
			'color' => esc_attr( $h5_color ),
		),
		'h6, .entry-content h6'                            => array(
			'color' => esc_attr( $h6_color ),
		),

		/**
		 * Header
		 */
		'.main-header-bar, .ast-header-break-point .main-header-menu' => array(
			'background-color' => esc_attr( $header_bg_color ),
		),
		'.main-header-bar .ast-search-menu-icon form'      => array(
			'background-color' => esc_attr( $header_bg_color ),
		),
		'.ast-masthead-custom-menu-items .slide-search .search-field' => array(
			'background-color' => esc_attr( $header_bg_color ),
		),
		'.ast-masthead-custom-menu-items .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $header_bg_color ),
		),

		'.site-title a, .site-title a:focus, .site-title a:hover, .site-title a:visited' => array(
			'color' => esc_attr( $header_color_site_title ),
		),
		'.site-header .site-title a:hover'                 => array(
			'color' => esc_attr( $header_color_h_site_title ),
		),
		'.site-header .site-description'                   => array(
			'color' => esc_attr( $header_color_site_tagline ),
		),

		/**
		 * Transparent Header
		 */
		'.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header .ast-header-break-point .main-header-menu' => array(
			'background-color' => esc_attr( $transparent_bg_color ),
		),
		'.ast-theme-transparent-header .main-header-bar .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color ),
		),
		'.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color ),
		),
		'.ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color ),
		),

		'.ast-theme-transparent-header .ast-above-header, .ast-theme-transparent-header .ast-below-header' => array(
			'background-color' => esc_attr( $transparent_bg_color ),
		),

		'.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited' => array(
			'color' => esc_attr( $transparent_color_site_title ),
		),
		'.ast-theme-transparent-header .site-header .site-title a:hover' => array(
			'color' => esc_attr( $transparent_color_h_site_title ),
		),

		'.ast-theme-transparent-header .site-header .site-description' => array(
			'color' => esc_attr( $transparent_color_site_title ),
		),

		'.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .ast-header-break-point .main-header-menu' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color ),
		),
		'.ast-theme-transparent-header .main-header-menu li.current-menu-item > a, .ast-theme-transparent-header .main-header-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .main-header-menu li.current_page_item > a' => array(
			'color' => esc_attr( $transparent_menu_h_color ),
		),
		'.ast-theme-transparent-header .main-header-menu a:hover, .ast-theme-transparent-header .main-header-menu li:hover > a, .ast-theme-transparent-header .main-header-menu li.focus > a' => array(
			'color' => esc_attr( $transparent_menu_h_color ),
		),
		'.ast-theme-transparent-header .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header .main-header-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_h_color ),
		),

		'.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .main-header-menu a, .ast-theme-transparent-header .ast-masthead-custom-menu-items .slide-search .search-submit, .ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a' => array(
			'color' => esc_attr( $transparent_menu_color ),
		),

		/**
		 * Primary Menu
		 */
		'.main-header-menu, .ast-header-break-point .main-header-menu, .ast-header-break-point .ast-header-custom-item' => array(
			'background-color' => esc_attr( $primary_menu_bg_color ),
		),
		'.main-header-menu li.current-menu-item > a, .main-header-menu li.current-menu-ancestor > a, .main-header-menu li.current_page_item > a' => array(
			'color'            => esc_attr( $primary_menu_a_color ),
			'background-color' => esc_attr( $primary_menu_a_bg_color ),
		),
		'.main-header-menu a:hover, .ast-header-custom-item a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a' => array(
			'background-color' => esc_attr( $primary_menu_h_bg_color ),
			'color'            => esc_attr( $primary_menu_h_color ),
		),
		'.main-header-menu .ast-masthead-custom-menu-items a:hover, .main-header-menu li:hover > .ast-menu-toggle, .main-header-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_menu_h_color ),
		),

		'.main-header-menu, .main-header-menu a, .ast-header-custom-item, .ast-header-custom-item a,  .ast-masthead-custom-menu-items .slide-search .search-submit, .ast-masthead-custom-menu-items, .ast-masthead-custom-menu-items a' => array(
			'color' => esc_attr( $primary_menu_color ),
		),

		'.ast-masthead-custom-menu-items .ast-inline-search form' => array(
			'border-color' => esc_attr( $primary_menu_color ),
		),

		/**
		 * Primary Submenu
		 */
		'.main-header-menu .sub-menu, .main-header-menu .sub-menu a, .main-header-menu .children a' => array(
			'color' => esc_attr( $primary_submenu_color ),
		),
		'.main-header-menu .sub-menu a:hover, .main-header-menu .children a:hover, .main-header-menu .sub-menu li:hover > a, .main-header-menu .children li:hover > a, .main-header-menu .sub-menu li.focus > a, .main-header-menu .children li.focus > a' => array(
			'color'            => esc_attr( $primary_submenu_h_color ),
			'background-color' => esc_attr( $primary_submenu_h_bg_color ),
		),
		'.main-header-menu .sub-menu li:hover > .ast-menu-toggle, .main-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $primary_submenu_h_color ),
		),
		'.main-header-menu .sub-menu li.current-menu-item > a, .main-header-menu .children li.current_page_item > a, .main-header-menu .sub-menu li.current-menu-ancestor > a, .main-header-menu .children li.current_page_ancestor > a, .main-header-menu .sub-menu li.current_page_item > a, .main-header-menu .children li.current_page_item > a' => array(
			'color'            => esc_attr( $primary_submenu_a_color ),
			'background-color' => esc_attr( $primary_submenu_a_bg_color ),
		),
		'.main-navigation ul ul, .main-navigation ul ul a' => array(
			'border-color' => esc_attr( $primary_submenu_b_color ),
		),
		'.main-navigation ul ul, .ast-header-break-point .main-header-menu ul' => array(
			'background-color' => esc_attr( $primary_submenu_bg_color ),
		),

		/**
		 * Single Post / Page Title Color
		 */
		'.ast-single-post .entry-title, .page-title'       => array(
			'color' => esc_attr( $entry_title_color ),
		),

		/**
		 * Sidebar
		 */
		'.secondary .widget-title, .secondary .widget-title *' => array(
			'color' => esc_attr( $sidebar_wgt_title_color ),
		),
		'.secondary'                                       => array(
			'color' => esc_attr( $sidebar_text_color ),
		),
		'.secondary a'                                     => array(
			'color' => esc_attr( $sidebar_link_color ),
		),
		'.secondary a:hover'                               => array(
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
		'.entry-title a'                                   => array(
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

		'.entry-meta, .entry-meta *'                       => array(
			'color' => esc_attr( $post_meta_color ),
		),

		/**
		 * Footer
		 */
		'.ast-small-footer'                                => array(
			'color' => esc_attr( $footer_color ),
		),
		'.ast-small-footer > .ast-footer-overlay'          => array(
			'background-color' => esc_attr( $footer_bg_color ),
		),
		'.ast-small-footer a'                              => array(
			'color' => esc_attr( $footer_link_color ),
		),
		'.ast-small-footer a:hover'                        => array(
			'color' => esc_attr( $footer_link_h_color ),
		),
	);

	$primary_nav = astra_get_option( 'disable-primary-nav' );
	if ( $primary_nav ) {
		$css_output['.ast-header-break-point .ast-header-custom-item'] = array(
			'background-color' => esc_attr( $primary_menu_bg_color ),
		);
	}

	/* Parse CSS from array() */
	$css_output = astra_parse_css( $css_output );

	// Advanced Fotter bg image.
	if ( ! empty( $footer_bg_img ) ) {
		$footer_bg_img_css = array(
			'.ast-small-footer' => array(
				'background-size'       => esc_attr( $footer_bg_size ),
				'background-image'      => astra_get_css_value( $footer_bg_img, 'url' ),
				'background-position'   => esc_attr( $footer_bg_pos ),
				'background-repeat'     => esc_attr( $footer_bg_rep ),
				'background-attachment' => esc_attr( $footer_bg_atch ),
			),
		);
		$css_output       .= astra_parse_css( $footer_bg_img_css );
	}

	// Sticky header is enabled.
	if ( Astra_Ext_Extension::is_active( 'sticky-header' ) ) {
		$main_stick                = astra_get_option( 'header-main-stick' );
		$header_color_site_title   = astra_get_option( 'header-color-site-title', '#222' );
		$text_color                = astra_get_option( 'text-color' );
		$link_color                = astra_get_option( 'link-color' );
		$header_color_site_tagline = astra_get_option( 'header-color-site-tagline', $text_color );

		$primary_menu_color   = astra_get_option( 'primary-menu-color', $text_color );
		$primary_menu_h_color = astra_get_option( 'primary-menu-h-color', $link_color );
		$primary_menu_a_color = astra_get_option( 'primary-menu-a-color', $link_color );

		if ( $main_stick ) {
			$sticky_css_output = array(
				'#ast-fixed-header .main-header-bar .site-title a, #ast-fixed-header .main-header-bar .site-title a:focus, #ast-fixed-header .main-header-bar .site-title a:hover, #ast-fixed-header .main-header-bar .site-title a:visited, .main-header-bar.ast-sticky-active .site-title a, .main-header-bar.ast-sticky-active .site-title a:focus, .main-header-bar.ast-sticky-active .site-title a:hover, .main-header-bar.ast-sticky-active .site-title a:visited' => array(
					'color' => esc_attr( $header_color_site_title ),
				),
				'#ast-fixed-header .main-header-bar .site-title a:hover, .main-header-bar.ast-sticky-active .site-title a:hover' => array(
					'color' => esc_attr( $header_color_h_site_title ),
				),
				'#ast-fixed-header .main-header-bar .site-description, .main-header-bar.ast-sticky-active .site-description' => array(
					'color' => esc_attr( $header_color_site_tagline ),
				),
				'#ast-fixed-header .main-header-menu, #ast-fixed-header .main-header-menu > li > a, #ast-fixed-header  .ast-masthead-custom-menu-items .slide-search .search-submit, #ast-fixed-header .ast-masthead-custom-menu-items, #ast-fixed-header .ast-masthead-custom-menu-items a, .main-header-bar.ast-sticky-active, .main-header-bar.ast-sticky-active .main-header-menu > li > a, .main-header-bar.ast-sticky-active  .ast-masthead-custom-menu-items .slide-search .search-submit, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items a' => array(
					'color' => esc_attr( $primary_menu_color ),
				),

				/**
				 * Primary Menu
				 */
				'#ast-fixed-header .main-header-menu li.current-menu-item > a, #ast-fixed-header .main-header-menu li.current-menu-ancestor > a, #ast-fixed-header .main-header-menu li.current_page_item > a, .main-header-bar.ast-sticky-active .main-header-menu li.current-menu-item > a, .main-header-bar.ast-sticky-active .main-header-menu li.current-menu-ancestor > a, .main-header-bar.ast-sticky-active .main-header-menu li.current_page_item > a' => array(
					'color'            => esc_attr( $primary_menu_a_color ),
					'background-color' => esc_attr( $primary_menu_a_bg_color ),
				),
				'#ast-fixed-header .main-header-menu a:hover, #ast-fixed-header .main-header-menu li:hover > a, #ast-fixed-header .main-header-menu li.focus > a, .main-header-bar.ast-sticky-active .main-header-menu li:hover > a, .main-header-bar.ast-sticky-active .main-header-menu li.focus > a' => array(
					'background-color' => esc_attr( $primary_menu_h_bg_color ),
					'color'            => esc_attr( $primary_menu_h_color ),
				),
				'#ast-fixed-header .main-header-menu .ast-masthead-custom-menu-items a:hover, #ast-fixed-header .main-header-menu li:hover > .ast-menu-toggle, #ast-fixed-header .main-header-menu li.focus > .ast-menu-toggle,.main-header-bar.ast-sticky-active .main-header-menu .ast-masthead-custom-menu-items a:hover,.main-header-bar.ast-sticky-active .main-header-menu li:hover > .ast-menu-toggle,.main-header-bar.ast-sticky-active .main-header-menu li.focus > .ast-menu-toggle' => array(
					'color' => esc_attr( $primary_menu_h_color ),
				),

				'#ast-fixed-header .ast-masthead-custom-menu-items .ast-inline-search form, .main-header-bar.ast-sticky-active .ast-masthead-custom-menu-items .ast-inline-search form' => array(
					'border-color' => esc_attr( $primary_menu_color ),
				),
			);

			/* Parse CSS from array() */
			$css_output .= astra_parse_css( $sticky_css_output );
		}
	}

	if ( version_compare( ASTRA_THEME_VERSION, '1.0.22', '>=' ) ) {
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

			'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single, .ast-separate-container .comment-respond,.ast-separate-container .ast-comment-list li, .ast-separate-container .ast-woocommerce-container, .ast-separate-container .blog-layout-1, .ast-separate-container .blog-layout-2, .ast-separate-container .blog-layout-3,.ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container .ast-author-meta, .ast-separate-container .related-posts-title-wrapper, .ast-separate-container.ast-two-container #secondary .widget,.ast-separate-container .comments-count-wrapper, .ast-box-layout.ast-plain-container .site-content,.ast-padded-layout.ast-plain-container .site-content' => array(
				'background-color' => esc_attr( $content_bg_color ),
			),

		);
	} else {
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

			'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single, .ast-separate-container .comment-respond,.ast-separate-container .ast-comment-list li, .ast-separate-container .ast-woocommerce-container, .ast-separate-container .blog-layout-1, .ast-separate-container .blog-layout-2, .ast-separate-container .blog-layout-3,.ast-separate-container .error-404, .ast-separate-container .no-results, .single.ast-separate-container .ast-author-meta, .ast-separate-container .related-posts-title-wrapper, .ast-separate-container.ast-two-container #secondary .widget,.ast-separate-container .comments-count-wrapper, .ast-box-layout.ast-plain-container .site-content,.ast-padded-layout.ast-plain-container .site-content' => array(
				'background-color' => esc_attr( $content_bg_color ),
			),
		);
	}

	$css_output .= astra_parse_css( $separate_container_css );

	/**
	 * Merge Header Section when no primary menu
	 */
	if ( Astra_Ext_Extension::is_active( 'header-sections' ) ) {

		$primary_menu_a_color = astra_get_option( 'primary-menu-a-color' );

		$header_scetion_css_output = array(

			/**
			 * Primary Menu merge with Above Header & Below Header menu
			 */
			'.ast-header-sections-navigation li.current-menu-item > a, .ast-above-header-menu-items li.current-menu-item > a,.ast-below-header-menu-items li.current-menu-item > a,.ast-header-sections-navigation li.current-menu-ancestor > a, .ast-above-header-menu-items li.current-menu-ancestor > a,.ast-below-header-menu-items li.current-menu-ancestor > a' => array(
				'color'            => esc_attr( $primary_menu_a_color ),
				'background-color' => esc_attr( $primary_menu_a_bg_color ),
			),
			'.main-header-menu a:hover, .ast-header-custom-item a:hover, .main-header-menu li:hover > a, .main-header-menu li.focus > a, .ast-header-break-point .ast-header-sections-navigation a:hover, .ast-header-break-point .ast-header-sections-navigation a:focus' => array(
				'background-color' => esc_attr( $primary_menu_h_bg_color ),
				'color'            => esc_attr( $primary_menu_h_color ),
			),
			'.ast-header-sections-navigation li:hover > .ast-menu-toggle, .ast-header-sections-navigation li.focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_menu_h_color ),
			),

			'.ast-header-sections-navigation, .ast-header-sections-navigation a, .ast-above-header-menu-items,.ast-above-header-menu-items a,.ast-below-header-menu-items, .ast-below-header-menu-items a' => array(
				'color' => esc_attr( $primary_menu_color ),
			),

			'.ast-header-sections-navigation .ast-inline-search form' => array(
				'border-color' => esc_attr( $primary_menu_color ),
			),

			/**
			 * Primary Submenu
			 */
			'.ast-header-sections-navigation .sub-menu a, .ast-above-header-menu-items .sub-menu a, .ast-below-header-menu-items .sub-menu a' => array(
				'color' => esc_attr( $primary_submenu_color ),
			),
			'.ast-header-sections-navigation .sub-menu a:hover, .ast-above-header-menu-items .sub-menu a:hover, .ast-below-header-menu-items .sub-menu a:hover' => array(
				'color'            => esc_attr( $primary_submenu_h_color ),
				'background-color' => esc_attr( $primary_submenu_h_bg_color ),
			),
			'.ast-header-sections-navigation .sub-menu li:hover > .ast-menu-toggle, .ast-header-sections-navigation .sub-menu li:focus > .ast-menu-toggle, .ast-above-header-menu-items .sub-menu li:hover > .ast-menu-toggle, .ast-below-header-menu-items .sub-menu li:hover > .ast-menu-toggle, .ast-above-header-menu-items .sub-menu li:focus > .ast-menu-toggle, .ast-below-header-menu-items .sub-menu li:focus > .ast-menu-toggle' => array(
				'color' => esc_attr( $primary_submenu_h_color ),
			),
			'.ast-header-sections-navigation .sub-menu li.current-menu-item > a, .ast-above-header-menu-items .sub-menu li.current-menu-item > a, .ast-below-header-menu-items .sub-menu li.current-menu-item > a' => array(
				'color'            => esc_attr( $primary_submenu_a_color ),
				'background-color' => esc_attr( $primary_submenu_a_bg_color ),
			),
			'.ast-header-sections-navigation div > li > ul' => array(
				'border-color' => esc_attr( $primary_submenu_b_color ),
			),
			'.main-navigation ul ul, .ast-header-break-point .main-header-menu ul, .ast-header-sections-navigation div > li > ul, .ast-above-header-menu-items li > ul, .ast-below-header-menu-items li > ul' => array(
				'background-color' => esc_attr( $primary_submenu_bg_color ),
			),

		);

		if ( $primary_menu_bg_color ) {
			$header_scetion_css_output['.ast-header-break-point .ast-header-sections-navigation'] = array(
				'background-color' => esc_attr( $primary_menu_bg_color ),
			);
		} else {
			$header_scetion_css_output['.ast-header-break-point .ast-header-sections-navigation, .ast-header-break-point .ast-above-header-menu-items, .ast-header-break-point .ast-below-header-menu-items'] = array(
				'background-color' => esc_attr( $header_bg_color ),
			);
		}

		/* Parse CSS from array() */
		$css_output .= astra_parse_css( $header_scetion_css_output );
	}

	// Foreground color.
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

	return $dynamic_css . $css_output;
}
