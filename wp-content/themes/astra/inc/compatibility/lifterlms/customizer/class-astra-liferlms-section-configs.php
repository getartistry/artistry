<?php
/**
 * Register customizer panels & sections.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.2.0
 */

if ( ! class_exists( 'Astra_Liferlms_Section_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Liferlms_Section_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register LearnDash Container settings.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				array(
					'name'     => 'section-lifterlms',
					'type'     => 'section',
					'priority' => 65,
					'title'    => __( 'LifterLMS', 'astra' ),
					'panel'    => 'panel-layout',
				),
			);

			return array_merge( $configurations, $_configs );

		}
	}
}

new Astra_Liferlms_Section_Configs;
