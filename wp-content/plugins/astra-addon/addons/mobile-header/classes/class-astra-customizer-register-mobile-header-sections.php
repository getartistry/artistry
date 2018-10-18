<?php
/**
 * Mobile Header - Panels & Sections
 *
 * @package Astra Addon
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Customizer_Register_Mobile_Header_Sections' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Customizer_Register_Mobile_Header_Sections extends Astra_Customizer_Config_Base {

		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(

				array(
					'name'     => 'section-colors-header-group',
					'type'     => 'section',
					'title'    => __( 'Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 20,
				),

				array(
					'name'     => 'section-colors-mobile-header',
					'type'     => 'section',
					'title'    => __( 'Mobile Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-header-group',
					'priority' => 40,
				),

				array(
					'name'     => 'section-colors-mobile-primary-header',
					'type'     => 'section',
					'title'    => __( 'Primary Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-mobile-header',
					'priority' => 10,
				),

				array(
					'name'     => 'section-mobile-header-typo',
					'type'     => 'section',
					'title'    => __( 'Mobile Header', 'astra-addon' ),
					'panel'    => 'panel-typography',
					'section'  => 'section-header-typo-group',
					'priority' => 30,
				),

				array(
					'name'     => 'section-mobile-primary-header-typo',
					'type'     => 'section',
					'title'    => __( 'Primary Header', 'astra-addon' ),
					'panel'    => 'panel-typography',
					'section'  => 'section-mobile-header-typo',
					'priority' => 10,
				),

				array(
					'name'     => 'section-colors-mobile-above-header',
					'type'     => 'section',
					'title'    => __( 'Above Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-mobile-header',
					'priority' => 5,
				),

				array(
					'name'     => 'section-mobile-above-header-typo',
					'type'     => 'section',
					'title'    => __( 'Above Header', 'astra-addon' ),
					'panel'    => 'panel-typography',
					'section'  => 'section-mobile-header-typo',
					'priority' => 5,
				),

				array(
					'name'     => 'section-colors-mobile-below-header',
					'type'     => 'section',
					'title'    => __( 'Below Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-mobile-header',
					'priority' => 15,
				),

				array(
					'name'     => 'section-mobile-below-header-typo',
					'type'     => 'section',
					'title'    => __( 'Below Header', 'astra-addon' ),
					'panel'    => 'panel-typography',
					'section'  => 'section-mobile-header-typo',
					'priority' => 15,
				),
			);

			return array_merge( $configurations, $configs );
		}
	}
}

new Astra_Customizer_Register_Mobile_Header_Sections;


