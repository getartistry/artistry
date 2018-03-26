<?php
/**
 * Woocommerce General Options for our theme.
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
	 * Option: Sale Notification
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[product-sale-notification]', array(
			'default'           => astra_get_option( 'product-sale-notification' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[product-sale-notification]', array(
			'section'  => 'section-woo-general',
			'label'    => __( 'Sale Notification', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 15,
			'choices'  => array(
				'none'            => __( 'None', 'astra-addon' ),
				'default'         => __( 'Default', 'astra-addon' ),
				'sale-percentage' => __( 'Custom String', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Sale Percentage Input
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[product-sale-percent-value]', array(
			'default'           => astra_get_option( 'product-sale-percent-value' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[product-sale-percent-value]', array(
			'section'     => 'section-woo-general',
			'label'       => __( 'Sale % Value', 'astra-addon' ),
			'description' => __( 'Sale percentage(%) value = [value]', 'astra-addon' ),
			'type'        => 'text',
			'priority'    => 20,
			'input_attrs' => array(
				'placeholder' => astra_get_option( 'product-sale-percent-value' ),
			),
		)
	);

	/**
	 * Option: Sale Bubble Shape
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[product-sale-style]', array(
			'default'           => astra_get_option( 'product-sale-style' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[product-sale-style]', array(
			'section'  => 'section-woo-general',
			'label'    => __( 'Sale Bubble Style', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 25,
			'choices'  => array(
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-cart-icon-divider]', array(
				'section'  => 'section-woo-general',
				'label'    => __( 'Header Cart Icon', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 30,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Header Cart Icon
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-icon]', array(
			'default'           => astra_get_option( 'woo-header-cart-icon' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-icon]', array(
			'section'  => 'section-woo-general',
			'label'    => __( 'Icon', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 35,
			'choices'  => array(
				'default' => __( 'Default', 'astra-addon' ),
				'cart'    => __( 'Cart', 'astra-addon' ),
				'bag'     => __( 'Bag', 'astra-addon' ),
				'basket'  => __( 'Basket', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Icon Style
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-icon-style]', array(
			'default'           => astra_get_option( 'woo-header-cart-icon-style' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-icon-style]', array(
			'section'  => 'section-woo-general',
			'label'    => __( 'Style', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 40,
			'choices'  => array(
				'none'    => __( 'None', 'astra-addon' ),
				'outline' => __( 'Outline', 'astra-addon' ),
				'fill'    => __( 'Fill', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Background color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-icon-color]', array(
			'default'           => astra_get_option( 'woo-header-cart-icon-color' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[woo-header-cart-icon-color]', array(
				'type'     => 'ast-color',
				'label'    => __( 'Color', 'astra-addon' ),
				'section'  => 'section-woo-general',
				'priority' => 45,
			)
		)
	);

	/**
	 * Option: Border Radius
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-icon-radius]', array(
			'default'           => astra_get_option( 'woo-header-cart-icon-radius' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-icon-radius]', array(
			'section'     => 'section-woo-general',
			'label'       => __( 'Border Radius', 'astra-addon' ),
			'type'        => 'number',
			'priority'    => 47,
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 200,
			),
		)
	);

	/**
	 * Option: Header cart total
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-total-display]', array(
			'default'           => astra_get_option( 'woo-header-cart-total-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-total-display]', array(
			'section'  => 'section-woo-general',
			'label'    => __( 'Display Cart Totals', 'astra-addon' ),
			'priority' => 50,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Cart Title
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-title-display]', array(
			'default'           => astra_get_option( 'woo-header-cart-title-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[woo-header-cart-title-display]', array(
			'section'  => 'section-woo-general',
			'label'    => __( 'Display Cart Title', 'astra-addon' ),
			'priority' => 55,
			'type'     => 'checkbox',
		)
	);
