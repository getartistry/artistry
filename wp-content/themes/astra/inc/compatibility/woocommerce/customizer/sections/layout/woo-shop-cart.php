<?php
/**
 * WooCommerce Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Disable Breadcrumb
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[enable-cart-upsells]', array(
			'default'           => astra_get_option( 'enable-cart-upsells' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[enable-cart-upsells]', array(
			'section'  => 'section-woo-shop-cart',
			'label'    => __( 'Enable Upsells', 'astra' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);
