<?php
/**
 * Blog Pro General Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.4.3
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
if ( ! class_exists( 'Astra_Customizer_Colors_Content' ) ) {

	/**
	 * Register General Customizer Configurations.
	 */
	class Astra_Customizer_Colors_Content extends Astra_Customizer_Config_Base {

		/**
		 * Register General Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				// Option: Content Background Color.
				array(
					'default'  => astra_get_option( 'content-bg-obj' ),
					'type'     => 'control',
					'control'  => 'ast-background',
					'name'     => ASTRA_THEME_SETTINGS . '[content-bg-obj]',
					'title'    => __( 'Background', 'astra-addon' ),
					'section'  => 'section-colors-content',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[site-content-layout]', '==', array( 'boxed-container', 'content-boxed-container', 'plain-container' ) ),
							array( ASTRA_THEME_SETTINGS . '[single-page-content-layout]', '==', array( 'boxed-container', 'content-boxed-container', 'plain-container' ) ),
							array( ASTRA_THEME_SETTINGS . '[single-post-content-layout]', '==', array( 'boxed-container', 'content-boxed-container', 'plain-container' ) ),
							array( ASTRA_THEME_SETTINGS . '[archive-post-content-layout]', '==', array( 'boxed-container', 'content-boxed-container', 'plain-container' ) ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Divider.
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-bg-color]',
					'section'  => 'section-colors-content',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[site-content-layout]', '==', array( 'boxed-container', 'content-boxed-container', 'plain-container' ) ),
							array( ASTRA_THEME_SETTINGS . '[single-page-content-layout]', '==', array( 'boxed-container', 'content-boxed-container', 'plain-container' ) ),
							array( ASTRA_THEME_SETTINGS . '[single-post-content-layout]', '==', array( 'boxed-container', 'content-boxed-container', 'plain-container' ) ),
							array( ASTRA_THEME_SETTINGS . '[archive-post-content-layout]', '==', array( 'boxed-container', 'content-boxed-container', 'plain-container' ) ),
						),
						'operator'   => 'OR',
					),
				),

				// Option: Heading 1 <h1> Color.
				array(
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[h1-color]',
					'title'     => __( 'Heading 1 (H1) Color', 'astra-addon' ),
					'section'   => 'section-colors-content',
				),

				// Option: Heading 2 <h2> Color.
				array(
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[h2-color]',
					'title'     => __( 'Heading 2 (H2) Color', 'astra-addon' ),
					'section'   => 'section-colors-content',
				),

				// Option: Heading 3 <h3> Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[h3-color]',
					'default'   => '',
					'title'     => __( 'Heading 3 (H3) Color', 'astra-addon' ),
					'section'   => 'section-colors-content',
				),

				// Option: Heading 4 <h4> Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'default'   => '',
					'name'      => ASTRA_THEME_SETTINGS . '[h4-color]',
					'title'     => __( 'Heading 4 (H4) Color', 'astra-addon' ),
					'section'   => 'section-colors-content',
				),

				// Option: Heading 5 <h5> Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[h5-color]',
					'title'     => __( 'Heading 5 (H5) Color', 'astra-addon' ),
					'section'   => 'section-colors-content',
				),

				// Option: Heading 6 <h6> Color.
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[h6-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'default'   => '',
					'title'     => __( 'Heading 6 (H6) Color', 'astra-addon' ),
					'section'   => 'section-colors-content',
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Colors_Content;
