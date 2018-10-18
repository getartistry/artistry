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

if ( ! class_exists( 'Astra_Nav_Menu_Below_Header_Typography' ) ) {

	/**
	 * Register Mega Menu Customizer Configurations.
	 */
	class Astra_Nav_Menu_Below_Header_Typography extends Astra_Customizer_Config_Base {

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

				// Option: Below Megamenu Divider.
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-typo-divider]',
					'title'    => __( 'Megamenu Column Heading', 'astra-addon' ),
					'section'  => 'section-below-header-typo',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Below Megamenu Header Menu Font Family.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-font-family]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'below-header-megamenu-heading-font-family' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-below-header-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-font-weight]',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Below Megamenu Header Menu Font Weight.
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-font-weight]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'default'           => astra_get_option( 'below-header-megamenu-heading-font-weight' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-below-header-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-font-family]',
					'required'          => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Below Megamenu Header Menu Text Transform.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-text-transform]',
					'type'      => 'control',
					'control'   => 'select',
					'section'   => 'section-below-header-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'below-header-megamenu-heading-text-transform' ),
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Below Megamenu Header Menu Font Size.
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[below-header-megamenu-heading-font-size]',
					'section'     => 'section-below-header-typo',
					'transport'   => 'postMessage',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'responsive'  => false,
					'default'     => astra_get_option( 'below-header-megamenu-heading-font-size' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
					'required'    => array(
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

new Astra_Nav_Menu_Below_Header_Typography;

