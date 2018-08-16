<?php
/**
 * Colors & Background - Panels & Sections
 *
 * @package Astra Addon
 */

$wp_customize->add_section(
	'section-colors-content', array(
		'title'    => __( 'Content', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 35,
	)
);

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
		$wp_customize, 'section-colors-header',
		array(
			'title'    => __( 'Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-header-group',
			'priority' => 20,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-primary-menu',
		array(
			'title'    => __( 'Primary Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-header-group',
			'priority' => 15,
		)
	)
);

$wp_customize->add_section(
	'section-colors-footer', array(
		'title'    => __( 'Footer', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 60,
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-blog-color-group',
		array(
			'priority' => 40,
			'title'    => __( 'Blog', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-archive',
		array(
			'priority' => 5,
			'title'    => __( 'Blog / Archive', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-blog-color-group',
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-single',
		array(
			'priority' => 10,
			'title'    => __( 'Single Post', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-blog-color-group',
		)
	)
);


$wp_customize->add_section(
	'section-colors-sidebar', array(
		'title'    => __( 'Sidebar', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 50,
	)
);
