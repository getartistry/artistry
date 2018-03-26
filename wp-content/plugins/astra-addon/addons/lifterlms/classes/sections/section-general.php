<?php
/**
 * LifterLMS General Options for our theme.
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
	 * Option: Shop Columns
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[llms-course-grid]', array(
			'default'           => array(
				'desktop' => 3,
				'tablet'  => 2,
				'mobile'  => 1,
			),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[llms-course-grid]', array(
				'type'        => 'ast-responsive-slider',
				'section'     => 'section-lifterlms-general',
				'label'       => __( 'Course Columns', 'astra-addon' ),
				'priority'    => 5,
				'input_attrs' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 6,
				),
			)
		)
	);

	/**
	 * Option: Shop Columns
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[llms-membership-grid]', array(
			'default'           => array(
				'desktop' => 3,
				'tablet'  => 2,
				'mobile'  => 1,
			),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[llms-membership-grid]', array(
				'type'        => 'ast-responsive-slider',
				'section'     => 'section-lifterlms-general',
				'label'       => __( 'Membership Columns', 'astra-addon' ),
				'priority'    => 5,
				'input_attrs' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 6,
				),
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[lifterlms-distraction-free-checkout-divider]', array(
				'section'  => 'section-lifterlms-general',
				'label'    => __( 'Checkout', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 10,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Distraction Free Checkout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-distraction-free-checkout]', array(
			'default'           => astra_get_option( 'lifterlms-distraction-free-checkout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-distraction-free-checkout]', array(
			'section'  => 'section-lifterlms-general',
			'label'    => __( 'Distraction Free Checkout', 'astra-addon' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[lifterlms-my-account-vertical-divider]', array(
				'section'  => 'section-lifterlms-general',
				'label'    => __( 'My Account', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 15,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Enable Vertical Tab
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-my-account-vertical]', array(
			'default'           => astra_get_option( 'lifterlms-my-account-vertical' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-my-account-vertical]', array(
			'section'  => 'section-lifterlms-general',
			'label'    => __( 'Display Tabs Vertically', 'astra-addon' ),
			'priority' => 15,
			'type'     => 'checkbox',
		)
	);
