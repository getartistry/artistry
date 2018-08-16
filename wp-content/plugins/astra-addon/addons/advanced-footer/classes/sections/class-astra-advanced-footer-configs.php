<?php
/**
 * Advanced Footer Options for our theme.
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

if ( ! class_exists( 'Astra_Advanced_Footer_Configs' ) ) {

	/**
	 * Register Advanced Footer Customizer Configurations.
	 */
	class Astra_Advanced_Footer_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Advanced Footer Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_config = array(
				/**
				 * Option: Footer Widgets Layout
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[footer-adv]',
					'default' => astra_get_option( 'footer-adv' ),
					'type'    => 'control',
					'control' => 'ast-radio-image',
					'title'   => __( 'Footer Widgets Layout', 'astra-addon' ),
					'section' => 'section-footer-adv',
					'choices' => array(
						'disabled' => array(
							'label' => __( 'Disable', 'astra-addon' ),
							'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/no-adv-footer-115x48.png',
						),
						'layout-1' => array(
							'label' => __( 'Layout 1', 'astra-addon' ),
							'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-1-115x48.png',
						),
						'layout-2' => array(
							'label' => __( 'Layout 2', 'astra-addon' ),
							'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-2-115x48.png',
						),
						'layout-3' => array(
							'label' => __( 'Layout 3', 'astra-addon' ),
							'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-3-115x48.png',
						),
						'layout-4' => array(
							'label' => __( 'Layout 4', 'astra-addon' ),
							'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-4-115x48.png',
						),
						'layout-5' => array(
							'label' => __( 'Layout 5', 'astra-addon' ),
							'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-5-115x48.png',
						),
						'layout-6' => array(
							'label' => __( 'Layout 6', 'astra-addon' ),
							'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-6-115x48.png',
						),
						'layout-7' => array(
							'label' => __( 'Layout 7', 'astra-addon' ),
							'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-7-115x48.png',
						),
					),
				),

				/**
				 * Footer Widgets Padding
				 *
				 * @since 1.2.0 Updated to support responsive spacing param
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[footer-adv-area-padding]',
					'default'        => astra_get_option( 'footer-adv-area-padding' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-footer-adv',
					'title'          => __( 'Footer Widgets Padding', 'astra-addon' ),
					'linked_choices' => true,
					'unit_choices'   => array( 'px', 'em', '%' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
					'required'       => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),
				/**
				 * Option: Footer Widgets Width
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-layout-width]',
					'default'  => astra_get_option( 'footer-adv-layout-width' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-footer-adv',
					'priority' => 35,
					'title'    => __( 'Footer Widgets Width', 'astra-addon' ),
					'choices'  => array(
						'full'    => __( 'Full Width', 'astra-addon' ),
						'content' => __( 'Content Width', 'astra-addon' ),
					),
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),
			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Advanced_Footer_Configs;



