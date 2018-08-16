<?php
/**
 * Below Header - Typpography Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Below_Header_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Below_Header_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Below Header Typo Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Below Header Menu Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-below-header-typography-primary-menu]',
					'title'    => __( 'Below Header Menu', 'astra-addon' ),
					'section'  => 'section-below-header-typo',
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'control'  => 'ast-divider',
					'settings' => array(),
				),

				/**
				 * Option: Below Header Menu Font Family
				 */

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-below-header-primary-menu]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'required'  => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'default'   => astra_get_option( 'font-family-below-header-primary-menu' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-below-header-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-below-header-primary-menu]',
				),

				/**
				 * Option: Below Header Menu Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-below-header-primary-menu]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'required'          => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'default'           => astra_get_option( 'font-weight-below-header-primary-menu' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-below-header-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-below-header-primary-menu]',
				),

				/**
				 * Option: Below Header Menu Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-below-header-primary-menu]',
					'type'      => 'control',
					'control'   => 'select',
					'section'   => 'section-below-header-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'required'  => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'text-transform-below-header-primary-menu' ),
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Below Header Menu Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-below-header-primary-menu]',
					'section'     => 'section-below-header-typo',
					'transport'   => 'postMessage',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'type'        => 'control',
					'required'    => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'control'     => 'ast-responsive',
					'default'     => astra_get_option( 'font-size-below-header-primary-menu' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Below Header Submenu Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-below-header-typography-dropdown-menu]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'title'    => __( 'Below Header Submenu', 'astra-addon' ),
					'section'  => 'section-below-header-typo',
					'settings' => array(),
				),

				/**
				 * Option: Below Header Submenu Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-below-header-dropdown-menu]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'required'  => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'default'   => astra_get_option( 'font-family-below-header-dropdown-menu' ),
					'section'   => 'section-below-header-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-below-header-dropdown-menu]',
				),

				/**
				 * Option: Below Header Submenu Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-below-header-dropdown-menu]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'default'           => astra_get_option( 'font-weight-below-header-dropdown-menu' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'required'          => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'section'           => 'section-below-header-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-below-header-dropdown-menu]',
				),

				/**
				 * Option: Below Header Submenu Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-below-header-dropdown-menu]',
					'section'   => 'section-below-header-typo',
					'transport' => 'postMessage',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'default'   => astra_get_option( 'text-transform-below-header-dropdown-menu' ),
					'type'      => 'control',
					'control'   => 'select',
					'required'  => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Below Header Submenu Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-below-header-dropdown-menu]',
					'section'     => 'section-below-header-typo',
					'transport'   => 'postMessage',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'required'    => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'title'       => __( 'Font Size', 'astra-addon' ),
					'default'     => astra_get_option( 'font-size-below-header-dropdown-menu' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-below-header-typography-content]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-1]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-2]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
						),
						'operator'   => 'OR',
					),
					'title'    => __( 'Content Section', 'astra-addon' ),
					'section'  => 'section-below-header-typo',
					'settings' => array(),
				),

				/**
				 * Option: Below Header Content Font Family
				 */

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-below-header-content]',
					'default'   => astra_get_option( 'font-family-below-header-content' ),
					'type'      => 'control',
					'required'  => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-1]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-2]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
						),
						'operator'   => 'OR',
					),
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-below-header-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-below-header-content]',
				),

				/**
				 * Option: Below Header Content Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-below-header-content]',
					'default'           => astra_get_option( 'font-weight-below-header-content' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'required'          => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-1]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-2]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
						),
						'operator'   => 'OR',
					),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-below-header-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-below-header-content]',
				),

				/**
				 * Option: Below Header Content Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-below-header-content]',
					'section'   => 'section-below-header-typo',
					'type'      => 'control',
					'control'   => 'select',
					'required'  => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-1]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-2]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
						),
						'operator'   => 'OR',
					),
					'default'   => astra_get_option( 'text-transform-below-header-content' ),
					'transport' => 'postMessage',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Below Header Content Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-below-header-content]',
					'section'     => 'section-below-header-typo',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'required'    => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-1]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-2]',
								'==',
								array( 'search', 'text-html', 'widget' ),
							),
						),
						'operator'   => 'OR',
					),
					'default'     => astra_get_option( 'font-size-below-header-content' ),
					'title'       => __( 'Font Size', 'astra-addon' ),
					'control'     => 'ast-responsive',
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Below_Header_Typo_Configs;


