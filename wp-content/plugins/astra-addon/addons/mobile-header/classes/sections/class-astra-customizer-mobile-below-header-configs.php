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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Customizer_Mobile_Below_Header_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Customizer_Mobile_Below_Header_Configs extends Astra_Customizer_Config_Base {

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
				 * Option: Mobile Menu Style Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-below-header-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-below-header',
					'settings' => array(),
					'priority' => 106,
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[below-header-merge-menu]', '!=', true ),
						),
					),
				),

				/**
				 * Option: Mobile Menu Style
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-below-header-menu-style]',
					'section'  => 'section-below-header',
					'default'  => astra_get_option( 'mobile-below-header-menu-style' ),
					'title'    => __( 'Menu Style', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'select',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[below-header-merge-menu]', '!=', true ),
						),
					),
					'priority' => 106,
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
					'name'     => ASTRA_THEME_SETTINGS . '[flyout-mobile-below-header-menu-alignment]',
					'section'  => 'section-below-header',
					'default'  => astra_get_option( 'flyout-mobile-below-header-menu-alignment' ),
					'title'    => __( 'Flyout Menu Alignment', 'astra-addon' ),
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[mobile-below-header-menu-style]', '==', 'flyout' ),
					'control'  => 'select',
					'priority' => 106,
					'choices'  => array(
						'left'  => __( 'Left', 'astra-addon' ),
						'right' => __( 'Right', 'astra-addon' ),
					),
				),

				/**
				* Option: Mobile Menu Style Divider
				*/
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-below-header-main-menu-style-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[below-header-merge-menu]', '!=', true ),
						),
					),
					'section'  => 'section-below-header',
					'priority' => 107,
					'settings' => array(),
				),

				/**
				* Option: Toggle Button Style
				*/
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[mobile-below-header-toggle-btn-style]',
					'type'      => 'control',
					'control'   => 'select',
					'section'   => 'section-below-header',
					'title'     => __( 'Toggle Button Style', 'astra-addon' ),
					'transport' => 'postMessage',
					'required'  => array( ASTRA_THEME_SETTINGS . '[mobile-below-header-menu-style]', '!=', 'no-toggle' ),
					'default'   => astra_get_option( 'mobile-below-header-toggle-btn-style' ),
					'priority'  => 107,
					'choices'   => array(
						'fill'    => __( 'Fill', 'astra-addon' ),
						'outline' => __( 'Outline', 'astra-addon' ),
						'minimal' => __( 'Minimal', 'astra-addon' ),
					),
				),

				/**
				* Option: Toggle Button Color
				*/
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-below-header-toggle-btn-style-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'required' => array( ASTRA_THEME_SETTINGS . '[mobile-below-header-menu-style]', '!=', 'no-toggle' ),
					'default'  => astra_get_option( 'mobile-below-header-toggle-btn-style-color' ),
					'title'    => __( 'Toggle Button Color', 'astra-addon' ),
					'section'  => 'section-below-header',
					'priority' => 107,
				),

				/**
				* Option: Border Radius
				*/
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[mobile-below-header-toggle-btn-border-radius]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'transport'   => 'postMessage',
					'required'    => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[mobile-below-header-menu-style]', '!=', 'no-toggle' ),
							array( ASTRA_THEME_SETTINGS . '[mobile-below-header-toggle-btn-style]', '!=', 'minimal' ),
						),
						'operator'   => 'AND',
					),
					'default'     => astra_get_option( 'mobile-below-header-toggle-btn-border-radius' ),
					'section'     => 'section-below-header',
					'title'       => __( 'Border Radius', 'astra-addon' ),
					'priority'    => 107,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
				),

				/**
				 * Option: Mobile Header Menu Border
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[mobile-below-header-menu-all-border]',
					'type'           => 'control',
					'control'        => 'ast-border',
					'transport'      => 'postMessage',
					'section'        => 'section-below-header',
					'default'        => astra_get_option( 'mobile-below-header-menu-all-border' ),
					'title'          => __( 'Border for Menu Items', 'astra-addon' ),
					'linked_choices' => true,
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[below-header-merge-menu]', '!=', true ),
						),
					),
					'priority'       => 130,
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
					'name'      => ASTRA_THEME_SETTINGS . '[mobile-below-header-menu-b-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '#dadada',
					'required'  => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'transport' => 'postMessage',
					'title'     => __( 'Border Color', 'astra-addon' ),
					'section'   => 'section-below-header',
					'priority'  => 135,
				),
			);

			return array_merge( $configurations, $configs );
		}
	}
}

new Astra_Customizer_Mobile_Below_Header_Configs;


