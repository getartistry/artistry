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
	 * Option: Two Step Checkout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[two-step-checkout]', array(
			'default'           => astra_get_option( 'two-step-checkout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[two-step-checkout]', array(
			'section' => 'section-checkout-page',
			'label'   => __( 'Two Step Checkout', 'astra-addon' ),
			'type'    => 'checkbox',
		)
	);

	/**
	 * Option: Display Order Note on Checkout Page
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[checkout-order-notes-display]', array(
			'default'           => astra_get_option( 'checkout-order-notes-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[checkout-order-notes-display]', array(
			'section' => 'section-checkout-page',
			'label'   => __( 'Display Order Note', 'astra-addon' ),
			'type'    => 'checkbox',
		)
	);

	/**
	 * Option: Display Coupon on Checkout Page
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[checkout-coupon-display]', array(
			'default'           => astra_get_option( 'checkout-coupon-display' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[checkout-coupon-display]', array(
			'section' => 'section-checkout-page',
			'label'   => __( 'Display Apply Coupon Field', 'astra-addon' ),
			'type'    => 'checkbox',
		)
	);

	/*
	 * Option: Distraction free Checkout.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[checkout-distraction-free]', array(
			'default'           => astra_get_option( 'checkout-distraction-free' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[checkout-distraction-free]', array(
			'section' => 'section-checkout-page',
			'label'   => __( 'Distraction Free Checkout', 'astra-addon' ),
			'type'    => 'checkbox',
		)
	);

	/*
	 * Option: Replace Form lable with placeholder
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[checkout-labels-as-placeholders]', array(
			'default'           => astra_get_option( 'checkout-labels-as-placeholders' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[checkout-labels-as-placeholders]', array(
			'section' => 'section-checkout-page',
			'label'   => __( 'Use Labels as Placeholders', 'astra-addon' ),
			'type'    => 'checkbox',
		)
	);

	/*
	 * Option: Preserve form data.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[checkout-persistence-form-data]', array(
			'default'           => astra_get_option( 'checkout-persistence-form-data' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[checkout-persistence-form-data]', array(
			'section'     => 'section-checkout-page',
			'label'       => __( 'Persistent Checkout Form Data', 'astra-addon' ),
			'description' => __( 'Retain the Checkout form fields even if the visitor accidentally reloads the checkout page.', 'astra-addon' ),
			'type'        => 'checkbox',
		)
	);


	/**
	 * Option: Checkout Content Width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[checkout-content-width]', array(
			'default'           => astra_get_option( 'checkout-content-width' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[checkout-content-width]', array(
			'type'    => 'select',
			'section' => 'section-checkout-page',
			'label'   => __( 'Checkout Form Width', 'astra-addon' ),
			'choices' => array(
				'default' => __( 'Default', 'astra-addon' ),
				'custom'  => __( 'Custom', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Enter Width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[checkout-content-max-width]', array(
			'default'           => 1200,
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[checkout-content-max-width]', array(
				'type'        => 'ast-slider',
				'section'     => 'section-checkout-page',
				'label'       => __( 'Enter Width', 'astra-addon' ),
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 768,
					'step' => 1,
					'max'  => 1920,
				),
			)
		)
	);
