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
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-below-header-colors-bg',
		array(
			'title'    => __( 'Below Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-header-group',
			'priority' => 30,
		)
	)
);


$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-below-header-typo',
		array(
			'title'    => __( 'Below Header', 'astra-addon' ),
			'panel'    => 'panel-typography',
			'section'  => 'section-header-typo-group',
			'priority' => 30,
		)
	)
);


$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-above-header',
		array(
			'title'    => __( 'Above Header', 'astra-addon' ),
			'panel'    => 'panel-layout',
			'section'  => 'section-header-group',
			'priority' => 20,
		)
	)
);


$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-above-header-colors-bg',
		array(
			'title'    => __( 'Above Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-header-group',
			'priority' => 20,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-above-header-typo',
		array(
			'title'    => __( 'Above Header', 'astra-addon' ),
			'panel'    => 'panel-typography',
			'section'  => 'section-header-typo-group',
			'priority' => 20,
		)
	)
);


/*
 * Update the Above Header section
 *
 * @since 1.4.0
 */
$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-mobile-header-above-header',
		array(
			'priority' => 5,
			'title'    => __( 'Above Header', 'astra-addon' ),
			'panel'    => 'panel-layout',
			'section'  => 'section-mobile-header',
		)
	)
);


/*
 * Update the Below Header section
 *
 * @since 1.4.0
 */
$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-mobile-header-below-header',
		array(
			'priority' => 15,
			'title'    => __( 'Below Header', 'astra-addon' ),
			'panel'    => 'panel-layout',
			'section'  => 'section-mobile-header',
		)
	)
);
