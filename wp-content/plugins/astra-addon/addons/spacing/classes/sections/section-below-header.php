<?php
/**
 * Below Header Spacing Options for our theme.
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
 * Option - Below Header Space Divider
 */
$wp_customize->add_control(
	new Astra_Control_Divider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-spacing-divider]', array(
			'section'  => 'section-below-header',
			'type'     => 'ast-divider',
			'priority' => 80,
			'settings' => array(),
		)
	)
);


/**
 * Option - Below Header Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[below-header-spacing]', array(
		'default'           => astra_get_option( 'below-header-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-below-header',
			'priority'       => 80,
			'label'          => __( 'Space Below Header', 'astra-addon' ),
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
 * Option - Below Header Menu Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[below-header-menu-spacing]', array(
		'default'           => astra_get_option( 'below-header-menu-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-menu-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-below-header',
			'priority'       => 85,
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
 * Option - Below Header Subenu Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[below-header-submenu-spacing]', array(
		'default'           => astra_get_option( 'below-header-submenu-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-submenu-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-below-header',
			'priority'       => 90,
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
