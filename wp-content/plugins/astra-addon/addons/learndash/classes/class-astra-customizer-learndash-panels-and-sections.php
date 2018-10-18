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

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}
/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */

if ( ! class_exists( 'Astra_Customizer_Learndash_Panels_And_Sections' ) ) {

	/**
	 * Register learndash Panels and sections Customizer Configurations.
	 */
	class Astra_Customizer_Learndash_Panels_And_Sections extends Astra_Customizer_Config_Base {

		/**
		 * Register learndash Panels and sections Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Layout Panel
				 */

				array(
					'name'     => 'section-learndash-colors',
					'type'     => 'section',
					'title'    => __( 'LearnDash', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 65,
				),

				array(
					'name'     => 'section-learndash-typo',
					'type'     => 'section',
					'title'    => __( 'LearnDash', 'astra-addon' ),
					'panel'    => 'panel-typography',
					'priority' => 65,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Learndash_Panels_And_Sections;
