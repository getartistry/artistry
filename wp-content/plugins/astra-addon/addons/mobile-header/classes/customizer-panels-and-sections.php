<?php
/**
 * Mobile Header - Panels & Sections
 *
 * @package Astra Addon
 */

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-header-group',
		array(
			'title'    => __( 'Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'priority' => 20,
		)
	)
);


$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-mobile-header',
		array(
			'title'    => __( 'Mobile Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-header-group',
			'priority' => 40,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-mobile-primary-header',
		array(
			'title'    => __( 'Primary Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-mobile-header',
			'priority' => 10,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-mobile-header-typo',
		array(
			'title'    => __( 'Mobile Header', 'astra-addon' ),
			'panel'    => 'panel-typography',
			'section'  => 'section-header-typo-group',
			'priority' => 30,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-mobile-primary-header-typo',
		array(
			'title'    => __( 'Primary Header', 'astra-addon' ),
			'panel'    => 'panel-typography',
			'section'  => 'section-mobile-header-typo',
			'priority' => 10,
		)
	)
);


$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-mobile-above-header',
		array(
			'title'    => __( 'Above Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-mobile-header',
			'priority' => 5,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-mobile-above-header-typo',
		array(
			'title'    => __( 'Above Header', 'astra-addon' ),
			'panel'    => 'panel-typography',
			'section'  => 'section-mobile-header-typo',
			'priority' => 5,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-mobile-below-header',
		array(
			'title'    => __( 'Below Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-mobile-header',
			'priority' => 15,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-mobile-below-header-typo',
		array(
			'title'    => __( 'Below Header', 'astra-addon' ),
			'panel'    => 'panel-typography',
			'section'  => 'section-mobile-header-typo',
			'priority' => 15,
		)
	)
);
