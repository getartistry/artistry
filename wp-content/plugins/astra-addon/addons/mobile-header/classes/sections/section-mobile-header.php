<?php
/**
 * Astra Mobile Header.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option: Mobile Menu Style
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[mobile-menu-style]', array(
		'default'           => astra_get_option( 'mobile-menu-style' ),
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
	)
);
$wp_customize->add_control(
	ASTRA_THEME_SETTINGS . '[mobile-menu-style]', array(
		'section'  => 'section-header',
		'label'    => __( 'Menu Style', 'astra-addon' ),
		'type'     => 'select',
		'priority' => 41,
		'choices'  => array(
			'default'    => __( 'Dropdown', 'astra-addon' ),
			'flyout'     => __( 'Flyout', 'astra-addon' ),
			'fullscreen' => __( 'Full-Screen', 'astra-addon' ),
			'no-toggle'  => __( 'No Toggle', 'astra-addon' ),
		),
	)
);

/**
 * Option: Mobile Menu Style - Flyout alignments
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[flyout-mobile-menu-alignment]', array(
		'default'           => astra_get_option( 'flyout-mobile-menu-alignment' ),
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
	)
);
$wp_customize->add_control(
	ASTRA_THEME_SETTINGS . '[flyout-mobile-menu-alignment]', array(
		'section'  => 'section-header',
		'label'    => __( 'Flyout Menu Alignment', 'astra-addon' ),
		'type'     => 'select',
		'priority' => 41,
		'choices'  => array(
			'left'  => __( 'Left', 'astra-addon' ),
			'right' => __( 'Right', 'astra-addon' ),
		),
	)
);


	/**
	 * Option - Header Menu Border
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-header-menu-all-border]', array(
			'default'           => astra_get_option( 'mobile-header-menu-all-border' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_border' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Border(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-menu-all-border]', array(
				'type'           => 'ast-border',
				'section'        => 'section-header',
				'label'          => __( 'Border for Menu Items', 'astra-addon' ),
				'linked_choices' => true,
				'priority'       => 65,
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
	 * Option: Mobile Header Menu Border Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-header-menu-b-color]', array(
			'default'           => '#dadada',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-menu-b-color]', array(
				'label'    => __( 'Border Color', 'astra-addon' ),
				'section'  => 'section-header',
				'priority' => 70,
			)
		)
	);
