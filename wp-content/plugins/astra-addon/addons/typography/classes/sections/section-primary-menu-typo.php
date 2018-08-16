<?php
/**
 * [Primary Menu] options for astra theme.
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
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-primary-menu-typo-main]', array(
				'label'    => __( 'Menu', 'astra-addon' ),
				'type'     => 'ast-divider',
				'section'  => 'section-primary-header-typo',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Primary Menu Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-primary-menu]', array(
			'default'           => astra_get_option( 'font-family-primary-menu' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-primary-menu]', array(
				'type'    => 'ast-font-family',
				'label'   => __( 'Font Family', 'astra-addon' ),
				'section' => 'section-primary-header-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[font-weight-primary-menu]',
			)
		)
	);

	/**
	 * Option: Primary Menu Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-primary-menu]', array(
			'default'           => astra_get_option( 'font-weight-primary-menu' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-primary-menu]', array(
				'type'    => 'ast-font-weight',
				'label'   => __( 'Font Weight', 'astra-addon' ),
				'section' => 'section-primary-header-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[font-family-primary-menu]',
			)
		)
	);

	/**
	 * Option: Primary Menu Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-primary-menu]', array(
			'default'           => astra_get_option( 'text-transform-primary-menu' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-primary-menu]', array(
			'section' => 'section-primary-header-typo',
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
	 * Option: Primary Menu Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-primary-menu]', array(
			'default'           => astra_get_option( 'font-size-primary-menu' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-primary-menu]', array(
				'section'     => 'section-primary-header-typo',
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
	 * Option: Primary Menu Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-primary-menu]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-primary-menu]', array(
				'section'     => 'section-primary-header-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 10,
				),
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-primary-menu-typo-dropdown]', array(
				'label'    => __( 'Submenu', 'astra-addon' ),
				'type'     => 'ast-divider',
				'section'  => 'section-primary-header-typo',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Primary Submenu Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-primary-dropdown-menu]', array(
			'default'           => astra_get_option( 'font-family-primary-dropdown-menu' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-primary-dropdown-menu]', array(
				'type'    => 'ast-font-family',
				'label'   => __( 'Font Family', 'astra-addon' ),
				'section' => 'section-primary-header-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[font-weight-primary-dropdown-menu]',
			)
		)
	);

	/**
	 * Option: Primary Submenu Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-primary-dropdown-menu]', array(
			'default'           => astra_get_option( 'font-weight-primary-dropdown-menu' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-primary-dropdown-menu]', array(
				'type'    => 'ast-font-weight',
				'label'   => __( 'Font Weight', 'astra-addon' ),
				'section' => 'section-primary-header-typo',
				'connect' => ASTRA_THEME_SETTINGS . '[font-family-primary-dropdown-menu]',
			)
		)
	);

	/**
	 * Option: Primary Submenu Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-primary-dropdown-menu]', array(
			'default'           => astra_get_option( 'text-transform-primary-dropdown-menu' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-primary-dropdown-menu]', array(
			'section' => 'section-primary-header-typo',
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
	 * Option: Primary Submenu Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-primary-dropdown-menu]', array(
			'default'           => astra_get_option( 'font-size-primary-dropdown-menu' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-primary-dropdown-menu]', array(
				'section'     => 'section-primary-header-typo',
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
	 * Option: Primary Submenu Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-primary-dropdown-menu]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-primary-dropdown-menu]', array(
				'section'     => 'section-primary-header-typo',
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
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-outside-menu-typo-dropdown]', array(
				'label'    => __( 'Outside menu item', 'astra-addon' ),
				'type'     => 'ast-divider',
				'section'  => 'section-primary-header-typo',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Outside menu font size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[outside-menu-font-size]', array(
			'default'           => astra_get_option( 'outside-menu-font-size' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[outside-menu-font-size]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-primary-header-typo',
				'priority'    => 20,
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
	 * Option: outside Menu Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[outside-menu-line-height]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[outside-menu-line-height]', array(
				'section'     => 'section-primary-header-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'priority'    => 20,
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 10,
				),
			)
		)
	);

