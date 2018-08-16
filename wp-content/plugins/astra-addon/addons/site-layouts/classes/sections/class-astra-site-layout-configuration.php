<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Site_Layout_Configuration' ) ) {

	/**
	 * Register Site Layout Customizer Configurations.
	 */
	class Astra_Site_Layout_Configuration extends Astra_Customizer_Config_Base {

		/**
		 * Register Site Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Site Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[site-layout]',
					'default'  => astra_get_option( 'site-layout' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-container-layout',
					'priority' => 5,
					'title'    => __( 'Site Layout', 'astra-addon' ),
					'choices'  => array(
						'ast-full-width-layout'  => __( 'Full Width', 'astra-addon' ),
						'ast-box-layout'         => __( 'Max Width', 'astra-addon' ),
						'ast-padded-layout'      => __( 'Padded', 'astra-addon' ),
						'ast-fluid-width-layout' => __( 'Fluid', 'astra-addon' ),
					),
				),

				/**
				 * Option: Padded Layout Custom Width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[site-layout-padded-width]',
					'default'     => 1200,
					'type'        => 'control',
					'control'     => 'ast-slider',
					'transport'   => 'postMessage',
					'section'     => 'section-container-layout',
					'priority'    => 15,
					'title'       => __( 'Container Width', 'astra-addon' ),
					'required'    => array( ASTRA_THEME_SETTINGS . '[site-layout]', '==', 'ast-padded-layout' ),
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
				),

				/**
				 * Option: Box Width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[site-layout-box-width]',
					'default'     => 1200,
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-slider',
					'section'     => 'section-container-layout',
					'priority'    => 25,
					'title'       => __( 'Max Width', 'astra-addon' ),
					'suffix'      => '',
					'required'    => array( ASTRA_THEME_SETTINGS . '[site-layout]', '==', 'ast-box-layout' ),
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
				),

				/**
				 * Option: Padded Layout Custom Width
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[site-layout-padded-pad]',
					'default'        => astra_get_option( 'site-layout-padded-pad' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-container-layout',
					'priority'       => 20,
					'title'          => __( 'Space Outside Body', 'astra-addon' ),
					'required'       => array( ASTRA_THEME_SETTINGS . '[site-layout]', '==', 'ast-padded-layout' ),
					'linked_choices' => true,
					'unit_choices'   => array( 'px', 'em', '%' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				),

				/**
				 * Option: Box Top & Bottom Margin
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[site-layout-box-tb-margin]',
					'default'     => 0,
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-slider',
					'section'     => 'section-container-layout',
					'priority'    => 30,
					'title'       => __( 'Top & Bottom Margin', 'astra-addon' ),
					'required'    => array( ASTRA_THEME_SETTINGS . '[site-layout]', '==', 'ast-box-layout' ),
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Layout: Fluid layout
				 */

				/**
				 * Option: Page Left & Right Padding
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[site-layout-fluid-lr-padding]',
					'default'     => 25,
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-slider',
					'section'     => 'section-container-layout',
					'priority'    => 35,
					'title'       => __( 'Page Left & Right Padding', 'astra-addon' ),
					'required'    => array( ASTRA_THEME_SETTINGS . '[site-layout]', '==', 'ast-fluid-width-layout' ),
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 200,
					),
				),

				/**
				 * Option: Body Background
				 *
				 * NOTE: We have added below field for backward compatibility.
				 * If plugin is updated before the theme update then this filed will be visible.
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[site-layout-outside-bg-obj]',
					'default'   => astra_get_option( 'site-layout-outside-bg-obj' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-background',
					'section'   => 'section-colors-body',
					'priority'  => 25,
					'title'     => __( 'Background', 'astra-addon' ),
				),

			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Site_Layout_Configuration;


