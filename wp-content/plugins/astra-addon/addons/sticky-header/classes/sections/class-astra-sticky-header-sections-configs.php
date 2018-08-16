<?php
/**
 * Sticky Header - Header Sections Options for our theme.
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

if ( ! class_exists( 'Astra_Sticky_Header_Sections_Configs' ) ) {

	/**
	 * Register Sticky Header - Header Sections Customizer Configurations.
	 */
	class Astra_Sticky_Header_Sections_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Sticky Header - Header Sections Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_config = array(
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-above-stick]',
					'default'  => astra_get_option( 'header-above-stick' ),
					'type'     => 'control',
					'section'  => 'section-sticky-header',
					'title'    => __( 'Stick Above Header Section', 'astra-addon' ),
					'priority' => 5,
					'control'  => 'checkbox',
					'required' => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
				),
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-below-stick]',
					'default'  => astra_get_option( 'header-below-stick' ),
					'type'     => 'control',
					'section'  => 'section-sticky-header',
					'title'    => __( 'Stick Below Header Section', 'astra-addon' ),
					'priority' => 14,
					'control'  => 'checkbox',
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
				),
			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Sticky_Header_Sections_Configs;



