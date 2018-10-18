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

if ( ! class_exists( 'Astra_Nav_Menu_Below_Header_Colors' ) ) {

	/**
	 * Register Mega Menu Customizer Configurations.
	 */
	class Astra_Nav_Menu_Below_Header_Colors extends Astra_Customizer_Config_Base {

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
				 * Normal Below Header Colors
				 */

				// Option: Divider.
				array(
					'type'     => 'control',
					'control'  => 'ast-divider',
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-colors-divider]',
					'title'    => __( 'Megamenu Column Heading', 'astra-addon' ),
					'section'  => 'section-below-header-colors-bg',
					'settings' => array(),
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Megamenu Heading Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-color]',
					'default'   => astra_get_option( 'below-header-megamenu-heading-color' ),
					'title'     => __( 'Color', 'astra-addon' ),
					'section'   => 'section-below-header-colors-bg',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Megamenu Heading Hover Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-h-color]',
					'default'   => astra_get_option( 'below-header-megamenu-heading-h-color' ),
					'title'     => __( 'Hover Color', 'astra-addon' ),
					'section'   => 'section-below-header-colors-bg',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Sticky Below Header Colors
				 */

				// Option: Divider.
				array(
					'type'     => 'control',
					'control'  => 'ast-divider',
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-below-header-megamenu-heading-colors-divider]',
					'title'    => __( 'Megamenu Column Heading', 'astra-addon' ),
					'section'  => 'section-colors-sticky-below-header',
					'settings' => array(),
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Megamenu Heading Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[sticky-below-header-megamenu-heading-color]',
					'default'   => astra_get_option( 'sticky-below-header-megamenu-heading-color' ),
					'title'     => __( 'Color', 'astra-addon' ),
					'section'   => 'section-colors-sticky-below-header',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Megamenu Heading Hover Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[sticky-below-header-megamenu-heading-h-color]',
					'default'   => astra_get_option( 'sticky-below-header-megamenu-heading-h-color' ),
					'title'     => __( 'Hover Color', 'astra-addon' ),
					'section'   => 'section-colors-sticky-below-header',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Nav_Menu_Below_Header_Colors;

