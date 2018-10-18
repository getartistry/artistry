<?php
/**
 * Customizer Notices Class.
 * Display Relavant notices in the customizer panels and sections to improve UX.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.4.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Customizer_Notices_Configs' ) ) :

	/**
	 * The Customizer class.
	 */
	class Astra_Customizer_Notices_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register General Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			// Add controls only if Advanced Hooks addon is active.
			if ( defined( 'ASTRA_ADVANCED_HOOKS_POST_TYPE' ) ) {

				$_configs = array(

					/**
					 * Notice for Above header created using custom layout.
					 */
					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-layout-above-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-above-header',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Below header created using custom layout.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-layout-below-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-below-header',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Primary header created using custom layout.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-layout-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-header',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Sticky header created using custom layout.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-layout-sticky-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-sticky-header',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Transparent header created using custom layout.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-layout-transparent-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-transparent-header',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Colors - Above header created using custom layout.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-color-above-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-above-header-colors-bg',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Colors - Primary header created using custom layout.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-color-primary-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-colors-primary-menu',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Colors - Below header created using custom layout.
					 */
					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-color-below-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-below-header-colors-bg',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Colors - Transparent header created using custom layout.
					 */
					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-color-transparent-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-colors-transparent-header',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Typography - Above header created using custom layout.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-typo-above-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-above-header-typo',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Typography - Primary header created using custom layout.
					 */
					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-typo-primary-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-primary-header-typo',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Typography - Below header created using custom layout.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-typo-below-header]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-below-header-typo',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),

					/**
					 * Notice for Title & Tagline section when header is created using custom layout.
					 */
					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-custom-title_tagline]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'title_tagline',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_custom_layout_header' ),
						'help'            => $this->get_help_text_notice( 'custom-header' ),
					),
				);

				$configurations = array_merge( $configurations, $_configs );

			}

			if ( defined( 'ASTRA_EXT_TRANSPARENT_HEADER_DIR' ) ) {

				$_configs = array(

					/**
					 * Notice for Colors - Transparent header enabled on page.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-color-transparent-above-header-notice]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-above-header-colors-bg',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_transparent_header_enabled' ),
						'help'            => $this->get_help_text_notice( 'transparent-header' ),
					),

					/**
					 * Notice for Colors - Transparent header enabled on page.
					 */
					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-color-transparent-header-notice]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-colors-primary-menu',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_transparent_header_enabled' ),
						'help'            => $this->get_help_text_notice( 'transparent-header' ),
					),

					/**
					 * Notice for Colors - Transparent header enabled on page.
					 */

					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-color-transparent-below-header-notice]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-below-header-colors-bg',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_transparent_header_enabled' ),
						'help'            => $this->get_help_text_notice( 'transparent-header' ),
					),
				);

				$configurations = array_merge( $configurations, $_configs );
			}

			return $configurations;

		}

		/**
		 * Check if transparent header is enabled on the page being previewed.
		 *
		 * @since  1.4.0
		 * @return boolean True - If Transparent Header is enabled, False if not.
		 */
		public function is_transparent_header_enabled() {
			return Astra_Ext_Transparent_Header_Markup::get_instance()->is_transparent_header();
		}

		/**
		 * Help notice message to be displayed when the page that is being previewed has header built using Custom Layout.
		 *
		 * @since  1.4.0
		 * @param String $context Type of notice message to be returned.
		 * @return String HTML Markup for the help notice.
		 */
		private function get_help_text_notice( $context ) {

			switch ( $context ) {
				case 'custom-header':
					$notice = '<div class="ast-customizer-notice wp-ui-highlight"><p>The header on the page you are previewing is built using Custom Layouts. Options given below will not work here.</p><p> <a href="' . $this->get_custom_layout_edit_link() . '" target="_blank">Click here</a> to modify the header on this page.<p></div>';
					break;

				case 'transparent-header':
					$notice = '<div class="ast-customizer-notice wp-ui-highlight"><p>This page has Transparent Header enabled, so the settings in this section may not apply</p><p><a href="#" class="ast-customizer-internal-link" data-ast-customizer-section="section-colors-transparent-header">Click here</a> to modify the transparent header settings.<p></div>';
					break;

				default:
					$notice = '';
					break;
			}

			return $notice;
		}

		/**
		 * Return post edit page url for Custom Layouts post type.
		 *
		 * @return String Admin URL for Custom Layouts post edit screen.
		 */
		private function get_custom_layout_edit_link() {
			return admin_url( 'edit.php?post_type=astra-advanced-hook' );
		}

		/**
		 * Decide if Notice for Header Built using Custom Layout should be displayed.
		 * This runs teh target rules to check if the page neing previewed has a header built using Custom Layout.
		 *
		 * @return boolean  True - If the notice should be displayed, False - If the notice should be hidden.
		 */
		public function is_custom_layout_header() {

			$option = array(
				'location'  => 'ast-advanced-hook-location',
				'exclusion' => 'ast-advanced-hook-exclusion',
				'users'     => 'ast-advanced-hook-users',
			);

			$advanced_hooks = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions( ASTRA_ADVANCED_HOOKS_POST_TYPE, $option );

			foreach ( $advanced_hooks as $post_id => $post_data ) {
				$layout = get_post_meta( $post_id, 'ast-advanced-hook-layout', false );

				if ( isset( $layout[0] ) && 'header' == $layout[0] ) {
					return true;
				}
			}

			return false;
		}

	}

endif;


new Astra_Customizer_Notices_Configs();
