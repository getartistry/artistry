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
if ( ! class_exists( 'Astra_Customizer_Learndash_General_Configs' ) ) {

	/**
	 * Register General Customizer Configurations.
	 */
	class Astra_Customizer_Learndash_General_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register General Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Distraction Free Learning
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[learndash-distraction-free-learning]',
					'default'     => astra_get_option( 'learndash-distraction-free-learning' ),
					'type'        => 'control',
					'section'     => 'section-learndash',
					'title'       => __( 'Enable Distraction Free Learning', 'astra-addon' ),
					'description' => __( 'Remove extra links in the header and footer in LearnDash learning pages', 'astra-addon' ),
					'priority'    => 5,
					'control'     => 'checkbox',
				),

				/**
				 * Option: Enable Header Profile Link
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-profile-link-enabled]',
					'default'  => astra_get_option( 'learndash-profile-link-enabled' ),
					'type'     => 'control',
					'section'  => 'section-learndash',
					'title'    => __( 'Display Student\'s Gravatar in Primary Header', 'astra-addon' ),
					'priority' => 10,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Profile Link
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[learndash-profile-link]',
					'default'  => astra_get_option( 'learndash-profile-link' ),
					'type'     => 'control',
					'control'  => 'text',
					'section'  => 'section-learndash',
					'title'    => __( 'Profile Picture Links to:', 'astra-addon' ),
					'priority' => 15,
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[learndash-profile-link-enabled]', '==', true ),
						),
					),
				),

				/**
				 * Option: Table Border Radius
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[learndash-table-border-radius]',
					'default'     => '0',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-slider',
					'title'       => __( 'Table Border Radius', 'astra-addon' ),
					'section'     => 'section-learndash',
					'suffix'      => '',
					'priority'    => 35,
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
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
new Astra_Customizer_Learndash_General_Configs;
