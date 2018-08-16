<?php
/**
 * Scroll To Top Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Scroll_To_Top_Configs' ) ) {

	/**
	 * Register Scroll To Top Customizer Configurations.
	 */
	class Astra_Scroll_To_Top_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Scroll To Top Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Scroll to Top Display On
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[scroll-to-top-on-devices]',
					'default' => astra_get_option( 'scroll-to-top-on-devices' ),
					'type'    => 'cpntrol',
					'cpntrol' => 'select',
					'section' => 'section-scroll-to-top',
					'title'   => __( 'Display On', 'astra-addon' ),
					'choices' => array(
						'desktop' => __( 'Desktop', 'astra-addon' ),
						'mobile'  => __( 'Mobile', 'astra-addon' ),
						'both'    => __( 'Desktop + Mobile', 'astra-addon' ),
					),
				),

				/**
				 * Option: Scroll to Top Position
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-position]',
					'default'   => astra_get_option( 'scroll-to-top-icon-position' ),
					'type'      => 'control',
					'control'   => 'select',
					'transport' => 'postMessage',
					'section'   => 'section-scroll-to-top',
					'title'     => __( 'Position', 'astra-addon' ),
					'choices'   => array(
						'right' => __( 'Right', 'astra-addon' ),
						'left'  => __( 'Left', 'astra-addon' ),
					),
				),

				/**
				 * Option: Scroll To Top Icon Size
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-size]',
					'default'   => astra_get_option( 'scroll-to-top-icon-size' ),
					'type'      => 'control',
					'control'   => 'number',
					'transport' => 'postMessage',
					'section'   => 'section-scroll-to-top',
					'title'     => __( 'Icon Size', 'astra-addon' ),
				),

				/**
				 * Option: Scroll To Top Radius
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-radius]',
					'default'   => astra_get_option( 'scroll-to-top-icon-radius' ),
					'type'      => 'control',
					'control'   => 'number',
					'transport' => 'postMessage',
					'section'   => 'section-scroll-to-top',
					'title'     => __( 'Icon Background Radius', 'astra-addon' ),
				),

				/**
				 * Option: Icon Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-color]',
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'title'     => __( 'Icon Color', 'astra-addon' ),
					'section'   => 'section-scroll-to-top',
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Icon Background Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-bg-color]',
					'default'   => '',
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-color',
					'title'     => __( 'Icon Background Color', 'astra-addon' ),
					'section'   => 'section-scroll-to-top',
				),

				/**
				 * Option: Icon Hover Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-h-color]',
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'title'     => __( 'Icon Hover Color', 'astra-addon' ),
					'section'   => 'section-scroll-to-top',
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Link Hover Background Color
				 */

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-h-bg-color]',
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'title'     => __( 'Icon Hover Background Color', 'astra-addon' ),
					'section'   => 'section-scroll-to-top',
				),

			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Scroll_To_Top_Configs;


