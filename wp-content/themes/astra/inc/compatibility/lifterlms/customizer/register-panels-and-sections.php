<?php
/**
 * Register customizer panels & sections.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.2.0
 */

	/**
	 * Section
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-lifterlms',
			array(
				'priority' => 65,
				'title'    => __( 'LifterLMS', 'astra' ),
				'panel'    => 'panel-layout',
			)
		)
	);
