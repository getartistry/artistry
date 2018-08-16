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

if ( ! class_exists( 'Astra_Customizer_Blog_Pro_Panels_And_Sections' ) ) {

	/**
	 * Register Blog Pro Panels and sections Customizer Configurations.
	 */
	class Astra_Customizer_Blog_Pro_Panels_And_Sections extends Astra_Customizer_Config_Base {

		/**
		 * Register Blog Pro Panels and sections Customizer Configurations.
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
					'name'     => 'section-colors-single',
					'type'     => 'section',
					'title'    => __( 'Single Page/Post', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 45,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Blog_Pro_Panels_And_Sections;
