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
	 * Shop Product Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-product-title-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-product-title-color]', array(
				'label'   => __( 'Product Title Color', 'astra-addon' ),
				'section' => 'section-woo-shop-color',
			)
		)
	);

	/**
	 * Shop Product Price Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-product-price-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-product-price-color]', array(
				'label'   => __( 'Product Price Color', 'astra-addon' ),
				'section' => 'section-woo-shop-color',
			)
		)
	);

	/**
	 * Shop Product Content Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[shop-product-content-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[shop-product-content-color]', array(
				'label'   => __( 'Product Content Color', 'astra-addon' ),
				'section' => 'section-woo-shop-color',
			)
		)
	);
