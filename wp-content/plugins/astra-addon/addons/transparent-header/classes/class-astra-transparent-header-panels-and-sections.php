<?php
/**
 * Transparent Header Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
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
if ( ! class_exists( 'Astra_Transparent_Header_Panels_And_Sections' ) ) {

	/**
	 * Register Transparent Header Customizer Configurations.
	 */
	class Astra_Transparent_Header_Panels_And_Sections extends Astra_Customizer_Config_Base {

		/**
		 * Register Transparent Header Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				array(
					'name'     => 'section-transparent-header',
					'title'    => __( 'Transparent Header', 'astra-addon' ),
					'panel'    => 'panel-layout',
					'type'     => 'section',
					'section'  => 'section-header-group',
					'priority' => 33,
				),

				array(
					'name'     => 'section-colors-transparent-header',
					'title'    => __( 'Transparent Header', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-header-group',
					'priority' => 32,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Transparent_Header_Panels_And_Sections;
