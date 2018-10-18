<?php
/**
 * [Header] options for astra theme.
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

if ( ! class_exists( 'Astra_Site_Header_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Site_Header_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Footer typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Site Title Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-site-title]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-site-title' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-primary-header-typo',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-title]', '==', '1' ),
							array( ASTRA_THEME_SETTINGS . '[display-sticky-site-title]', '==', '1' ),
						),
						'operator'   => 'OR',
					),
					'priority'  => 7,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-site-title]',
				),

				/**
				 * Option: Site Title Font Weight
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-weight-site-title]',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-weight',
					'type'      => 'control',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-title]', '==', '1' ),
							array( ASTRA_THEME_SETTINGS . '[display-sticky-site-title]', '==', '1' ),
						),
						'operator'   => 'OR',
					),
					'title'     => __( 'Font Weight', 'astra-addon' ),
					'default'   => astra_get_option( 'font-weight-site-title' ),
					'section'   => 'section-primary-header-typo',
					'priority'  => 8,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-family-site-title]',
				),

				/**
				 * Option: Site Title Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-site-title]',
					'section'   => 'section-primary-header-typo',
					'type'      => 'control',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-title]', '==', '1' ),
							array( ASTRA_THEME_SETTINGS . '[display-sticky-site-title]', '==', '1' ),
						),
						'operator'   => 'OR',
					),
					'default'   => astra_get_option( 'text-transform-site-title' ),
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'transport' => 'postMessage',
					'control'   => 'select',
					'priority'  => 9,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Site Title Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-site-title]',
					'section'           => 'section-primary-header-typo',
					'type'              => 'control',
					'title'             => __( 'Line Height', 'astra-addon' ),
					'transport'         => 'postMessage',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'required'          => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-title]', '==', '1' ),
							array( ASTRA_THEME_SETTINGS . '[display-sticky-site-title]', '==', '1' ),
						),
						'operator'   => 'OR',
					),
					'control'           => 'ast-slider',
					'priority'          => 10,
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Site Tagline Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-site-tagline]',
					'type'      => 'control',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-tagline]', '==', '1' ),
							array( ASTRA_THEME_SETTINGS . '[display-sticky-site-tagline]', '==', '1' ),
						),
						'operator'   => 'OR',
					),
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-site-tagline' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-primary-header-typo',
					'priority'  => 17,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-site-tagline]',
				),

				/**
				 * Option: Site Tagline Font Weight
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-weight-site-tagline]',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-weight',
					'type'      => 'control',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-tagline]', '==', '1' ),
							array( ASTRA_THEME_SETTINGS . '[display-sticky-site-tagline]', '==', '1' ),
						),
						'operator'   => 'OR',
					),
					'default'   => astra_get_option( 'font-weight-site-tagline' ),
					'title'     => __( 'Font Weight', 'astra-addon' ),
					'section'   => 'section-primary-header-typo',
					'priority'  => 18,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-family-site-tagline]',
				),

				/**
				 * Option: Site Tagline Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-site-tagline]',
					'section'   => 'section-primary-header-typo',
					'type'      => 'control',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'transport' => 'postMessage',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-tagline]', '==', '1' ),
							array( ASTRA_THEME_SETTINGS . '[display-sticky-site-tagline]', '==', '1' ),
						),
						'operator'   => 'OR',
					),
					'default'   => astra_get_option( 'text-transform-site-tagline' ),
					'control'   => 'select',
					'priority'  => 19,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Site Tagline Line Height
				 */

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-site-tagline]',
					'section'           => 'section-primary-header-typo',
					'type'              => 'control',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'required'          => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-tagline]', '==', '1' ),
							array( ASTRA_THEME_SETTINGS . '[display-sticky-site-tagline]', '==', '1' ),
						),
						'operator'   => 'OR',
					),
					'title'             => __( 'Line Height', 'astra-addon' ),
					'transport'         => 'postMessage',
					'control'           => 'ast-slider',
					'priority'          => 20,
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

new Astra_Site_Header_Typo_Configs;


