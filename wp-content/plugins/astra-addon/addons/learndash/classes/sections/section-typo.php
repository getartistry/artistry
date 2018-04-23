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
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-table-tapography-divider]', array(
				'label'    => __( 'LearnDash Tables', 'astra-addon' ),
				'section'  => 'section-learndash-typo',
				'type'     => 'ast-divider',
				'priority' => 5,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-learndash-table-heading]', array(
				'label'    => __( 'Table Heading', 'astra-addon' ),
				'section'  => 'section-learndash-typo',
				'type'     => 'ast-divider',
				'settings' => array(),
				'priority' => 10,
			)
		)
	);

	/**
	 * Option: Table Heading Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-learndash-table-heading]', array(
			'default'           => astra_get_option( 'font-family-learndash-table-heading' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-learndash-table-heading]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-learndash-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-heading]',
				'priority' => 15,
			)
		)
	);

	/**
	 * Option: Table Heading Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-heading]', array(
			'default'           => astra_get_option( 'font-weight-learndash-table-heading' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-heading]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-learndash-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-learndash-table-heading]',
				'priority' => 20,
			)
		)
	);

	/**
	 * Option: Table Heading Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-learndash-table-heading]', array(
			'default'           => astra_get_option( 'text-transform-learndash-table-heading' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-learndash-table-heading]', array(
			'section'  => 'section-learndash-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
			'priority' => 25,
		)
	);

	/**
	 * Option: Table Heading Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-learndash-table-heading]', array(
			'default'           => astra_get_option( 'font-size-learndash-table-heading' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-learndash-table-heading]', array(
				'section'     => 'section-learndash-typo',
				'label'       => __( 'Font Size', 'astra-addon' ),
				'type'        => 'ast-responsive',
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
				'priority'    => 30,
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-learndash-table-content]', array(
				'label'    => __( 'Table Content', 'astra-addon' ),
				'section'  => 'section-learndash-typo',
				'type'     => 'ast-divider',
				'settings' => array(),
				'priority' => 35,
			)
		)
	);

	/**
	 * Option: Table Heading Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-learndash-table-content]', array(
			'default'           => astra_get_option( 'font-family-learndash-table-content' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-learndash-table-content]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-learndash-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-content]',
				'priority' => 40,
			)
		)
	);

	/**
	 * Option: Table Heading Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-content]', array(
			'default'           => astra_get_option( 'font-weight-learndash-table-content' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-content]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-learndash-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-learndash-table-content]',
				'priority' => 45,
			)
		)
	);

	/**
	 * Option: Table Heading Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-learndash-table-content]', array(
			'default'           => astra_get_option( 'text-transform-learndash-table-content' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-learndash-table-content]', array(
			'section'  => 'section-learndash-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
			'priority' => 50,
		)
	);

	/**
	 * Option: Table Heading Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-learndash-table-content]', array(
			'default'           => astra_get_option( 'font-size-learndash-table-content' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-learndash-table-content]', array(
				'section'     => 'section-learndash-typo',
				'label'       => __( 'Font Size', 'astra-addon' ),
				'type'        => 'ast-responsive',
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
				'priority'    => 55,
			)
		)
	);

