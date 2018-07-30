<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Adv_Footer_Colors_Configs' ) ) {

	/**
	 * Register Advanced Footer Color Customizer Configurations.
	 */
	class Astra_Advanced_Footer_Colors_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Advanced Footer Color Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = array(

				/**
				 * Option: Widget Title Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
					'title'    => __( 'Widget Title Color', 'astra' ),
					'default'  => '',
					'section'  => 'section-footer-adv-color-bg',
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-text-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
					'title'    => __( 'Text Color', 'astra' ),
					'default'  => '',
					'section'  => 'section-footer-adv-color-bg',
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-link-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
					'title'    => __( 'Link Color', 'astra' ),
					'default'  => '',
					'section'  => 'section-footer-adv-color-bg',
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-link-h-color]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
					'title'    => __( 'Link Hover Color', 'astra' ),
					'default'  => '',
					'section'  => 'section-footer-adv-color-bg',
				),

				/**
				 * Option: Background Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-background-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
					'section'  => 'section-footer-adv-color-bg',
					'settings' => array(),
				),

				/**
				 * Option: Footer widget Background
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-bg-obj]',
					'type'     => 'control',
					'control'  => 'ast-background',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
					'default'  => astra_get_option( 'footer-adv-bg-obj' ),
					'section'  => 'section-footer-adv-color-bg',
					'title'    => __( 'Background', 'astra' ),
				),

			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}

new Astra_Advanced_Footer_Colors_Configs;


