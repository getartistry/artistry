<?php
/**
 * Customizer Notices Class.
 * Display Relavant notices in the customizer panels and sections to improve UX.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Customizer_Notices' ) ) :

	/**
	 * The Customizer class.
	 */
	class Astra_Customizer_Notices {

		/**
		 * Constructor
		 *
		 * @since 1.4.0
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ), 10 );
		}

		/**
		 * Customizer Controls and Settings
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since 1.4.0
		 */
		public function customize_register( $wp_customize ) {

			// Add controls only if Advanced Hooks addon is active.
			if ( defined( 'ASTRA_ADVANCED_HOOKS_POST_TYPE' ) ) {

				/**
				 * Notice for Above header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-layout-above-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-layout-above-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-above-header',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Below header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-layout-below-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-layout-below-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-below-header',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Primary header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-layout-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-layout-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-header',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Sticky header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-layout-sticky-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-layout-sticky-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-sticky-header',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Transparent header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-layout-transparent-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-layout-transparent-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-transparent-header',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Colors - Above header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-color-above-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-color-above-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-above-header-colors-bg',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Colors - Primary header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-color-primary-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-color-primary-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-colors-primary-menu',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Colors - Below header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-color-below-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-color-below-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-below-header-colors-bg',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Colors - Transparent header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-color-transparent-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-color-transparent-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-colors-transparent-header',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Typography - Above header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-typo-above-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-typo-above-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-above-header-typo',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Typography - Primary header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-typo-primary-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-typo-primary-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-primary-header-typo',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Typography - Below header created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-typo-below-header]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-typo-below-header]', array(
							'type'            => 'ast-description',
							'section'         => 'section-below-header-typo',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

				/**
				 * Notice for Title & Tagline section when header is created using custom layout.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-custom-title_tagline]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-custom-title_tagline]', array(
							'type'            => 'ast-description',
							'section'         => 'title_tagline',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_custom_layout_header' ),
							'help'            => $this->get_help_text_notice( 'custom-header' ),
						)
					)
				);

			}

			if ( defined( 'ASTRA_EXT_TRANSPARENT_HEADER_DIR' ) ) {

				/**
				 * Notice for Colors - Transparent header enabled on page.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-color-transparent-above-header-notice]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-color-transparent-above-header-notice]', array(
							'type'            => 'ast-description',
							'section'         => 'section-above-header-colors-bg',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_transparent_header_enabled' ),
							'help'            => $this->get_help_text_notice( 'transparent-header' ),
						)
					)
				);

				/**
				 * Notice for Colors - Transparent header enabled on page.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-color-transparent-header-notice]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-color-transparent-header-notice]', array(
							'type'            => 'ast-description',
							'section'         => 'section-colors-primary-menu',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_transparent_header_enabled' ),
							'help'            => $this->get_help_text_notice( 'transparent-header' ),
						)
					)
				);

				/**
				 * Notice for Colors - Transparent header enabled on page.
				 */
				$wp_customize->add_setting(
					ASTRA_THEME_SETTINGS . '[header-color-transparent-below-header-notice]', array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new Astra_Control_Description(
						$wp_customize, ASTRA_THEME_SETTINGS . '[header-color-transparent-below-header-notice]', array(
							'type'            => 'ast-description',
							'section'         => 'section-below-header-colors-bg',
							'priority'        => 1,
							'active_callback' => array( $this, 'is_transparent_header_enabled' ),
							'help'            => $this->get_help_text_notice( 'transparent-header' ),
						)
					)
				);

			}

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
					$notice = '<div class="ast-customizer-notice wp-ui-highlight"><p>This page has Transparent Header enabled, so the settings of Primary Header may not apply</p><p><a href="#" class="ast-customizer-internal-link" data-ast-customizer-section="section-colors-transparent-header">Click here</a> to modify the transparent header settings.<p></div>';
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


new Astra_Customizer_Notices();
