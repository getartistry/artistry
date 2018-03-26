<?php
/**
 * Site Layouts - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_ext_site_layouts_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_ext_site_layouts_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	/**
	 * - Variable Declaration
	 */
	$page_width            = '100%';
	$parse_css             = '';
	$header_break_point    = astra_header_break_point(); // Header Break Point.
	$secondary_width       = astra_get_option( 'site-sidebar-width' );
	$primary_width         = 100 - $secondary_width;
	$layout                = astra_get_option( 'site-layout', 'ast-full-width-layout' );
	$site_container_layout = astra_get_option( 'site-content-layout' );
	$single_post_max       = astra_get_option( 'blog-single-width' );
	$single_post_max_width = astra_get_option( 'blog-single-max-width' );
	$blog_width            = astra_get_option( 'blog-width' );
	$blog_max_width        = astra_get_option( 'blog-max-width' );

	$woo_shop_archive_width     = astra_get_option( 'shop-archive-width' );
	$woo_shop_archive_max_width = astra_get_option( 'shop-archive-max-width' );

	// set page width depending on site layout.
	if ( 'ast-box-layout' == $layout ) {
		$page_width = astra_get_option( 'site-layout-box-width' ) . 'px';
	} elseif ( 'ast-full-width-layout' == $layout ) {
		$page_width = ASTRA_THEME_CONTAINER_PADDING_TWICE + astra_get_option( 'site-content-width' ) . 'px';
	} elseif ( 'ast-padded-layout' == $layout ) {
		if ( '' != astra_get_option( 'site-layout-padded-width' ) ) {
			$page_width = ASTRA_THEME_CONTAINER_BOX_PADDED_PADDING_TWICE + astra_get_option( 'site-layout-padded-width' ) . 'px';
		}
	}

	// Fluid layout padding.
	$fluid_layout_padding = astra_get_option( 'site-layout-fluid-lr-padding' );

	// Box Layout - Top & Bottom Margin.
	$box_topbottom_margin = astra_get_option( 'site-layout-box-tb-margin' );

	// Box Layout - Background Color / Image.
	$box_bg_color      = astra_get_option( 'site-layout-outside-bg-color' );
	$box_bg_image      = esc_url( astra_get_option( 'site-layout-box-bg-img' ) );
	$box_bg_size       = astra_get_option( 'site-layout-box-bg-size' );
	$box_bg_repeat     = astra_get_option( 'site-layout-box-bg-rep' );
	$box_bg_position   = str_replace( '-', ' ', astra_get_option( 'site-layout-box-bg-pos' ) );
	$box_bg_attachment = astra_get_option( 'site-layout-box-bg-atch' );

	// Padded Layout - Background Color / Image.
	$padded_bg_image    = esc_url( astra_get_option( 'site-layout-padded-bg-img' ) );
	$padded_bg_size     = astra_get_option( 'site-layout-padded-bg-size' );
	$padded_bg_repeat   = astra_get_option( 'site-layout-padded-bg-rep' );
	$padded_bg_position = str_replace( '-', ' ', astra_get_option( 'site-layout-padded-bg-pos' ) );

	// Padded Layout - Padding.
	$padded_layout_padding = astra_get_option( 'site-layout-padded-pad' );

	$body_font_weight = astra_get_option( 'body-font-weight' );

	if ( 'ast-box-layout' == $layout || 'ast-padded-layout' == $layout ) {
		$blog_max_width        += ASTRA_THEME_CONTAINER_BOX_PADDED_PADDING_TWICE;
		$single_post_max_width += ASTRA_THEME_CONTAINER_BOX_PADDED_PADDING_TWICE;
	} else {
		$blog_max_width        += ASTRA_THEME_CONTAINER_PADDING_TWICE;
		$single_post_max_width += ASTRA_THEME_CONTAINER_PADDING_TWICE;
	}

	/* Global Responsive */
	$global_responsive_media = array(
		'.ast-container' => array(
			'max-width' => esc_attr( $page_width ),
		),
	);

	/* Parse CSS from array()*/
	$parse_css .= astra_parse_css( $global_responsive_media, '769' );
	$parse_css .= astra_parse_css( $global_responsive_media, '993' );
	$parse_css .= astra_parse_css( $global_responsive_media, '1201' );

	if ( 'default' == $woo_shop_archive_width ) {
		/* Global Responsive for default woocommerce shop archive page */
		$woo_shop_archive_responsive_media = array(
			'.ast-woo-shop-archive .site-content > .ast-container' => array(
				'max-width' => esc_attr( $page_width ),
			),
		);

		/* Parse CSS from array()*/
		$parse_css .= astra_parse_css( $woo_shop_archive_responsive_media, '769' );
		$parse_css .= astra_parse_css( $woo_shop_archive_responsive_media, '993' );
		$parse_css .= astra_parse_css( $woo_shop_archive_responsive_media, '1201' );
	}

	/* Fluid Width Layout CSS */
	if ( 'ast-fluid-width-layout' == $layout ) :
		$fw_layout          = '@media (min-width:769px) {';
			$fw_layout     .= '.ast-container {';
				$fw_layout .= 'padding-left:' . esc_attr( $fluid_layout_padding ) . 'px;';
				$fw_layout .= 'padding-right:' . esc_attr( $fluid_layout_padding ) . 'px;';
			$fw_layout     .= '}';
		$fw_layout         .= '}';
		$parse_css         .= $fw_layout;

		if ( 'default' === $woo_shop_archive_width ) :
			$woo_shop_archive_padding_css  = '@media (min-width:921px) {';
			$woo_shop_archive_padding_css .= 'body.ast-woo-shop-archive .site-content > .ast-container{';
			$woo_shop_archive_padding_css .= 'padding-left:' . esc_attr( $fluid_layout_padding ) . 'px;';
			$woo_shop_archive_padding_css .= 'padding-right:' . esc_attr( $fluid_layout_padding ) . 'px;';
			$woo_shop_archive_padding_css .= '}';
			$woo_shop_archive_padding_css .= '}';
			$parse_css                    .= $woo_shop_archive_padding_css;
		endif;

	endif;

	/* Box Layout CSS */
	if ( 'ast-box-layout' == $layout ) :
		$box_layout = array(
			'#page' => array(
				'max-width'    => $page_width,
				'margin-left'  => 'auto',
				'margin-right' => 'auto',
			),
		);
		if ( '' != $box_bg_image ) :
			$box_layout['body']['background']            = 'url(' . esc_url( $box_bg_image ) . ') ' . esc_attr( $box_bg_position );
			$box_layout['body']['background-repeat']     = esc_attr( $box_bg_repeat );
			$box_layout['body']['background-size']       = esc_attr( $box_bg_size );
			$box_layout['body']['background-attachment'] = esc_attr( $box_bg_attachment );
			$box_layout['body']['background-position']   = esc_attr( $box_bg_position );
			$box_layout['body:before']                   = array(
				'content'    => '""',
				'position'   => 'fixed',
				'left'       => '0',
				'right'      => '0',
				'top'        => '0',
				'bottom'     => '0',
				'background' => esc_attr( $box_bg_color ),
			);
		endif;

		/* Parse CSS from array()*/
		$parse_css .= astra_parse_css( $box_layout );
		if ( 'fixed' == $box_bg_attachment ) :
			$parse_css .= '@media only screen and (max-width: 1024px){body{background-attachment:scroll;}}';
		endif;

		$bx_layout          = '@media (min-width:769px) {';
			$bx_layout     .= '#page{';
				$bx_layout .= 'margin-top:' . esc_attr( $box_topbottom_margin ) . 'px;';
				$bx_layout .= 'margin-bottom:' . esc_attr( $box_topbottom_margin ) . 'px;';
			$bx_layout     .= '}';
			$bx_layout     .= ' .ast-container{';
				$bx_layout .= 'padding-left: ' . ASTRA_THEME_CONTAINER_BOX_PADDED_PADDING . 'px;';
				$bx_layout .= 'padding-right: ' . ASTRA_THEME_CONTAINER_BOX_PADDED_PADDING . 'px;';
			$bx_layout     .= '}';
		$bx_layout         .= '}';
		$parse_css         .= $bx_layout;
	endif;

	/* Padded Layout CSS */
	if ( 'ast-padded-layout' == $layout ) :
		$padded_layout = array(
			'body' => array(
				'background' => $box_bg_color,
			),
		);

		if ( '' != $padded_bg_image ) :
			$padded_layout['body']['background']        = 'url(' . esc_url( $padded_bg_image ) . ') ' . esc_attr( $padded_bg_position );
			$padded_layout['body']['background-repeat'] = esc_attr( $padded_bg_repeat );
			$padded_layout['body']['background-size']   = esc_attr( $padded_bg_size );
		endif;

		/* Parse CSS from array()*/
		$parse_css .= astra_parse_css( $padded_layout );

		/**
		 * Padded layout Desktop Spacing
		 */
		$padded_layout_spacing = array(
			'body'                           => array(
				'padding-top'    => astra_responsive_spacing( $padded_layout_padding, 'top', 'desktop' ),
				'padding-right'  => astra_responsive_spacing( $padded_layout_padding, 'right', 'desktop' ),
				'padding-bottom' => astra_responsive_spacing( $padded_layout_padding, 'bottom', 'desktop' ),
				'padding-left'   => astra_responsive_spacing( $padded_layout_padding, 'left', 'desktop' ),
			),
			'body.ast-padded-layout::before' => array(
				'height' => astra_responsive_spacing( $padded_layout_padding, 'top', 'desktop' ),
			),
			'body.ast-padded-layout::after'  => array(
				'height' => astra_responsive_spacing( $padded_layout_padding, 'bottom', 'desktop' ),
			),
		);

		$parse_css .= astra_parse_css( $padded_layout_spacing );

		/**
		 * Padded layout Tablet Spacing
		 */
		$tablet_padded_layout_spacing = array(
			'body'                           => array(
				'padding-top'    => astra_responsive_spacing( $padded_layout_padding, 'top', 'tablet' ),
				'padding-right'  => astra_responsive_spacing( $padded_layout_padding, 'right', 'tablet' ),
				'padding-bottom' => astra_responsive_spacing( $padded_layout_padding, 'bottom', 'tablet' ),
				'padding-left'   => astra_responsive_spacing( $padded_layout_padding, 'left', 'tablet' ),

			),
			'body.ast-padded-layout::before' => array(
				'height' => astra_responsive_spacing( $padded_layout_padding, 'top', 'tablet' ),
			),
			'body.ast-padded-layout::after'  => array(
				'height' => astra_responsive_spacing( $padded_layout_padding, 'bottom', 'tablet' ),
			),
		);

		/**
		 * Padded layout Mobile Spacing
		 */
		$mobile_padded_layout_spacing = array(
			'body'                           => array(
				'padding-top'    => astra_responsive_spacing( $padded_layout_padding, 'top', 'mobile' ),
				'padding-right'  => astra_responsive_spacing( $padded_layout_padding, 'right', 'mobile' ),
				'padding-bottom' => astra_responsive_spacing( $padded_layout_padding, 'bottom', 'mobile' ),
				'padding-left'   => astra_responsive_spacing( $padded_layout_padding, 'left', 'mobile' ),
			),
			'body.ast-padded-layout::before' => array(
				'height' => astra_responsive_spacing( $padded_layout_padding, 'top', 'mobile' ),
			),
			'body.ast-padded-layout::after'  => array(
				'height' => astra_responsive_spacing( $padded_layout_padding, 'bottom', 'mobile' ),
			),
		);

		/**
		 * Padded layout Container Spacing
		 */
		$padded_layout_spacing_container = array(
			'.ast-container' => array(
				'padding-left'  => ASTRA_THEME_CONTAINER_BOX_PADDED_PADDING . 'px',
				'padding-right' => ASTRA_THEME_CONTAINER_BOX_PADDED_PADDING . 'px',
			),
		);
		// Add Container padding only for desktop devices.
		$parse_css .= astra_parse_css( $padded_layout_spacing_container, '769' );

		$parse_css .= astra_parse_css( $padded_layout_spacing );
		$parse_css .= astra_parse_css( $tablet_padded_layout_spacing, '', '768' );
		$parse_css .= astra_parse_css( $mobile_padded_layout_spacing, '', '544' );

	endif;

	/* Blog */
	if ( 'ast-fluid-width-layout' == $layout ) :
		if ( 'custom' === $blog_width ) :
			$blog_css   = '@media (min-width:921px) {';
			$blog_css  .= '.blog .site-content > .ast-container, .archive .site-content > .ast-container, .search .site-content > .ast-container{';
			$blog_css  .= 'padding-left:' . ASTRA_THEME_CONTAINER_PADDING . 'px;';
			$blog_css  .= 'padding-right:' . ASTRA_THEME_CONTAINER_PADDING . 'px;';
			$blog_css  .= '}';
			$blog_css  .= '}';
			$parse_css .= $blog_css;
		endif;
		if ( 'custom' === $woo_shop_archive_width ) :
			$woo_shop_archive_css  = '@media (min-width:921px) {';
			$woo_shop_archive_css .= '.ast-woo-shop-archive .site-content > .ast-container{';
			$woo_shop_archive_css .= 'padding-left:' . ASTRA_THEME_CONTAINER_PADDING . 'px;';
			$woo_shop_archive_css .= 'padding-right:' . ASTRA_THEME_CONTAINER_PADDING . 'px;';
			$woo_shop_archive_css .= '}';
			$woo_shop_archive_css .= '}';
			$parse_css            .= $woo_shop_archive_css;
		endif;
	endif;

	/* Single Blog */
	if ( 'ast-fluid-width-layout' == $layout ) :
		if ( 'custom' === $single_post_max ) :
			$single_blog_css  = '@media (min-width:921px) {';
			$single_blog_css .= '.single .site-content > .ast-container{';
			$single_blog_css .= 'padding-left:' . ASTRA_THEME_CONTAINER_PADDING . 'px;';
			$single_blog_css .= 'padding-right:' . ASTRA_THEME_CONTAINER_PADDING . 'px;';
			$single_blog_css .= '}';
			$single_blog_css .= '}';
			$parse_css       .= $single_blog_css;
		endif;
	endif;

	return $dynamic_css . $parse_css;

}
