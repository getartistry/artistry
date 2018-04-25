<?php
/**
 * Typography - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_typography_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_typography_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$body_font_family    = astra_body_font_family();
	$body_text_transform = astra_get_option( 'body-text-transform', 'inherit' );

	$headings_font_family    = astra_get_option( 'headings-font-family' );
	$headings_font_weight    = astra_get_option( 'headings-font-weight' );
	$headings_font_transform = astra_get_option( 'headings-text-transform', $body_text_transform );

	$site_title_font_family    = astra_get_option( 'font-family-site-title' );
	$site_title_font_weight    = astra_get_option( 'font-weight-site-title' );
	$site_title_line_height    = astra_get_option( 'line-height-site-title' );
	$site_title_text_transform = astra_get_option( 'text-transform-site-title', $headings_font_transform );

	$site_tagline_font_family    = astra_get_option( 'font-family-site-tagline' );
	$site_tagline_font_weight    = astra_get_option( 'font-weight-site-tagline' );
	$site_tagline_line_height    = astra_get_option( 'line-height-site-tagline' );
	$site_tagline_text_transform = astra_get_option( 'text-transform-site-tagline' );

	$primary_menu_font_size      = astra_get_option( 'font-size-primary-menu' );
	$primary_menu_font_weight    = astra_get_option( 'font-weight-primary-menu' );
	$primary_menu_font_family    = astra_get_option( 'font-family-primary-menu' );
	$primary_menu_line_height    = astra_get_option( 'line-height-primary-menu' );
	$primary_menu_text_transform = astra_get_option( 'text-transform-primary-menu' );

	$primary_dropdown_menu_font_size      = astra_get_option( 'font-size-primary-dropdown-menu' );
	$primary_dropdown_menu_font_weight    = astra_get_option( 'font-weight-primary-dropdown-menu' );
	$primary_dropdown_menu_font_family    = astra_get_option( 'font-family-primary-dropdown-menu' );
	$primary_dropdown_menu_line_height    = astra_get_option( 'line-height-primary-dropdown-menu' );
	$primary_dropdown_menu_text_transform = astra_get_option( 'text-transform-primary-dropdown-menu' );

	$single_entry_title_font_family    = astra_get_option( 'font-family-entry-title' );
	$single_entry_title_font_weight    = astra_get_option( 'font-weight-entry-title' );
	$single_entry_title_line_height    = astra_get_option( 'line-height-entry-title' );
	$single_entry_title_text_transform = astra_get_option( 'text-transform-entry-title', $headings_font_transform );

	$archive_summary_title_font_family    = astra_get_option( 'font-family-archive-summary-title' );
	$archive_summary_title_font_weight    = astra_get_option( 'font-weight-archive-summary-title' );
	$archive_summary_title_line_height    = astra_get_option( 'line-height-archive-summary-title' );
	$archive_summary_title_text_transform = astra_get_option( 'text-transform-archive-summary-title', $headings_font_transform );

	$archive_page_title_font_family    = astra_get_option( 'font-family-page-title' );
	$archive_page_title_font_weight    = astra_get_option( 'font-weight-page-title' );
	$archive_page_title_text_transform = astra_get_option( 'text-transform-page-title', $headings_font_transform );
	$archive_page_title_line_height    = astra_get_option( 'line-height-page-title' );

	$post_meta_font_size      = astra_get_option( 'font-size-post-meta' );
	$post_meta_font_family    = astra_get_option( 'font-family-post-meta' );
	$post_meta_font_weight    = astra_get_option( 'font-weight-post-meta' );
	$post_meta_line_height    = astra_get_option( 'line-height-post-meta' );
	$post_meta_text_transform = astra_get_option( 'text-transform-post-meta' );

	$post_pagination_font_size      = astra_get_option( 'font-size-post-pagination' );
	$post_pagination_text_transform = astra_get_option( 'text-transform-post-pagination' );

	$widget_title_font_size      = astra_get_option( 'font-size-widget-title' );
	$widget_title_font_family    = astra_get_option( 'font-family-widget-title' );
	$widget_title_font_weight    = astra_get_option( 'font-weight-widget-title' );
	$widget_title_line_height    = astra_get_option( 'line-height-widget-title' );
	$widget_title_text_transform = astra_get_option( 'text-transform-widget-title', $headings_font_transform );

	$widget_content_font_size      = astra_get_option( 'font-size-widget-content' );
	$widget_content_font_family    = astra_get_option( 'font-family-widget-content' );
	$widget_content_font_weight    = astra_get_option( 'font-weight-widget-content' );
	$widget_content_line_height    = astra_get_option( 'line-height-widget-content' );
	$widget_content_text_transform = astra_get_option( 'text-transform-widget-content' );

	$footer_content_font_size      = astra_get_option( 'font-size-footer-content' );
	$footer_content_font_family    = astra_get_option( 'font-family-footer-content' );
	$footer_content_font_weight    = astra_get_option( 'font-weight-footer-content' );
	$footer_content_line_height    = astra_get_option( 'line-height-footer-content' );
	$footer_content_text_transform = astra_get_option( 'text-transform-footer-content' );

	$h1_font_family    = astra_get_option( 'font-family-h1' );
	$h1_font_weight    = astra_get_option( 'font-weight-h1' );
	$h1_line_height    = astra_get_option( 'line-height-h1' );
	$h1_text_transform = astra_get_option( 'text-transform-h1' );

	$h2_font_family    = astra_get_option( 'font-family-h2' );
	$h2_font_weight    = astra_get_option( 'font-weight-h2' );
	$h2_line_height    = astra_get_option( 'line-height-h2' );
	$h2_text_transform = astra_get_option( 'text-transform-h2' );

	$h3_font_family    = astra_get_option( 'font-family-h3' );
	$h3_font_weight    = astra_get_option( 'font-weight-h3' );
	$h3_line_height    = astra_get_option( 'line-height-h3' );
	$h3_text_transform = astra_get_option( 'text-transform-h3' );

	$h4_font_family    = astra_get_option( 'font-family-h4' );
	$h4_font_weight    = astra_get_option( 'font-weight-h4' );
	$h4_line_height    = astra_get_option( 'line-height-h4' );
	$h4_text_transform = astra_get_option( 'text-transform-h4' );

	$h5_font_family    = astra_get_option( 'font-family-h5' );
	$h5_font_weight    = astra_get_option( 'font-weight-h5' );
	$h5_line_height    = astra_get_option( 'line-height-h5' );
	$h5_text_transform = astra_get_option( 'text-transform-h5' );

	$h6_font_family    = astra_get_option( 'font-family-h6' );
	$h6_font_weight    = astra_get_option( 'font-weight-h6' );
	$h6_line_height    = astra_get_option( 'line-height-h6' );
	$h6_text_transform = astra_get_option( 'text-transform-h6' );

	$button_font_size      = astra_get_option( 'font-size-button' );
	$button_font_family    = astra_get_option( 'font-family-button' );
	$button_font_weight    = astra_get_option( 'font-weight-button' );
	$button_line_height    = astra_get_option( 'line-height-button' );
	$button_text_transform = astra_get_option( 'text-transform-button' );

	// Fallback for Site Title - headings typography.
	if ( 'inherit' == $site_title_font_family ) {
		$site_title_font_family = $headings_font_family;
	}
	if ( 'normal' == $site_title_font_weight ) {
		$site_title_font_weight = $headings_font_weight;
	}

	// Fallback for Single Post Title - headings typography.
	if ( 'inherit' == $single_entry_title_font_family ) {
		$single_entry_title_font_family = $headings_font_family;
	}
	if ( 'normal' == $single_entry_title_font_weight ) {
		$single_entry_title_font_weight = $headings_font_weight;
	}

	// Fallback for Archive Summary Box Page Title - headings typography.
	if ( 'inherit' == $archive_summary_title_font_family ) {
		$archive_summary_title_font_family = $headings_font_family;
	}
	if ( 'normal' == $archive_summary_title_font_weight ) {
		$archive_summary_title_font_weight = $headings_font_weight;
	}

	// Fallback for Archive Page Title - headings typography.
	if ( 'inherit' == $archive_page_title_font_family ) {
		$archive_page_title_font_family = $headings_font_family;
	}
	if ( 'normal' == $archive_page_title_font_weight ) {
		$archive_page_title_font_weight = $headings_font_weight;
	}

	// Fallback for Sidebar Widget Title - headings typography.
	if ( 'inherit' == $widget_title_font_family ) {
		$widget_title_font_family = $headings_font_family;
	}
	if ( 'normal' == $widget_title_font_weight ) {
		$widget_title_font_weight = $headings_font_weight;
	}

	// Fallback for H1 - headings typography.
	if ( 'inherit' == $h1_font_family ) {
		$h1_font_family = $headings_font_family;
	}
	if ( 'normal' == $h1_font_weight ) {
		$h1_font_weight = $headings_font_weight;
	}
	if ( '' == $h1_text_transform ) {
		$h1_text_transform = $headings_font_transform;
	}

	// Fallback for H2 - headings typography.
	if ( 'inherit' == $h2_font_family ) {
			$h2_font_family = $headings_font_family;
	}
	if ( 'normal' == $h2_font_weight ) {
		$h2_font_weight = $headings_font_weight;
	}
	if ( '' == $h2_text_transform ) {
		$h2_text_transform = $headings_font_transform;
	}

	// Fallback for H3 - headings typography.
	if ( 'inherit' == $h3_font_family ) {
			$h3_font_family = $headings_font_family;
	}
	if ( 'normal' == $h3_font_weight ) {
		$h3_font_weight = $headings_font_weight;
	}
	if ( '' == $h3_text_transform ) {
		$h3_text_transform = $headings_font_transform;
	}

	// Fallback for H4 - headings typography.
	if ( 'inherit' == $h4_font_family ) {
			$h4_font_family = $headings_font_family;
	}
	if ( 'normal' == $h4_font_weight ) {
		$h4_font_weight = $headings_font_weight;
	}
	if ( '' == $h4_text_transform ) {
		$h4_text_transform = $headings_font_transform;
	}

	// Fallback for H5 - headings typography.
	if ( 'inherit' == $h5_font_family ) {
			$h5_font_family = $headings_font_family;
	}
	if ( 'normal' == $h5_font_weight ) {
		$h5_font_weight = $headings_font_weight;
	}
	if ( '' == $h5_text_transform ) {
		$h5_text_transform = $headings_font_transform;
	}

	// Fallback for H6 - headings typography.
	if ( 'inherit' == $h6_font_family ) {
			$h6_font_family = $headings_font_family;
	}
	if ( 'normal' == $h6_font_weight ) {
		$h6_font_weight = $headings_font_weight;
	}
	if ( '' == $h6_text_transform ) {
		$h6_text_transform = $headings_font_transform;
	}

	/**
	 * Set font sizes
	 */
	$css_output = array(

		/**
		 * Site Title
		 */
		'.site-title, .site-title a'                   => array(
			'font-weight'    => astra_get_css_value( $site_title_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $site_title_font_family, 'font', $body_font_family ),
			'line-height'    => esc_attr( $site_title_line_height ),
			'text-transform' => esc_attr( $site_title_text_transform ),
		),

		/**
		 * Site Description
		 */
		'.site-header .site-description'               => array(
			'font-weight'    => astra_get_css_value( $site_tagline_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $site_tagline_font_family, 'font' ),
			'line-height'    => esc_attr( $site_tagline_line_height ),
			'text-transform' => esc_attr( $site_tagline_text_transform ),
		),

		/**
		 * Primary Menu
		 */
		'.main-navigation'                             => array(

			'font-size'   => astra_responsive_font( $primary_menu_font_size, 'desktop' ),
			'font-weight' => astra_get_css_value( $primary_menu_font_weight, 'font' ),
			'font-family' => astra_get_css_value( $primary_menu_font_family, 'font' ),
		),

		'.main-header-bar'                             => array(
			'line-height' => esc_attr( $primary_menu_line_height ),
		),

		'.main-header-bar .main-header-bar-navigation' => array(
			'text-transform' => esc_attr( $primary_menu_text_transform ),
		),

		/**
		 * Primary Submenu
		 */
		'.main-header-menu > li > .sub-menu:first-of-type, .main-header-menu > li > .children:first-of-type' => array(
			'font-size'   => astra_responsive_font( $primary_dropdown_menu_font_size, 'desktop' ),
			'font-weight' => astra_get_css_value( $primary_dropdown_menu_font_weight, 'font' ),
			'font-family' => astra_get_css_value( $primary_dropdown_menu_font_family, 'font' ),
		),

		'.main-header-bar .main-header-bar-navigation .sub-menu, .main-header-bar .main-header-bar-navigation .children' => array(
			'line-height'    => esc_attr( $primary_dropdown_menu_line_height ),
			'text-transform' => esc_attr( $primary_dropdown_menu_text_transform ),
		),

		/**
		 * Post Meta
		 */
		'.entry-meta, .read-more'                      => array(
			'font-size'      => astra_responsive_font( $post_meta_font_size, 'desktop' ),
			'font-weight'    => astra_get_css_value( $post_meta_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $post_meta_font_family, 'font' ),
			'line-height'    => esc_attr( $post_meta_line_height ),
			'text-transform' => esc_attr( $post_meta_text_transform ),
		),

		/**
		 * Pagination
		 */
		'.ast-pagination .page-numbers, .ast-pagination .page-navigation' => array(
			'font-size'      => astra_responsive_font( $post_pagination_font_size, 'desktop' ),
			'text-transform' => esc_attr( $post_pagination_text_transform ),
		),

		/**
		 * Widget Content
		 */
		'.secondary .widget-title'                     => array(
			'font-size'      => astra_responsive_font( $widget_title_font_size, 'desktop' ),
			'font-weight'    => astra_get_css_value( $widget_title_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $widget_title_font_family, 'font', $body_font_family ),
			'line-height'    => esc_attr( $widget_title_line_height ),
			'text-transform' => esc_attr( $widget_title_text_transform ),
		),

		/**
		 * Widget Content
		 */
		'.secondary .widget > *:not(.widget-title)'    => array(
			'font-size'      => astra_responsive_font( $widget_content_font_size, 'desktop' ),
			'font-weight'    => astra_get_css_value( $widget_content_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $widget_content_font_family, 'font', $body_font_family ),
			'line-height'    => esc_attr( $widget_content_line_height ),
			'text-transform' => esc_attr( $widget_content_text_transform ),
		),

		/**
		 * Small Footer
		 */
		'.ast-small-footer'                            => array(
			'font-size'      => astra_responsive_font( $footer_content_font_size, 'desktop' ),
			'font-weight'    => astra_get_css_value( $footer_content_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $footer_content_font_family, 'font' ),
			'line-height'    => esc_attr( $footer_content_line_height ),
			'text-transform' => esc_attr( $footer_content_text_transform ),
		),

		/**
		 * Single Entry Title / Page Title
		 */
		'.ast-single-post .entry-title, .page-title'   => array(
			'font-weight'    => astra_get_css_value( $single_entry_title_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $single_entry_title_font_family, 'font', $body_font_family ),
			'line-height'    => esc_attr( $single_entry_title_line_height ),
			'text-transform' => esc_attr( $single_entry_title_text_transform ),
		),

		/**
		 * Archive Summary Box
		 */
		'.ast-archive-description .ast-archive-title'  => array(
			'font-family'    => astra_get_css_value( $archive_summary_title_font_family, 'font', $body_font_family ),
			'font-weight'    => astra_get_css_value( $archive_summary_title_font_weight, 'font' ),
			'line-height'    => esc_attr( $archive_summary_title_line_height ),
			'text-transform' => esc_attr( $archive_summary_title_text_transform ),
		),

		/**
		 * Entry Title
		 */
		'.blog .entry-title, .blog .entry-title a, .archive .entry-title, .archive .entry-title a, .search .entry-title, .search .entry-title a ' => array(
			'font-family'    => astra_get_css_value( $archive_page_title_font_family, 'font', $body_font_family ),
			'font-weight'    => astra_get_css_value( $archive_page_title_font_weight, 'font' ),
			'line-height'    => esc_attr( $archive_page_title_line_height ),
			'text-transform' => esc_attr( $archive_page_title_text_transform ),
		),

		/**
		 * Heading - <h1>
		 */
		'h1, .entry-content h1, .entry-content h1 a'   => array(
			'font-weight'    => astra_get_css_value( $h1_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $h1_font_family, 'font' ),
			'line-height'    => esc_attr( $h1_line_height ),
			'text-transform' => esc_attr( $h1_text_transform ),
		),

		/**
		 * Heading - <h2>
		 */
		'h2, .entry-content h2, .entry-content h2 a'   => array(
			'font-weight'    => astra_get_css_value( $h2_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $h2_font_family, 'font' ),
			'line-height'    => esc_attr( $h2_line_height ),
			'text-transform' => esc_attr( $h2_text_transform ),
		),

		/**
		 * Heading - <h3>
		 */
		'h3, .entry-content h3, .entry-content h3 a'   => array(
			'font-weight'    => astra_get_css_value( $h3_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $h3_font_family, 'font' ),
			'line-height'    => esc_attr( $h3_line_height ),
			'text-transform' => esc_attr( $h3_text_transform ),
		),

		/**
		 * Heading - <h4>
		 */
		'h4, .entry-content h4, .entry-content h4 a'   => array(
			'font-weight'    => astra_get_css_value( $h4_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $h4_font_family, 'font' ),
			'line-height'    => esc_attr( $h4_line_height ),
			'text-transform' => esc_attr( $h4_text_transform ),
		),

		/**
		 * Heading - <h5>
		 */
		'h5, .entry-content h5, .entry-content h5 a'   => array(
			'font-weight'    => astra_get_css_value( $h5_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $h5_font_family, 'font' ),
			'line-height'    => esc_attr( $h5_line_height ),
			'text-transform' => esc_attr( $h5_text_transform ),
		),

		/**
		 * Heading - <h6>
		 */
		'h6, .entry-content h6, .entry-content h6 a'   => array(
			'font-weight'    => astra_get_css_value( $h6_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $h6_font_family, 'font' ),
			'line-height'    => esc_attr( $h6_line_height ),
			'text-transform' => esc_attr( $h6_text_transform ),
		),
		/**
		 * Button
		 */
		'button, .ast-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' => array(
			'font-size'      => astra_get_font_css_value( $button_font_size['desktop'], $button_font_size['desktop-unit'] ),
			'font-weight'    => astra_get_css_value( $button_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $button_font_family, 'font' ),
			'text-transform' => esc_attr( $button_text_transform ),
		),
	);

	/* Parse CSS from array() */
	$css_output = astra_parse_css( $css_output );

	$tablet_css = array(

		'.main-navigation'                          => array(
			'font-size' => astra_responsive_font( $primary_menu_font_size, 'tablet' ),
		),

		'.main-header-menu > li > .sub-menu:first-of-type, .main-header-menu > li > .children:first-of-type' => array(
			'font-size' => astra_responsive_font( $primary_dropdown_menu_font_size, 'tablet' ),
		),

		'.entry-meta, .read-more'                   => array(
			'font-size' => astra_responsive_font( $post_meta_font_size, 'tablet' ),
		),

		'.ast-pagination .page-numbers, .ast-pagination .page-navigation' => array(
			'font-size' => astra_responsive_font( $post_pagination_font_size, 'tablet' ),
		),

		'.secondary .widget-title'                  => array(
			'font-size' => astra_responsive_font( $widget_title_font_size, 'tablet' ),
		),

		'.secondary .widget > *:not(.widget-title)' => array(
			'font-size' => astra_responsive_font( $widget_content_font_size, 'tablet' ),
		),

		'.ast-small-footer'                         => array(
			'font-size' => astra_responsive_font( $footer_content_font_size, 'tablet' ),
		),
		'button, .ast-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' => array(
			'font-size' => astra_get_font_css_value( $button_font_size['tablet'], $button_font_size['tablet-unit'] ),
		),
	);

	/* Parse CSS from array() */
	$css_output .= astra_parse_css( $tablet_css, '', '768' );

	$mobile_css = array(

		'.main-navigation'                          => array(
			'font-size' => astra_responsive_font( $primary_menu_font_size, 'mobile' ),
		),

		'.main-header-menu > li > .sub-menu:first-of-type, .main-header-menu > li > .children:first-of-type' => array(
			'font-size' => astra_responsive_font( $primary_dropdown_menu_font_size, 'mobile' ),
		),

		'.entry-meta, .read-more'                   => array(
			'font-size' => astra_responsive_font( $post_meta_font_size, 'mobile' ),
		),

		'.ast-pagination .page-numbers, .ast-pagination .page-navigation' => array(
			'font-size' => astra_responsive_font( $post_pagination_font_size, 'mobile' ),
		),

		'.secondary .widget-title'                  => array(
			'font-size' => astra_responsive_font( $widget_title_font_size, 'mobile' ),
		),

		'.secondary .widget > *:not(.widget-title)' => array(
			'font-size' => astra_responsive_font( $widget_content_font_size, 'mobile' ),
		),

		'.ast-small-footer'                         => array(
			'font-size' => astra_responsive_font( $footer_content_font_size, 'mobile' ),
		),
		'button, .ast-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' => array(
			'font-size' => astra_get_font_css_value( $button_font_size['mobile'], $button_font_size['mobile-unit'] ),
		),
	);

	/* Parse CSS from array() */
	$css_output .= astra_parse_css( $mobile_css, '', '544' );

	/**
	 * Merge Header Section when no primary menu
	 */
	if ( Astra_Ext_Extension::is_active( 'header-sections' ) ) {
		/**
		 * Set font sizes
		 */
		$header_sections = array(

			/**
			 * Primary Menu
			 */
			'.ast-header-sections-navigation, .ast-above-header-menu-items, .ast-below-header-menu-items'                             => array(

				'font-size'   => astra_responsive_font( $primary_menu_font_size, 'desktop' ),
				'font-weight' => astra_get_css_value( $primary_menu_font_weight, 'font' ),
				'font-family' => astra_get_css_value( $primary_menu_font_family, 'font' ),
			),

			/**
			 * Primary Submenu
			 */
			'.ast-header-sections-navigation li > .sub-menu:first-of-type, .ast-above-header-menu-items li > .sub-menu:first-of-type, .ast-below-header-menu-items li > .sub-menu:first-of-type' => array(
				'font-size'   => astra_responsive_font( $primary_dropdown_menu_font_size, 'desktop' ),
				'font-weight' => astra_get_css_value( $primary_dropdown_menu_font_weight, 'font' ),
				'font-family' => astra_get_css_value( $primary_dropdown_menu_font_family, 'font' ),
			),

			'.ast-header-sections-navigation .sub-menu, .ast-above-header-menu-items .sub-menu, .ast-below-header-menu-items .sub-menu,' => array(
				'line-height'    => esc_attr( $primary_dropdown_menu_line_height ),
				'text-transform' => esc_attr( $primary_dropdown_menu_text_transform ),
			),

		);

		/* Parse CSS from array() */
		$css_output .= astra_parse_css( $header_sections );

		$tablet_header_sections = array(

			'.ast-header-sections-navigation, .ast-above-header-menu-items, .ast-below-header-menu-items'                          => array(
				'font-size' => astra_responsive_font( $primary_menu_font_size, 'tablet' ),
			),

			'.ast-header-sections-navigation li > .sub-menu:first-of-type, .ast-above-header-menu-items li > .sub-menu:first-of-type, .ast-below-header-menu-items li > .sub-menu:first-of-type' => array(
				'font-size' => astra_responsive_font( $primary_dropdown_menu_font_size, 'tablet' ),
			),

		);

		/* Parse CSS from array() */
		$css_output .= astra_parse_css( $tablet_header_sections, '', '768' );

		$mobile_header_sections = array(

			'.ast-header-sections-navigation, .ast-above-header-menu-items, .ast-below-header-menu-items'                          => array(
				'font-size' => astra_responsive_font( $primary_menu_font_size, 'mobile' ),
			),

			'.ast-header-sections-navigation li > .sub-menu:first-of-type, .ast-above-header-menu-items li > .sub-menu:first-of-type, .ast-below-header-menu-items li > .sub-menu:first-of-type' => array(
				'font-size' => astra_responsive_font( $primary_dropdown_menu_font_size, 'mobile' ),
			),

		);

		/* Parse CSS from array() */
		$css_output .= astra_parse_css( $mobile_header_sections, '', '543' );
	}

	return $dynamic_css . $css_output;

}
