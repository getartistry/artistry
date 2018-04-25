<?php
/**
 * Above Header - Typography Options for our theme.
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
	 * Option: Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-font-family]', array(
			'default'           => astra_get_option( 'above-header-font-family' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-font-family]', array(
				'type'    => 'ast-font-family',
				'label'   => __( 'Font Family', 'astra-addon' ),
				'section' => 'section-above-header-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[above-header-font-weight]',
			)
		)
	);

	/**
	 * Option: Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-font-weight]', array(
			'default'           => astra_get_option( 'above-header-font-weight' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-font-weight]', array(
				'type'    => 'ast-font-weight',
				'label'   => __( 'Font Weight', 'astra-addon' ),
				'section' => 'section-above-header-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[above-header-font-family]',
			)
		)
	);

	/**
	 * Option: Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-text-transform]', array(
			'default'           => astra_get_option( 'above-header-text-transform' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-text-transform]', array(
			'section' => 'section-above-header-typo',
			'label'   => __( 'Text Transform', 'astra-addon' ),
			'type'    => 'select',
			'choices' => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-font-size]', array(
			'default'           => astra_get_option( 'above-header-font-size' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-font-size]', array(
				'section'     => 'section-above-header-typo',
				'label'       => __( 'Font Size', 'astra-addon' ),
				'type'        => 'ast-responsive',
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
			)
		)
	);

