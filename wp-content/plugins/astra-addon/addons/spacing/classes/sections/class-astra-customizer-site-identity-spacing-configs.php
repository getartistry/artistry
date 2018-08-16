<?php
/**
 * Site Identity Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.4.3
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'Astra_Customizer_Site_Identity_Spacing_Configs' ) ) {

	/**
	 * Register Site Identity Spacing Customizer Configurations.
	 */
	class Astra_Customizer_Site_Identity_Spacing_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Site Identity Spacing Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-site-identity-spacing]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'title_tagline',
					'title'    => __( 'Spacing', 'astra-addon' ),
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-title]', '!=', 0 ),
							array( 'custom_logo', '!=', '' ),
						),
						'operator'   => 'OR',
					),
					'priority' => 50,
					'settings' => array(),
				),

				/**
				 * Option - Header Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[site-identity-spacing]',
					'default'        => astra_get_option( 'site-identity-spacing' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'title_tagline',
					'priority'       => 50,
					'title'          => __( 'Site Identity Space', 'astra-addon' ),
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-title]', '!=', 0 ),
							array( 'custom_logo', '!=', '' ),
						),
						'operator'   => 'OR',
					),
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
				 * Option: Site Identity Spacing Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[site-identity-spacing-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[display-site-title]', '!=', '' ),
							array( ASTRA_THEME_SETTINGS . '[display-site-tagline]', '!=', '' ),
							array( 'custom_logo', '!=', '' ),
							array( ASTRA_THEME_SETTINGS . '[ast-header-retina-logo]', '!=', '' ),
						),
						'operator'   => 'OR',
					),
					'section'  => 'title_tagline',
					'priority' => 50,
					'settings' => array(),
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Site_Identity_Spacing_Configs;
