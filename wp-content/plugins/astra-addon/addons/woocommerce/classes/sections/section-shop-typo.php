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
	 * Option: Product Title Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[typo-shop-product-title-divider]', array(
				'section'  => 'section-woo-shop-typo',
				'label'    => __( 'Product Title', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 5,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Product Title Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-shop-product-title]', array(
			'default'           => astra_get_option( 'font-family-shop-product-title' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-shop-product-title]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-woo-shop-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-shop-product-title]',
				'priority' => 5,
			)
		)
	);

	/**
	 * Option: Product Title Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-shop-product-title]', array(
			'default'           => astra_get_option( 'font-weight-shop-product-title' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-shop-product-title]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-woo-shop-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-shop-product-title]',
				'priority' => 5,
			)
		)
	);

	/**
		 * Option: Product Title Text Transform
		 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-shop-product-title]', array(
			'default'           => astra_get_option( 'text-transform-shop-product-title' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-shop-product-title]', array(
			'section'  => 'section-woo-shop-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 5,
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
	 * Option: Product Title Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-shop-product-title]', array(
			'default'           => astra_get_option( 'font-size-shop-product-title' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-shop-product-title]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-woo-shop-typo',
				'priority'    => 5,
				'label'       => __( 'Font Size', 'astra-addon' ),
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
	 * Option: Product Title Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-shop-product-title]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-shop-product-title]', array(
				'section'     => 'section-woo-shop-typo',
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
	 * Option: Product Price Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[typo-product-shop-price-divider]', array(
				'section'  => 'section-woo-shop-typo',
				'label'    => __( 'Product Price', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 10,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Product Price Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-shop-product-price]', array(
			'default'           => astra_get_option( 'font-family-shop-product-price' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-shop-product-price]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-woo-shop-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-shop-product-price]',
				'priority' => 10,
			)
		)
	);

	/**
	 * Option: Product Price Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-shop-product-price]', array(
			'default'           => astra_get_option( 'font-weight-shop-product-price' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-shop-product-price]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-woo-shop-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-shop-product-price]',
				'priority' => 10,
			)
		)
	);

	/**
	 * Option: Product Price Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-shop-product-price]', array(
			'default'           => astra_get_option( 'font-size-shop-product-price' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-shop-product-price]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-woo-shop-typo',
				'priority'    => 10,
				'label'       => __( 'Font Size', 'astra-addon' ),
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
	 * Option: Product Price Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-shop-product-price]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-shop-product-price]', array(
				'section'     => 'section-woo-shop-typo',
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
	 * Option: Product Content Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[typo-product-shop-content-divider]', array(
				'section'  => 'section-woo-shop-typo',
				'label'    => __( 'Product Content', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 15,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Product Content Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-shop-product-content]', array(
			'default'           => astra_get_option( 'font-family-shop-product-content' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-shop-product-content]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-woo-shop-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-shop-product-content]',
				'priority' => 15,
			)
		)
	);

	/**
	 * Option: Product Content Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-shop-product-content]', array(
			'default'           => astra_get_option( 'font-weight-shop-product-content' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-shop-product-content]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-woo-shop-typo',
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-shop-product-content',
				'priority' => 15,
			)
		)
	);

	/**
	 * Option: Product Title Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-shop-product-content]', array(
			'default'           => astra_get_option( 'text-transform-shop-product-content' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-shop-product-content]', array(
			'section'  => 'section-woo-shop-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 15,
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
	 * Option: Product Content Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-shop-product-content]', array(
			'default'           => astra_get_option( 'font-size-shop-product-content' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-shop-product-content]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-woo-shop-typo',
				'priority'    => 15,
				'label'       => __( 'Font Size', 'astra-addon' ),
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
	 * Option: Product Content Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-shop-product-content]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-shop-product-content]', array(
				'section'     => 'section-woo-shop-typo',
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
