<?php
/**
 * Section [Footer] options for astra theme.
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
	 * Option: Footer Content Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-footer-content]', array(
			'default'           => astra_get_option( 'font-family-footer-content' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-footer-content]', array(
				'type'    => 'ast-font-family',
				'label'   => __( 'Font Family', 'astra-addon' ),
				'section' => 'section-footer-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[font-weight-footer-content]',
			)
		)
	);

	/**
	 * Option: Footer Content Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-footer-content]', array(
			'default'           => astra_get_option( 'font-weight-footer-content' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-footer-content]', array(
				'type'    => 'ast-font-weight',
				'label'   => __( 'Font Weight', 'astra-addon' ),
				'section' => 'section-footer-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[font-family-footer-content]',
			)
		)
	);

	/**
	 * Option: Footer Content Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-footer-content]', array(
			'default'           => astra_get_option( 'text-transform-footer-content' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-footer-content]', array(
			'section' => 'section-footer-typo',
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
	 * Option: Footer Content Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-footer-content]', array(
			'default'           => astra_get_option( 'font-size-footer-content' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-footer-content]', array(
				'section'     => 'section-footer-typo',
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

	/**
	 * Option: Footer Content Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-footer-content]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-footer-content]', array(
				'section'     => 'section-footer-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			)
		)
	);

