<?php
/**
 * Header Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Option - Header Space
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[site-identity-spacing]', array(
		'default'           => astra_get_option( 'site-identity-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[site-identity-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'title_tagline',
			'priority'       => 50,
			'label'          => __( 'Site Identity Space', 'astra-addon' ),
			'linked_choices' => true,
			'unit_choices'   => array( 'px', 'em', '%' ),
			'choices'        => array(
				'top'    => __( 'Top', 'astra-addon' ),
				'right'  => __( 'Right', 'astra-addon' ),
				'bottom' => __( 'Bottom', 'astra-addon' ),
				'left'   => __( 'Left', 'astra-addon' ),
			),
		)
	)
);

/**
 * Option: Site Identity Spacing Divider
 */
$wp_customize->add_control(
	new Astra_Control_Divider(
		$wp_customize, ASTRA_THEME_SETTINGS . '[site-identity-spacing-divider]', array(
			'type'     => 'ast-divider',
			'section'  => 'title_tagline',
			'priority' => 50,
			'settings' => array(),
		)
	)
);
