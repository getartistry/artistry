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
if ( ! class_exists( 'Astra_Customizer_Learndash_Color_Configs' ) ) {

	/**
	 * Register Learndash color Customizer Configurations.
	 */
	class Astra_Customizer_Learndash_Color_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Learndash color Customizer Configurations.
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
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-table-colors-divider]',
					'section'  => 'section-learndash-colors',
					'title'    => __( 'LearnDash Tables', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 5,
					'settings' => array(),
				),

				/**
				 * Option: Heading Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-table-heading-color]',
					'default'  => '',
					'type'     => 'control',
					'control'  => 'ast-color',
					'title'    => __( 'Heading Color', 'astra-addon' ),
					'section'  => 'section-learndash-colors',
					'priority' => 10,
				),

				/**
				 * Option: Heading Background Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-table-heading-bg-color]',
					'default'  => '',
					'type'     => 'control',
					'control'  => 'ast-color',
					'title'    => __( 'Heading Background Color', 'astra-addon' ),
					'section'  => 'section-learndash-colors',
					'priority' => 15,
				),

				/**
				 * Option: Title Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-table-title-color]',
					'default'  => '',
					'type'     => 'control',
					'control'  => 'ast-color',
					'title'    => __( 'Title Color', 'astra-addon' ),
					'section'  => 'section-learndash-colors',
					'priority' => 20,
				),

				/**
				 * Option: Title Background Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-table-title-bg-color]',
					'default'  => '',
					'type'     => 'control',
					'control'  => 'ast-color',
					'title'    => __( 'Title Background Color', 'astra-addon' ),
					'section'  => 'section-learndash-colors',
					'priority' => 25,
				),

				/**
				 * Option: Separator Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-table-title-separator-color]',
					'default'  => '',
					'type'     => 'control',
					'control'  => 'ast-color',
					'title'    => __( 'Separator Color', 'astra-addon' ),
					'section'  => 'section-learndash-colors',
					'priority' => 30,
				),

				/**
				 * Option: Complete Icon Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-complete-icon-color]',
					'default'  => '',
					'type'     => 'control',
					'control'  => 'ast-color',
					'title'    => __( 'Complete Icon Color', 'astra-addon' ),
					'section'  => 'section-learndash-colors',
					'priority' => 35,
				),

				/**
				 * Option: Incomplete Icon Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-incomplete-icon-color]',
					'default'  => '',
					'type'     => 'control',
					'control'  => 'ast-color',
					'title'    => __( 'Incomplete Icon Color', 'astra-addon' ),
					'section'  => 'section-learndash-colors',
					'priority' => 40,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Learndash_Color_Configs;
