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
		$wp_customize, 'section-colors-transparent-header',
		array(
			'title'    => __( 'Transparent Header', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-header-group',
			'priority' => 32,
		)
	)
);

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-colors-primary-menu',
		array(
			'title'    => __( 'Primary Menu', 'astra-addon' ),
			'panel'    => 'panel-colors-background',
			'section'  => 'section-colors-header-group',
			'priority' => 25,
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
	'section-colors-archive', array(
		'title'    => __( 'Blog/Archive', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 40,
	)
);

$wp_customize->add_section(
	'section-colors-single', array(
		'title'    => __( 'Single Page/Post', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 45,
	)
);

$wp_customize->add_section(
	'section-colors-sidebar', array(
		'title'    => __( 'Sidebar', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 50,
	)
);
