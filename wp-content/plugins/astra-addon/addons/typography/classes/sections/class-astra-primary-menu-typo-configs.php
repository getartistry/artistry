<?php
/**
 * [Primary Menu] options for astra theme.
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

if ( ! class_exists( 'Astra_Primary_Menu_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Primary_Menu_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Primary Menu typography Customizer Configurations.
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
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-primary-menu-typo-main]',
					'title'    => __( 'Menu', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-primary-header-typo',
					'priority' => 21,
					'settings' => array(),
				),

				/**
				 * Option: Primary Menu Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-primary-menu]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-primary-menu' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-primary-header-typo',
					'priority'  => 22,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-primary-menu]',
				),

				/**
				 * Option: Primary Menu Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-primary-menu]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-primary-menu' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-primary-header-typo',
					'priority'          => 23,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-primary-menu]',
				),

				/**
				 * Option: Primary Menu Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-primary-menu]',
					'section'   => 'section-primary-header-typo',
					'type'      => 'control',
					'control'   => 'select',
					'transport' => 'postMessage',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'priority'  => 24,
					'default'   => astra_get_option( 'text-transform-primary-menu' ),
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Primary Menu Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-primary-menu]',
					'section'     => 'section-primary-header-typo',
					'type'        => 'control',
					'priority'    => 25,
					'title'       => __( 'Font Size', 'astra-addon' ),
					'control'     => 'ast-responsive',
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
				 * Option: Primary Menu Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-primary-menu]',
					'section'           => 'section-primary-header-typo',
					'type'              => 'control',
					'priority'          => 26,
					'title'             => __( 'Line Height', 'astra-addon' ),
					'transport'         => 'postMessage',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'control'           => 'ast-slider',
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 10,
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-primary-menu-typo-dropdown]',
					'title'    => __( 'Submenu', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-primary-header-typo',
					'priority' => 27,
					'settings' => array(),
				),

				/**
				 * Option: Primary Submenu Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-primary-dropdown-menu]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'default'   => astra_get_option( 'font-family-primary-dropdown-menu' ),
					'section'   => 'section-primary-header-typo',
					'priority'  => 28,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-primary-dropdown-menu]',
				),

				/**
				 * Option: Primary Submenu Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-primary-dropdown-menu]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-primary-dropdown-menu' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-primary-header-typo',
					'priority'          => 29,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-primary-dropdown-menu]',
				),

				/**
				 * Option: Primary Submenu Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-primary-dropdown-menu]',
					'section'   => 'section-primary-header-typo',
					'type'      => 'control',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'transport' => 'postMessage',
					'priority'  => 30,
					'default'   => astra_get_option( 'text-transform-primary-dropdown-menu' ),
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
				 * Option: Primary Submenu Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-primary-dropdown-menu]',
					'section'     => 'section-primary-header-typo',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'transport'   => 'postMessage',
					'priority'    => 31,
					'default'     => astra_get_option( 'font-size-primary-dropdown-menu' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Primary Submenu Line Height
				 */

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-primary-dropdown-menu]',
					'type'              => 'control',
					'section'           => 'section-primary-header-typo',
					'priority'          => 32,
					'title'             => __( 'Line Height', 'astra-addon' ),
					'transport'         => 'postMessage',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
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
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-outside-menu-typo-dropdown]',
					'title'    => __( 'Outside menu item', 'astra-addon' ),
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[header-display-outside-menu]', '==', '1' ),
					'control'  => 'ast-divider',
					'priority' => 33,
					'section'  => 'section-primary-header-typo',
					'settings' => array(),
				),

				/**
				 * Option: Outside menu font size
				 */

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[outside-menu-font-size]',
					'control'     => 'ast-responsive',
					'type'        => 'control',
					'required'    => array( ASTRA_THEME_SETTINGS . '[header-display-outside-menu]', '==', '1' ),
					'section'     => 'section-primary-header-typo',
					'default'     => astra_get_option( 'outside-menu-font-size' ),
					'priority'    => 34,
					'title'       => __( 'Font Size', 'astra-addon' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: outside Menu Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[outside-menu-line-height]',
					'section'           => 'section-primary-header-typo',
					'transport'         => 'postMessage',
					'required'          => array( ASTRA_THEME_SETTINGS . '[header-display-outside-menu]', '==', '1' ),
					'title'             => __( 'Line Height', 'astra-addon' ),
					'type'              => 'control',
					'control'           => 'ast-slider',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'priority'          => 35,
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 10,
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Primary_Menu_Typo_Configs;
