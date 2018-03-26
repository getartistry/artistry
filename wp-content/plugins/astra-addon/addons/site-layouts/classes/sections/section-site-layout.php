<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option: Site Layout
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[site-layout]', array(
		'default'           => astra_get_option( 'site-layout' ),
		'type'              => 'option',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
	)
);
$wp_customize->add_control(
	ASTRA_THEME_SETTINGS . '[site-layout]', array(
		'type'     => 'select',
		'section'  => 'section-container-layout',
		'priority' => 5,
		'label'    => __( 'Site Layout', 'astra-addon' ),
		'choices'  => array(
			'ast-full-width-layout'  => __( 'Full Width', 'astra-addon' ),
			'ast-box-layout'         => __( 'Max Width', 'astra-addon' ),
			'ast-padded-layout'      => __( 'Padded', 'astra-addon' ),
			'ast-fluid-width-layout' => __( 'Fluid', 'astra-addon' ),
		),
	)
);

/**
 * Option: Padded Layout Custom Width
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[site-layout-padded-width]', array(
		'default'           => 1200,
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'validate_site_width' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Slider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[site-layout-padded-width]', array(
			'type'        => 'ast-slider',
			'section'     => 'section-container-layout',
			'priority'    => 15,
			'label'       => __( 'Container Width', 'astra-addon' ),
			'suffix'      => '',
			'input_attrs' => array(
				'min'  => 768,
				'step' => 1,
				'max'  => 1920,
			),
		)
	)
);

/**
 * Option: Padded Layout Custom Width
 */
// Astra_Control_Responsive_Spacing introduced in Astra 1.2.0.
// If found older version then do not load any settings from customizer.
if ( version_compare( ASTRA_THEME_VERSION, '1.2.0', '>=' ) ) {
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-padded-pad]', array(
			'default'           => astra_get_option( 'site-layout-padded-pad' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Spacing(
			$wp_customize, ASTRA_THEME_SETTINGS . '[site-layout-padded-pad]', array(
				'type'           => 'ast-responsive-spacing',
				'section'        => 'section-container-layout',
				'priority'       => 20,
				'label'          => __( 'Space Outside Body', 'astra-addon' ),
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
}

/**
 * Option: Box Width
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[site-layout-box-width]', array(
		'default'           => 1200,
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'validate_site_width' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Slider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[site-layout-box-width]', array(
			'type'        => 'ast-slider',
			'section'     => 'section-container-layout',
			'priority'    => 25,
			'label'       => __( 'Max Width', 'astra-addon' ),
			'suffix'      => '',
			'input_attrs' => array(
				'min'  => 768,
				'step' => 1,
				'max'  => 1920,
			),
		)
	)
);

/**
 * Option: Box Top & Bottom Margin
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[site-layout-box-tb-margin]', array(
		'default'           => 0,
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'validate_site_margin' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Slider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[site-layout-box-tb-margin]', array(
			'type'        => 'ast-slider',
			'section'     => 'section-container-layout',
			'priority'    => 30,
			'label'       => __( 'Top & Bottom Margin', 'astra-addon' ),
			'suffix'      => '',
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 600,
			),
		)
	)
);

/**
 * Layout: Fluid layout
 */

/**
 * Option: Page Left & Right Padding
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[site-layout-fluid-lr-padding]', array(
		'default'           => 25,
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'validate_site_padding' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Slider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[site-layout-fluid-lr-padding]', array(
			'type'        => 'ast-slider',
			'section'     => 'section-container-layout',
			'priority'    => 35,
			'label'       => __( 'Page Left & Right Padding', 'astra-addon' ),
			'suffix'      => '',
			'input_attrs' => array(
				'min'  => 1,
				'step' => 1,
				'max'  => 200,
			),
		)
	)
);
