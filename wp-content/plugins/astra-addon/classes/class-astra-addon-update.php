<?php
/**
 * Astra Addon Update
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Astra_Addon_Update' ) ) {

	/**
	 * Astra_Addon_Update initial setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Addon_Update {

		/**
		 * Class instance.
		 *
		 * @access private
		 * @var $instance Class instance.
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {

			// Theme Updates.
			add_action( 'astra_update_before', __CLASS__ . '::init' );

			add_action( 'astra_update_after', __CLASS__ . '::theme_init_after' );
		}


		/**
		 * Implement theme update logic.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static public function theme_init_after() {
			// If equals then return.
			if ( version_compare( ASTRA_THEME_VERSION, '1.0.22', '>=' ) ) {
				self::v_1_0_0_rc_7();
			}
		}

		/**
		 * Implement addon update logic.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static public function init() {
			do_action( 'astra_addon_update_before' );

			// Get auto saved version number.
			$saved_version = astra_get_option( 'astra-addon-auto-version', false );

			if ( ! $saved_version ) {

				// Get all customizer options.
				$customizer_options = get_option( ASTRA_THEME_SETTINGS );

				// Get all customizer options.
				/* Add Current version constant "ASTRA_EXT_VER" here after 1.0.0-rc.9 update */
				$version_array = array(
					'astra-addon-auto-version' => ASTRA_EXT_VER,
				);
				$saved_version = ASTRA_EXT_VER;

				// Merge customizer options with version.
				$astra_options = wp_parse_args( $version_array, $customizer_options );

				// Update auto saved version number.
				update_option( ASTRA_THEME_SETTINGS, $astra_options );
			}

			// If equals then return.
			if ( version_compare( $saved_version, ASTRA_EXT_VER, '=' ) ) {
				return;
			}

			// Update to older version than 1.0.0-beta.6 version.
			if ( version_compare( $saved_version, '1.0.0-beta.6', '<' ) ) {
				self::v_1_0_0_beta_6();
			}

			// Update to older version than 1.0.0-beta.7 version.
			if ( version_compare( $saved_version, '1.0.0-beta.7', '<' ) ) {
				self::v_1_0_0_beta_7();
			}

			// Update to older version than 1.0.0-rc.3 version.
			if ( version_compare( $saved_version, '1.0.0-rc.3', '<' ) ) {
				self::v_1_0_0_rc_3();
			}

			// Update to older version than 1.0.0-rc.6 version.
			if ( version_compare( $saved_version, '1.0.0-rc.6', '<' ) ) {
				self::v_1_0_0_rc_6();
			}

			// Update to older version than 1.0.0-rc.7 version.
			if ( version_compare( $saved_version, '1.0.0-rc.7', '<' ) && version_compare( ASTRA_THEME_VERSION, '1.0.22', '>=' ) ) {
					self::v_1_0_0_rc_7();
			}

			// Update to older version than 1.0.0-rc.9 version.
			if ( version_compare( $saved_version, '1.0.0-rc.9', '<' ) ) {
					self::v_1_0_0_rc_9();
			}

			// Footer Widget Spacing Top/Right/Bottom/Left updated for responsive devices.
			if ( version_compare( $saved_version, '1.2.0-beta.1', '<' ) ) {
					self::v_1_2_0_beta_1();
			}
			// Site Lauout Padded layout Top/Right/Bottom/Left updated for responsive devices.
			if ( version_compare( $saved_version, '1.2.0-beta.2', '<' ) ) {
					self::v_1_2_0_beta_2();
			}
			// Update to older version than 1.2.0-beta.4 version.
			if ( version_compare( $saved_version, '1.2.0-beta.4', '<' ) ) {
					self::v_1_2_0_beta_4();
			}

			// Refresh Astra Addon CSS and JS Files on update.
			Astra_Minify::refresh_assets();

			$astra_addon_version = ASTRA_EXT_VER;

			// Get all customizer options.
			$customizer_options = get_option( ASTRA_THEME_SETTINGS );

			// Get all customizer options.
			$version_array = array(
				'astra-addon-auto-version' => $astra_addon_version,
			);

			// Merge customizer options with version.
			$astra_options = wp_parse_args( $version_array, $customizer_options );

			// Update auto saved version number.
			update_option( ASTRA_THEME_SETTINGS, $astra_options );

			// Update variables.
			Astra_Theme_Options::refresh();

			do_action( 'astra_addon_update_after' );
		}

		/**
		 * Update options of older version than 1.0.0-beta.6.
		 *
		 * @since 1.0.0-beta.6
		 * @return void
		 */
		static public function v_1_0_0_beta_6() {

			$options = array(
				'footer-adv'              => 'layout-3',
				'footer-adv-area-padding' => array(
					'top'    => 50,
					'right'  => '',
					'bottom' => 50,
					'left'   => '',
				),
			);

			// Get all supported post Types. [excluding 'page', 'post'].
			$post_types = astra_get_supported_posts();
			foreach ( $post_types as $slug => $label ) {
				$options[ 'single-' . esc_attr( $slug ) . '-content-layout' ] = 'content-boxed-container';
			}

			// Get all supported post Types which HAVE TAXONOMIES. [excluding 'page', 'post'].
			$post_types_tax = astra_get_supported_posts( true );
			foreach ( $post_types_tax as $index => $slug ) {
				$options[ 'archive-' . esc_attr( $slug ) . '-content-layout' ] = 'content-boxed-container';
			}

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			foreach ( $options as $key => $value ) {
				if ( ! isset( $astra_options[ $key ] ) ) {
					$astra_options[ $key ] = $value;
				}
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update options of older version than 1.0.0-beta.7.
		 *
		 * @since 1.0.0-beta.7
		 * @return void
		 */
		static public function v_1_0_0_beta_7() {

			$options = array(
				'footer-adv' => 'disabled',
			);

			// Get all supported post Types. [excluding 'page', 'post'].
			$post_types = astra_get_supported_posts();
			foreach ( $post_types as $slug => $label ) {
				$options[ 'single-' . esc_attr( $slug ) . '-sidebar-layout' ] = 'right-sidebar';
			}

			// Get all supported post Types which HAVE TAXONOMIES. [excluding 'page', 'post'].
			$post_types_tax = astra_get_supported_posts( true );
			foreach ( $post_types_tax as $index => $slug ) {
				$options[ 'archive-' . esc_attr( $slug ) . '-sidebar-layout' ] = 'right-sidebar';
			}

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			foreach ( $options as $key => $value ) {
				if ( ! isset( $astra_options[ $key ] ) ) {
					$astra_options[ $key ] = $value;
				}
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update options of older version than 1.0.0-rc.3.
		 *
		 * @since 1.0.0-rc.3
		 * @return void
		 */
		static public function v_1_0_0_rc_3() {

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			if ( isset( $astra_options['sticky-header-mobile'] ) && 'enabled' == $astra_options['sticky-header-mobile'] ) {
				unset( $astra_options['sticky-header-mobile'] );
				$astra_options['sticky-header-on-devices'] = 'both';
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update options of older version than 1.0.0-rc.6.
		 *
		 * @since 1.0.0-rc.3
		 * @return void
		 */
		static public function v_1_0_0_rc_6() {

			// Get the site-wide option if we're in the network admin.
			if ( is_multisite() ) {
				$white_label = get_site_option( '_astra_ext_white_label' );
			} else {
				$white_label = get_option( '_astra_ext_white_label' );
			}

			// updated white label options.
			$updated_branding = array();
			if ( isset( $white_label['theme_name'] ) ) {
				$updated_branding['astra']['name'] = $white_label['theme_name'];
			}
			if ( isset( $white_label['theme_desc'] ) ) {
				$updated_branding['astra']['description'] = $white_label['theme_desc'];
			}
			if ( isset( $white_label['theme_author'] ) ) {
				$updated_branding['astra-agency']['author'] = $white_label['theme_author'];
			}
			if ( isset( $white_label['theme_author_url'] ) ) {
				$updated_branding['astra-agency']['author_url'] = $white_label['theme_author_url'];
			}
			if ( isset( $white_label['theme_screenshot'] ) ) {
				$updated_branding['astra']['screenshot'] = $white_label['theme_screenshot'];
			}
			if ( isset( $white_label['plugin_name'] ) ) {
				$updated_branding['astra-pro']['name'] = $white_label['plugin_name'];
			}
			if ( isset( $white_label['plugin_desc'] ) ) {
				$updated_branding['astra-pro']['description'] = $white_label['plugin_desc'];
			}

			if ( isset( $white_label['plugin_licence'] ) ) {
				$updated_branding['astra-agency']['licence'] = $white_label['plugin_licence'];
			}
			if ( isset( $white_label['hide_branding'] ) ) {
				$updated_branding['astra-agency']['hide_branding'] = $white_label['hide_branding'];
			}

			// Update the site-wide option since we're in the network admin.
			if ( is_multisite() ) {
				update_site_option( '_astra_ext_white_label', $updated_branding );
			} else {
				update_option( '_astra_ext_white_label', $updated_branding );
			}
		}

		/**
		 * Update options of older version than 1.0.0-rc.7.
		 *
		 * @since 1.0.0-rc.7
		 * @return void
		 */
		static public function v_1_0_0_rc_7() {

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			if ( ! empty( $astra_options['footer-bg-color'] ) && ! empty( $astra_options['footer-bg-img'] ) ) {

				$astra_options['footer-bg-color-opc'] = ! empty( $astra_options['footer-bg-color-opc'] ) ? $astra_options['footer-bg-color-opc'] : '0.8';

				$astra_options['footer-bg-color'] = astra_hex_to_rgba( $astra_options['footer-bg-color'], $astra_options['footer-bg-color-opc'] );
			}

			if ( ! empty( $astra_options['footer-adv-bg-color'] ) && ! empty( $astra_options['footer-adv-bg-img'] ) ) {

				$astra_options['footer-adv-bg-color-opac'] = ! empty( $astra_options['footer-adv-bg-color-opac'] ) ? $astra_options['footer-adv-bg-color-opac'] : '0.8';
				$astra_options['footer-adv-bg-color']      = astra_hex_to_rgba( $astra_options['footer-adv-bg-color'], $astra_options['footer-adv-bg-color-opac'] );
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update options of older version than 1.0.0-rc.9.
		 *
		 * @since 1.0.0-rc.9
		 * @return void
		 */
		static public function v_1_0_0_rc_9() {
			$query_args = array(
				'post_type'      => 'astra-advanced-hook',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			);

			$adv_hooks = new WP_Query( $query_args );
			$layouts   = $adv_hooks->posts;

			if ( is_array( $layouts ) && ! empty( $layouts ) ) {
				foreach ( $layouts as $key => $layout_id ) {

					$hook_layout = get_post_meta( $layout_id, 'ast-advanced-hook-layout', true );
					$hook_action = get_post_meta( $layout_id, 'ast-advanced-hook-action', true );

					if ( '' != $hook_action && '' == $hook_layout ) {

						update_post_meta( $layout_id, 'ast-advanced-hook-layout', 'hooks' );
					}
				}
			}

			wp_reset_postdata();
		}

		/**
		 * Update options of older version than 1.2.0-beta.1.
		 *
		 * Footer Widget Spacing Top/Right/Bottom/Left updated for responsive devices.
		 * Merge menu backward compatibility.
		 *
		 * @since 1.2.0-beta.1
		 */
		static public function v_1_2_0_beta_1() {

			$options = array(
				'footer-adv-area-padding' => array(
					'top'    => 70,
					'right'  => '',
					'bottom' => 70,
					'left'   => '',
				),
			);

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			if ( 0 < count( $astra_options ) ) {
				foreach ( $options as $key => $value ) {

					if ( array_key_exists( $key, $astra_options ) ) {

						$astra_options[ $key ] = array(
							'desktop'      => array(
								'top'    => $astra_options[ $key ]['top'],
								'right'  => $astra_options[ $key ]['right'],
								'bottom' => $astra_options[ $key ]['bottom'],
								'left'   => $astra_options[ $key ]['left'],
							),
							'tablet'       => array(
								'top'    => '',
								'right'  => '',
								'bottom' => '',
								'left'   => '',
							),
							'mobile'       => array(
								'top'    => '',
								'right'  => '',
								'bottom' => '',
								'left'   => '',
							),
							'desktop-unit' => 'px',
							'tablet-unit'  => 'px',
							'mobile-unit'  => 'px',
						);
					}
				}
			}

			// Above Header Merge menu backward compatibility.
			if ( ! isset( $astra_options['above-header-merge-menu'] ) ) {

				$astra_options['above-header-merge-menu'] = true;
			}
			// Above Header Merge menu backward compatibility.
			if ( ! isset( $astra_options['below-header-merge-menu'] ) ) {

				$astra_options['below-header-merge-menu'] = true;
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update options of older version than 1.2.0-beta.2.
		 *
		 * Padded Layout Spacing Top/Right/Bottom/Left updated for responsive devices.
		 *
		 * @since 1.2.0-beta.2
		 */
		static public function v_1_2_0_beta_2() {

			$options = array(
				'site-layout-padded-pad' => array(
					'top'    => 25,
					'right'  => 50,
					'bottom' => 25,
					'left'   => 50,
				),
			);

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );

			if ( 0 < count( $astra_options ) ) {
				foreach ( $options as $key => $value ) {

					if ( array_key_exists( $key, $astra_options ) ) {

						$astra_options[ $key ] = array(
							'desktop'      => array(
								'top'    => $astra_options[ $key ]['top'],
								'right'  => $astra_options[ $key ]['right'],
								'bottom' => $astra_options[ $key ]['bottom'],
								'left'   => $astra_options[ $key ]['left'],
							),
							'tablet'       => array(
								'top'    => '',
								'right'  => '',
								'bottom' => '',
								'left'   => '',
							),
							'mobile'       => array(
								'top'    => '',
								'right'  => '',
								'bottom' => '',
								'left'   => '',
							),
							'desktop-unit' => 'px',
							'tablet-unit'  => 'px',
							'mobile-unit'  => 'px',
						);
					}
				}
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}

		/**
		 * Update Sticky Header & Transparent Header Logo width options of older version than 1.2.0-beta.4.
		 *
		 * Responsive Sticky & Transparent Header Logo Width
		 *
		 * @since 1.2.0-beta.4
		 */
		static public function v_1_2_0_beta_4() {

			$astra_options = get_option( ASTRA_THEME_SETTINGS, array() );
			// Trasnparent Header value to reponsive width option.
			if ( isset( $astra_options['transparent-header-logo-width'] ) && ! is_array( $astra_options['transparent-header-logo-width'] ) ) {
				$astra_options['transparent-header-logo-width'] = array(
					'desktop' => $astra_options['transparent-header-logo-width'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			// Trasnparent Header value to reponsive width option.
			if ( isset( $astra_options['sticky-header-logo-width'] ) && ! is_array( $astra_options['sticky-header-logo-width'] ) ) {
				$astra_options['sticky-header-logo-width'] = array(
					'desktop' => $astra_options['sticky-header-logo-width'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}

			update_option( ASTRA_THEME_SETTINGS, $astra_options );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Addon_Update::get_instance();
