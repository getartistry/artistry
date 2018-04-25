<?php
/**
 * Spacing - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_ext_spacing_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_ext_spacing_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	// Spacing.
	$site_identity_spacing        = astra_get_option( 'site-identity-spacing' );
	$continder_outside_spacing    = astra_get_option( 'container-outside-spacing' );
	$container_inside_spacing     = astra_get_option( 'container-inside-spacing' );
	$header_spacing               = astra_get_option( 'header-spacing' );
	$primary_menu_spacing         = astra_get_option( 'primary-menu-spacing' );
	$primary_submenu_spacing      = astra_get_option( 'primary-submenu-spacing' );
	$above_header_spacing         = astra_get_option( 'above-header-spacing' );
	$above_header_menu_spacing    = astra_get_option( 'above-header-menu-spacing' );
	$above_header_submenu_spacing = astra_get_option( 'above-header-submenu-spacing' );
	$below_header_spacing         = astra_get_option( 'below-header-spacing' );
	$below_header_menu_spacing    = astra_get_option( 'below-header-menu-spacing' );
	$below_header_submenu_spacing = astra_get_option( 'below-header-submenu-spacing' );
	$sidebar_outside_spacing      = astra_get_option( 'sidebar-outside-spacing' );
	$sidebar_inside_spacing       = astra_get_option( 'sidebar-inside-spacing' );
	$astra_footer_width           = astra_get_option( 'footer-layout-width' );
	$footer_spacing               = astra_get_option( 'footer-sml-spacing' );
	$footer_menu_spacing          = astra_get_option( 'footer-menu-spacing' );
	$site_content_layout          = astra_get_option( 'site-content-layout' );
	$header_content_layout        = astra_get_option( 'header-main-layout-width' );
	$header_layouts               = astra_get_option( 'header-layouts' );

	// Sticky header.
	$stick_header_main      = astra_get_option( 'header-main-stick' );
	$stick_header_main_meta = astra_get_option_meta( 'header-main-stick-meta' );

	// Blog Grid spacing.
	$blog_grid   = astra_get_option( 'blog-grid' );
	$blog_layout = astra_get_option( 'blog-layout' );

	$blog_post_outside_spacing    = astra_get_option( 'blog-post-outside-spacing' );
	$blog_post_inside_spacing     = astra_get_option( 'blog-post-inside-spacing' );
	$blog_post_pagination_spacing = astra_get_option( 'blog-post-pagination-spacing' );

	$blog_featured_image_padding    = astra_get_option( 'blog-featured-image-padding' );
	$remove_single_featured_padding = astra_get_option( 'single-featured-image-padding' );
	// Desktop Spacing.
	$spacing = array(

		/**
		 * Site Identity Spacing
		 */
		'.site-header .ast-site-identity'  => array(
			'padding-top'    => astra_responsive_spacing( $site_identity_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $site_identity_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $site_identity_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $site_identity_spacing, 'left', 'desktop' ),
		),
		/**
		 * Header Spacing
		 */
		// Header Spacing Top / Bottom Padding.
		'.main-header-bar, .ast-header-break-point .main-header-bar, .ast-header-break-point .header-main-layout-2 .main-header-bar' => array(
			'padding-top'    => astra_responsive_spacing( $header_spacing, 'top', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $header_spacing, 'bottom', 'desktop' ),
		),
		// Header Spacing Left / Right Padding apply to inside container.
		'.main-header-bar .ast-container'  => array(
			'padding-left'  => astra_responsive_spacing( $header_spacing, 'left', 'desktop' ),
			'padding-right' => astra_responsive_spacing( $header_spacing, 'right', 'desktop' ),
		),

		/**
		 * Main Menu Spacing
		 */
		'.main-navigation ul li a, .ast-header-break-point .main-navigation ul li a, .ast-header-break-point li.ast-masthead-custom-menu-items, li.ast-masthead-custom-menu-items' => array(
			'padding-top'    => astra_responsive_spacing( $primary_menu_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $primary_menu_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $primary_menu_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $primary_menu_spacing, 'left', 'desktop' ),
		),

		/**
		 * Main Submenu Spacing
		 */
		'.main-header-menu ul a, .ast-header-break-point .main-navigation ul.sub-menu li a, .ast-header-break-point .main-navigation ul.children li a' => array(
			'padding-top'    => astra_responsive_spacing( $primary_submenu_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $primary_submenu_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $primary_submenu_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $primary_submenu_spacing, 'left', 'desktop' ),
		),

		/**
		 * Below Header Spacing
		 */
		// Below Header Spacing Top / Bottom Padding.
		'.ast-below-header, .ast-header-break-point .ast-below-header' => array(
			'padding-top'    => astra_responsive_spacing( $below_header_spacing, 'top', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $below_header_spacing, 'bottom', 'desktop' ),
		),
		// Below Header Spacing Left / Right Padding apply to inside container.
		'.ast-below-header .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $below_header_spacing, 'left', 'desktop' ),
			'padding-right' => astra_responsive_spacing( $below_header_spacing, 'right', 'desktop' ),
		),
		/**
		 * Below Header Menu Spacing
		 */
		'.ast-below-header-menu a, .below-header-nav-padding-support .below-header-section-1 .below-header-menu > li > a, .below-header-nav-padding-support .below-header-section-2 .below-header-menu > li > a, .ast-header-break-point .ast-below-header-actual-nav > ul > li > a' => array(
			'padding-top'    => astra_responsive_spacing( $below_header_menu_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $below_header_menu_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $below_header_menu_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $below_header_menu_spacing, 'left', 'desktop' ),
		),
		/**
		 * Below Header Submenu Spacing
		 */
		'.ast-below-header-menu ul a, .ast-header-break-point .ast-below-header-actual-nav > ul ul li a' => array(
			'padding-top'    => astra_responsive_spacing( $below_header_submenu_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $below_header_submenu_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $below_header_submenu_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $below_header_submenu_spacing, 'left', 'desktop' ),
		),

		/**
		 * Above Header Spacing
		 */
		// Above Header Spacing Top / Bottom Padding.
		'.ast-above-header'                => array(
			'padding-top'    => astra_responsive_spacing( $above_header_spacing, 'top', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $above_header_spacing, 'bottom', 'desktop' ),
		),
		// Above Header Spacing Left / Right Padding apply to inside container.
		'.ast-above-header .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $above_header_spacing, 'left', 'desktop' ),
			'padding-right' => astra_responsive_spacing( $above_header_spacing, 'right', 'desktop' ),
		),
		/**
		 * Above Header Menu Spacing
		 */
		'.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu > li > a, .above-header-nav-padding-support .ast-justify-content-flex-end .ast-above-header-menu > li > a,.above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu > li:first-child > a, .above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu > li:last-child > a' => array(
			'padding-top'    => astra_responsive_spacing( $above_header_menu_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $above_header_menu_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $above_header_menu_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $above_header_menu_spacing, 'left', 'desktop' ),
		),

		/**
		 * Above Header Submenu Spacing
		 */
		'.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu li ul a, .above-header-nav-padding-support .ast-justify-content-flex-end .ast-above-header-menu li ul a, .above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu li ul.sub-menu a,.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu > li:first-child .sub-menu li a' => array(
			'padding-top'    => astra_responsive_spacing( $above_header_submenu_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $above_header_submenu_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $above_header_submenu_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $above_header_submenu_spacing, 'left', 'desktop' ),
		),

		/**
		 * Content Spacing
		 */
		'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single, .ast-separate-container .ast-comment-list li.depth-1, .ast-separate-container .comment-respond, .single.ast-separate-container .ast-author-details, .ast-separate-container .ast-related-posts-wrap, .ast-separate-container .ast-woocommerce-container' => array(
			'padding-top'    => astra_responsive_spacing( $container_inside_spacing, 'top', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $container_inside_spacing, 'bottom', 'desktop' ),
		),
		'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single,.ast-separate-container .comments-count-wrapper, .ast-separate-container .ast-comment-list li.depth-1, .ast-separate-container .comment-respond,.ast-separate-container .related-posts-title-wrapper,.ast-separate-container .related-posts-title-wrapper, .single.ast-separate-container .ast-author-details, .single.ast-separate-container .about-author-title-wrapper, .ast-separate-container .ast-related-posts-wrap, .ast-separate-container .ast-woocommerce-container' => array(
			'padding-right' => astra_responsive_spacing( $container_inside_spacing, 'right', 'desktop' ),
			'padding-left'  => astra_responsive_spacing( $container_inside_spacing, 'left', 'desktop' ),
		),

		'.ast-separate-container.ast-right-sidebar #primary, .ast-separate-container.ast-left-sidebar #primary, .ast-separate-container #primary, .ast-plain-container #primary' => array(
			'margin-top'    => astra_responsive_spacing( $continder_outside_spacing, 'top', 'desktop' ),
			'margin-bottom' => astra_responsive_spacing( $continder_outside_spacing, 'bottom', 'desktop' ),
		),
		'.ast-left-sidebar #primary, .ast-right-sidebar #primary, .ast-separate-container.ast-right-sidebar #primary, .ast-separate-container.ast-left-sidebar #primary, .ast-separate-container #primary' => array(
			'padding-left'  => astra_responsive_spacing( $continder_outside_spacing, 'left', 'desktop' ),
			'padding-right' => astra_responsive_spacing( $continder_outside_spacing, 'right', 'desktop' ),
		),
		/**
		 * Sidebar Spacing
		 */
		'.ast-plain-container #secondary, .ast-separate-container #secondary, .ast-page-builder-template #secondary' => array(
			'margin-top'    => astra_responsive_spacing( $sidebar_outside_spacing, 'top', 'desktop' ),
			'margin-bottom' => astra_responsive_spacing( $sidebar_outside_spacing, 'bottom', 'desktop' ),
		),
		'.ast-right-sidebar #secondary, .ast-left-sidebar #secondary, .ast-separate-container.ast-two-container.ast-left-sidebar #secondary, .ast-separate-container.ast-two-container.ast-right-sidebar #secondary, .ast-separate-container.ast-right-sidebar #secondary, .ast-separate-container.ast-left-sidebar #secondary' => array(
			'padding-left'  => astra_responsive_spacing( $sidebar_outside_spacing, 'left', 'desktop' ),
			'padding-right' => astra_responsive_spacing( $sidebar_outside_spacing, 'right', 'desktop' ),
		),
		// Sidebar Inside Spacing Top / Bottom / Left / Right Padding.
		'.ast-separate-container.ast-two-container #secondary .widget, .ast-separate-container #secondary .widget, .ast-plain-container #secondary .widget' => array(
			'padding-top'    => astra_responsive_spacing( $sidebar_inside_spacing, 'top', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $sidebar_inside_spacing, 'bottom', 'desktop' ),
		),
		'.ast-two-container.ast-right-sidebar #secondary .widget, .ast-two-container.ast-left-sidebar #secondary .widget, .ast-separate-container #secondary .widget, .ast-plain-container #secondary .widget' => array(
			'padding-left'  => astra_responsive_spacing( $sidebar_inside_spacing, 'left', 'desktop' ),
			'padding-right' => astra_responsive_spacing( $sidebar_inside_spacing, 'right', 'desktop' ),
		),

		/**
		 * Footer Spacing
		 */
		// Footer Spacing Top / Bottom Padding.
		'.ast-footer-overlay'              => array(
			'padding-top'    => astra_responsive_spacing( $footer_spacing, 'top', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $footer_spacing, 'bottom', 'desktop' ),
		),
		// Footer Spacing Left / Right Padding to inside container.
		'.ast-small-footer .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $footer_spacing, 'left', 'desktop' ),
			'padding-right' => astra_responsive_spacing( $footer_spacing, 'right', 'desktop' ),
		),

		/**
		 * Footer Menu Spacing
		 */
		'.ast-small-footer .nav-menu a, .footer-sml-layout-2 .ast-small-footer-section-1 .menu-item a, .footer-sml-layout-2 .ast-small-footer-section-2 .menu-item a' => array(
			'padding-top'    => astra_responsive_spacing( $footer_menu_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $footer_menu_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $footer_menu_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $footer_menu_spacing, 'left', 'desktop' ),
		),

		/**
		 * Blog Grid Spacing
		 */
		// Blog Grid Outside Spacing.
		'.ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+1)' => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_outside_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_outside_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_outside_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_outside_spacing, 'left', 'desktop' ),
		),
		// Blog Grid Inside Spacing.
		'.ast-separate-container .ast-grid-2 .blog-layout-1, .ast-separate-container .ast-grid-2 .blog-layout-2, .ast-separate-container .ast-grid-2 .blog-layout-3, .ast-separate-container .ast-grid-3 .blog-layout-1, .ast-separate-container .ast-grid-3 .blog-layout-2, .ast-separate-container .ast-grid-3 .blog-layout-3, .ast-separate-container .ast-grid-4 .blog-layout-1, .ast-separate-container .ast-grid-4 .blog-layout-2, .ast-separate-container .ast-grid-4 .blog-layout-3' => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'desktop' ),
		),
		// Blog Pagination Spacing.
		'.ast-pagination'                  => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_pagination_spacing, 'top', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_pagination_spacing, 'right', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_pagination_spacing, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_pagination_spacing, 'left', 'desktop' ),
		),

	);

	$spacing_css_output = astra_parse_css( $spacing );

	$tablet_spacing = array(

		/**
		 * Site Identity Spacing
		 */
		'.site-header .ast-site-identity'  => array(
			'padding-top'    => astra_responsive_spacing( $site_identity_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $site_identity_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $site_identity_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $site_identity_spacing, 'left', 'tablet' ),
		),
		/**
		 * Header Tablet Spacing
		 */
		// Header Spacing Top / Bottom Padding.
		'.main-header-bar, .ast-header-break-point .main-header-bar, .ast-header-break-point .header-main-layout-2 .main-header-bar' => array(
			'padding-top'    => astra_responsive_spacing( $header_spacing, 'top', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $header_spacing, 'bottom', 'tablet' ),
		),
		// Header Spacing Left / Right Padding apply to inside container.
		'.main-header-bar .ast-container, #masthead .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $header_spacing, 'left', 'tablet' ),
			'padding-right' => astra_responsive_spacing( $header_spacing, 'right', 'tablet' ),
		),

		/**
		 * Main Menu Spacing
		 */
		'.main-navigation ul li a, .ast-header-break-point .main-navigation ul li a, .ast-header-break-point li.ast-masthead-custom-menu-items, li.ast-masthead-custom-menu-items' => array(
			'padding-top'    => astra_responsive_spacing( $primary_menu_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $primary_menu_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $primary_menu_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $primary_menu_spacing, 'left', 'tablet' ),
		),

		/**
		 * Main Submenu Spacing
		 */
		'.main-header-menu ul a, .ast-header-break-point .main-navigation ul.sub-menu li a, .ast-header-break-point .main-navigation ul.children li a' => array(
			'padding-top'    => astra_responsive_spacing( $primary_submenu_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $primary_submenu_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $primary_submenu_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $primary_submenu_spacing, 'left', 'tablet' ),
		),

		/**
		 * Below Header Spacing
		 */
		// Below Header Spacing Top / Bottom Padding.
		'.ast-below-header, .ast-header-break-point .ast-below-header' => array(
			'padding-top'    => astra_responsive_spacing( $below_header_spacing, 'top', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $below_header_spacing, 'bottom', 'tablet' ),
		),
		// Below Header Spacing Left / Right Padding apply to inside container.
		'.ast-below-header .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $below_header_spacing, 'left', 'tablet' ),
			'padding-right' => astra_responsive_spacing( $below_header_spacing, 'right', 'tablet' ),
		),
		/**
		 * Below Header Menu Spacing
		 */
		'.ast-below-header-menu a, .below-header-nav-padding-support .below-header-section-1 .below-header-menu > li > a, .below-header-nav-padding-support .below-header-section-2 .below-header-menu > li > a, .ast-header-break-point .ast-below-header-actual-nav > ul > li > a' => array(
			'padding-top'    => astra_responsive_spacing( $below_header_menu_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $below_header_menu_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $below_header_menu_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $below_header_menu_spacing, 'left', 'tablet' ),
		),
		/**
		 * Below Header Submenu Spacing
		 */
		'.ast-below-header-menu ul a, .ast-header-break-point .ast-below-header-actual-nav > ul ul li a' => array(
			'padding-top'    => astra_responsive_spacing( $below_header_submenu_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $below_header_submenu_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $below_header_submenu_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $below_header_submenu_spacing, 'left', 'tablet' ),
		),

		/**
		 * Above Header Spacing
		 */
		// Above Header Spacing Top / Bottom Padding.
		'.ast-above-header'                => array(
			'padding-top'    => astra_responsive_spacing( $above_header_spacing, 'top', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $above_header_spacing, 'bottom', 'tablet' ),
		),
		// Above Header Spacing Left / Right Padding apply to inside container.
		'.ast-above-header .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $above_header_spacing, 'left', 'tablet' ),
			'padding-right' => astra_responsive_spacing( $above_header_spacing, 'right', 'tablet' ),
		),
		/**
		 * Above Header Menu Spacing
		 */
		'.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu > li > a, .above-header-nav-padding-support .ast-justify-content-flex-end .ast-above-header-menu > li > a,.above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu > li:first-child > a, .above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu > li:last-child > a' => array(
			'padding-top'    => astra_responsive_spacing( $above_header_menu_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $above_header_menu_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $above_header_menu_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $above_header_menu_spacing, 'left', 'tablet' ),
		),

		/**
		 * Above Header Submenu Spacing
		 */
		'.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu li ul a, .above-header-nav-padding-support .ast-justify-content-flex-end .ast-above-header-menu li ul a, .above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu li ul.sub-menu a,.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu > li:first-child .sub-menu li a' => array(
			'padding-top'    => astra_responsive_spacing( $above_header_submenu_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $above_header_submenu_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $above_header_submenu_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $above_header_submenu_spacing, 'left', 'tablet' ),
		),

		/**
		 * Content Spacing
		 */
		'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single, .ast-separate-container .ast-comment-list li.depth-1, .ast-separate-container .comment-respond, .single.ast-separate-container .ast-author-details, .ast-separate-container .ast-related-posts-wrap, .ast-separate-container .ast-woocommerce-container' => array(
			'padding-top'    => astra_responsive_spacing( $container_inside_spacing, 'top', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $container_inside_spacing, 'bottom', 'tablet' ),
		),
		'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single,.ast-separate-container .comments-count-wrapper, .ast-separate-container .ast-comment-list li.depth-1, .ast-separate-container .comment-respond,.ast-separate-container .related-posts-title-wrapper,.ast-separate-container .related-posts-title-wrapper, .single.ast-separate-container .ast-author-details, .single.ast-separate-container .about-author-title-wrapper, .ast-separate-container .ast-related-posts-wrap, .ast-separate-container .ast-woocommerce-container' => array(
			'padding-right' => astra_responsive_spacing( $container_inside_spacing, 'right', 'tablet' ),
			'padding-left'  => astra_responsive_spacing( $container_inside_spacing, 'left', 'tablet' ),
		),

		'.ast-separate-container.ast-right-sidebar #primary, .ast-separate-container.ast-left-sidebar #primary, .ast-separate-container #primary, .ast-plain-container #primary' => array(
			'margin-top'    => astra_responsive_spacing( $continder_outside_spacing, 'top', 'tablet' ),
			'margin-bottom' => astra_responsive_spacing( $continder_outside_spacing, 'bottom', 'tablet' ),
		),
		'.ast-left-sidebar #primary, .ast-right-sidebar #primary, .ast-separate-container.ast-right-sidebar #primary, .ast-separate-container.ast-left-sidebar #primary, .ast-separate-container #primary' => array(
			'padding-left'  => astra_responsive_spacing( $continder_outside_spacing, 'left', 'tablet' ),
			'padding-right' => astra_responsive_spacing( $continder_outside_spacing, 'right', 'tablet' ),
		),

		/**
		 * Sidebar Spacing
		 */
		// Sidebar Spacing Top / Bottom Padding.
		'.ast-plain-container #secondary,.ast-separate-container #secondary, .ast-page-builder-template #secondary' => array(
			'margin-top'    => astra_responsive_spacing( $sidebar_outside_spacing, 'top', 'tablet' ),
			'margin-bottom' => astra_responsive_spacing( $sidebar_outside_spacing, 'bottom', 'tablet' ),
		),
		'.ast-right-sidebar #secondary, .ast-left-sidebar #secondary, .ast-separate-container.ast-two-container.ast-left-sidebar #secondary, .ast-separate-container.ast-two-container.ast-right-sidebar #secondary, .ast-separate-container.ast-right-sidebar #secondary, .ast-separate-container.ast-left-sidebar #secondary' => array(
			'padding-left'  => astra_responsive_spacing( $sidebar_outside_spacing, 'left', 'tablet' ),
			'padding-right' => astra_responsive_spacing( $sidebar_outside_spacing, 'right', 'tablet' ),
		),
		// Sidebar Inside Spacing Top / Bottom / Left / Right Padding.
		'.ast-separate-container.ast-two-container #secondary .widget,  .ast-separate-container #secondary .widget, .ast-plain-container #secondary .widget' => array(
			'padding-top'    => astra_responsive_spacing( $sidebar_inside_spacing, 'top', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $sidebar_inside_spacing, 'bottom', 'tablet' ),
		),
		'.ast-two-container.ast-right-sidebar #secondary .widget, .ast-two-container.ast-left-sidebar #secondary .widget, .ast-separate-container #secondary .widget, .ast-plain-container #secondary .widget' => array(
			'padding-left'  => astra_responsive_spacing( $sidebar_inside_spacing, 'left', 'tablet' ),
			'padding-right' => astra_responsive_spacing( $sidebar_inside_spacing, 'right', 'tablet' ),
		),

		/**
		 * Footer Spacing
		 */
		// Footer Spacing Top / Bottom Padding.
		'.ast-footer-overlay'              => array(
			'padding-top'    => astra_responsive_spacing( $footer_spacing, 'top', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $footer_spacing, 'bottom', 'tablet' ),
		),
		// Footer Spacing Left / Right Padding to inside container.
		'.ast-small-footer .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $footer_spacing, 'left', 'tablet' ),
			'padding-right' => astra_responsive_spacing( $footer_spacing, 'right', 'tablet' ),
		),

		/**
		 * Footer Menu Spacing
		 */
		'.ast-small-footer .nav-menu a, .footer-sml-layout-2 .ast-small-footer-section-1 .menu-item a, .footer-sml-layout-2 .ast-small-footer-section-2 .menu-item a' => array(
			'padding-top'    => astra_responsive_spacing( $footer_menu_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $footer_menu_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $footer_menu_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $footer_menu_spacing, 'left', 'tablet' ),
		),

		/**
		 * Blog Grid Spacing
		 */
		// Blog Grid Outside Spacing.
		'.ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+1)' => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_outside_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_outside_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_outside_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_outside_spacing, 'left', 'tablet' ),
		),
		// Blog Grid Inside Spacing.
		'.ast-separate-container .ast-grid-2 .blog-layout-1, .ast-separate-container .ast-grid-2 .blog-layout-2, .ast-separate-container .ast-grid-2 .blog-layout-3, .ast-separate-container .ast-grid-3 .blog-layout-1, .ast-separate-container .ast-grid-3 .blog-layout-2, .ast-separate-container .ast-grid-3 .blog-layout-3, .ast-separate-container .ast-grid-4 .blog-layout-1, .ast-separate-container .ast-grid-4 .blog-layout-2, .ast-separate-container .ast-grid-4 .blog-layout-3' => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'tablet' ),
		),
		// Blog Pagination Spacing.
		'.ast-pagination'                  => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_pagination_spacing, 'top', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_pagination_spacing, 'right', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_pagination_spacing, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_pagination_spacing, 'left', 'tablet' ),
		),

	);

	$spacing_css_output .= astra_parse_css( $tablet_spacing, '', '768' );

	$mobile_spacing = array(

		/**
		 * Site Identity Spacing
		 */
		'.site-header .ast-site-identity'  => array(
			'padding-top'    => astra_responsive_spacing( $site_identity_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $site_identity_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $site_identity_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $site_identity_spacing, 'left', 'mobile' ),
		),
		/**
		 * Header Mobile Spacing
		 */
		// Header Spacing Top / Bottom Padding.
		'.main-header-bar, .ast-header-break-point .main-header-bar, .ast-header-break-point .header-main-layout-2 .main-header-bar, .ast-header-break-point .ast-mobile-header-stack .main-header-bar' => array(
			'padding-top'    => astra_responsive_spacing( $header_spacing, 'top', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $header_spacing, 'bottom', 'mobile' ),
		),
		// Header Spacing Left / Right Padding apply to inside container.
		'.main-header-bar .ast-container, #masthead .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $header_spacing, 'left', 'mobile' ),
			'padding-right' => astra_responsive_spacing( $header_spacing, 'right', 'mobile' ),
		),

		/**
		 * Main Menu Spacing
		 */
		'.main-navigation ul li a, .ast-header-break-point .main-navigation ul li a, .ast-header-break-point li.ast-masthead-custom-menu-items, li.ast-masthead-custom-menu-items' => array(
			'padding-top'    => astra_responsive_spacing( $primary_menu_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $primary_menu_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $primary_menu_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $primary_menu_spacing, 'left', 'mobile' ),
		),

		/**
		 * Main Submenu Spacing
		 */
		'.main-header-menu ul a, .ast-header-break-point .main-navigation ul.sub-menu li a, .ast-header-break-point .main-navigation ul.children li a' => array(
			'padding-top'    => astra_responsive_spacing( $primary_submenu_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $primary_submenu_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $primary_submenu_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $primary_submenu_spacing, 'left', 'mobile' ),
		),

		/**
		 * Below Header Spacing
		 */
		// Below Header Spacing Top / Bottom Padding.
		'.ast-below-header, .ast-header-break-point .ast-below-header' => array(
			'padding-top'    => astra_responsive_spacing( $below_header_spacing, 'top', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $below_header_spacing, 'bottom', 'mobile' ),
		),
		// Below Header Spacing Left / Right Padding apply to inside container.
		'.ast-below-header .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $below_header_spacing, 'left', 'mobile' ),
			'padding-right' => astra_responsive_spacing( $below_header_spacing, 'right', 'mobile' ),
		),
		/**
		 * Below Header Menu Spacing
		 */
		'.ast-below-header-menu a, .below-header-nav-padding-support .below-header-section-1 .below-header-menu > li > a, .below-header-nav-padding-support .below-header-section-2 .below-header-menu > li > a, .ast-header-break-point .ast-below-header-actual-nav > ul > li > a' => array(
			'padding-top'    => astra_responsive_spacing( $below_header_menu_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $below_header_menu_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $below_header_menu_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $below_header_menu_spacing, 'left', 'mobile' ),
		),
		/**
		 * Below Header Submenu Spacing
		 */
		'.ast-below-header-menu ul a, .ast-header-break-point .ast-below-header-actual-nav > ul ul li a' => array(
			'padding-top'    => astra_responsive_spacing( $below_header_submenu_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $below_header_submenu_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $below_header_submenu_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $below_header_submenu_spacing, 'left', 'mobile' ),
		),

		/**
		 * Above Header Spacing
		 */
		// Above Header Spacing Top / Bottom Padding.
		'.ast-above-header'                => array(
			'padding-top'    => astra_responsive_spacing( $above_header_spacing, 'top', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $above_header_spacing, 'bottom', 'mobile' ),
		),
		// Above Header Spacing Left / Right Padding apply to inside container.
		'.ast-above-header .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $above_header_spacing, 'left', 'mobile' ),
			'padding-right' => astra_responsive_spacing( $above_header_spacing, 'right', 'mobile' ),
		),
		/**
		 * Above Header Menu Spacing
		 */
		'.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu > li > a, .above-header-nav-padding-support .ast-justify-content-flex-end .ast-above-header-menu > li > a,.above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu > li:first-child > a, .above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu > li:last-child > a' => array(
			'padding-top'    => astra_responsive_spacing( $above_header_menu_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $above_header_menu_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $above_header_menu_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $above_header_menu_spacing, 'left', 'mobile' ),
		),

		/**
		 * Above Header Submenu Spacing
		 */
		'.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu li ul a, .above-header-nav-padding-support .ast-justify-content-flex-end .ast-above-header-menu li ul a, .above-header-nav-padding-support.ast-header-break-point .ast-above-header-menu li ul.sub-menu a,.above-header-nav-padding-support .ast-justify-content-flex-start .ast-above-header-menu > li:first-child .sub-menu li a' => array(
			'padding-top'    => astra_responsive_spacing( $above_header_submenu_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $above_header_submenu_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $above_header_submenu_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $above_header_submenu_spacing, 'left', 'mobile' ),
		),
		/**
		 * Content Spacing
		 */
		'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single, .ast-separate-container .ast-comment-list li.depth-1, .ast-separate-container .comment-respond, .single.ast-separate-container .ast-author-details, .ast-separate-container .ast-related-posts-wrap, .ast-separate-container .ast-woocommerce-container' => array(
			'padding-top'    => astra_responsive_spacing( $container_inside_spacing, 'top', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $container_inside_spacing, 'bottom', 'mobile' ),
		),
		'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-single,.ast-separate-container .comments-count-wrapper, .ast-separate-container .ast-comment-list li.depth-1, .ast-separate-container .comment-respond,.ast-separate-container .related-posts-title-wrapper,.ast-separate-container .related-posts-title-wrapper, .single.ast-separate-container .ast-author-details, .single.ast-separate-container .about-author-title-wrapper, .ast-separate-container .ast-related-posts-wrap, .ast-separate-container .ast-woocommerce-container' => array(
			'padding-right' => astra_responsive_spacing( $container_inside_spacing, 'right', 'mobile' ),
			'padding-left'  => astra_responsive_spacing( $container_inside_spacing, 'left', 'mobile' ),
		),

		'.ast-separate-container.ast-right-sidebar #primary, .ast-separate-container.ast-left-sidebar #primary, .ast-separate-container #primary, .ast-plain-container #primary' => array(
			'margin-top'    => astra_responsive_spacing( $continder_outside_spacing, 'top', 'mobile' ),
			'margin-bottom' => astra_responsive_spacing( $continder_outside_spacing, 'bottom', 'mobile' ),
		),
		'.ast-left-sidebar #primary, .ast-right-sidebar #primary, .ast-separate-container.ast-right-sidebar #primary, .ast-separate-container.ast-left-sidebar #primary, .ast-separate-container #primary' => array(
			'padding-left'  => astra_responsive_spacing( $continder_outside_spacing, 'left', 'mobile' ),
			'padding-right' => astra_responsive_spacing( $continder_outside_spacing, 'right', 'mobile' ),
		),

		/**
		 * Sidebar Spacing
		 */
		// Sidebar Spacing Top / Bottom Padding.
		'.ast-plain-container #secondary,.ast-separate-container #secondary, .ast-page-builder-template #secondary' => array(
			'margin-top'    => astra_responsive_spacing( $sidebar_outside_spacing, 'top', 'mobile' ),
			'margin-bottom' => astra_responsive_spacing( $sidebar_outside_spacing, 'bottom', 'mobile' ),
		),
		'.ast-right-sidebar #secondary, .ast-left-sidebar #secondary, .ast-separate-container.ast-two-container.ast-left-sidebar #secondary, .ast-separate-container.ast-two-container.ast-right-sidebar #secondary, .ast-separate-container.ast-right-sidebar #secondary, .ast-separate-container.ast-left-sidebar #secondary' => array(
			'padding-left'  => astra_responsive_spacing( $sidebar_outside_spacing, 'left', 'mobile' ),
			'padding-right' => astra_responsive_spacing( $sidebar_outside_spacing, 'right', 'mobile' ),
		),
		// Sidebar Inside Spacing Top / Bottom / Left / Right Padding.
		'.ast-separate-container.ast-two-container #secondary .widget, .ast-separate-container #secondary .widget, .ast-plain-container #secondary .widget' => array(
			'padding-top'    => astra_responsive_spacing( $sidebar_inside_spacing, 'top', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $sidebar_inside_spacing, 'bottom', 'mobile' ),
		),
		'.ast-two-container.ast-right-sidebar #secondary .widget, .ast-two-container.ast-left-sidebar #secondary .widget, .ast-separate-container #secondary .widget, .ast-plain-container #secondary .widget' => array(
			'padding-left'  => astra_responsive_spacing( $sidebar_inside_spacing, 'left', 'mobile' ),
			'padding-right' => astra_responsive_spacing( $sidebar_inside_spacing, 'right', 'mobile' ),
		),

		/**
		 * Footer Spacing
		 */
		// Footer Spacing Top / Bottom Padding.
		'.ast-footer-overlay'              => array(
			'padding-top'    => astra_responsive_spacing( $footer_spacing, 'top', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $footer_spacing, 'bottom', 'mobile' ),
		),
		// Footer Spacing Left / Right Padding to inside container.
		'.ast-small-footer .ast-container' => array(
			'padding-left'  => astra_responsive_spacing( $footer_spacing, 'left', 'mobile' ),
			'padding-right' => astra_responsive_spacing( $footer_spacing, 'right', 'mobile' ),
		),

		/**
		 * Footer Menu Spacing
		 */
		'.ast-small-footer .nav-menu a, .footer-sml-layout-2 .ast-small-footer-section-1 .menu-item a, .footer-sml-layout-2 .ast-small-footer-section-2 .menu-item a' => array(
			'padding-top'    => astra_responsive_spacing( $footer_menu_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $footer_menu_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $footer_menu_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $footer_menu_spacing, 'left', 'mobile' ),
		),

		/**
		 * Blog Grid Spacing
		 */
		// Blog Grid Outside Spacing.
		'.ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-2 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-3 .ast-article-post.ast-separate-posts:nth-child(2n+1), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+0), .ast-separate-container .ast-grid-4 .ast-article-post.ast-separate-posts:nth-child(2n+1)' => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_outside_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_outside_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_outside_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_outside_spacing, 'left', 'mobile' ),
		),
		// Blog Grid Inside Spacing.
		'.ast-separate-container .ast-grid-2 .blog-layout-1, .ast-separate-container .ast-grid-2 .blog-layout-2, .ast-separate-container .ast-grid-2 .blog-layout-3, .ast-separate-container .ast-grid-3 .ast-article-post .blog-layout-1, .ast-separate-container .ast-grid-3 .blog-layout-2, .ast-separate-container .ast-grid-3 .blog-layout-3, .ast-separate-container .ast-grid-4 .ast-article-post .blog-layout-1, .ast-separate-container .ast-grid-4 .blog-layout-2, .ast-separate-container .ast-grid-4 .blog-layout-3' => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'mobile' ),
		),
		// Blog Pagination Spacing.
		'.ast-pagination'                  => array(
			'padding-top'    => astra_responsive_spacing( $blog_post_pagination_spacing, 'top', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $blog_post_pagination_spacing, 'right', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $blog_post_pagination_spacing, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $blog_post_pagination_spacing, 'left', 'mobile' ),
		),
	);

	$spacing_css_output .= astra_parse_css( $mobile_spacing, '', '544' );

	/**
	 * Header Desktop/Tablet/Mobile Spacing
	 */
	// Remove padding bottom to header's Menu Toggle button which is static.
	// Only if any of the bottom ( desktop, tablet, mobile ) spacing is given.
	$remove_bottom_header = array(
		'.ast-header-break-point .site-branding, .ast-header-break-point .ast-mobile-menu-buttons, .ast-header-break-point.ast-header-custom-item-outside .ast-masthead-custom-menu-items, .ast-header-break-point .header-main-layout-2 .ast-mobile-menu-buttons'                    => array(
			'padding-bottom' => astra_get_css_value( 0, 'px' ),
		),
		// Add padding top to header navigation for responsive devices.
		'.ast-header-break-point .main-header-bar .main-header-bar-navigation'  => array(
			'padding-top' => astra_get_css_value( 1, 'em' ),
		),
	);

	$remove_bottom_sire_brancing = array(
		'.ast-header-break-point .header-main-layout-2 .site-branding, .ast-header-break-point .ast-mobile-header-stack .ast-mobile-menu-buttons'                    => array(
			'padding-bottom' => astra_get_css_value( 0, 'px' ),
		),
	);

	if ( isset( $header_spacing['desktop']['bottom'] ) && ( '' != $header_spacing['desktop']['bottom'] ) ) {
		$spacing_css_output .= astra_parse_css( $remove_bottom_header );
	}
	if ( isset( $header_spacing['tablet']['bottom'] ) && ( '' != $header_spacing['tablet']['bottom'] ) ) {
		$spacing_css_output .= astra_parse_css( $remove_bottom_header, '', '768' );
	}
	if ( isset( $header_spacing['mobile']['bottom'] ) && ( '' != $header_spacing['mobile']['bottom'] ) ) {
		$spacing_css_output .= astra_parse_css( $remove_bottom_header, '', '544' );
		$spacing_css_output .= astra_parse_css( $remove_bottom_sire_brancing, '', '544' );
	}

	/**
	 *
	 * Sidebar Desktop/Tablet/Mobile Spacing
	 */
	$remove_sidebar_widget_outside_margin_desktop = array(
		'.ast-separate-container.ast-two-container #secondary .widget, .ast-separate-container #secondary .widget'                    => array(
			'margin-bottom' => astra_responsive_spacing( $sidebar_outside_spacing, 'bottom', 'desktop' ),
		),
	);
	$spacing_css_output                          .= astra_parse_css( $remove_sidebar_widget_outside_margin_desktop );

	// Tablet.
	$remove_sidebar_widget_outside_margin_tablet = array(
		'.ast-separate-container.ast-two-container #secondary .widget, .ast-separate-container #secondary .widget'                    => array(
			'margin-bottom' => astra_responsive_spacing( $sidebar_outside_spacing, 'bottom', 'tablet' ),
		),
	);
	$spacing_css_output                         .= astra_parse_css( $remove_sidebar_widget_outside_margin_tablet, '', '768' );

	// Mobile.
	$remove_sidebar_widget_outside_margin_mobile = array(
		'.ast-separate-container.ast-two-container #secondary .widget, .ast-separate-container #secondary .widget'                    => array(
			'margin-bottom' => astra_responsive_spacing( $sidebar_outside_spacing, 'bottom', 'mobile' ),
		),
	);
	$spacing_css_output                         .= astra_parse_css( $remove_sidebar_widget_outside_margin_mobile, '', '544' );

	/**
	 * Container Outer spacing
	 */
	// To apply Container Outside Spacing we need to remove default top padding given from the theme.
	$remove_top_padding_container = array(
		'.ast-separate-container #primary' => array(
			'padding-top' => astra_get_css_value( 0, 'px' ),
		),
	);
	if ( '' != $continder_outside_spacing['desktop']['top'] ) {
		$spacing_css_output .= astra_parse_css( $remove_top_padding_container );
	}
	if ( '' != $continder_outside_spacing['tablet']['top'] ) {
		$spacing_css_output .= astra_parse_css( $remove_top_padding_container, '', '768' );
	}
	if ( '' != $continder_outside_spacing['mobile']['top'] ) {
		$spacing_css_output .= astra_parse_css( $remove_top_padding_container, '', '544' );
	}

	// To apply Container Outside Spacing we need to remove default bottom padding given from the theme.
	$remove_bottom_padding_container = array(
		'.ast-separate-container #primary' => array(
			'padding-bottom' => astra_get_css_value( 0, 'px' ),
		),
	);
	if ( '' != $continder_outside_spacing['desktop']['top'] ) {
		$spacing_css_output .= astra_parse_css( $remove_bottom_padding_container );
	}
	if ( '' != $continder_outside_spacing['tablet']['top'] ) {
		$spacing_css_output .= astra_parse_css( $remove_bottom_padding_container, '', '768' );
	}
	if ( '' != $continder_outside_spacing['mobile']['top'] ) {
		$spacing_css_output .= astra_parse_css( $remove_bottom_padding_container, '', '544' );
	}

	/**
	 * Blog Grid Outer spacing
	 */
	if ( ( 1 == $blog_grid || 'blog-layout-2' === $blog_layout || 'blog-layout-3' === $blog_layout || ! Astra_Ext_Extension::is_active( 'blog-pro' ) ) ) {
		// Apply margin only if grid is selected 1 column.
		$single_column_margin_blog_pro = array(
			'.ast-separate-container .ast-article-post, .ast-separate-container .ast-separate-posts.ast-article-post' => array(
				'margin-top'    => astra_responsive_spacing( $blog_post_outside_spacing, 'top', 'desktop' ),
				'margin-right'  => astra_responsive_spacing( $blog_post_outside_spacing, 'right', 'desktop' ),
				'margin-bottom' => astra_responsive_spacing( $blog_post_outside_spacing, 'bottom', 'desktop' ),
				'margin-left'   => astra_responsive_spacing( $blog_post_outside_spacing, 'left', 'desktop' ),
			),
			'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-post' => array(
				'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'desktop' ),
				'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'desktop' ),
				'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'desktop' ),
				'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'desktop' ),
			),
		);
		$spacing_css_output           .= astra_parse_css( $single_column_margin_blog_pro );

		$single_column_margin_blog_pro_tablet = array(
			'.ast-separate-container .ast-article-post, .ast-separate-container .ast-separate-posts.ast-article-post' => array(
				'margin-top'    => astra_responsive_spacing( $blog_post_outside_spacing, 'top', 'tablet' ),
				'margin-right'  => astra_responsive_spacing( $blog_post_outside_spacing, 'right', 'tablet' ),
				'margin-bottom' => astra_responsive_spacing( $blog_post_outside_spacing, 'bottom', 'tablet' ),
				'margin-left'   => astra_responsive_spacing( $blog_post_outside_spacing, 'left', 'tablet' ),
			),
			'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-post' => array(
				'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'tablet' ),
				'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'tablet' ),
				'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'tablet' ),
				'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'tablet' ),
			),
		);
		$spacing_css_output                  .= astra_parse_css( $single_column_margin_blog_pro_tablet, '', '768' );

		$single_column_margin_blog_pro_mobile = array(
			'.ast-separate-container .ast-article-post, .ast-separate-container .ast-separate-posts.ast-article-post' => array(
				'margin-top'    => astra_responsive_spacing( $blog_post_outside_spacing, 'top', 'mobile' ),
				'margin-right'  => astra_responsive_spacing( $blog_post_outside_spacing, 'right', 'mobile' ),
				'margin-bottom' => astra_responsive_spacing( $blog_post_outside_spacing, 'bottom', 'mobile' ),
				'margin-left'   => astra_responsive_spacing( $blog_post_outside_spacing, 'left', 'mobile' ),
			),
			'.ast-separate-container .ast-article-post, .ast-separate-container .ast-article-post' => array(
				'padding-top'    => astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'mobile' ),
				'padding-right'  => astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'mobile' ),
				'padding-bottom' => astra_responsive_spacing( $blog_post_inside_spacing, 'bottom', 'mobile' ),
				'padding-left'   => astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'mobile' ),
			),
		);
		$spacing_css_output                  .= astra_parse_css( $single_column_margin_blog_pro_mobile, '', '544' );
	} else {
		// To apply Blog Grid Outside Spacing we need to remove default bottom margin given from the blog pro.
		$remove_bottom_margin_blog_pro = array(
			'.ast-separate-container .ast-separate-posts.ast-article-post' => array(
				'margin-bottom' => astra_get_css_value( 0, 'px' ),
			),
		);
		if ( '' != $blog_post_outside_spacing['desktop']['bottom'] ) {
			$spacing_css_output .= astra_parse_css( $remove_bottom_margin_blog_pro );
		}
		if ( '' != $blog_post_outside_spacing['tablet']['bottom'] ) {
			$spacing_css_output .= astra_parse_css( $remove_bottom_margin_blog_pro, '', '768' );
		}
		if ( '' != $blog_post_outside_spacing['mobile']['bottom'] ) {
			$spacing_css_output .= astra_parse_css( $remove_bottom_margin_blog_pro, '', '544' );
		}
	}

	/**
	 * Blog Pro Featured Image padding
	 */

	if ( $blog_featured_image_padding ) {
		$remove_featured_image_margin_top = array(
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section:first-child .square .posted-on' => array(
				'margin-top' => ( isset( $blog_post_inside_spacing['desktop']['top'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'desktop' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on' => array(
				'margin-left'  => ( isset( $blog_post_inside_spacing['desktop']['left'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'desktop' ) ) : '' ),
				'margin-right' => ( isset( $blog_post_inside_spacing['desktop']['right'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'desktop' ) ) : '' ),
			),
		);
		$spacing_css_output              .= astra_parse_css( $remove_featured_image_margin_top );

		$remove_featured_image_margin_top_tablet = array(
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content' => array(
				'margin-top' => ( isset( $blog_post_inside_spacing['tablet']['top'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'tablet' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on' => array(
				'margin-left'  => ( isset( $blog_post_inside_spacing['tablet']['left'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'tablet' ) ) : '' ),
				'margin-right' => ( isset( $blog_post_inside_spacing['tablet']['right'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'tablet' ) ) : '' ),
			),
		);
		$spacing_css_output                     .= astra_parse_css( $remove_featured_image_margin_top_tablet, '', '768' );

		$remove_featured_image_margin_top_mobile = array(
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-content .ast-blog-featured-section:first-child .post-thumb-img-content' => array(
				'margin-top' => ( isset( $blog_post_inside_spacing['mobile']['top'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'top', 'mobile' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content,.ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding .blog-layout-1 .post-thumb-img-content, .ast-separate-container.ast-blog-grid-2 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-3 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on, .ast-separate-container.ast-blog-grid-4 .ast-article-post.remove-featured-img-padding.has-post-thumbnail .blog-layout-1 .post-content .ast-blog-featured-section .square .posted-on' => array(
				'margin-left'  => ( isset( $blog_post_inside_spacing['mobile']['left'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'left', 'mobile' ) ) : '' ),
				'margin-right' => ( isset( $blog_post_inside_spacing['mobile']['right'] ) ? ( '-' . astra_responsive_spacing( $blog_post_inside_spacing, 'right', 'mobile' ) ) : '' ),
			),
		);
		$spacing_css_output                     .= astra_parse_css( $remove_featured_image_margin_top_mobile, '', '544' );
	}

	/**
	 * Single Blog Featured Image padding
	 */
	if ( $remove_single_featured_padding ) {
		$remove_single_featured_image_margin_top = array(
			'.ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child' => array(
				'margin-top' => ( isset( $container_inside_spacing['desktop']['top'] ) ? ( '-' . astra_responsive_spacing( $container_inside_spacing, 'top', 'desktop' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child, .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .post-thumb-img-content' => array(
				'margin-left'  => ( isset( $container_inside_spacing['desktop']['left'] ) ? ( '-' . astra_responsive_spacing( $container_inside_spacing, 'left', 'desktop' ) ) : '' ),
				'margin-right' => ( isset( $container_inside_spacing['desktop']['right'] ) ? ( '-' . astra_responsive_spacing( $container_inside_spacing, 'right', 'desktop' ) ) : '' ),
			),
		);
		$spacing_css_output                     .= astra_parse_css( $remove_single_featured_image_margin_top );

		$remove_single_featured_image_margin_top_tablet = array(
			'.ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child' => array(
				'margin-top' => ( isset( $container_inside_spacing['tablet']['top'] ) ? ( '-' . astra_responsive_spacing( $container_inside_spacing, 'top', 'tablet' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child, .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .post-thumb-img-content' => array(
				'margin-left'  => ( isset( $container_inside_spacing['tablet']['left'] ) ? ( '-' . astra_responsive_spacing( $container_inside_spacing, 'left', 'tablet' ) ) : '' ),
				'margin-right' => ( isset( $container_inside_spacing['tablet']['right'] ) ? ( '-' . astra_responsive_spacing( $container_inside_spacing, 'right', 'tablet' ) ) : '' ),
			),
		);
		$spacing_css_output                            .= astra_parse_css( $remove_single_featured_image_margin_top_tablet, '', '768' );

		$remove_single_featured_image_margin_top_mobile = array(
			'.ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child' => array(
				'margin-top' => ( isset( $container_inside_spacing['mobile']['top'] ) ? ( '-' . astra_responsive_spacing( $container_inside_spacing, 'top', 'mobile' ) ) : '' ),
			),
			'.ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .entry-header .post-thumb-img-content:first-child, .ast-separate-container .ast-article-single.remove-featured-img-padding .single-layout-1 .post-thumb-img-content' => array(
				'margin-left'  => ( isset( $container_inside_spacing['mobile']['left'] ) ? ( '-' . astra_responsive_spacing( $container_inside_spacing, 'left', 'mobile' ) ) : '' ),
				'margin-right' => ( isset( $container_inside_spacing['mobile']['right'] ) ? ( '-' . astra_responsive_spacing( $container_inside_spacing, 'right', 'mobile' ) ) : '' ),
			),
		);
		$spacing_css_output                            .= astra_parse_css( $remove_single_featured_image_margin_top_mobile, '', '544' );
	}

	/**
	 * Sticky Header - Shrink realted spacing
	 * Remove Top, Bottom spacing from Site Identity, Header Spacing, Primary Menu and Add default Sub menu top
	 */
	if ( Astra_Ext_Extension::is_active( 'sticky-header' ) && ( $stick_header_main || $stick_header_main_meta ) ) {
		/**
		 * Site Identity Spacing
		 */
		$remove_header_shrink_padding = array(
			'.site-header .ast-sticky-shrunk .ast-site-identity, .ast-sticky-shrunk .main-header-menu > li > a, .ast-sticky-shrunk li.ast-masthead-custom-menu-items' => array(
				'padding-top'    => astra_get_css_value( 0, 'px' ),
				'padding-bottom' => astra_get_css_value( 0, 'px' ),
			),
			'.ast-sticky-shrunk .main-header-menu ul a, .ast-header-break-point .main-navigation ul.sub-menu li a, .ast-header-break-point .main-navigation ul.children li a' => array(
				'padding-top'    => astra_get_css_value( 0.9, 'em' ),
				'padding-bottom' => astra_get_css_value( 0.9, 'em' ),
			),
		);

		$spacing_css_output .= astra_parse_css( $remove_header_shrink_padding );
	}

	return $dynamic_css . $spacing_css_output;
}
