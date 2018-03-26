<?php
/**
 * Footer Widgets - Panels & Sections
 *
 * @package Astra Addon
 */

$wp_customize->add_section(
	new Astra_WP_Customize_Section(
		$wp_customize, 'section-footer-adv',
		array(
			'title'    => __( 'Footer Widgets', 'astra-addon' ),
			'panel'    => 'panel-layout',
			'section'  => 'section-footer-group',
			'priority' => 5,
		)
	)
);

$wp_customize->add_section(
	'section-footer-adv-color-bg', array(
		'title'    => __( 'Footer Widgets', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 55,
	)
);

$wp_customize->add_section(
	'section-footer-adv-typo', array(
		'title'    => __( 'Footer Widgets', 'astra-addon' ),
		'panel'    => 'panel-typography',
		'priority' => 55,
	)
);
