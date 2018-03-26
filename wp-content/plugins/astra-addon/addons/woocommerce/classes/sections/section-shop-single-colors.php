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
	 * Single Product Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-title-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-title-color]', array(
				'label'   => __( 'Product Title Color', 'astra-addon' ),
				'section' => 'section-woo-single-product-color',
			)
		)
	);

	/**
	 * Single Product Price Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-price-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-price-color]', array(
				'label'   => __( 'Product Price Color', 'astra-addon' ),
				'section' => 'section-woo-single-product-color',
			)
		)
	);

	/**
	 * Single Product Content Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-content-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-content-color]', array(
				'label'   => __( 'Product Content Color', 'astra-addon' ),
				'section' => 'section-woo-single-product-color',
			)
		)
	);

	/**
	 * Single Product Breadcrumb Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-product-breadcrumb-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[single-product-breadcrumb-color]', array(
				'label'   => __( 'Product Breadcrumb Color', 'astra-addon' ),
				'section' => 'section-woo-single-product-color',
			)
		)
	);
