<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Scroll_To_Top_Panels_And_Sections' ) ) {

	/**
	 * Register Scroll To Top Customizer Configurations.
	 */
	class Astra_Scroll_To_Top_Panels_And_Sections extends Astra_Customizer_Config_Base {

		/**
		 * Register Scroll To Top Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Scroll To Top
				 */
				array(
					'name'     => 'section-scroll-to-top',
					'title'    => __( 'Scroll To Top', 'astra-addon' ),
					'type'     => 'panel',
					'panel'    => 'panel-layout',
					'priority' => 60,
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Scroll_To_Top_Panels_And_Sections;


