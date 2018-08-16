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
if ( ! class_exists( 'Astra_Customizer_Colors_Archive' ) ) {

	/**
	 * Register General Customizer Configurations.
	 */
	class Astra_Customizer_Colors_Archive extends Astra_Customizer_Config_Base {

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

				// Option: Divider.
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[archive-summary-box-lable]',
					'type'    => 'control',
					'control' => 'ast-divider',
					'title'   => __( 'Archive Summary Box', 'astra-addon' ),
					'section' => 'section-colors-archive',
				),

				// Option: Archive Summary Box Background Color.
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[archive-summary-box-bg-color]',
					'default'     => '',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-color',
					'title'       => __( 'Background Color', 'astra-addon' ),
					'section'     => 'section-colors-archive',
					'description' => __( 'This background color will not work on Full-width layouts.', 'astra-addon' ),
				),

				// Option: Archive Summary Box Title Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[archive-summary-box-title-color]',
					'default'   => '',
					'title'     => __( 'Title Color', 'astra-addon' ),
					'section'   => 'section-colors-archive',
				),

				// Option: Archive Summary Box Description Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[archive-summary-box-text-color]',
					'default'   => '',
					'title'     => __( 'Description Color', 'astra-addon' ),
					'section'   => 'section-colors-archive',
				),

				// Option: Divider.
				array(
					'type'    => 'control',
					'control' => 'ast-divider',
					'name'    => ASTRA_THEME_SETTINGS . '[archive-summary-box-divider]',
					'section' => 'section-colors-archive',
				),

				// Option: Blog / Archive Post Title Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[page-title-color]',
					'title'     => __( 'Blog/Archive Post Title Color', 'astra-addon' ),
					'section'   => 'section-colors-archive',
				),

				// Option: Post Meta Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[post-meta-color]',
					'title'     => __( 'Post Meta Color', 'astra-addon' ),
					'section'   => 'section-colors-archive',
				),

				// Option: Post Meta Link Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[post-meta-link-color]',
					'title'     => __( 'Post Meta Link Color', 'astra-addon' ),
					'section'   => 'section-colors-archive',
				),

				// Option: Post Meta Link Hover Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[post-meta-link-h-color]',
					'title'     => __( 'Post Meta Link Hover Color', 'astra-addon' ),
					'section'   => 'section-colors-archive',
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Colors_Archive;
