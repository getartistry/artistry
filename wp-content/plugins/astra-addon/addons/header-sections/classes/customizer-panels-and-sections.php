<?php
/**
 * Advanced Header - Panels & Sections
 *
 * @package Astra Addon
 */

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-below-header',
		array(
			'title'    => __( 'Below Header', 'astra-addon' ),
			'panel'    => 'panel-layout',
			'section'  => 'section-header-group',
			'priority' => 30,
		)
	)
);

$wp_customize->add_section(
	'section-below-header-colors-bg', array(
		'title'    => __( 'Below Header', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 30,
	)
);

$wp_customize->add_section(
	'section-below-header-typo', array(
		'title'    => __( 'Below Header', 'astra-addon' ),
		'panel'    => 'panel-typography',
		'priority' => 30,
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-above-header',
		array(
			'title'    => __( 'Above Header', 'astra-addon' ),
			'panel'    => 'panel-layout',
			'section'  => 'section-header-group',
			'priority' => 15,
		)
	)
);

$wp_customize->add_section(
	'section-above-header-colors-bg', array(
		'title'    => __( 'Above Header', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 15,
	)
);

$wp_customize->add_section(
	'section-above-header-typo', array(
		'title'    => __( 'Above Header', 'astra-addon' ),
		'panel'    => 'panel-typography',
		'priority' => 15,
	)
);
