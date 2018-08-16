<?php
/**
 * Below Header - Layout Options for our theme.
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
			'none'      => __( 'None', 'astra-addon' ),
			'menu'      => __( 'Menu', 'astra-addon' ),
			'search'    => __( 'Search', 'astra-addon' ),
			'text-html' => __( 'Text / HTML', 'astra-addon' ),
			'widget'    => __( 'Widget', 'astra-addon' ),
		),
		'below-header'
	);

	/**
	 * Option: Below Header Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-layout]', array(
			'default'           => astra_get_option( 'below-header-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-layout]', array(
				'section'  => 'section-below-header',
				'priority' => 5,
				'label'    => __( 'Below Header Layout', 'astra-addon' ),
				'type'     => 'ast-radio-image',
				'choices'  => array(
					'disabled'              => array(
						'label' => __( 'Disabled', 'astra-addon' ),
						'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/disabled-76x48.png',
					),
					'below-header-layout-1' => array(
						'label' => __( 'Below Header Layout 1', 'astra-addon' ),
						'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/below-header-1-76x48.png',
					),
					'below-header-layout-2' => array(
						'label' => __( 'Below Header Layout 2', 'astra-addon' ),
						'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/below-header-2-76x48.png',
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-layout-section-1-divider]', array(
				'section'  => 'section-below-header',
				'label'    => __( 'Section 1', 'astra-addon' ),
				'priority' => 10,
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Section 1
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-section-1]', array(
			'default'           => astra_get_option( 'below-header-section-1' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-section-1]', array(
			'section'  => 'section-below-header',
			'priority' => 15,
			'type'     => 'select',
			'choices'  => $sections,
		)
	);

	/**
	 * Option: Text/HTML
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-section-1-html]', array(
			'default'           => astra_get_option( 'below-header-section-1-html' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_html' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-section-1-html]', array(
			'section'  => 'section-below-header',
			'priority' => 20,
			'label'    => __( 'Text/HTML', 'astra-addon' ),
			'type'     => 'textarea',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			ASTRA_THEME_SETTINGS . '[below-header-section-1-html]', array(
				'selector'            => '.below-header-section-1 .user-select .ast-custom-html',
				'container_inclusive' => false,
				'render_callback'     => array( 'Astra_Customizer_Header_Sections_Partials', '_render_below_header_section_1' ),
			)
		);
	}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-layout-section-2-divider]', array(
				'section'  => 'section-below-header',
				'label'    => __( 'Section 2', 'astra-addon' ),
				'priority' => 30,
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Section 2
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-section-2]', array(
			'default'           => astra_get_option( 'below-header-section-2' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-section-2]', array(
			'section'  => 'section-below-header',
			'priority' => 35,
			'type'     => 'select',
			'choices'  => $sections,
		)
	);

	/**
	 * Option: Text/HTML
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-section-2-html]', array(
			'default'           => astra_get_option( 'below-header-section-2-html' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_html' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-section-2-html]', array(
			'section'  => 'section-below-header',
			'priority' => 40,
			'label'    => __( 'Text/HTML', 'astra-addon' ),
			'type'     => 'textarea',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			ASTRA_THEME_SETTINGS . '[below-header-section-2-html]', array(
				'selector'            => '.below-header-section-2 .user-select .ast-custom-html',
				'container_inclusive' => false,
				'render_callback'     => array( 'Astra_Customizer_Header_Sections_Partials', '_render_below_header_section_2' ),
			)
		);
	}

	/**
	 * Option: Below Header Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-layout-options-separator-divider]', array(
				'section'  => 'section-below-header',
				'priority' => 50,
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Above Header Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-separator]', array(
			'default'           => astra_get_option( 'below-header-separator' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-separator]', array(
			'section'     => 'section-below-header',
			'priority'    => 55,
			'label'       => __( 'Bottom Border', 'astra-addon' ),
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 600,
			),
		)
	);

	/**
	 * Option: Bottom Border Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-bottom-border-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-bottom-border-color]', array(
				'type'     => 'ast-color',
				'section'  => 'section-below-header',
				'priority' => 60,
				'label'    => __( 'Bottom Border Color', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Above Header Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-height]', array(
			'default'           => 60,
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-height]', array(
				'section'     => 'section-below-header',
				'priority'    => 75,
				'label'       => __( 'Below Header Height', 'astra-addon' ),
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
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Heading(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-mobile-menu-divider]', array(
				'section'  => 'section-below-header',
				'priority' => 100,
				'type'     => 'ast-heading',
				'label'    => __( 'Mobile Header', 'astra-addon' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Display Below Header on Mobile
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-on-mobile]', array(
			'default'           => astra_get_option( 'below-header-on-mobile' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-on-mobile]', array(
			'type'     => 'checkbox',
			'section'  => 'section-below-header',
			'label'    => __( 'Display on mobile devices', 'astra-addon' ),
			'priority' => 105,
		)
	);

	/**
	 * Option: Merged with primary header menu
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-merge-menu]', array(
			'default'           => astra_get_option( 'below-header-merge-menu' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-merge-menu]', array(
			'type'        => 'checkbox',
			'section'     => 'section-below-header',
			'label'       => __( 'Merge menu on mobile devices', 'astra-addon' ),
			'description' => __( 'You can merge menu with Primary menu in mobile devices by enabling this option.', 'astra-addon' ),
			'priority'    => 105,
		)
	);



	/**
	 * Option: Mobile Menu Alignment
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-menu-align]', array(
			'default'           => astra_get_option( 'below-header-menu-align' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-menu-align]', array(
				'section'  => 'section-below-header',
				'priority' => 110,
				'label'    => __( 'Mobile Header Alignment', 'astra-addon' ),
				'type'     => 'ast-radio-image',
				'choices'  => array(
					'inline' => array(
						'label' => __( 'Inline', 'astra-addon' ),
						'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/above-header-1-76x47.png',
					),
					'stack'  => array(
						'label' => __( 'Stack', 'astra-addon' ),
						'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/mobile-header-stack-76x48.png',
					),
				),
			)
		)
	);

	/**
	 * Option: Mobile Menu Label
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-menu-label]', array(
			'default'           => astra_get_option( 'below-header-menu-label' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-menu-label]', array(
			'section'  => 'section-below-header',
			'priority' => 107,
			'label'    => __( 'Menu Label on Small Devices', 'astra-addon' ),
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-swap-mobile]', array(
			'default'           => astra_get_option( 'below-header-section-swap-mobile' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-swap-mobile]', array(
			'type'     => 'checkbox',
			'section'  => 'section-below-header',
			'label'    => __( 'Swap sections on mobile devices', 'astra-addon' ),
			'priority' => 120,
		)
	);
