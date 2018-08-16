<?php
/**
 * Typography - Dynamic CSS
 *
 * @package Astra Addon
 */

add_filter( 'astra_dynamic_css', 'astra_woocommerce_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return string
 */
function astra_woocommerce_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$link_h_color      = astra_get_option( 'link-h-color' );
	$theme_color       = astra_get_option( 'theme-color' );
	$link_color        = astra_get_option( 'link-color', $theme_color );
	$product_img_width = astra_get_option( 'single-product-image-width' );
	$product_nav_style = astra_get_option( 'single-product-nav-style' );

	$btn_h_color = astra_get_option( 'button-h-color' );
	if ( empty( $btn_h_color ) ) {
		$btn_h_color = astra_get_foreground_color( $link_h_color );
	}

	$body_font_family = astra_body_font_family();

	// General Options.
	$product_price_font_family = astra_get_option( 'font-family-product-price' );
	$product_price_font_weight = astra_get_option( 'font-weight-product-price' );

	// General Colors.
	$product_rating_color = astra_get_option( 'single-product-rating-color' );
	$product_price_color  = astra_get_option( 'single-product-price-color' );

	// Single Product Typo.
	$product_title_font_size      = astra_get_option( 'font-size-product-title' );
	$product_title_line_height    = astra_get_option( 'line-height-product-title' );
	$product_title_font_family    = astra_get_option( 'font-family-product-title' );
	$product_title_font_weight    = astra_get_option( 'font-weight-product-title' );
	$product_title_text_transform = astra_get_option( 'text-transform-product-title' );

	// Single Product Content Typo.
	$product_content_font_size      = astra_get_option( 'font-size-product-content' );
	$product_content_line_height    = astra_get_option( 'line-height-product-content' );
	$product_content_font_family    = astra_get_option( 'font-family-product-content' );
	$product_content_font_weight    = astra_get_option( 'font-weight-product-content' );
	$product_content_text_transform = astra_get_option( 'text-transform-product-content' );

	$product_price_font_size   = astra_get_option( 'font-size-product-price' );
	$product_price_line_height = astra_get_option( 'line-height-product-price' );
	$product_price_font_family = astra_get_option( 'font-family-product-price' );
	$product_price_font_weight = astra_get_option( 'font-weight-product-price' );

	$product_breadcrumb_font_family    = astra_get_option( 'font-family-product-breadcrumb' );
	$product_breadcrumb_font_weight    = astra_get_option( 'font-weight-product-breadcrumb' );
	$product_breadcrumb_text_transform = astra_get_option( 'text-transform-product-breadcrumb' );
	$product_breadcrumb_line_height    = astra_get_option( 'line-height-product-breadcrumb' );
	$product_breadcrumb_font_size      = astra_get_option( 'font-size-product-breadcrumb' );

	// Single Product Colors.
	$product_title_color      = astra_get_option( 'single-product-title-color' );
	$product_price_color      = astra_get_option( 'single-product-price-color' );
	$product_content_color    = astra_get_option( 'single-product-content-color' );
	$product_breadcrumb_color = astra_get_option( 'single-product-breadcrumb-color' );

	// Shop Typo.
	$shop_product_title_font_size      = astra_get_option( 'font-size-shop-product-title' );
	$shop_product_title_line_height    = astra_get_option( 'line-height-shop-product-title' );
	$shop_product_title_font_family    = astra_get_option( 'font-family-shop-product-title' );
	$shop_product_title_font_weight    = astra_get_option( 'font-weight-shop-product-title' );
	$shop_product_title_text_transform = astra_get_option( 'text-transform-shop-product-title' );

	$shop_product_price_font_family = astra_get_option( 'font-family-shop-product-price' );
	$shop_product_price_font_weight = astra_get_option( 'font-weight-shop-product-price' );
	$shop_product_price_font_size   = astra_get_option( 'font-size-shop-product-price' );
	$shop_product_price_line_height = astra_get_option( 'line-height-shop-product-price' );

	$shop_product_content_font_family    = astra_get_option( 'font-family-shop-product-content' );
	$shop_product_content_font_weight    = astra_get_option( 'font-weight-shop-product-content' );
	$shop_product_content_line_height    = astra_get_option( 'line-height-shop-product-content' );
	$shop_product_content_text_transform = astra_get_option( 'text-transform-shop-product-content' );
	$shop_product_content_font_size      = astra_get_option( 'font-size-shop-product-content' );

	// Shop Colors.
	$shop_product_title_color   = astra_get_option( 'shop-product-title-color' );
	$shop_product_price_color   = astra_get_option( 'shop-product-price-color' );
	$shop_product_content_color = astra_get_option( 'shop-product-content-color' );

	$btn_v_padding  = astra_get_option( 'shop-button-v-padding' );
	$btn_h_padding  = astra_get_option( 'shop-button-h-padding' );
	$btn_bg_color   = astra_get_option( 'button-bg-color', '', $theme_color );
	$btn_bg_h_color = astra_get_option( 'button-bg-h-color', '', $link_h_color );

	$product_desc_width = 96 - intval( $product_img_width );

	$two_step_checkout     = astra_get_option( 'two-step-checkout' );
	$checkout_width        = astra_get_option( 'checkout-content-width' );
	$checkout_custom_width = astra_get_option( 'checkout-content-max-width' );

	$header_cart_icon_style  = astra_get_option( 'woo-header-cart-icon-styl' );
	$header_cart_icon_color  = astra_get_option( 'woo-header-cart-icon-color', $theme_color );
	$header_cart_icon_radius = astra_get_option( 'woo-header-cart-icon-radius' );
	$cart_h_color            = astra_get_foreground_color( $header_cart_icon_color );

	// Default headings font family.
	$headings_font_family = astra_get_option( 'headings-font-family' );

	/**
	 * Set font sizes
	 */
	$css_output = array(

		// Shop / Archive / Related / Upsell /Woocommerce Shortcode buttons Vertical/Horizontal padding.
		'.woocommerce.archive ul.products li a.button, .woocommerce > ul.products li a.button, .woocommerce related a.button, .woocommerce .related a.button, .woocommerce .up-sells a.button .woocommerce .cross-sells a.button' => array(
			'padding' => $btn_v_padding . 'px ' . $btn_h_padding . 'px',
		),

		/**
		 * Sale Bubble Styles.
		 */
		// Outline.
		'.woocommerce .ast-woocommerce-container ul.products li.product .onsale.circle-outline, .woocommerce .ast-woocommerce-container ul.products li.product .onsale.square-outline, .woocommerce div.product .onsale.circle-outline, .woocommerce div.product .onsale.square-outline' => array(
			'background' => '#ffffff',
			'border'     => '2px solid ' . $link_color,
			'color'      => $link_color,
		),

		'.ast-shop-load-more:hover'                => array(
			'color'            => astra_get_foreground_color( $link_color ),
			'border-color'     => esc_attr( $link_color ),
			'background-color' => esc_attr( $link_color ),
		),

		'.ast-loader > div'                        => array(
			'background-color' => esc_attr( $link_color ),
		),

		'.woocommerce nav.woocommerce-pagination ul li > span.current, .woocommerce nav.woocommerce-pagination ul li > .page-numbers' => array(
			'border-color' => esc_attr( $link_color ),
		),

		/**
		 * Checkout button Two step checkout back button
		 */
		'.ast-woo-two-step-checkout .ast-checkout-slides .flex-prev.button' => array(
			'color'            => $btn_h_color,
			'border-color'     => $btn_bg_h_color,
			'background-color' => $btn_bg_h_color,
		),
		'.widget_layered_nav_filters ul li.chosen a::before' => array(
			'color' => esc_attr( $link_color ),
		),
		'.ast-site-header-cart i.astra-icon:after' => array(
			'background' => $theme_color,
			'color'      => astra_get_foreground_color( $theme_color ),
		),

		'.single-product div.product .entry-title' => array(
			'font-size'      => astra_responsive_font( $product_title_font_size, 'desktop' ),
			'line-height'    => esc_attr( $product_title_line_height ),
			'font-weight'    => astra_get_css_value( $product_title_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $product_title_font_family, 'font', $headings_font_family ),
			'text-transform' => esc_attr( $product_title_text_transform ),
			'color'          => esc_attr( $product_title_color ),
		),
		// Single Product Content.
		'.single-product div.product .woocommerce-product-details__short-description, .single-product div.product .product_meta, .single-product div.product .entry-content' => array(
			'font-size'      => astra_responsive_font( $product_content_font_size, 'desktop' ),
			'line-height'    => esc_attr( $product_content_line_height ),
			'font-weight'    => astra_get_css_value( $product_content_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $product_content_font_family, 'font', $body_font_family ),
			'text-transform' => esc_attr( $product_content_text_transform ),
			'color'          => esc_attr( $product_content_color ),
		),

		'.single-product div.product p.price, .single-product div.product span.price' => array(
			'font-size'   => astra_responsive_font( $product_price_font_size, 'desktop' ),
			'line-height' => esc_attr( $product_price_line_height ),
			'font-weight' => astra_get_css_value( $product_price_font_weight, 'font' ),
			'font-family' => astra_get_css_value( $product_price_font_family, 'font', $body_font_family ),
			'color'       => esc_attr( $product_price_color ),
		),

		'.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce-page ul.products li.product .woocommerce-loop-product__title' => array(
			'font-size'      => astra_responsive_font( $shop_product_title_font_size, 'desktop' ),
			'line-height'    => esc_attr( $shop_product_title_line_height ),
			'font-weight'    => astra_get_css_value( $shop_product_title_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $shop_product_title_font_family, 'font', $body_font_family ),
			'text-transform' => esc_attr( $shop_product_title_text_transform ),
			'color'          => esc_attr( $shop_product_title_color ),
		),

		'.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price' => array(
			'font-family' => astra_get_css_value( $shop_product_price_font_family, 'font', $body_font_family ),
			'font-weight' => astra_get_css_value( $shop_product_price_font_weight, 'font' ),
			'font-size'   => astra_responsive_font( $shop_product_price_font_size, 'desktop' ),
			'line-height' => esc_attr( $shop_product_price_line_height ),
			'color'       => esc_attr( $shop_product_price_color ),
		),

		'.woocommerce ul.products li.product .price, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce ul.products li.product .price ins, .woocommerce div.product p.price ins, .woocommerce div.product span.price ins' => array(
			'font-weight' => astra_get_css_value( $product_price_font_weight, 'font' ),
		),

		'.woocommerce .star-rating, .woocommerce .comment-form-rating .stars a, .woocommerce .star-rating::before' => array(
			'color' => esc_attr( $product_rating_color ),
		),

		'.single-product div.product .woocommerce-breadcrumb, .single-product div.product .woocommerce-breadcrumb a' => array(
			'color' => esc_attr( $product_breadcrumb_color ),
		),

		'.single-product div.product .woocommerce-breadcrumb' => array(
			'font-size'      => astra_responsive_font( $product_breadcrumb_font_size, 'desktop' ),
			'font-weight'    => astra_get_css_value( $product_breadcrumb_font_weight, 'font' ),
			'font-family'    => astra_get_css_value( $product_breadcrumb_font_family, 'font', $body_font_family ),
			'text-transform' => esc_attr( $product_breadcrumb_text_transform ),
			'line-height'    => esc_attr( $product_breadcrumb_line_height ),
		),

		'.woocommerce ul.products li.product .ast-woo-product-category, .woocommerce-page ul.products li.product .ast-woo-product-category, .woocommerce ul.products li.product .ast-woo-shop-product-description, .woocommerce-page ul.products li.product .ast-woo-shop-product-description' => array(
			'font-family'    => astra_get_css_value( $shop_product_content_font_family, 'font', $body_font_family ),
			'font-weight'    => astra_get_css_value( $shop_product_content_font_weight, 'font' ),
			'font-size'      => astra_responsive_font( $shop_product_content_font_size, 'desktop' ),
			'text-transform' => esc_attr( $shop_product_content_text_transform ),
			'line-height'    => esc_attr( $shop_product_content_line_height ),
			'color'          => esc_attr( $shop_product_content_color ),
		),

	);

	/* Parse CSS from array() */
	$css_output = astra_parse_css( $css_output );

	/**
	 * Header Cart color
	 */
	if ( 'none' != $header_cart_icon_style ) {

		/**
		 * Header Cart Icon colors
		 */
		$header_cart_icon = array(
			'li.ast-masthead-custom-menu-items.woocommerce-custom-menu-item, .ast-masthead-custom-menu-items.woocommerce-custom-menu-item' => array(
				'padding' => esc_attr( 0 ),
			),
			'.ast-header-break-point li.ast-masthead-custom-menu-items.woocommerce-custom-menu-item' => array(
				'padding-left'  => esc_attr( '20px' ),
				'padding-right' => esc_attr( '20px' ),
				'margin'        => esc_attr( '0' ),
			),
			'.ast-header-break-point .ast-masthead-custom-menu-items.woocommerce-custom-menu-item' => array(
				'margin-left'  => esc_attr( '1em' ),
				'margin-right' => esc_attr( '1em' ),
			),
			'.ast-header-break-point .ast-above-header-mobile-inline.mobile-header-order-2 .ast-masthead-custom-menu-items.woocommerce-custom-menu-item' => array(
				'margin-left' => esc_attr( '0' ),
			),
			'.ast-header-break-point li.ast-masthead-custom-menu-items.woocommerce-custom-menu-item .ast-addon-cart-wrap' => array(
				'display' => esc_attr( 'inline-block' ),
			),

			'.woocommerce-custom-menu-item .ast-addon-cart-wrap' => array(
				'padding' => esc_attr( '0 .6em' ),
			),

			// Default icon colors.
			'.ast-woocommerce-cart-menu .ast-cart-menu-wrap .count, .ast-woocommerce-cart-menu .ast-cart-menu-wrap .count:after' => array(
				'border-color' => esc_attr( $header_cart_icon_color ),
				'color'        => esc_attr( $header_cart_icon_color ),
			),
			// Outline icon hover colors.
			'.ast-woocommerce-cart-menu .ast-cart-menu-wrap:hover .count' => array(
				'color'            => esc_attr( $cart_h_color ),
				'background-color' => esc_attr( $header_cart_icon_color ),
			),
			// Outline icon colors.
			'.ast-menu-cart-outline .ast-addon-cart-wrap' => array(
				'background' => '#ffffff',
				'border'     => '1px solid ' . $header_cart_icon_color,
				'color'      => esc_attr( $header_cart_icon_color ),
			),
			// Fill icon Color.
			'.ast-woocommerce-cart-menu .ast-menu-cart-fill .ast-cart-menu-wrap .count,.ast-menu-cart-fill .ast-addon-cart-wrap' => array(
				'background-color' => esc_attr( $header_cart_icon_color ),
				'color'            => esc_attr( $cart_h_color ),
			),

			// Border radius.
			'.ast-site-header-cart.ast-menu-cart-outline .ast-addon-cart-wrap, .ast-site-header-cart.ast-menu-cart-fill .ast-addon-cart-wrap' => array(
				'border-radius' => astra_get_css_value( $header_cart_icon_radius, 'px' ),
			),
		);

		$css_output .= astra_parse_css( $header_cart_icon );
	}

	if ( 'disable' != $product_nav_style ) {

		/**
		 * Product Navingation Style
		 */
		$product_nav = array(

			'.ast-product-navigation-wrapper .product-links a'    => array(
				'border-color' => $link_color,
				'color'        => $link_color,
			),

			'.ast-product-navigation-wrapper .product-links a:hover'    => array(
				'background' => $link_color,
				'color'      => astra_get_foreground_color( $link_color ),
			),

			'.ast-product-navigation-wrapper.circle .product-links a, .ast-product-navigation-wrapper.square .product-links a'    => array(
				'background' => $link_color,
				'color'      => astra_get_foreground_color( $link_color ),
			),
		);

		$css_output .= astra_parse_css( $product_nav );
	}

	if ( $two_step_checkout ) {

		$two_step_nav_colors_light  = astra_hex_to_rgba( $link_color, 0.4 );
		$two_step_nav_colors_medium = astra_hex_to_rgba( $link_color, 1 );

		/**
		 * Two Step Checkout Style
		 */
		$two_step_checkout = array(

			'.ast-woo-two-step-checkout .ast-checkout-control-nav li a:after'    => array(
				'background-color' => $link_color,
				'border-color'     => $two_step_nav_colors_medium,
			),
			'.ast-woo-two-step-checkout .ast-checkout-control-nav li:nth-child(2) a.flex-active:after'    => array(
				'border-color' => $two_step_nav_colors_medium,
			),
			'.ast-woo-two-step-checkout .ast-checkout-control-nav li a:before, .ast-woo-two-step-checkout .ast-checkout-control-nav li:nth-child(2) a.flex-active:before'    => array(
				'background-color' => $two_step_nav_colors_medium,
			),
			'.ast-woo-two-step-checkout .ast-checkout-control-nav li:nth-child(2) a:before'    => array(
				'background-color' => $two_step_nav_colors_light,
			),
			'.ast-woo-two-step-checkout .ast-checkout-control-nav li:nth-child(2) a:after '    => array(
				'border-color' => $two_step_nav_colors_light,
			),
		);

		$css_output .= astra_parse_css( $two_step_checkout );
	}

	$product_width = array(
		'.woocommerce #content .ast-woocommerce-container div.product div.images, .woocommerce .ast-woocommerce-container div.product div.images, .woocommerce-page #content .ast-woocommerce-container div.product div.images, .woocommerce-page .ast-woocommerce-container div.product div.images' => array(
			'width' => $product_img_width . '%',
		),

		'.woocommerce #content .ast-woocommerce-container div.product div.summary, .woocommerce .ast-woocommerce-container div.product div.summary, .woocommerce-page #content .ast-woocommerce-container div.product div.summary, .woocommerce-page .ast-woocommerce-container div.product div.summary' => array(
			'width' => $product_desc_width . '%',
		),
	);

	$left_position = (int) $product_img_width * 25 / 100;
	$css_output   .= '@media screen and ( min-width: 769px ) { .woocommerce div.product.ast-product-gallery-layout-vertical .onsale {
		left: ' . $left_position . '%;
		left: -webkit-calc(' . $left_position . '% - .5em);
		left: calc(' . $left_position . '% - .5em);
	} .woocommerce div.product.ast-product-gallery-with-no-image .onsale {
		left: -.5em;
	} }';

	/* Parse CSS from array()*/
	$css_output .= astra_parse_css( $product_width, '769' );

	/* Checkout Width */
	if ( 'custom' === $checkout_width ) :
			$checkout_css  = '@media (min-width:769px) {';
			$checkout_css .= '.woocommerce-checkout form.checkout {';
			$checkout_css .= 'max-width:' . esc_attr( $checkout_custom_width ) . 'px;';
			$checkout_css .= 'margin:' . esc_attr( '0 auto' ) . ';';
			$checkout_css .= '}';
			$checkout_css .= '}';
			$css_output   .= $checkout_css;
	endif;

	$tablet_css = array(

		'.single-product div.product .entry-title' => array(
			'font-size' => astra_responsive_font( $product_title_font_size, 'tablet' ),
		),
		// Single Product Content.
		'.single-product div.product .woocommerce-product-details__short-description, .single-product div.product .product_meta, .single-product div.product .entry-content' => array(
			'font-size' => astra_responsive_font( $product_content_font_size, 'tablet' ),
		),
		'.single-product div.product p.price, .single-product div.product span.price' => array(
			'font-size' => astra_responsive_font( $product_price_font_size, 'tablet' ),
		),
		'.single-product div.product .woocommerce-breadcrumb' => array(
			'font-size' => astra_responsive_font( $product_breadcrumb_font_size, 'tablet' ),
		),

		'.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce-page ul.products li.product .woocommerce-loop-product__title' => array(
			'font-size' => astra_responsive_font( $shop_product_title_font_size, 'tablet' ),
		),
		'.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price' => array(
			'font-size' => astra_responsive_font( $shop_product_price_font_size, 'tablet' ),
		),
		'.woocommerce ul.products li.product .ast-woo-product-category, .woocommerce-page ul.products li.product .ast-woo-product-category, .woocommerce ul.products li.product .ast-woo-shop-product-description, .woocommerce-page ul.products li.product .ast-woo-shop-product-description' => array(
			'font-size' => astra_responsive_font( $shop_product_content_font_size, 'tablet' ),
		),
	);
	$css_output .= astra_parse_css( $tablet_css, '', '768' );

	$mobile_css  = array(
		'.single-product div.product .entry-title' => array(
			'font-size' => astra_responsive_font( $product_title_font_size, 'mobile' ),
		),
		'.single-product div.product .woocommerce-product-details__short-description, .single-product div.product .product_meta, .single-product div.product .entry-content' => array(
			'font-size' => astra_responsive_font( $product_content_font_size, 'mobile' ),
		),
		'.single-product div.product p.price, .single-product div.product span.price' => array(
			'font-size' => astra_responsive_font( $product_price_font_size, 'mobile' ),
		),
		'.single-product div.product .woocommerce-breadcrumb' => array(
			'font-size' => astra_responsive_font( $product_breadcrumb_font_size, 'mobile' ),
		),
		'.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce-page ul.products li.product .woocommerce-loop-product__title' => array(
			'font-size' => astra_responsive_font( $shop_product_title_font_size, 'mobile' ),
		),
		'.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price' => array(
			'font-size' => astra_responsive_font( $shop_product_price_font_size, 'mobile' ),
		),
		'.woocommerce ul.products li.product .ast-woo-product-category, .woocommerce-page ul.products li.product .ast-woo-product-category, .woocommerce ul.products li.product .ast-woo-shop-product-description, .woocommerce-page ul.products li.product .ast-woo-shop-product-description' => array(
			'font-size' => astra_responsive_font( $shop_product_content_font_size, 'mobile' ),
		),
		'.ast-header-break-point .ast-above-header-mobile-inline.mobile-header-order-2 .ast-masthead-custom-menu-items.woocommerce-custom-menu-item' => array(
			'margin-left' => esc_attr( '0' ),
		),
		'.ast-header-break-point .ast-above-header-mobile-inline.mobile-header-order-3 .ast-masthead-custom-menu-items.woocommerce-custom-menu-item, .ast-header-break-point .ast-above-header-mobile-inline.mobile-header-order-5 .ast-masthead-custom-menu-items.woocommerce-custom-menu-item' => array(
			'margin-right' => esc_attr( '0' ),
		),
	);
	$css_output .= astra_parse_css( $mobile_css, '', '544' );

	return $dynamic_css . $css_output;

}

