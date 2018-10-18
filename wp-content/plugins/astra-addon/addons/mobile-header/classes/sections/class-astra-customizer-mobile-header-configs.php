<?php
/**
 * Astra Mobile Header.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.4.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Customizer_Mobile_Header_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Customizer_Mobile_Header_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(

				/**
				 * Option: Mobile Menu Style
				 */

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-menu-style]',
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-header',
					'default'  => astra_get_option( 'mobile-menu-style' ),
					'title'    => __( 'Menu Style', 'astra-addon' ),
					'required' => array( ASTRA_THEME_SETTINGS . '[disable-primary-nav]', '!=', '1' ),
					'priority' => 41,
					'choices'  => array(
						'default'    => __( 'Dropdown', 'astra-addon' ),
						'flyout'     => __( 'Flyout', 'astra-addon' ),
						'fullscreen' => __( 'Full-Screen', 'astra-addon' ),
						'no-toggle'  => __( 'No Toggle', 'astra-addon' ),
					),
				),

				/**
				 * Option: Mobile Menu Style - Flyout alignments
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[flyout-mobile-menu-alignment]',
					'section'  => 'section-header',
					'title'    => __( 'Flyout Menu Alignment', 'astra-addon' ),
					'default'  => astra_get_option( 'flyout-mobile-menu-alignment' ),
					'type'     => 'control',
					'control'  => 'select',
					'required' => array( ASTRA_THEME_SETTINGS . '[mobile-menu-style]', '==', 'flyout' ),
					'priority' => 41,
					'choices'  => array(
						'left'  => __( 'Left', 'astra-addon' ),
						'right' => __( 'Right', 'astra-addon' ),
					),
				),

				/**
				 * Option - Header Menu Border
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[mobile-header-menu-all-border]',
					'type'           => 'control',
					'control'        => 'ast-border',
					'default'        => astra_get_option( 'mobile-header-menu-all-border' ),
					'transport'      => 'postMessage',
					'section'        => 'section-header',
					'title'          => __( 'Border for Menu Items', 'astra-addon' ),
					'linked_choices' => true,
					'priority'       => 65,
					'choices'        => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				),

				/**
				 * Option: Mobile Header Menu Border Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[mobile-header-menu-b-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'title'     => __( 'Border Color', 'astra-addon' ),
					'default'   => '#dadada',
					'transport' => 'postMessage',
					'section'   => 'section-header',
					'priority'  => 70,
				),
			);

			return array_merge( $configurations, $configs );
		}
	}
}

new Astra_Customizer_Mobile_Header_Configs;
