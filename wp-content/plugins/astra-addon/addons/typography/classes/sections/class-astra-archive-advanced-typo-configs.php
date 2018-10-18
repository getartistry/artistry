<?php
/**
 * Section [Archive] options for astra theme.
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

if ( ! class_exists( 'Astra_Archive_Advanced_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Archive_Advanced_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Archive Summary Box - Title Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-archive-summary-title]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'default'   => astra_get_option( 'font-family-archive-summary-title' ),
					'section'   => 'section-archive-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-archive-summary-title]',
					'priority'  => 1,
				),

				/**
				 * Option: Archive Summary Box Title Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-archive-summary-title]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-archive-summary-title' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-archive-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-archive-summary-title]',
					'priority'          => 2,
				),

				/**
				 * Option: Archive Summary Box Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-archive-summary-title]',
					'section'   => 'section-archive-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'transport' => 'postMessage',
					'type'      => 'control',
					'control'   => 'select',
					'default'   => astra_get_option( 'text-transform-archive-summary-title' ),
					'priority'  => 3,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Archive Summary Box Title Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-archive-summary-title]',
					'section'           => 'section-archive-typo',
					'transport'         => 'postMessage',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'astra-addon' ),
					'type'              => 'control',
					'control'           => 'ast-slider',
					'priority'          => 4,
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Blog - Post Title Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-page-title]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-page-title' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-archive-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-page-title]',
					'priority'  => 7,
				),

				/**
				 * Option: Blog - Post Title Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-page-title]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-page-title' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-archive-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-page-title]',
					'priority'          => 8,
				),

				/**
				 * Option: Blog - Post Title Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-page-title]',
					'section'   => 'section-archive-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'default'   => astra_get_option( 'text-transform-page-title' ),
					'transport' => 'postMessage',
					'type'      => 'control',
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
				 * Option: Blog - Post Title Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-page-title]',
					'section'           => 'section-archive-typo',
					'title'             => __( 'Line Height', 'astra-addon' ),
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'type'              => 'control',
					'control'           => 'ast-slider',
					'transport'         => 'postMessage',
					'priority'          => 10,
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
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-archive-typo-post-meta]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'title'    => __( 'Post Meta', 'astra-addon' ),
					'section'  => 'section-archive-typo',
					'settings' => array(),
				),

				/**
				 * Option: Post Meta Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-post-meta]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-post-meta' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-archive-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-post-meta]',
				),

				/**
				 * Option: Post Meta Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-post-meta]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-post-meta' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-archive-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-post-meta]',
				),

				/**
				 * Option: Post Meta Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-post-meta]',
					'section'   => 'section-archive-typo',
					'type'      => 'control',
					'control'   => 'select',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'default'   => astra_get_option( 'text-transform-post-meta' ),
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
				 * Option: Post Meta Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-post-meta]',
					'section'     => 'section-archive-typo',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'default'     => astra_get_option( 'font-size-post-meta' ),
					'transport'   => 'postMessage',
					'type'        => 'control',
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
				 * Option: Post Meta Line Height
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[line-height-post-meta]',
					'section'           => 'section-archive-typo',
					'title'             => __( 'Line Height', 'astra-addon' ),
					'type'              => 'control',
					'control'           => 'ast-slider',
					'default'           => '',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'transport'         => 'postMessage',
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
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-archive-typo-pagination]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'title'    => __( 'Pagination', 'astra-addon' ),
					'section'  => 'section-archive-typo',
					'settings' => array(),
				),

				/**
				 * Option: Pagination Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-post-pagination]',
					'section'   => 'section-archive-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'default'   => astra_get_option( 'text-transform-post-pagination' ),
					'transport' => 'postMessage',
					'type'      => 'control',
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
				 * Option: Pagination Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-post-pagination]',
					'section'     => 'section-archive-typo',
					'default'     => astra_get_option( 'font-size-post-pagination' ),
					'transport'   => 'postMessage',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Archive_Advanced_Typo_Configs;
