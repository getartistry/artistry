<?php
/**
 * Above Header - Layout Options for our theme.
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

$sections = apply_filters(
	'astra_header_section_elements',
	array(
		''          => __( 'None', 'astra-addon' ),
		'menu'      => __( 'Menu', 'astra-addon' ),
		'search'    => __( 'Search', 'astra-addon' ),
		'text-html' => __( 'Text / HTML', 'astra-addon' ),
		'widget'    => __( 'Widget', 'astra-addon' ),
	),
	'above-header'
);

	/**
	 * Option: Above Header Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-layout]', array(
			'default'           => astra_get_option( 'above-header-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-layout]', array(
				'section'  => 'section-above-header',
				'priority' => 1,
				'label'    => __( 'Above Header Layout', 'astra-addon' ),
				'type'     => 'ast-radio-image',
				'choices'  => array(
					'disabled'              => array(
						'label' => __( 'Disabled', 'astra-addon' ),
						'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/disabled-76x48.png',
					),
					'above-header-layout-1' => array(
						'label' => __( 'Above Header Layout 1', 'astra-addon' ),
						'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/above-header-1-76x47.png',
					),
					'above-header-layout-2' => array(
						'label' => __( 'Above Header Layout 2', 'astra-addon' ),
						'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/above-header-2-76x47.png',
					),
				),
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-layout-section-1-divider]', array(
				'section'  => 'section-above-header',
				'label'    => __( 'Section 1', 'astra-addon' ),
				'priority' => 5,
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 *  Section: Section
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-section-1]', array(
			'default'           => astra_get_option( 'above-header-section-1' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-section-1]', array(
			'section'  => 'section-above-header',
			'priority' => 35,
			'type'     => 'select',
			'choices'  => $sections,
		)
	);

	/**
	 * Option: Text/HTML
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-section-1-html]', array(
			'default'           => astra_get_option( 'above-header-section-1-html' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_html' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-section-1-html]', array(
			'section'  => 'section-above-header',
			'priority' => 50,
			'label'    => __( 'Text/HTML', 'astra-addon' ),
			'type'     => 'textarea',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			ASTRA_THEME_SETTINGS . '[above-header-section-1-html]', array(
				'selector'            => '.ast-above-header-section-1 .user-select  .ast-custom-html',
				'container_inclusive' => false,
				'render_callback'     => array( 'Astra_Customizer_Header_Sections_Partials', '_render_above_header_section_1_html' ),
			)
		);
	}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-layout-section-2-divider]', array(
				'section'  => 'section-above-header',
				'label'    => __( 'Section 2', 'astra-addon' ),
				'priority' => 55,
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Section 2
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-section-2]', array(
			'default'           => astra_get_option( 'above-header-section-2' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-section-2]', array(
			'section'  => 'section-above-header',
			'priority' => 60,
			'type'     => 'select',
			'choices'  => $sections,
		)
	);

	/**
	 * Option: Text/HTML
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-section-2-html]', array(
			'default'           => astra_get_option( 'above-header-section-2-html' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_html' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-section-2-html]', array(
			'section'  => 'section-above-header',
			'priority' => 75,
			'label'    => __( 'Text/HTML', 'astra-addon' ),
			'type'     => 'textarea',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			ASTRA_THEME_SETTINGS . '[above-header-section-2-html]', array(
				'selector'            => '.ast-above-header-section-2 .user-select .ast-custom-html',
				'container_inclusive' => false,
				'render_callback'     => array( 'Astra_Customizer_Header_Sections_Partials', '_render_above_header_section_2_html' ),
			)
		);
	}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[section-ast-above-header-border]', array(
				'section'  => 'section-above-header',
				'priority' => 80,
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Above Header Bottom Border
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-divider]', array(
			'default'           => astra_get_option( 'above-header-divider' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-divider]', array(
			'section'     => 'section-above-header',
			'priority'    => 85,
			'label'       => __( 'Above Header Bottom Border', 'astra-addon' ),
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 600,
			),
		)
	);

	/**
	 * Option: Above Header Bottom Border Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-divider-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-divider-color]', array(
				'section'  => 'section-above-header',
				'priority' => 90,
				'label'    => __( 'Above Header Bottom Border Color', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Above Header Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-height]', array(
			'default'           => 40,
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-height]', array(
				'section'     => 'section-above-header',
				'priority'    => 95,
				'label'       => __( 'Above Header Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 30,
					'step' => 1,
					'max'  => 600,
				),
			)
		)
	);

	/**
	 * Option: Mobile Menu Label Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-mobile-menu-divider]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-above-header',
				'priority' => 100,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Mobile Menu Alignment
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-align]', array(
			'default'           => astra_get_option( 'above-header-menu-align' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-menu-align]', array(
			'type'     => 'select',
			'section'  => 'section-above-header',
			'priority' => 100,
			'label'    => __( 'Mobile Header Alignment', 'astra-addon' ),
			'choices'  => array(
				'inline' => __( 'Inline', 'astra-addon' ),
				'stack'  => __( 'Stack', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Mobile Menu Label
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-label]', array(
			'default'           => astra_get_option( 'above-header-menu-label' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-menu-label]', array(
			'section'  => 'section-above-header',
			'priority' => 100,
			'label'    => __( 'Menu Label on Small Devices', 'astra-addon' ),
			'type'     => 'text',
		)
	);


	/**
	 * Option: Merged with primary header menu
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-merge-menu]', array(
			'default'           => astra_get_option( 'above-header-merge-menu' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-merge-menu]', array(
			'type'        => 'checkbox',
			'section'     => 'section-above-header',
			'label'       => __( 'Merge menu with primary menu in responsive', 'astra-addon' ),
			'description' => __( 'You can merge menu with Primary menu in mobile devices by enabling this option.', 'astra-addon' ),
			'priority'    => 100,
		)
	);
