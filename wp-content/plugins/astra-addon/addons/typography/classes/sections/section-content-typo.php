<?php
/**
 * Section [Content] options for astra theme.
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
	 * Option: Heading <H1> Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-h1]', array(
			'default'           => astra_get_option( 'font-family-h1' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-h1]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 4,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-h1]',
			)
		)
	);

	/**
	 * Option: Heading <H1> Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-h1]', array(
			'default'           => astra_get_option( 'font-weight-h1' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-h1]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 4,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-h1]',
			)
		)
	);

	/**
	 * Option: Heading <H1> Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-h1]', array(
			'default'           => astra_get_option( 'text-transform-h1' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-h1]', array(
			'section'  => 'section-content-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 4,
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Heading <H1> Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-h1]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-h1]', array(
				'section'     => 'section-content-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'priority'    => 5,
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
	 * Option: Heading <H2> Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-h2]', array(
			'default'           => astra_get_option( 'font-family-h2' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-h2]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 9,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-h2]',
			)
		)
	);

	/**
	 * Option: Heading <H2> Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-h2]', array(
			'default'           => astra_get_option( 'font-weight-h2' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-h2]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 9,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-h2]',
			)
		)
	);

	/**
	 * Option: Heading <H2> Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-h2]', array(
			'default'           => astra_get_option( 'text-transform-h2' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-h2]', array(
			'section'  => 'section-content-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 9,
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Heading <H2> Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-h2]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-h2]', array(
				'section'     => 'section-content-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'priority'    => 10,
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
	 * Option: Heading <H3> Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-h3]', array(
			'default'           => astra_get_option( 'font-family-h3' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-h3]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 14,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-h3]',
			)
		)
	);

	/**
	 * Option: Heading <H3> Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-h3]', array(
			'default'           => astra_get_option( 'font-weight-h3' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-h3]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 14,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-h3]',
			)
		)
	);

	/**
	 * Option: Heading <H3> Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-h3]', array(
			'default'           => astra_get_option( 'text-transform-h3' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-h3]', array(
			'section'  => 'section-content-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 14,
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Heading <H3> Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-h3]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-h3]', array(
				'section'     => 'section-content-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'priority'    => 15,
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
	 * Option: Heading <H4> Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-h4]', array(
			'default'           => astra_get_option( 'font-family-h4' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-h4]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 19,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-h4]',
			)
		)
	);

	/**
	 * Option: Heading <H4> Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-h4]', array(
			'default'           => astra_get_option( 'font-weight-h4' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-h4]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 19,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-h4]',
			)
		)
	);

	/**
	 * Option: Heading <H4> Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-h4]', array(
			'default'           => astra_get_option( 'text-transform-h4' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-h4]', array(
			'section'  => 'section-content-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 19,
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Heading <H4> Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-h4]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-h4]', array(
				'section'     => 'section-content-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'priority'    => 20,
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
	 * Option: Heading <H5> Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-h5]', array(
			'default'           => astra_get_option( 'font-family-h5' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-h5]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 24,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-h5]',
			)
		)
	);

	/**
	 * Option: Heading <H5> Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-h5]', array(
			'default'           => astra_get_option( 'font-weight-h5' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-h5]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 24,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-h5]',
			)
		)
	);

	/**
	 * Option: Heading <H5> Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-h5]', array(
			'default'           => astra_get_option( 'text-transform-h5' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-h5]', array(
			'section'  => 'section-content-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 24,
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Heading <H5> Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-h5]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-h5]', array(
				'section'     => 'section-content-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'priority'    => 25,
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
	 * Option: Heading <H6> Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-h6]', array(
			'default'           => astra_get_option( 'font-family-h6' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-h6]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 29,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-h6]',
			)
		)
	);

	/**
	 * Option: Heading <H6> Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-h6]', array(
			'default'           => astra_get_option( 'font-weight-h6' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-h6]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-content-typo',
				'priority' => 29,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-h6]',
			)
		)
	);

	/**
	 * Option: Heading <H6> Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-h6]', array(
			'default'           => astra_get_option( 'text-transform-h6' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-h6]', array(
			'section'  => 'section-content-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 29,
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Heading <H6> Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-h6]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-h6]', array(
				'section'     => 'section-content-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'priority'    => 30,
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			)
		)
	);

