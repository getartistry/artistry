<?php
/**
 * Shop Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	/**
	 * Option: Product Gallery Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-gallery-layout]', array(
			'default'           => astra_get_option( 'single-product-gallery-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-product-gallery-layout]', array(
			'section'  => 'section-woo-shop-single',
			'label'    => __( 'Gallery Layout', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 5,
			'choices'  => array(
				'vertical'   => __( 'Vertical', 'astra-addon' ),
				'horizontal' => __( 'Horizontal', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Product Image Width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-image-width]', array(
			'default'           => astra_get_option( 'single-product-image-width' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-image-width]', array(
				'type'        => 'ast-slider',
				'section'     => 'section-woo-shop-single',
				'label'       => __( 'Image Width', 'astra-addon' ),
				'suffix'      => '%',
				'priority'    => 5,
				'input_attrs' => array(
					'min'  => 20,
					'step' => 1,
					'max'  => 70,
				),
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-meta-divider]', array(
				'section'  => 'section-woo-shop-single',
				'type'     => 'ast-divider',
				'settings' => array(),
				'priority' => 9,
			)
		)
	);

	/**
	 * Option: Single Post Meta
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-structure]', array(
			'default'           => astra_get_option( 'single-product-structure' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Sortable(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-structure]', array(
				'type'     => 'ast-sortable',
				'section'  => 'section-woo-shop-single',
				'label'    => __( 'Single Product Structure', 'astra-addon' ),
				'priority' => 15,
				'choices'  => array(
					'title'      => __( 'Title', 'astra-addon' ),
					'price'      => __( 'Price', 'astra-addon' ),
					'ratings'    => __( 'Ratings', 'astra-addon' ),
					'add_cart'   => __( 'Add To Cart', 'astra-addon' ),
					'short_desc' => __( 'Short Description', 'astra-addon' ),
					'meta'       => __( 'Meta', 'astra-addon' ),
				),
			)
		)
	);

	/**
	 * Option: Enable Ajax add to cart.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-ajax-add-to-cart]', array(
			'default'           => astra_get_option( 'single-product-ajax-add-to-cart' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-product-ajax-add-to-cart]', array(
			'section'  => 'section-woo-shop-single',
			'label'    => __( 'Enable Ajax Add To Cart', 'astra-addon' ),
			'priority' => 18,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable product zoom effect.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-image-zoom-effect]', array(
			'default'           => astra_get_option( 'single-product-image-zoom-effect' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-product-image-zoom-effect]', array(
			'section'  => 'section-woo-shop-single',
			'label'    => __( 'Enable Image Zoom Effect', 'astra-addon' ),
			'priority' => 18,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Navigation Style
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-nav-style]', array(
			'default' => astra_get_option( 'single-product-nav-style' ),
			'type'    => 'option',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-product-nav-style]', array(
			'section'  => 'section-woo-shop-single',
			'label'    => __( 'Product Navigation', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 20,
			'choices'  => array(
				'disable'        => __( 'Disable', 'astra-addon' ),
				'circle'         => __( 'Circle', 'astra-addon' ),
				'circle-outline' => __( 'Circle Outline', 'astra-addon' ),
				'square'         => __( 'Square', 'astra-addon' ),
				'square-outline' => __( 'Square Outline', 'astra-addon' ),
			),
		)
	);


	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-tabs-divider]', array(
				'section'  => 'section-woo-shop-single',
				'label'    => __( 'Product Description Tabs', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 25,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Enable Product Tabs Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-tabs-display]', array(
			'default'           => astra_get_option( 'single-product-tabs-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-product-tabs-display]', array(
			'section'  => 'section-woo-shop-single',
			'label'    => __( 'Display Product Tabs', 'astra-addon' ),
			'type'     => 'checkbox',
			'priority' => 30,
		)
	);

	/**
	 * Option: Product Tabs Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-tabs-layout]', array(
			'default'           => astra_get_option( 'single-product-tabs-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-product-tabs-layout]', array(
			'section'  => 'section-woo-shop-single',
			'label'    => __( 'Layout', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 35,
			'choices'  => array(
				'horizontal' => __( 'Horizontal', 'astra-addon' ),
				'vertical'   => __( 'Vertical', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-related-upsell-divider]', array(
				'section'  => 'section-woo-shop-single',
				'type'     => 'ast-divider',
				'label'    => __( 'Related & Up Sell Products', 'astra-addon' ),
				'settings' => array(),
				'priority' => 55,
			)
		)
	);


	/**
	 * Option: Display related products
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-related-display]', array(
			'default'           => astra_get_option( 'single-product-related-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-product-related-display]', array(
			'section'  => 'section-woo-shop-single',
			'label'    => __( 'Display related products', 'astra-addon' ),
			'type'     => 'checkbox',
			'priority' => 60,
		)
	);

	/**
	 * Option: Display Up Sells
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-up-sells-display]', array(
			'default'           => astra_get_option( 'single-product-up-sells-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-product-up-sells-display]', array(
			'section'  => 'section-woo-shop-single',
			'label'    => __( 'Display Up Sells', 'astra-addon' ),
			'type'     => 'checkbox',
			'priority' => 65,
		)
	);

	/**
	 * Option: Related Product Columns
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-related-upsell-grid]', array(
			'default'           => array(
				'desktop' => 4,
				'tablet'  => 3,
				'mobile'  => 2,
			),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-related-upsell-grid]', array(
				'type'        => 'ast-responsive-slider',
				'section'     => 'section-woo-shop-single',
				'priority'    => 70,
				'label'       => __( 'Columns', 'astra-addon' ),
				'input_attrs' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 6,
				),
			)
		)
	);

	/**
	 * Option: No. of Related Product
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-related-upsell-per-page]', array(
			'default'           => astra_get_option( 'single-product-related-upsell-per-page' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-product-related-upsell-per-page]', array(
			'section'  => 'section-woo-shop-single',
			'label'    => __( 'No. of Related Product', 'astra-addon' ),
			'type'     => 'number',
			'priority' => 75,
		)
	);
