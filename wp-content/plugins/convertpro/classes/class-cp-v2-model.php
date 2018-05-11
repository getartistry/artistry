<?php
/**
 * Model Class.
 *
 * @package ConvertPro
 */

if ( ! class_exists( 'Cp_V2_Model' ) ) {

	/**
	 * Class Cp_V2_Model.
	 */
	class Cp_V2_Model {

		/**
		 * The unique instance of the plugin.
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Is popup live.
		 *
		 * @var is_popup_live
		 */
		private static $is_popup_live = null;

		/**
		 * The mobile include option array.
		 *
		 * @var mobile_include_opt
		 */
		public static $mobile_include_opt = array(
			/* Form Fields */
			'form_field_font_size',
			'form_field_text_align',
			'form_field_letter_spacing',
			'form_field_padding',

			/* Panel */
			'panel_height',
			'panel_width',
			'background_type',
			'panel_lighter_color',
			'gradient_lighter_location',
			'panel_darker_color',
			'gradient_darker_location',
			'panel_gradient_type',
			'radial_panel_gradient_direction',
			'gradient_angle',
			'panel_background_color',
			'panel_bg_image',
			'panel_bg_image_sizes',
			'opt_bg',
			'panel_img_overlay_color',
			'font_size',
			'letter_spacing',
			'line_height',
			'btn_text_align',
			'btn_grad_letter_spacing',
			'btn_grad_text_align',
			'btn_title_size',
			'btn_load_letter_spacing',
			'btn_load_text_align',
			'btn_prog_letter_spacing',
			'btn_prog_text_align',
			'close_letter_spacing',
			'close_line_height',
			'close_title_size',
			'close_padding',

			/* Count down */
			'countdown_border_style',
			'countdown_border_color',
			'countdown_border_width',
			'countdown_border_radius',
			'countdown_field_padding',
			'text_space',
			'number_font_size',
			'text_font_size',


			/* Common */
			'height',
			'width',
			'position',
			'rotate_field',
			/* Toggle */
			'toggle_font_size',
			'toggle_text_color',
			'toggle_bg_color',
			'toggle_width',
			'toggle_height',
			/* Infobar Toggle */
			'toggle_infobar_font_size',
			'toggle_infobar_text_color',
			'toggle_infobar_bg_color',
			'toggle_infobar_width',
			'toggle_infobar_height',
		);

		/**
		 * The step dependent options.
		 *
		 * @var step_dependent_options
		 */
		public static $step_dependent_options = array(
			'panel_height',
			'panel_width',
			'background_type',
			'panel_lighter_color',
			'gradient_lighter_location',
			'panel_darker_color',
			'gradient_darker_location',
			'panel_gradient_type',
			'radial_panel_gradient_direction',
			'gradient_angle',
			'panel_background_color',
			'panel_bg_image',
			'opt_bg',
			'panel_img_overlay_color',
			'inherit_bg_prop',
			'panel_bg_image_sizes',
		);

		/**
		 * Gets an instance of our plugin.
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		function __construct() {

			add_action( 'init', array( $this, 'add_capabilities' ), 1 );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_scripts' ), 10 );
			add_filter( 'the_content', array( $this, 'add_content' ) );

			add_action( 'wp_footer', array( $this, 'load_popup_globally' ) );
			add_action( 'widgets_init', 'load_convertplug_v2_widget' );

			/* Css Asynchronous Loading */
			add_action( 'wp_head', array( $this, 'load_css_async' ), 7 );
			add_filter( 'style_loader_tag', array( $this, 'link_to_load_css_script' ), 999, 3 );
			add_filter( 'script_loader_tag', array( $this, 'link_async_js' ), 999, 3 );
		}

		/**
		 * Function Name: is_load_popup_data.
		 * Function Description: is_load_popup_data.
		 */
		function is_load_popup_data() {

			if ( null === self::$is_popup_live ) {

				if ( function_exists( 'cp_get_live_popups' ) ) {
					$live_popups = cp_get_live_popups();

					if ( empty( $live_popups ) ) {
						self::$is_popup_live = false;
					} else {
						self::$is_popup_live = true;
					}
				}
			}

			return apply_filters( 'cppro_load_popup_data', self::$is_popup_live );
		}

		/**
		 * Function Name: load_css_async.
		 * Function Description: load_css_async.
		 */
		function load_css_async() {

			if ( false == $this->is_load_popup_data() ) {
				return;
			}

			$scripts  = '<script>function cpLoadCSS(e,t,n){"use strict";var i=window.document.createElement("link"),o=t||window.document.getElementsByTagName("script")[0];return i.rel="stylesheet",i.href=e,i.media="only x",o.parentNode.insertBefore(i,o),setTimeout(function(){i.media=n||"all"}),i}</script>';
			$scripts .= '<style>.cp-popup-container .cpro-overlay,.cp-popup-container .cp-popup-wrapper{opacity:0;visibility:hidden;display:none}</style>';

			echo $scripts;
		}

		/**
		 * Function Name: link_async_js.
		 * Function Description: link_async_js.
		 *
		 * @param string $tag tag.
		 * @param string $handle handle.
		 * @param string $src src.
		 */
		function link_async_js( $tag, $handle, $src ) {

			if ( false == $this->is_load_popup_data() ) {
				return $tag;
			}

			// The handles of the enqueued scripts we want to defer.
			$defer_scripts = array(
				'cp-ideal-timer-script',
				'cp-popup-script',
			);

			if ( in_array( $handle, $defer_scripts ) ) {
				$tag = str_replace( 'src', 'defer="defer" src', $tag );
			}

			return $tag;
		}

		/**
		 * Function Name: link_to_load_css_script.
		 * Function Description: link_to_load_css_script.
		 *
		 * @param string $html html.
		 * @param string $handle handle.
		 * @param string $href href.
		 */
		function link_to_load_css_script( $html, $handle, $href ) {

			if ( false == $this->is_load_popup_data() ) {
				return $html;
			}

			$load_async = array(
				'cp-popup-style',
			);

			if ( is_admin() ) {
				return $html;
			}

			if ( in_array( $handle, $load_async ) ) {
				$cp_script = "<script>document.addEventListener('DOMContentLoaded', function(event) {  if( typeof cpLoadCSS !== 'undefined' ) { cpLoadCSS('" . $href . "', 0, 'all'); } }); </script>\n";
				$html      = $cp_script;
			}

			return $html;
		}

		/**
		 * Add ConvertPro access capabilities to user roles
		 *
		 * @since 0.0.1
		 */
		function add_capabilities() {

			global $wp_roles;

			if ( ! class_exists( 'WP_Roles' ) ) {
				return;
			}

			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}

			$wp_roles_data = $wp_roles->get_names();
			$roles         = false;

			$roles = get_option( 'cp_access_role' );

			if ( ! $roles ) {
				$roles = array();
			}

			// Give access to administrator.
			$roles[] = 'administrator';

			$capabilities = self::get_cpro_capabilities();

			if ( ! empty( $wp_roles_data ) ) {
				foreach ( $wp_roles_data as $key => $value ) {
					$role = get_role( $key );

					if ( in_array( $key, $roles ) ) {

						foreach ( $capabilities as $cap_group ) {
							foreach ( $cap_group as $cap ) {

								// add capabilities to role.
								$role->add_cap( $cap );
							}
						}
					} else {
						foreach ( $capabilities as $cap_group ) {
							foreach ( $cap_group as $cap ) {
								// remove capabilities to role.
								$role->remove_cap( $cap );
							}
						}
					}
				}
			}
		}

		/**
		 * Get capabilities for Convert Pro - these are assigned to the user roles to whom Convert Pro access has been granted
		 *
		 * @return array
		 */
		private static function get_cpro_capabilities() {
			$capabilities = array();

			$capabilities['core'] = array(
				'access_cp_pro',
			);

			$capability_types = array( 'cp_popup' );

			foreach ( $capability_types as $capability_type ) {

				$capabilities[ $capability_type ] = array(
					// Post type.
					"edit_{$capability_type}",
					"read_{$capability_type}",
					"delete_{$capability_type}",
					"edit_{$capability_type}s",
					"edit_others_{$capability_type}s",
					"publish_{$capability_type}s",
					"read_private_{$capability_type}s",
					"delete_{$capability_type}s",
					"delete_private_{$capability_type}s",
					"delete_published_{$capability_type}s",
					"delete_others_{$capability_type}s",
					"edit_private_{$capability_type}s",
					"edit_published_{$capability_type}s",

					// Terms.
					"manage_{$capability_type}_terms",
					"edit_{$capability_type}_terms",
					"delete_{$capability_type}_terms",
					"assign_{$capability_type}_terms",
				);
			}

			return $capabilities;
		}

		/**
		 * Load plugin text domain.
		 *
		 * @since 1.0.0
		 */
		function load_textdomain() {

			// Traditional WordPress plugin locale filter.
			$locale = apply_filters( 'plugin_locale', get_locale(), 'convertpro' );

			// Setup paths to current locale file.
			$mofile_global = trailingslashit( WP_LANG_DIR ) . 'plugins/convertpro/' . $locale . '.mo';
			$mofile_local  = trailingslashit( CP_V2_BASE_DIR ) . 'languages/' . $locale . '.mo';

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/plugins/convertpro/ folder.
				return load_textdomain( 'convertpro', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/convertpro/languages/ folder.
				return load_textdomain( 'convertpro', $mofile_local );
			}

			// Nothing found.
			return false;
		}

		/**
		 * Enqueue scripts and styles on frontend
		 *
		 * @since 1.0
		 */
		function enqueue_front_scripts() {

			if ( false == $this->is_load_popup_data() ) {
				return;
			}

			$dev_mode = get_option( 'cp_dev_mode' );

			wp_register_script( 'cp-ideal-timer-script', CP_V2_BASE_URL . 'assets/modules/js/idle-timer.min.js', array( 'jquery' ), null, true );

			if ( '1' == $dev_mode ) {

				// Register styles.
				wp_enqueue_style( 'cp-popup-style', CP_V2_BASE_URL . 'assets/modules/css/cp-popup.css' );
				wp_enqueue_style( 'cp-animate-style', CP_V2_BASE_URL . 'assets/modules/css/animate.css' );

				// Register scripts.
				wp_register_script( 'cp-cookie-script', CP_V2_BASE_URL . 'assets/admin/js/jquery.cookies.js', array( 'jquery' ), null, true );

				wp_register_script( 'cp-popup-script', CP_V2_BASE_URL . 'assets/modules/js/cp-popup.js', array( 'jquery' ), null, true );

				wp_register_script( 'cp-video-api', CP_V2_BASE_URL . 'assets/modules/js/cp-video-api.js', array( 'jquery' ), null, true );
				// Common JS.
				wp_register_script( 'cp-submit-actions-script', CP_V2_BASE_URL . 'assets/modules/js/cp-submit-actions.js', array( 'jquery' ), null, true );
			} else {
				wp_register_script( 'cp-popup-script', CP_V2_BASE_URL . 'assets/modules/js/cp-popup.min.js', array( 'jquery' ), null, true );
				wp_enqueue_style( 'cp-popup-style', CP_V2_BASE_URL . 'assets/modules/css/cp-popup.min.css' );
			}

			$image_on_ready = esc_attr( get_option( 'cpro_image_on_ready' ) );

			$params = array(
				'url'                     => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'              => wp_create_nonce( 'cp_add_subscriber_nonce' ),
				'assets_url'              => CP_V2_BASE_URL . 'assets/',
				'not_connected_to_mailer' => __( 'This form is not connected with any mailer service! Please contact web administrator.', 'convertpro' ),
				'timer_labels'            => __( 'Years', 'convertpro' ) . ',' . __( 'Months', 'convertpro' ) . ',' . __( 'Weeks', 'convertpro' ) . ',' . __( 'Days', 'convertpro' ) . ',' . __( 'Hours', 'convertpro' ) . ',' . __( 'Minutes', 'convertpro' ) . ',' . __( 'Seconds', 'convertpro' ),

				'timer_labels_singular'   => __( 'Year', 'convertpro' ) . ',' . __( 'Month', 'convertpro' ) . ',' . __( 'Week', 'convertpro' ) . ',' . __( 'Day', 'convertpro' ) . ',' . __( 'Hour', 'convertpro' ) . ',' . __( 'Minute', 'convertpro' ) . ',' . __( 'Second', 'convertpro' ),
				'image_on_ready'          => $image_on_ready,
			);

			$inactivity_val = esc_attr( get_option( 'cp_user_inactivity' ) );
			if ( ! $inactivity_val ) {
				$inactivity_val = 60;
			}

			$cp_pro_params = array(
				'inactive_time' => $inactivity_val,
			);

			wp_localize_script( 'cp-popup-script', 'cp_ajax', $params );
			wp_localize_script( 'cp-popup-script', 'cp_pro', $cp_pro_params );
		}

		/**
		 * Add a class at the end of the post for after content trigger
		 *
		 * @param string $content content.
		 * @since 1.0.3
		 */
		function add_content( $content ) {
			if ( ( is_single() || is_page() ) && ! is_front_page() ) {
				$content_str_array = cp_v2_display_style_inline();

				$enable_after_post = apply_filters( 'cpro_enable_after_post', true );

				if ( $enable_after_post ) {
					$content .= '<span class="cp-load-after-post"></span>';
				}

				$content  = $content_str_array[0] . $content;
				$content .= $content_str_array[1];
			}
			return $content;
		}

		/**
		 * Load popup globally
		 *
		 * @since 1.0.3
		 */
		function load_popup_globally() {

			$load = true;

			// Do not load popups if current page is fl builder edit page.
			if ( current_user_can( 'manage_options' ) ) {
				if ( class_exists( 'FLBuilder' ) ) {
					if ( FLBuilderModel::is_builder_active() ) {
						$load = false;
					}
				}
			}

			// Do not load popups if current page is Visual Composer edit page.
			if ( current_user_can( 'manage_options' ) ) {
				if ( class_exists( 'WPBakeryShortCode' ) ) {
					if ( isset( $_GET['vc_action'] ) && 'vc_inline' == $_GET['vc_action'] ) {
						$load = false;
					}
				}
			}

			$load = apply_filters( 'before_cp_load_popup', $load );

			if ( ! is_customize_preview() && $load ) {
				// Load popup only when customizer is off.
				if ( function_exists( 'cp_load_popup_content' ) ) {
					cp_load_popup_content();
				}
			}
		}

		/**
		 * Get global fonts
		 *
		 * @since 1.0.3
		 * @return array
		 */
		static function get_cp_global_fonts() {

			/* Add GlObal Font */
			$cp_global_font = get_option( 'cp_global_font' );

			if ( ! $cp_global_font ) {
				$cp_global_font = 'Verdana:Normal';
			}

			$font_values = explode( ':', $cp_global_font );

			$font_data['family'] = $font_values[0];
			$font_data['weight'] = isset( $font_values[1] ) ? $font_values[1] : '';

			return $font_data;
		}
	}

	$cp_v2_model = Cp_V2_Model::get_instance();
}

