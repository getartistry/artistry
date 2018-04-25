<?php
/**
 * Register customizer Aspra Pro Section.
 *
 * @package   Astra
 * @author    Astra
 * @copyright Copyright (c) 2018, Astra
 * @link      http://wpastra.com/
 * @since     Astra 1.0.10
 */

// Register custom section types.
$wp_customize->register_section_type( 'Astra_Pro_Customizer' );

// Register sections.
$wp_customize->add_section(
	new Astra_Pro_Customizer(
		$wp_customize, 'astra-pro', array(
			'title'    => esc_html__( 'More Options Available in Astra Pro!', 'astra' ),
			'pro_url'  => htmlspecialchars_decode( astra_get_pro_url( 'https://wpastra.com/pricing/', 'customizer', 'upgrade-link', 'upgrade-to-pro' ) ),
			'priority' => 1,
		)
	)
);
