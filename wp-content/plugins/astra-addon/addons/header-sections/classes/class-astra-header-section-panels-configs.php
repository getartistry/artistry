<?php
/**
 * Advanced Header - Panels & Sections
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Astra_Header_Section_Panels_Configs' ) ) {

	/**
	 * Register Header Layout Customizer Configurations.
	 */
	class Astra_Header_Section_Panels_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Header Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_config = array(

				array(
					'name'     => 'section-below-header',
					'type'     => 'section',
					'title'    => __( 'Below Header', 'astra-addon' ),
					'panel'    => 'panel-layout',
					'section'  => 'section-header-group',
					'priority' => 30,
				),

				array(
					'name'     => 'section-below-header-colors-bg',
					'type'     => 'section',
					'title'    => __( 'Below Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-header-group',
					'priority' => 30,
				),

				array(
					'name'     => 'section-below-header-typo',
					'type'     => 'section',
					'title'    => __( 'Below Header', 'astra-addon' ),
					'panel'    => 'panel-typography',
					'section'  => 'section-header-typo-group',
					'priority' => 30,
				),

				array(
					'name'     => 'section-above-header',
					'type'     => 'section',
					'title'    => __( 'Above Header', 'astra-addon' ),
					'panel'    => 'panel-layout',
					'section'  => 'section-header-group',
					'priority' => 20,
				),

				array(
					'name'     => 'section-above-header-colors-bg',
					'type'     => 'section',
					'title'    => __( 'Above Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-header-group',
					'priority' => 20,
				),

				array(
					'name'     => 'section-above-header-typo',
					'type'     => 'section',
					'title'    => __( 'Above Header', 'astra-addon' ),
					'panel'    => 'panel-typography',
					'section'  => 'section-header-typo-group',
					'priority' => 20,
				),

				/*
				 * Update the Above Header section
				 *
				 * @since 1.4.0
				 */
				array(
					'name'     => 'section-mobile-header-above-header',
					'type'     => 'section',
					'priority' => 5,
					'title'    => __( 'Above Header', 'astra-addon' ),
					'panel'    => 'panel-layout',
					'section'  => 'section-mobile-header',
				),

				/*
				 * Update the Below Header section
				 *
				 * @since 1.4.0
				 */
				array(
					'name'     => 'section-mobile-header-below-header',
					'priority' => 15,
					'type'     => 'section',
					'title'    => __( 'Below Header', 'astra-addon' ),
					'panel'    => 'panel-layout',
					'section'  => 'section-mobile-header',
				),
			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Header_Section_Panels_Configs;
