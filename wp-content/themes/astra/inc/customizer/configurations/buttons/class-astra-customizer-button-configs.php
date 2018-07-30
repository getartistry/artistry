<?php
/**
 * Astra Theme Customizer Configuration Base.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.4.3
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
if ( ! class_exists( 'Astra_Customizer_Button_Configs' ) ) {

	/**
	 * Register Button Customizer Configurations.
	 */
	class Astra_Customizer_Button_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Button Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Button Color
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[button-color]',
					'default' => '',
					'type'    => 'control',
					'control' => 'ast-color',
					'section' => 'section-buttons',
					'title'   => __( 'Button Text Color', 'astra' ),
				),

				/**
				 * Option: Button Hover Color
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[button-h-color]',
					'default' => '',
					'section' => 'section-buttons',
					'type'    => 'control',
					'control' => 'ast-color',
					'title'   => __( 'Button Text Hover Color', 'astra' ),
				),

				/**
				 * Option: Button Background Color
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[button-bg-color]',
					'default' => '',
					'section' => 'section-buttons',
					'type'    => 'control',
					'control' => 'ast-color',
					'title'   => __( 'Button Background Color', 'astra' ),
				),

				/**
				 * Option: Button Background Hover Color
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[button-bg-h-color]',
					'section' => 'section-buttons',
					'default' => '',
					'type'    => 'control',
					'control' => 'ast-color',
					'title'   => __( 'Button Background Hover Color', 'astra' ),
				),

				/**
				 * Option: Button Radius
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[button-radius]',
					'section'     => 'section-buttons',
					'default'     => astra_get_option( 'button-radius' ),
					'type'        => 'control',
					'control'     => 'number',
					'title'       => __( 'Button Radius', 'astra' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 200,
					),
				),

				/**
				 * Option: Vertical Padding
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[button-v-padding]',
					'section'     => 'section-buttons',
					'default'     => astra_get_option( 'button-v-padding' ),
					'title'       => __( 'Vertical Padding', 'astra' ),
					'type'        => 'control',
					'control'     => 'number',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 200,
					),
				),

				/**
				 * Option: Horizontal Padding
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[button-h-padding]',
					'section'     => 'section-buttons',
					'default'     => astra_get_option( 'button-h-padding' ),
					'title'       => __( 'Horizontal Padding', 'astra' ),
					'type'        => 'control',
					'control'     => 'number',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 200,
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Button_Configs;
