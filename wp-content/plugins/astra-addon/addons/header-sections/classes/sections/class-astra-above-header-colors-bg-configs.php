<?php
/**
 * Above Header Header Color Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Above_Header_Colors_Bg_Configs' ) ) {

	/**
	 * Register Header Layout Customizer Configurations.
	 */
	class Astra_Above_Header_Colors_Bg_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Header Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$defaults = Astra_Theme_Options::defaults();

			$_config = array(

				/**
				 * Option: Background
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[above-header-bg-obj-responsive]',
					'type'      => 'control',
					'control'   => 'ast-responsive-background',
					'transport' => 'postMessage',
					'required'  => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'default'   => $defaults['above-header-bg-obj-responsive'],
					'section'   => 'section-above-header-colors-bg',
					'title'     => __( 'Background', 'astra-addon' ),
				),

				/**
				 * Option: Above Header Menu Color Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-menu-color-divider]',
					'title'    => __( 'Above Header Menu', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'  => 'section-above-header-colors-bg',
					'settings' => array(),
				),

				/**
				 * Option: Menu Background Image, Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[above-header-menu-bg-obj-responsive]',
					'type'      => 'control',
					'control'   => 'ast-responsive-background',
					'transport' => 'postMessage',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'   => $defaults['above-header-menu-bg-obj-responsive'],
					'title'     => __( 'Background', 'astra-addon' ),
					'section'   => 'section-above-header-colors-bg',
				),

				/**
				 * Option: Menu Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-menu-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'default'    => $defaults['above-header-menu-color-responsive'],
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'    => 'section-above-header-colors-bg',
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Menu Hover Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-menu-h-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'    => 'section-above-header-colors-bg',
					'default'    => $defaults['above-header-menu-h-color-responsive'],
					'transport'  => 'postMessage',
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Menu Hover Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-menu-h-bg-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'    => 'section-above-header-colors-bg',
					'default'    => $defaults['above-header-menu-h-bg-color-responsive'],
					'title'      => __( 'Hover Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Menu Active Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-menu-active-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'section'    => 'section-above-header-colors-bg',
					'default'    => $defaults['above-header-menu-active-color-responsive'],
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'title'      => __( 'Active Link Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Menu Active Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-menu-active-bg-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['above-header-menu-active-bg-color-responsive'],
					'section'    => 'section-above-header-colors-bg',
					'title'      => __( 'Active Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Submenu Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-color-bg-dropdown-menu-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'title'    => __( 'Above Header Submenu', 'astra-addon' ),
					'section'  => 'section-above-header-colors-bg',
					'settings' => array(),
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Submenu Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'    => 'section-above-header-colors-bg',
					'default'    => $defaults['above-header-submenu-bg-color-responsive'],
					'title'      => __( 'Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Submenu Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-submenu-text-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'    => 'section-above-header-colors-bg',
					'default'    => $defaults['above-header-submenu-text-color-responsive'],
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Submenu Hover Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-submenu-hover-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'default'    => $defaults['above-header-submenu-hover-color-responsive'],
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'    => 'section-above-header-colors-bg',
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Menu Hover Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-hover-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'    => 'section-above-header-colors-bg',
					'default'    => $defaults['above-header-submenu-bg-hover-color-responsive'],
					'title'      => __( 'Hover Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Submenu Active Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-submenu-active-color-responsive]',
					'transport'  => 'postMessage',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['above-header-submenu-active-color-responsive'],
					'section'    => 'section-above-header-colors-bg',
					'title'      => __( 'Active Link Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Submenu Active Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-submenu-active-bg-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['above-header-submenu-active-bg-color-responsive'],
					'section'    => 'section-above-header-colors-bg',
					'title'      => __( 'Active Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Above Header Content Color Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-content-color-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[above-header-section-1]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[above-header-section-2]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
						),
						'operator'   => 'OR',
					),
					'title'    => __( 'Content Section', 'astra-addon' ),
					'section'  => 'section-above-header-colors-bg',
					'settings' => array(),
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-text-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[above-header-section-1]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[above-header-section-2]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
						),
						'operator'   => 'OR',
					),
					'section'    => 'section-above-header-colors-bg',
					'default'    => $defaults['above-header-text-color-responsive'],
					'title'      => __( 'Text Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-link-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'required'   => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[above-header-section-1]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[above-header-section-2]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['above-header-link-color-responsive'],
					'transport'  => 'postMessage',
					'section'    => 'section-above-header-colors-bg',
					'title'      => __( 'Link Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[above-header-link-h-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[above-header-section-1]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[above-header-section-2]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['above-header-link-h-color-responsive'],
					'section'    => 'section-above-header-colors-bg',
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),
			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Above_Header_Colors_Bg_Configs;



