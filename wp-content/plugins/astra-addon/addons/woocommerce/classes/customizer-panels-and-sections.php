<?php
/**
 * Register customizer panels & sections.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0.0
 */

	/**
	 * Section Checkout Page
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-checkout-page',
			array(
				'priority' => 25,
				'title'    => __( 'Checkout Page', 'astra-addon' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-woo-group',
			)
		)
	);

	/**
	 * WooCommerce
	 *
	 * Customizer > Typography
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-typo',
			array(
				'priority' => 60,
				'title'    => __( 'WooCommerce', 'astra-addon' ),
				'panel'    => 'panel-typography',
			)
		)
	);

	/**
	 * General
	 *
	 * Customizer > Typography > WooCommerce
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-general-typo',
			array(
				'priority' => 5,
				'title'    => __( 'General', 'astra-addon' ),
				'panel'    => 'panel-typography',
				'section'  => 'section-woo-typo',
			)
		)
	);

	/**
	 * Shop
	 *
	 * Customizer > Typography > WooCommerce
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-shop-typo',
			array(
				'priority' => 10,
				'title'    => __( 'Shop', 'astra-addon' ),
				'panel'    => 'panel-typography',
				'section'  => 'section-woo-typo',
			)
		)
	);

	/**
	 * Single Product
	 *
	 * Customizer > Typography > WooCommerce
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-single-product-typo',
			array(
				'priority' => 15,
				'title'    => __( 'Single Product', 'astra-addon' ),
				'panel'    => 'panel-typography',
				'section'  => 'section-woo-typo',
			)
		)
	);

	/**
	 * WooCommerce
	 *
	 * Customizer > Colors & Background
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-colors-bg',
			array(
				'priority' => 60,
				'title'    => __( 'WooCommerce', 'astra-addon' ),
				'panel'    => 'panel-colors-background',
			)
		)
	);

	/**
	 * General
	 *
	 * Customizer > Colors & Background > WooCommerce
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-general-color',
			array(
				'priority' => 5,
				'title'    => __( 'General', 'astra-addon' ),
				'panel'    => 'panel-colors-background',
				'section'  => 'section-woo-colors-bg',
			)
		)
	);

	/**
	 * Shop
	 *
	 * Customizer > Colors & Background > WooCommerce
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-shop-color',
			array(
				'priority' => 10,
				'title'    => __( 'Shop', 'astra-addon' ),
				'panel'    => 'panel-colors-background',
				'section'  => 'section-woo-colors-bg',
			)
		)
	);

	/**
	 * Single Product
	 *
	 * Customizer > Colors & Background > WooCommerce
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-woo-single-product-color',
			array(
				'priority' => 15,
				'title'    => __( 'Single Product', 'astra-addon' ),
				'panel'    => 'panel-colors-background',
				'section'  => 'section-woo-colors-bg',
			)
		)
	);
