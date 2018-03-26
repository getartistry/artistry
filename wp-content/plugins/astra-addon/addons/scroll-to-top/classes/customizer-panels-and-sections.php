<?php
/**
 * Scroll To Top - Panels & Sections
 *
 * @package Astra Addon
 */

$wp_customize->add_section(
	'section-scroll-to-top', array(
		'title'    => __( 'Scroll To Top', 'astra-addon' ),
		'panel'    => 'panel-miscellaneous',
		'panel'    => 'panel-layout',
		'priority' => 60,
	)
);
