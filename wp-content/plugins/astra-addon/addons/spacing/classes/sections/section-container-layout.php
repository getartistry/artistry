<?php
/**
 * Content Spacing Options for our theme.
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
 * Option: Divider
 */
$wp_customize->add_control(
	new Astra_Control_Heading(
		$wp_customize, ASTRA_THEME_SETTINGS . '[content-spacing-divider]', array(
			'section'  => 'section-container-layout',
			'type'     => 'ast-heading',
			'label'    => __( 'Spacing', 'astra-addon' ),
			'priority' => 90,
			'settings' => array(),
		)
	)
);

/**
 * Option - Content Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[container-outside-spacing]', array(
		'default'           => astra_get_option( 'container-outside-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[container-outside-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-container-layout',
			'priority'       => 95,
			'label'          => __( 'Space Outside Container', 'astra-addon' ),
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
 * Option - Content Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[container-inside-spacing]', array(
		'default'           => astra_get_option( 'container-inside-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[container-inside-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-container-layout',
			'priority'       => 100,
			'label'          => __( 'Space Inside Container', 'astra-addon' ),
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
