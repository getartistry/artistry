<?php
/**
 * Bottom Footer Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.12
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Footer Widgets Layout Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv]', array(
			'default'           => astra_get_option( 'footer-adv' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv]', array(
				'type'    => 'ast-radio-image',
				'label'   => __( 'Footer Widgets Layout', 'astra' ),
				'section' => 'section-footer-adv',
				'choices' => array(
					'disabled' => array(
						'label' => __( 'Disable', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/no-adv-footer-115x48.png',
					),
					'layout-4' => array(
						'label' => __( 'Layout 4', 'astra' ),
						'path'  => ASTRA_THEME_URI . '/assets/images/layout-4-115x48.png',
					),
				),
			)
		)
	);

	// Learn More link if Astra Pro is not activated.
	if ( ! defined( 'ASTRA_EXT_VER' ) ) {

		/**
		 * Option: Divider
		 */
		$wp_customize->add_control(
			new Astra_Control_Divider(
				$wp_customize, ASTRA_THEME_SETTINGS . '[ast-footer-widget-more-feature-divider]', array(
					'type'     => 'ast-divider',
					'section'  => 'section-footer-adv',
					'priority' => 20,
					'settings' => array(),
				)
			)
		);
		/**
		 * Option: Learn More about Footer Widget
		 */
		$wp_customize->add_control(
			new Astra_Control_Description(
				$wp_customize, ASTRA_THEME_SETTINGS . '[ast-footer-widget-more-feature-description]', array(
					'type'     => 'ast-description',
					'section'  => 'section-footer-adv',
					'priority' => 20,
					'label'    => '',
					'help'     => '<p>' . __( 'More Options Available for Footer Widgets in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/docs/footer-widgets-astra-pro/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-primary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
					'settings' => array(),
				)
			)
		);
	}
