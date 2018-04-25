<?php
/**
 * Header Spacing Options for our theme.
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
 * Option: Mobile Menu Label Divider
 */
$wp_customize->add_control(
	new Astra_Control_Divider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[header-spacing-divider]', array(
			'type'     => 'ast-divider',
			'section'  => 'section-header',
			'priority' => 40,
			'settings' => array(),
		)
	)
);

/**
 * Option - Header Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[header-spacing]', array(
		'default'           => astra_get_option( 'header-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[header-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-header',
			'priority'       => 45,
			'label'          => __( 'Header Space', 'astra-addon' ),
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
 * Option - Primary Menu Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[primary-menu-spacing]', array(
		'default'           => astra_get_option( 'primary-menu-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-header',
			'priority'       => 50,
			'label'          => __( 'Primary Menu Space', 'astra-addon' ),
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
 * Option - Primary Menu Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[primary-submenu-spacing]', array(
		'default'           => astra_get_option( 'primary-submenu-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-header',
			'priority'       => 51,
			'label'          => __( 'Primary Submenu Space', 'astra-addon' ),
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
