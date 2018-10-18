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

if ( ! class_exists( 'Astra_Customizer_Mobile_Above_Header_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Customizer_Mobile_Above_Header_Configs extends Astra_Customizer_Config_Base {

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
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-above-header-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-above-header',
					'settings' => array(),
					'priority' => 102,
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[above-header-merge-menu]', '!=', true ),
						),
					),
				),

				/**
				 * Option: Mobile Menu Style
				 */

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-style]',
					'section'  => 'section-above-header',
					'type'     => 'control',
					'default'  => astra_get_option( 'mobile-above-header-menu-style' ),
					'title'    => __( 'Menu Style', 'astra-addon' ),
					'control'  => 'select',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[above-header-merge-menu]', '!=', true ),
						),
					),
					'priority' => 102,
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
					'name'     => ASTRA_THEME_SETTINGS . '[flyout-mobile-above-header-menu-alignment]',
					'section'  => 'section-above-header',
					'type'     => 'control',
					'default'  => astra_get_option( 'flyout-mobile-above-header-menu-alignment' ),
					'title'    => __( 'Flyout Menu Alignment', 'astra-addon' ),
					'control'  => 'select',
					'required' => array( ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-style]', '==', 'flyout' ),
					'priority' => 102,
					'choices'  => array(
						'left'  => __( 'Left', 'astra-addon' ),
						'right' => __( 'Right', 'astra-addon' ),
					),
				),

				/**
				* Option: Mobile Menu Style Divider
				*/
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-above-header-main-menu-style-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[above-header-merge-menu]', '!=', true ),
						),
					),
					'section'  => 'section-above-header',
					'priority' => 103,
					'settings' => array(),
				),

				/**
				* Option: Toggle Button Style
				*/
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-style]',
					'section'   => 'section-above-header',
					'title'     => __( 'Toggle Button Style', 'astra-addon' ),
					'default'   => astra_get_option( 'mobile-above-header-toggle-btn-style' ),
					'transport' => 'postMessage',
					'required'  => array( ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-style]', '!=', 'no-toggle' ),
					'type'      => 'control',
					'control'   => 'select',
					'priority'  => 103,
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
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-style-color]',
					'default'  => astra_get_option( 'mobile-above-header-toggle-btn-style-color' ),
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-style]', '!=', 'no-toggle' ),
					'control'  => 'ast-color',
					'title'    => __( 'Toggle Button Color', 'astra-addon' ),
					'section'  => 'section-above-header',
					'priority' => 103,
				),

				/**
				* Option: Border Radius
				*/

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-border-radius]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'mobile-above-header-toggle-btn-border-radius' ),
					'section'     => 'section-above-header',
					'required'    => array( ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-style]', '!=', 'minimal' ),
					'title'       => __( 'Border Radius', 'astra-addon' ),
					'priority'    => 103,
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
					'name'           => ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-all-border]',
					'type'           => 'control',
					'control'        => 'ast-border',
					'default'        => astra_get_option( 'mobile-above-header-menu-all-border' ),
					'section'        => 'section-above-header',
					'transport'      => 'postMessage',
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[above-header-merge-menu]', '!=', true ),
						),
					),
					'title'          => __( 'Border for Menu Items', 'astra-addon' ),
					'linked_choices' => true,
					'priority'       => 125,
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
					'name'      => ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-b-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '#dadada',
					'required'  => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'transport' => 'postMessage',
					'title'     => __( 'Border Color', 'astra-addon' ),
					'section'   => 'section-above-header',
					'priority'  => 130,
				),
			);

			return array_merge( $configurations, $configs );
		}
	}
}

new Astra_Customizer_Mobile_Above_Header_Configs;
