<?php
/**
 * Typography - Panels & Sections
 *
 * @package Astra Addon
 */

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-primary-menu-typo',
		array(
			'title'    => __( 'Primary Menu', 'astra-addon' ),
			'panel'    => 'panel-typography',
			'section'  => 'section-header-typo-group',
			'priority' => 25,
		)
	)
);

$wp_customize->add_section(
	'section-button-typo', array(
		'title'    => __( 'Button', 'astra-addon' ),
		'panel'    => 'panel-typography',
		'priority' => 36,
	)
);


$wp_customize->add_section(
	'section-footer-typo', array(
		'title'    => __( 'Footer', 'astra-addon' ),
		'panel'    => 'panel-typography',
		'priority' => 60,
	)
);

$wp_customize->add_section(
	'section-sidebar-typo', array(
		'title'    => __( 'Sidebar', 'astra-addon' ),
		'panel'    => 'panel-typography',
		'priority' => 50,
	)
);
