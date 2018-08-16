<?php
/**
 * Sticky Header - Panels & Sections
 *
 * @package Astra Addon
 */

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-sticky-header',
		array(
			'title'    => __( 'Sticky Header', 'astra-addon' ),
			'panel'    => 'panel-layout',
			'section'  => 'section-header-group',
			'priority' => 31,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-sticky-header',
		array(
			'title'    => __( 'Sticky Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-header-group',
			'priority' => 31,
		)
	)
);


$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-sticky-primary-header',
		array(
			'title'    => __( 'Primary Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-sticky-header',
			'priority' => 10,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-sticky-above-header',
		array(
			'title'    => __( 'Above Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-sticky-header',
			'priority' => 20,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-sticky-below-header',
		array(
			'title'    => __( 'Below Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-sticky-header',
			'priority' => 30,
		)
	)
);
