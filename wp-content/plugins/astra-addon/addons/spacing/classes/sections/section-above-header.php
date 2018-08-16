<?php
/**
 * Above Header Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option - Top Menu Space
 */
$wp_customize->add_control(
	new Astra_Control_Heading(
		$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-spacing-divider]', array(
			'section'  => 'section-above-header',
			'type'     => 'ast-heading',
			'label'    => __( 'Spacing', 'astra-addon' ),
			'priority' => 150,
			'settings' => array(),
		)
	)
);

/**
 * Option - Above Header Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[above-header-spacing]', array(
		'default'           => astra_get_option( 'above-header-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-above-header',
			'priority'       => 160,
			'label'          => __( 'Space Above Header', 'astra-addon' ),
			'linked_choices' => true,
			'unit_choices'   => array( 'px', 'em', '%' ),
			'choices'        => array(
				'top'    => __( 'Top', 'astra-addon' ),
				'right'  => __( 'Right', 'astra-addon' ),
				'bottom' => __( 'Bottom', 'astra-addon' ),
				'left'   => __( 'Left', 'astra-addon' ),
			),
		)
	)
);

/**
 * Option - Above Header Menu Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[above-header-menu-spacing]', array(
		'default'           => astra_get_option( 'above-header-menu-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-above-header',
			'priority'       => 165,
			'label'          => __( 'Menu Space', 'astra-addon' ),
			'linked_choices' => true,
			'unit_choices'   => array( 'px', 'em', '%' ),
			'choices'        => array(
				'top'    => __( 'Top', 'astra-addon' ),
				'right'  => __( 'Right', 'astra-addon' ),
				'bottom' => __( 'Bottom', 'astra-addon' ),
				'left'   => __( 'Left', 'astra-addon' ),
			),
		)
	)
);

/**
 * Option - Above Header Submenu Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[above-header-submenu-spacing]', array(
		'default'           => astra_get_option( 'above-header-submenu-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-above-header',
			'priority'       => 170,
			'label'          => __( 'Submenu Space', 'astra-addon' ),
			'linked_choices' => true,
			'unit_choices'   => array( 'px', 'em', '%' ),
			'choices'        => array(
				'top'    => __( 'Top', 'astra-addon' ),
				'right'  => __( 'Right', 'astra-addon' ),
				'bottom' => __( 'Bottom', 'astra-addon' ),
				'left'   => __( 'Left', 'astra-addon' ),
			),
		)
	)
);

