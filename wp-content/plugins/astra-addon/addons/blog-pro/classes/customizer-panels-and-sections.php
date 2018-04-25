<?php
/**
 * Colors & Background - Panels & Sections
 *
 * @package Astra Addon
 */

$wp_customize->add_section(
	'section-colors-single', array(
		'title'    => __( 'Single Page/Post', 'astra-addon' ),
		'panel'    => 'panel-colors-background',
		'priority' => 45,
	)
);
