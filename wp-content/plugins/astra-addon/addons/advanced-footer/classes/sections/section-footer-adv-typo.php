<?php
/**
 * Footer Widgets Options for our theme.
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
	 * Footer Widgets Title Typography
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-typo-title]', array(
				'label'    => __( 'Widget Title', 'astra-addon' ),
				'type'     => 'ast-divider',
				'section'  => 'section-footer-adv-typo',
				'settings' => array(),
				'priority' => 5,
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-family]', array(
			'default'           => astra_get_option( 'footer-adv-wgt-title-font-family' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-family]', array(
				'type'    => 'ast-font-family',
				'label'   => __( 'Font Family', 'astra-addon' ),
				'section' => 'section-footer-adv-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-weight]',
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-weight]', array(
			'default'           => astra_get_option( 'footer-adv-wgt-title-font-weight' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-weight]', array(
				'type'    => 'ast-font-weight',
				'label'   => __( 'Font Weight', 'astra-addon' ),
				'section' => 'section-footer-adv-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-family]',
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-text-transform]', array(
			'default'           => astra_get_option( 'footer-adv-wgt-title-text-transform' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-text-transform]', array(
			'section' => 'section-footer-adv-typo',
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

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-size]', array(
			'default'           => astra_get_option( 'footer-adv-wgt-title-font-size' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-size]', array(
				'section'     => 'section-footer-adv-typo',
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

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-line-height]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-line-height]', array(
				'section'     => 'section-footer-adv-typo',
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

	/**
	 * Footer Widgets Content Typography
	 */

	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-typo-content]', array(
				'label'    => __( 'Widget Content', 'astra-addon' ),
				'type'     => 'ast-divider',
				'section'  => 'section-footer-adv-typo',
				'settings' => array(),
				'priority' => 10,
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-family]', array(
			'default'           => astra_get_option( 'footer-adv-wgt-content-font-family' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-family]', array(
				'type'    => 'ast-font-family',
				'label'   => __( 'Font Family', 'astra-addon' ),
				'section' => 'section-footer-adv-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-weight]',
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-weight]', array(
			'default'           => astra_get_option( 'footer-adv-wgt-content-font-weight' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-weight]', array(
				'type'    => 'ast-font-weight',
				'label'   => __( 'Font Weight', 'astra-addon' ),
				'section' => 'section-footer-adv-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-family]',
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-text-transform]', array(
			'default'           => astra_get_option( 'footer-adv-wgt-content-text-transform' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-text-transform]', array(
			'section' => 'section-footer-adv-typo',
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

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-size]', array(
			'default'           => astra_get_option( 'footer-adv-wgt-content-font-size' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-size]', array(
				'section'     => 'section-footer-adv-typo',
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

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-line-height]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-line-height]', array(
				'section'     => 'section-footer-adv-typo',
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
