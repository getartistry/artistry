<?php
/**
 * Footer Spacing Options for our theme.
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
 * Option - Footer Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[footer-sml-spacing]', array(
		'default'           => astra_get_option( 'footer-sml-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[footer-sml-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-footer-small',
			'priority'       => 36,
			'label'          => __( 'Footer Space', 'astra-addon' ),
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
 * Option - Footer Menu Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[footer-menu-spacing]', array(
		'default'           => astra_get_option( 'footer-menu-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[footer-menu-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-footer-small',
			'priority'       => 37,
			'label'          => __( 'Footer Menu Space', 'astra-addon' ),
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
 * Option: Divider
 */
$wp_customize->add_control(
	new Astra_Control_Divider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[footer-spacing-divider]', array(
			'section'  => 'section-footer-small',
			'type'     => 'ast-divider',
			'priority' => 37,
			'settings' => array(),
		)
	)
);
