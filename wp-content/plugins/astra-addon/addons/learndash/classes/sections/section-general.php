<?php
/**
 * LearnDash General Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Distraction Free Learning
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-distraction-free-learning]', array(
			'default'           => astra_get_option( 'learndash-distraction-free-learning' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[learndash-distraction-free-learning]', array(
			'section'     => 'section-learndash',
			'label'       => __( 'Enable Distraction Free Learning', 'astra-addon' ),
			'description' => __( 'Remove extra links in the header and footer in LearnDash learning pages', 'astra-addon' ),
			'priority'    => 5,
			'type'        => 'checkbox',
		)
	);

	/**
	 * Option: Enable Header Profile Link
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-profile-link-enabled]', array(
			'default'           => astra_get_option( 'learndash-profile-link-enabled' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[learndash-profile-link-enabled]', array(
			'section'  => 'section-learndash',
			'label'    => __( 'Display Student\'s Gravatar in Primary Header', 'astra-addon' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Profile Link
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-profile-link]', array(
			'default'           => astra_get_option( 'learndash-profile-link' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[learndash-profile-link]', array(
			'section'  => 'section-learndash',
			'label'    => __( 'Profile Picture Links to:', 'astra-addon' ),
			'priority' => 15,
			'type'     => 'text',
		)
	);

	/**
	 * Option: Table Border Radius
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-table-border-radius]', array(
			'default'           => '0',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-table-border-radius]', array(
				'type'        => 'ast-slider',
				'label'       => __( 'Table Border Radius', 'astra-addon' ),
				'section'     => 'section-learndash',
				'suffix'      => '',
				'priority'    => 35,
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 50,
				),
			)
		)
	);
