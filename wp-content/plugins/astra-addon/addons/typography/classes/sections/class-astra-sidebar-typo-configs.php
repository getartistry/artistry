<?php
/**
 * Section [Sidebar] options for astra theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Sidebar_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Sidebar_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Side bar typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-sidebar-typo-title]',
					'title'    => __( 'Widget Title', 'astra-addon' ),
					'type'     => 'control',
					'section'  => 'section-sidebar-typo',
					'control'  => 'ast-divider',
					'settings' => array(),
				),
				/**
				 * Option: Widget Title Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-widget-title]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-widget-title' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-sidebar-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-widget-title]',
				),

				/**
				 * Option: Widget Title Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-widget-title]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-widget-title' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-sidebar-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-widget-title]',
				),

				/**
				 * Option: Widget Title Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-widget-title]',
					'section'   => 'section-sidebar-typo',
					'type'      => 'control',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'default'   => astra_get_option( 'text-transform-widget-title' ),
					'control'   => 'select',
					'transport' => 'postMessage',
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Widget Title Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-widget-title]',
					'section'     => 'section-sidebar-typo',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'font-size-widget-title' ),
					'title'       => __( 'Font Size', 'astra-addon' ),
					'control'     => 'ast-responsive',
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Widget Title Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-widget-title]',
					'transport'         => 'postMessage',
					'section'           => 'section-sidebar-typo',
					'type'              => 'control',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'astra-addon' ),
					'control'           => 'ast-slider',
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-sidebar-typo-content]',
					'title'    => __( 'Widget Content', 'astra-addon' ),
					'section'  => 'section-sidebar-typo',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),

				/**
				 * Option: Widget Content Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-widget-content]',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'type'      => 'control',
					'default'   => astra_get_option( 'font-family-widget-content' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-sidebar-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-widget-content]',
				),

				/**
				 * Option: Widget Content Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-widget-content]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-widget-content' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-sidebar-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-widget-content]',
				),

				/**
				 * Option: Widget Content Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-widget-content]',
					'section'   => 'section-sidebar-typo',
					'type'      => 'control',
					'default'   => astra_get_option( 'text-transform-widget-content' ),
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'transport' => 'postMessage',
					'control'   => 'select',
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Widget Content Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-widget-content]',
					'section'     => 'section-sidebar-typo',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'default'     => astra_get_option( 'font-size-widget-content' ),
					'title'       => __( 'Font Size', 'astra-addon' ),
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Widget Content Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-widget-content]',
					'section'           => 'section-sidebar-typo',
					'type'              => 'control',
					'transport'         => 'postMessage',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'astra-addon' ),
					'control'           => 'ast-slider',
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Sidebar_Typo_Configs;
