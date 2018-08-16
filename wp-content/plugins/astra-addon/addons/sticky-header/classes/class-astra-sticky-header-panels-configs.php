<?php
/**
 * Sticky Header - Panels & Sections
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Astra_Sticky_Header_Panels_Configs' ) ) {

	/**
	 * Register Sticky Header Customizer Configurations.
	 */
	class Astra_Sticky_Header_Panels_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Sticky Header Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_config = array(

				array(
					'name'     => 'section-sticky-header',
					'title'    => __( 'Sticky Header', 'astra-addon' ),
					'panel'    => 'panel-layout',
					'section'  => 'section-header-group',
					'priority' => 31,
					'type'     => 'section',
				),

				array(
					'name'     => 'section-colors-sticky-header',
					'title'    => __( 'Sticky Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-header-group',
					'priority' => 31,
					'type'     => 'section',
				),

				array(
					'name'     => 'section-colors-sticky-primary-header',
					'title'    => __( 'Primary Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-sticky-header',
					'priority' => 10,
					'type'     => 'section',
				),

				array(
					'name'     => 'section-colors-sticky-above-header',
					'title'    => __( 'Above Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-sticky-header',
					'priority' => 20,
					'type'     => 'section',
				),

				array(
					'name'     => 'section-colors-sticky-below-header',
					'title'    => __( 'Below Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-sticky-header',
					'priority' => 30,
					'type'     => 'section',
				),

			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Sticky_Header_Panels_Configs;
