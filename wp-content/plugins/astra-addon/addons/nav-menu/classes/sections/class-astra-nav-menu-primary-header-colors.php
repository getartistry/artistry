<?php
/**
 * Mega Menu Options configurations.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.6.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Nav_Menu_Primary_Header_Colors' ) ) {

	/**
	 * Register Mega Menu Customizer Configurations.
	 */
	class Astra_Nav_Menu_Primary_Header_Colors extends Astra_Customizer_Config_Base {

		/**
		 * Register Mega Menu Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.6.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Normal Primary Header Colors
				 */

				// Option: Divider.
				array(
					'type'     => 'control',
					'control'  => 'ast-divider',
					'name'     => ASTRA_THEME_SETTINGS . '[primary-header-megamenu-heading-colors-divider]',
					'title'    => __( 'Megamenu Column Heading', 'astra-addon' ),
					'section'  => 'section-colors-primary-menu',
					'settings' => array(),
				),

				// Option: Megamenu Heading Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[primary-header-megamenu-heading-color]',
					'default'   => astra_get_option( 'primary-header-megamenu-heading-color' ),
					'title'     => __( 'Color', 'astra-addon' ),
					'section'   => 'section-colors-primary-menu',
				),

				// Option: Megamenu Heading Hover Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[primary-header-megamenu-heading-h-color]',
					'default'   => astra_get_option( 'primary-header-megamenu-heading-h-color' ),
					'title'     => __( 'Hover Color', 'astra-addon' ),
					'section'   => 'section-colors-primary-menu',
				),

				/**
				 * Sticky Primary Header Colors
				 */

				// Option: Divider.
				array(
					'type'     => 'control',
					'control'  => 'ast-divider',
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-primary-header-megamenu-heading-colors-divider]',
					'title'    => __( 'Megamenu Column Heading', 'astra-addon' ),
					'section'  => 'section-colors-sticky-primary-header',
					'settings' => array(),
				),

				// Option: Megamenu Heading Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[sticky-primary-header-megamenu-heading-color]',
					'default'   => astra_get_option( 'sticky-primary-header-megamenu-heading-color' ),
					'title'     => __( 'Color', 'astra-addon' ),
					'section'   => 'section-colors-sticky-primary-header',
				),

				// Option: Megamenu Heading Hover Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[sticky-primary-header-megamenu-heading-h-color]',
					'default'   => astra_get_option( 'sticky-primary-header-megamenu-heading-h-color' ),
					'title'     => __( 'Hover Color', 'astra-addon' ),
					'section'   => 'section-colors-sticky-primary-header',
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Nav_Menu_Primary_Header_Colors;

