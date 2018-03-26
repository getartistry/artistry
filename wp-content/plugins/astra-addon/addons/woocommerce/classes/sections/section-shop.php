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
	 * Option: Choose Product Style
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-style]', array(
			'default'           => astra_get_option( 'shop-style' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-style]', array(
				'section'  => 'section-woo-shop',
				'label'    => __( 'Choose Product Style', 'astra-addon' ),
				'type'     => 'ast-radio-image',
				'priority' => 5,
				'choices'  => array(
					'shop-page-grid-style' => array(
						'label' => __( 'Grid View', 'astra-addon' ),
						'path'  => ASTRA_EXT_WOOCOMMERCE_URI . 'assets/images/blog-layout-1-76x48.png',
					),
					'shop-page-list-style' => array(
						'label' => __( 'List View', 'astra-addon' ),
						'path'  => ASTRA_EXT_WOOCOMMERCE_URI . 'assets/images/blog-layout-3-76x48.png',
					),
				),
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-box-styling]', array(
				'section'  => 'section-woo-shop',
				'label'    => __( 'Product Styling', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 75,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Content Alignment
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-product-align]', array(
			'default'           => astra_get_option( 'shop-product-align' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-product-align]', array(
			'type'     => 'select',
			'section'  => 'section-woo-shop',
			'priority' => 80,
			'label'    => __( 'Content Alignment', 'astra-addon' ),
			'choices'  => array(
				'align-left'   => __( 'Left', 'astra-addon' ),
				'align-center' => __( 'Center', 'astra-addon' ),
				'align-right'  => __( 'Right', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Box shadow
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-product-shadow]', array(
			'default'           => astra_get_option( 'shop-product-shadow' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-product-shadow]', array(
				'type'        => 'ast-slider',
				'label'       => __( 'Box Shadow', 'astra-addon' ),
				'section'     => 'section-woo-shop',
				'suffix'      => '',
				'priority'    => 85,
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 5,
				),
			)
		)
	);

	/**
	 * Option: Box hover shadow
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-product-shadow-hover]', array(
			'default'           => astra_get_option( 'shop-product-shadow-hover' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-product-shadow-hover]', array(
				'type'        => 'ast-slider',
				'label'       => __( 'Box Hover Shadow', 'astra-addon' ),
				'section'     => 'section-woo-shop',
				'suffix'      => '',
				'priority'    => 90,
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 5,
				),
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-button-divider]', array(
				'section'  => 'section-woo-shop',
				'label'    => __( 'Button', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 110,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Vertical Padding
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-button-v-padding]', array(
			'default'           => astra_get_option( 'shop-button-v-padding' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-button-v-padding]', array(
			'section'     => 'section-woo-shop',
			'label'       => __( 'Vertical Padding', 'astra-addon' ),
			'type'        => 'number',
			'priority'    => 110,
			'input_attrs' => array(
				'min'  => 1,
				'step' => 1,
				'max'  => 200,
			),
		)
	);

	/**
	 * Option: Horizontal Padding
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-button-h-padding]', array(
			'default'           => astra_get_option( 'shop-button-h-padding' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-button-h-padding]', array(
			'section'     => 'section-woo-shop',
			'priority'    => 110,
			'label'       => __( 'Horizontal Padding', 'astra-addon' ),
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'step' => 1,
				'max'  => 200,
			),
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-pagination-divider]', array(
				'section'  => 'section-woo-shop',
				'type'     => 'ast-divider',
				'priority' => 140,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Shop Pagination
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-pagination]', array(
			'default'           => astra_get_option( 'shop-pagination' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-pagination]', array(
			'type'     => 'select',
			'section'  => 'section-woo-shop',
			'priority' => 145,
			'label'    => __( 'Shop Pagination', 'astra-addon' ),
			'choices'  => array(
				'number'   => __( 'Number', 'astra-addon' ),
				'infinite' => __( 'Infinite Scroll', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Shop Pagination Style
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-pagination-style]', array(
			'default'           => astra_get_option( 'shop-pagination-style' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-pagination-style]', array(
			'type'     => 'select',
			'section'  => 'section-woo-shop',
			'priority' => 150,
			'label'    => __( 'Shop Pagination Style', 'astra-addon' ),
			'choices'  => array(
				'default' => __( 'Default', 'astra-addon' ),
				'square'  => __( 'Square', 'astra-addon' ),
				'circle'  => __( 'Circle', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Event to Trigger Infinite Loading
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-infinite-scroll-event]', array(
			'default'           => astra_get_option( 'shop-infinite-scroll-event' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-infinite-scroll-event]', array(
			'type'        => 'select',
			'section'     => 'section-woo-shop',
			'description' => __( 'Infinite Scroll cannot be previewed in the Customizer.', 'astra-addon' ),
			'priority'    => 155,
			'label'       => __( 'Event to Trigger Infinite Loading', 'astra-addon' ),
			'choices'     => array(
				'scroll' => __( 'Scroll', 'astra-addon' ),
				'click'  => __( 'Click', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Read more text
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-load-more-text]', array(
			'default'           => astra_get_option( 'shop-load-more-text' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-load-more-text]', array(
			'section'  => 'section-woo-shop',
			'priority' => 160,
			'label'    => __( 'Load More Text', 'astra-addon' ),
			'type'     => 'text',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			ASTRA_THEME_SETTINGS . '[shop-load-more-text]', array(
				'selector'            => '.ast-shop-pagination-infinite .ast-shop-load-more',
				'container_inclusive' => false,
				'render_callback'     => array( 'Astra_Customizer_Ext_WooCommerce_Partials', '_render_shop_load_more' ),
			)
		);
	}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-meta-divider]', array(
				'section'  => 'section-woo-shop',
				'type'     => 'ast-divider',
				'priority' => 29,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Display Page Title
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-page-title-display]', array(
			'default'           => astra_get_option( 'shop-page-title-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-page-title-display]', array(
			'section'  => 'section-woo-shop',
			'label'    => __( 'Display Page Title', 'astra-addon' ),
			'priority' => 29,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Display Breadcrumb
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-breadcrumb-display]', array(
			'default'           => astra_get_option( 'shop-breadcrumb-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-breadcrumb-display]', array(
			'section'  => 'section-woo-shop',
			'label'    => __( 'Display Breadcrumb', 'astra-addon' ),
			'priority' => 29,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Display Toolbar
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-toolbar-display]', array(
			'default'           => astra_get_option( 'shop-toolbar-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-toolbar-display]', array(
			'section'  => 'section-woo-shop',
			'label'    => __( 'Display Toolbar', 'astra-addon' ),
			'priority' => 29,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-filters-off-canvas-divider]', array(
				'section'  => 'section-woo-shop',
				'label'    => __( 'Off Canvas Sidebar', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 195,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Display Off Canvas On Click Of
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-off-canvas-trigger-type]', array(
			'default'           => astra_get_option( 'shop-off-canvas-trigger-type' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-off-canvas-trigger-type]', array(
			'type'     => 'select',
			'section'  => 'section-woo-shop',
			'priority' => 200,
			'label'    => __( 'Trigger for Off Canvas Sidebar', 'astra-addon' ),
			'choices'  => array(
				'disable'      => __( 'Disable', 'astra-addon' ),
				'link'         => __( 'Link', 'astra-addon' ),
				'button'       => __( 'Button', 'astra-addon' ),
				'custom-class' => __( 'Custom Class', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Filter Button Text
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-filter-trigger-link]', array(
			'default'           => astra_get_option( 'shop-filter-trigger-link' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-filter-trigger-link]', array(
			'section'  => 'section-woo-shop',
			'priority' => 205,
			'label'    => __( 'Off Canvas Button/Link Text', 'astra-addon' ),
			'type'     => 'text',
		)
	);

	/**
	 * Option: Custom Class
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-filter-trigger-custom-class]', array(
			'default'           => astra_get_option( 'shop-filter-trigger-custom-class' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-filter-trigger-custom-class]', array(
			'section'  => 'section-woo-shop',
			'priority' => 210,
			'label'    => __( 'Custom Class', 'astra-addon' ),
			'type'     => 'text',
		)
	);

	/**
	 * Option: Display Active Filters
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-active-filters-display]', array(
			'default'           => astra_get_option( 'shop-active-filters-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-active-filters-display]', array(
			'section'  => 'section-woo-shop',
			'label'    => __( 'Display Active Filters', 'astra-addon' ),
			'priority' => 215,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-quick-view-divider]', array(
				'section'  => 'section-woo-shop',
				'type'     => 'ast-divider',
				'priority' => 190,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Enable Quick View
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-quick-view-enable]', array(
			'default'           => astra_get_option( 'shop-quick-view-enable' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[shop-quick-view-enable]', array(
			'section'  => 'section-woo-shop',
			'label'    => __( 'Quick View', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 190,
			'choices'  => array(
				'disabled'       => __( 'Disabled', 'astra-addon' ),
				'on-image'       => __( 'On Image', 'astra-addon' ),
				'on-image-click' => __( 'On Image Click', 'astra-addon' ),
				'after-summary'  => __( 'After Summary', 'astra-addon' ),
			),
		)
	);
