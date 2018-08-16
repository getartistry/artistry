<?php
/**
 * LearnDash General Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.4.3
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'Astra_Customizer_Learndash_Typo_Configs' ) ) {

	/**
	 * Register Typo Customizer Configurations.
	 */
	class Astra_Customizer_Learndash_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Typo Customizer Configurations.
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
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-table-tapography-divider]',
					'title'    => __( 'LearnDash Tables', 'astra-addon' ),
					'section'  => 'section-learndash-typo',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 5,
					'settings' => array(),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-learndash-table-heading]',
					'title'    => __( 'Table Heading', 'astra-addon' ),
					'section'  => 'section-learndash-typo',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
					'priority' => 10,
				),

				/**
				 * Option: Table Heading Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-learndash-table-heading]',
					'default'   => astra_get_option( 'font-family-learndash-table-heading' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-learndash-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-heading]',
					'priority'  => 15,
				),

				/**
				 * Option: Table Heading Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-heading]',
					'default'           => astra_get_option( 'font-weight-learndash-table-heading' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-learndash-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-learndash-table-heading]',
					'priority'          => 20,
				),

				/**
				 * Option: Table Heading Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-learndash-table-heading]',
					'default'   => astra_get_option( 'text-transform-learndash-table-heading' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'section'   => 'section-learndash-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'control'   => 'select',
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
					'priority'  => 25,
				),

				/**
				 * Option: Table Heading Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-learndash-table-heading]',
					'default'     => astra_get_option( 'font-size-learndash-table-heading' ),
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'transport'   => 'postMessage',
					'section'     => 'section-learndash-typo',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
					'priority'    => 30,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-learndash-table-content]',
					'title'    => __( 'Table Content', 'astra-addon' ),
					'section'  => 'section-learndash-typo',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
					'priority' => 35,
				),

				/**
				 * Option: Table Heading Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-learndash-table-content]',
					'default'   => astra_get_option( 'font-family-learndash-table-content' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-learndash-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-content]',
					'priority'  => 40,
				),

				/**
				 * Option: Table Heading Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-learndash-table-content]',
					'default'           => astra_get_option( 'font-weight-learndash-table-content' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-learndash-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-learndash-table-content]',
					'priority'          => 45,
				),

				/**
				 * Option: Table Heading Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-learndash-table-content]',
					'default'   => astra_get_option( 'text-transform-learndash-table-content' ),
					'type'      => 'control',
					'control'   => 'select',
					'transport' => 'postMessage',
					'section'   => 'section-learndash-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
					'priority'  => 50,
				),

				/**
				 * Option: Table Heading Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-learndash-table-content]',
					'default'     => astra_get_option( 'font-size-learndash-table-content' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-learndash-typo',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'control'     => 'ast-responsive',
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
					'priority'    => 55,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Learndash_Typo_Configs;
