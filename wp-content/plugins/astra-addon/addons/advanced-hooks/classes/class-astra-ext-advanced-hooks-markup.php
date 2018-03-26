<?php
/**
 * Advanced Hooks Markup
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Astra_Ext_Advanced_Hooks_Markup' ) ) {

	/**
	 * Advanced Hooks Markup Initial Setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Advanced_Hooks_Markup {


		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {
			add_action( 'wp', array( $this, 'load_advanced_hooks_template' ), 1 );
			add_action( 'wp', array( $this, 'load_markup' ), 1 );
			add_action( 'wp', array( $this, 'remove_navigation_markup' ), 1 );
			add_action( 'template_redirect', array( $this, 'advanced_hook_template_frontend' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ), 9 );
			add_action( 'astra_advanced_hook_template', array( $this, 'template_empty_content' ) );

			add_filter( 'wp_enqueue_scripts', array( $this, 'advanced_hook_scripts' ) );
			add_filter( 'the_content', array( $this, 'advanced_hook_content_markup' ) );
			add_filter( 'single_template', array( $this, 'get_custom_post_type_template' ) );

			/* Add Body Classes */
			add_filter( 'body_class', array( $this, 'body_classes' ), 10, 1 );

			add_action( 'astra_get_css_files', array( $this, 'add_front_styles' ) );
			add_action( 'astra_get_js_files', array( $this, 'add_scripts' ) );
			add_filter( 'astra_addon_js_localize', array( $this, 'localize_variables' ) );
			add_filter( 'astra_dynamic_css', array( $this, 'astra_ext_advanced_hooks_dynamic_css' ) );

		}

		/**
		 * Empty Content area for Advanced hooks.
		 *
		 * @return void
		 */
		function template_empty_content() {
			$post_id = get_the_id();
			$layout  = get_post_meta( $post_id, 'ast-advanced-hook-layout', true );
			if ( empty( $layout ) ) {
				the_content();
			}
		}

		/**
		 * Custom template for Advanced Hook post type.
		 *
		 * @param  string $template Single Post template path.
		 * @return string
		 */
		function get_custom_post_type_template( $template ) {
			global $post;

			$post_id = get_the_id();
			$action  = get_post_meta( $post_id, 'ast-advanced-hook-action', true );
			$layout  = get_post_meta( $post_id, 'ast-advanced-hook-layout', true );

			// Dispaly single post content as normal post content only for woocommerce actions.
			$woocommerce_hooks     = array( 'woo-global', 'woo-shop', 'woo-product', 'woo-cart', 'woo-checkout', 'woo-distraction-checkout', 'woo-account' );
			$woocommerce_activated = false;

			if ( 'astra-advanced-hook' == $post->post_type ) {
				if ( 'hooks' === $layout ) {
					foreach ( Astra_Ext_Advanced_Hooks_Meta::$astra_hooks as $key => $value ) {
						if ( in_array( $key, $woocommerce_hooks ) && isset( Astra_Ext_Advanced_Hooks_Meta::$astra_hooks[ $key ]['hooks'][ $action ] ) ) {
							$woocommerce_activated = true;
						}
					}
				}

				// Exclude rendeting the content in correct action for WooCommerce and 404 Layout.
				if ( ( 'hooks' === $layout && false == $woocommerce_activated ) ||
					'404-page' !== $layout ) {
					$template = ASTRA_EXT_ADVANCED_HOOKS_DIR . '/template/template.php';
				}
			}
			return $template;
		}


		/**
		 * Load Advanced hook markup.
		 *
		 * @return void
		 */
		public function load_advanced_hooks_template() {

			if ( is_singular( 'astra-advanced-hook' ) ) {
				$post_id  = get_the_id();
				$action   = get_post_meta( $post_id, 'ast-advanced-hook-action', true );
				$layout   = get_post_meta( $post_id, 'ast-advanced-hook-layout', true );
				$priority = get_post_meta( $post_id, 'ast-advanced-hook-priority', true );

				// Exclude default p tag wrapper from the content if selected hook is from below list.
				$exclude_wrapper_hooks = array( 'astra_html_before', 'astra_body_top', 'astra_head_top', 'astra_head_bottom', 'wp_head', 'astra_body_bottom', 'wp_footer' );
				$with_wrapper          = in_array( $action, $exclude_wrapper_hooks );
				if ( $with_wrapper ) {
					remove_filter( 'the_content', 'wpautop' );
				}

				$astra_hooks = Astra_Ext_Advanced_Hooks_Meta::$astra_hooks;
				if ( isset( $astra_hooks['content']['hooks'][ $action ] ) || isset( $astra_hooks['comment']['hooks'][ $action ] ) || isset( $astra_hooks['sidebar']['hooks'][ $action ] ) ) {

					$action = 'astra_advanced_hook_template';
				}

				$vc_activated = self::is_vc_activated( $post_id );
				if ( 'astra_advanced_hook_template' == $action || $vc_activated ) {
					remove_action( 'astra_advanced_hook_template', array( $this, 'template_empty_content' ) );
					add_action( 'astra_advanced_hook_template', 'the_content' );
				} elseif ( 'header' == $layout ) {
					// remove default site's header.
					remove_action( 'astra_header', 'astra_header_markup' );
					// remove default site's fixed header if sticky header is activated.
					add_filter( 'astra_fixed_header_markup_enabled', '__return_false' );

					$action = 'astra_custom_header';
					// if astra_custom_header not exist then call astra_header.
					if ( ! has_action( 'astra_custom_header' ) ) {
						$action = 'astra_header';
					}

					add_action(
						$action, function() use ( $post_id ) {
							echo '<header class="ast-custom-header" itemscope="itemscope" itemtype="http://schema.org/WPHeader">';

								Astra_Ext_Advanced_Hooks_Markup::get_instance()->get_the_hook_content();

							echo '</header>';
						}, $priority
					);
				} elseif ( 'footer' == $layout ) {
					// remove default site's footer.
					remove_action( 'astra_footer', 'astra_footer_markup' );

					$action = 'astra_custom_footer';
					// if astra_custom_footer not exist then call astra_footer.
					if ( ! has_action( 'astra_custom_footer' ) ) {
						$action = 'astra_footer';
					}

					// Add Action for custom header advanced-hooks.
					add_action(
						$action, function() use ( $post_id ) {
							echo '<footer class="ast-custom-footer" itemscope="itemscope" itemtype="http://schema.org/WPFooter">';

								Astra_Ext_Advanced_Hooks_Markup::get_instance()->get_the_hook_content();

							echo '</footer>';
						}, $priority
					);
				} else {
					add_action(
						$action, function() use ( $post_id ) {

							Astra_Ext_Advanced_Hooks_Markup::get_instance()->get_the_hook_content();

						}, $priority
					);
				}
			}
		}

		/**
		 * Get the content of the hook
		 */
		function get_the_hook_content() {
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
		}

		/**
		 * Filter content markup for Advanced Hook post type.
		 *
		 * @param  html $content the_content markup.
		 * @return html
		 */
		public function advanced_hook_content_markup( $content ) {
			if ( is_singular( 'astra-advanced-hook' ) ) {
				$post_id     = get_the_id();
				$php_snippet = $this->get_php_snippet( $post_id );
				if ( $php_snippet ) {
					$content = $php_snippet;
				}

				$action = get_post_meta( $post_id, 'ast-advanced-hook-action', true );
				// Exclude div wrapper if selected hook is from below list.
				$exclude_wrapper_hooks = array( 'astra_html_before', 'astra_body_top', 'astra_head_top', 'astra_head_bottom', 'wp_head', 'astra_body_bottom', 'wp_footer' );
				$with_wrapper          = ! in_array( $action, $exclude_wrapper_hooks );
				if ( $with_wrapper ) {
					$content = '<div class="astra-advanced-hook-' . $post_id . '">' . $content . '</div>';
				}
			}
			return $content;
		}

		/**
		 * Get PHP snippet if enabled.
		 *
		 * @param  int $post_id Post Id.
		 * @return boolean|html
		 */
		public function get_php_snippet( $post_id ) {
			$php_enabled = get_post_meta( $post_id, 'ast-advanced-hook-with-php', true );
			if ( 'enabled' == $php_enabled ) {
				$code = get_post_meta( $post_id, 'ast-advanced-hook-php-code', true );
				ob_start();
				// @codingStandardsIgnoreStart
				eval( '?>' . $code . '<?php ' );
				// @codingStandardsIgnoreEnd
				return ob_get_clean();
			}
			return false;
		}

		/**
		 * Add style for Advanced Hook post type.
		 *
		 * @return void
		 */
		public function advanced_hook_scripts() {
			if ( is_singular( 'astra-advanced-hook' ) ) {
				$post_id = get_the_id();
				$styles  = '';

				$padding        = get_post_meta( $post_id, 'ast-advanced-hook-padding', true );
				$padding_top    = isset( $padding['top'] ) ? $padding['top'] : '';
				$padding_top    = is_numeric( $padding_top ) ? $padding_top . 'px' : $padding_top;
				$padding_bottom = isset( $padding['bottom'] ) ? $padding['bottom'] : '';
				$padding_bottom = is_numeric( $padding_bottom ) ? $padding_bottom . 'px' : $padding_bottom;

				$styles .= ' .astra-advanced-hook-' . $post_id . ' { ';
				if ( ! empty( $padding_top ) ) {
					$styles .= 'padding-top: ' . $padding_top . ';';
				}
				if ( ! empty( $padding_bottom ) ) {
					$styles .= 'padding-bottom: ' . $padding_bottom . ';';
				}
				$styles .= '}';
				wp_add_inline_style( 'astra-addon-css', $styles );
			}
		}

		/**
		 * Remove Navigation Links.
		 */
		public function remove_navigation_markup() {
			$post_type = get_post_type();
			if ( 'astra-advanced-hook' == $post_type ) {
				remove_action( 'astra_entry_after', 'astra_single_post_navigation_markup' );
			}
		}

		/**
		 * Add Styles
		 */
		public function add_styles() {

			$option = array(
				'location'  => 'ast-advanced-hook-location',
				'exclusion' => 'ast-advanced-hook-exclusion',
				'users'     => 'ast-advanced-hook-users',
			);

			$result = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions( 'astra-advanced-hook', $option );
			foreach ( $result as $post_id => $post_data ) {

				// Check if current layout is built using the thrive architect.
				if ( self::is_tve_activated( $post_id ) && ! is_editor_page() ) {

					if ( tve_get_post_meta( $post_id, 'thrive_icon_pack' ) && ! wp_style_is( 'thrive_icon_pack', 'enqueued' ) ) {
						TCB_Icon_Manager::enqueue_icon_pack();
					}

					tve_enqueue_extra_resources( $post_id );
					tve_enqueue_style_family( $post_id );
					tve_enqueue_custom_fonts( $post_id, true );
					tve_load_custom_css( $post_id );

					add_filter( 'tcb_enqueue_resources', '__return_true' );
					tve_frontend_enqueue_scripts();
					remove_filter( 'tcb_enqueue_resources', '__return_true' );
				}

				if ( self::is_elementor_activated( $post_id ) ) {
					if ( class_exists( '\Elementor\Plugin' ) ) {
						$elementor = \Elementor\Plugin::instance();
						$elementor->frontend->enqueue_styles();
					}
					if ( class_exists( '\ElementorPro\Plugin' ) ) {
						$elementor = \ElementorPro\Plugin::instance();
						$elementor->enqueue_styles();
					}
					if ( '' !== $post_id && class_exists( '\Elementor\Post_CSS_File' ) ) {
						$css_file = new \Elementor\Post_CSS_File( $post_id );
						$css_file->enqueue();
					}
				}
			}
		}


		/**
		 * Don't display the elementor header footer templates on the frontend for non edit_posts capable users.
		 *
		 * @since  1.0.0
		 */
		public function advanced_hook_template_frontend() {
			if ( is_singular( 'astra-advanced-hook' ) && ! current_user_can( 'edit_posts' ) ) {
				wp_redirect( site_url(), 301 );
				die;
			}
		}

		/**
		 * Advanced Hooks markup loader
		 *
		 * Loads appropriate template file based on the style option selected in options panel.
		 *
		 * @since 1.0.0
		 */
		public function load_markup() {

			$option = array(
				'location'  => 'ast-advanced-hook-location',
				'exclusion' => 'ast-advanced-hook-exclusion',
				'users'     => 'ast-advanced-hook-users',
			);

			$result             = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions( 'astra-advanced-hook', $option );
			$header_counter     = 0;
			$footer_counter     = 0;
			$layout_404_counter = 0;
			foreach ( $result as $post_id => $post_data ) {
				$post_type = get_post_type();

				if ( 'astra-advanced-hook' != $post_type ) {
					$action   = get_post_meta( $post_id, 'ast-advanced-hook-action', true );
					$layout   = get_post_meta( $post_id, 'ast-advanced-hook-layout', false );
					$priority = get_post_meta( $post_id, 'ast-advanced-hook-priority', true );
					add_action(
						'wp_enqueue_scripts', function() use ( $post_id ) {

							$styles = '';

							// Add VC style if it is activated.
							$wpb_custom_css = get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true );
							$styles        .= $wpb_custom_css;

							$padding        = get_post_meta( $post_id, 'ast-advanced-hook-padding', true );
							$padding_top    = isset( $padding['top'] ) ? $padding['top'] : '';
							$padding_top    = is_numeric( $padding_top ) ? $padding_top . 'px' : $padding_top;
							$padding_bottom = isset( $padding['bottom'] ) ? $padding['bottom'] : '';
							$padding_bottom = is_numeric( $padding_bottom ) ? $padding_bottom . 'px' : $padding_bottom;

							$styles .= ' .astra-advanced-hook-' . $post_id . ' { ';
							if ( ! empty( $padding_top ) ) {
								$styles .= 'padding-top: ' . $padding_top . ';';
							}
							if ( ! empty( $padding_bottom ) ) {
								$styles .= 'padding-bottom: ' . $padding_bottom . ';';
							}
							$styles .= '}';
							wp_add_inline_style( 'astra-addon-css', $styles );
						}
					);

					if ( isset( $layout[0] ) && '404-page' == $layout[0] && 0 == $layout_404_counter ) {

						remove_action( 'astra_entry_content_404_page', 'astra_entry_content_404_page_template' );
						add_action( 'astra_get_content_layout', 'astra_return_content_layout_page_builder' );
						add_action( 'astra_page_layout', 'astra_return_page_layout_no_sidebar' );

						$layout_404_settings = get_post_meta( $post_id, 'ast-404-page', true );
						if ( isset( $layout_404_settings['disable_header'] ) && 'enabled' == $layout_404_settings['disable_header'] ) {
							remove_action( 'astra_header', 'astra_header_markup' );
						}

						if ( isset( $layout_404_settings['disable_footer'] ) && 'enabled' == $layout_404_settings['disable_footer'] ) {
							remove_action( 'astra_footer', 'astra_footer_markup' );
						}

						add_action(
							'astra_entry_content_404_page', function() use ( $post_id ) {
									Astra_Ext_Advanced_Hooks_Markup::get_instance()->get_action_content( $post_id );
							}, $priority
						);

						$layout_404_counter ++;
					} elseif ( isset( $layout[0] ) && 'header' == $layout[0] && 0 == $header_counter ) {
						// remove default site's header.
						remove_action( 'astra_header', 'astra_header_markup' );
						// remove default site's fixed header if sticky header is activated.
						add_filter( 'astra_fixed_header_markup_enabled', '__return_false' );

						$action = 'astra_custom_header';
						// if astra_custom_header not exist then call astra_header.
						if ( ! has_action( 'astra_custom_header' ) ) {
							$action = 'astra_header';
						}
						add_action(
							$action, function() use ( $post_id ) {
								echo '<header class="ast-custom-header" itemscope="itemscope" itemtype="http://schema.org/WPHeader">';
									Astra_Ext_Advanced_Hooks_Markup::get_instance()->get_action_content( $post_id );
								echo '</header>';
							}, $priority
						);
						$header_counter++;
					} elseif ( isset( $layout[0] ) && 'footer' == $layout[0] && 0 == $footer_counter ) {
						// remove default site's footer.
						remove_action( 'astra_footer', 'astra_footer_markup' );

						$action = 'astra_custom_footer';
						// if astra_custom_footer not exist then call astra_footer.
						if ( ! has_action( 'astra_custom_footer' ) ) {
							$action = 'astra_footer';
						}

						// Add Action for custom header advanced-hooks.
						add_action(
							$action, function() use ( $post_id ) {
								echo '<footer class="ast-custom-footer" itemscope="itemscope" itemtype="http://schema.org/WPFooter">';

								Astra_Ext_Advanced_Hooks_Markup::get_instance()->get_action_content( $post_id );

								echo '</footer>';
							}, $priority
						);
						$footer_counter++;
					}

					if ( isset( $layout[0] ) && 'header' != $layout[0] && 'footer' != $layout[0] ) {
						// Add Action for advanced-hooks.
						add_action(
							$action, function() use ( $post_id ) {

								Astra_Ext_Advanced_Hooks_Markup::get_instance()->get_action_content( $post_id );

							}, $priority
						);
					}
				}
			}
		}

		/**
		 * Advanced Hooks get content
		 *
		 * Loads content
		 *
		 * @since 1.0.0
		 * @param int $post_id post id.
		 */
		public function get_action_content( $post_id ) {

			$action = get_post_meta( $post_id, 'ast-advanced-hook-action', true );
			// Exclude div wrapper if selected hook is from below list.
			$exclude_wrapper_hooks = array( 'astra_html_before', 'astra_body_top', 'astra_head_top', 'astra_head_bottom', 'wp_head', 'astra_body_bottom', 'wp_footer' );
			$with_wrapper          = ! in_array( $action, $exclude_wrapper_hooks );
			if ( $with_wrapper ) {
				echo '<div class="astra-advanced-hook-' . $post_id . '">';
			}

			$php_snippet = $this->get_php_snippet( $post_id );
			if ( $php_snippet ) {
				echo $php_snippet;

			} else {

				$current_post = get_post( $post_id, OBJECT );

				if ( class_exists( 'FLBuilderModel' ) ) {
					$do_render  = apply_filters( 'fl_builder_do_render_content', true, FLBuilderModel::get_post_id() );
					$fl_enabled = get_post_meta( $post_id, '_fl_builder_enabled', true );
					if ( $do_render && $fl_enabled ) {

						if ( is_callable( 'FLBuilderShortcodes::insert_layout' ) ) {
							echo FLBuilderShortcodes::insert_layout(
								array( // WPCS: XSS OK.
									'id' => $post_id,
								)
							);
						}

						if ( $with_wrapper ) {
							echo '</div>';
						}

						return;
					}
				}
				if ( self::is_elementor_activated( $post_id ) ) {
					// set post to glabal post.
					$elementor_instance = Elementor\Plugin::instance();
					echo $elementor_instance->frontend->get_builder_content_for_display( $post_id );
					if ( $with_wrapper ) {
						echo '</div>';
					}
					return;
				}

				if ( self::is_vc_activated( $post_id ) ) {
					echo do_shortcode( $current_post->post_content );
					if ( $with_wrapper ) {
						echo '</div>';
					}
					return;
				}

				// Add custom support for the Thrive Architect.
				if ( self::is_tve_activated( $post_id ) ) {
					echo apply_filters( 'the_content', $current_post->post_content );
					if ( $with_wrapper ) {
						echo '</div>';
					}
					return;
				}

				ob_start();
				echo do_shortcode( $current_post->post_content );
				echo ob_get_clean();
			}
			if ( $with_wrapper ) {
				echo '</div>';
			}
		}

		/**
		 * Check if Thrive Architect is enabled for the post.
		 *
		 * @since  1.1.0
		 *
		 * @param  int $id Post ID of the post which is to be tested for the Thrive Architect.
		 * @return boolean     Returns true if the post is created using Thrive Architect, False if not.
		 */
		public static function is_tve_activated( $id ) {

			if ( ! defined( 'TVE_VERSION' ) ) {
				return false;
			}

			if ( get_post_meta( $id, 'tcb_editor_enabled', true ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Check is elementor activated.
		 *
		 * @param int $id Post/Page Id.
		 * @return boolean
		 */
		public static function is_elementor_activated( $id ) {
			if ( ! class_exists( '\Elementor\Plugin' ) ) {
				return false;
			}
			if ( version_compare( ELEMENTOR_VERSION, '1.5.0', '<' ) ) {
				return ( 'builder' === Elementor\Plugin::$instance->db->get_edit_mode( $id ) );
			} else {
				return Elementor\Plugin::$instance->db->is_built_with_elementor( $id );
			}

			return false;
		}

		/**
		 * Check VC activated or not on post.
		 *
		 * @param  int $post_id Post Id.
		 * @return boolean
		 */
		public static function is_vc_activated( $post_id ) {

			$post      = get_post( $post_id );
			$vc_active = get_post_meta( $post_id, '_wpb_vc_js_status', true );

			if ( class_exists( 'Vc_Manager' ) && ( 'true' == $vc_active || has_shortcode( $post->post_content, 'vc_row' ) ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Add Styles Callback
		 */
		function add_front_styles() {
			/**
			* Start Path Logic */
			/* Define Variables */
			$uri  = ASTRA_EXT_ADVANCED_HOOKS_URL . 'assets/css/';
			$path = ASTRA_EXT_ADVANCED_HOOKS_DIR . 'assets/css/';
			$rtl  = '';

			if ( is_rtl() ) {
				$rtl = '-rtl';
			}

			/* Directory and Extension */
			$file_prefix = $rtl . '.min';
			$dir_name    = 'minified';

			if ( SCRIPT_DEBUG ) {
				$file_prefix = $rtl;
				$dir_name    = 'unminified';
			}

			$css_uri = $uri . $dir_name . '/';
			$css_dir = $path . $dir_name . '/';

			if ( defined( 'ASTRA_THEME_HTTP2' ) && ASTRA_THEME_HTTP2 ) {
				$gen_path = $css_uri;
			} else {
				$gen_path = $css_dir;
			}

			/*** End Path Logic */
			Astra_Minify::add_css( $gen_path . 'astra-hooks-sticky-header-footer' . $file_prefix . '.css' );
			Astra_Minify::add_css( $gen_path . 'style' . $file_prefix . '.css' );
		}

		/**
		 * Add Scripts Callback
		 */
		function add_scripts() {
			/*** Start Path Logic */

			/* Define Variables */
			$uri  = ASTRA_EXT_ADVANCED_HOOKS_URL . 'assets/js/';
			$path = ASTRA_EXT_ADVANCED_HOOKS_DIR . 'assets/js/';

			/* Directory and Extension */
			$file_prefix = '.min';
			$dir_name    = 'minified';

			if ( SCRIPT_DEBUG ) {
				$file_prefix = '';
				$dir_name    = 'unminified';
			}

			$js_uri = $uri . $dir_name . '/';
			$js_dir = $path . $dir_name . '/';

			if ( defined( 'ASTRA_THEME_HTTP2' ) && ASTRA_THEME_HTTP2 ) {
				$gen_path = $js_uri;
			} else {
				$gen_path = $js_dir;
			}

			/*** End Path Logic */
			Astra_Minify::add_dependent_js( 'jquery' );

			Astra_Minify::add_js( $gen_path . 'advanced-hooks-sticky-header-footer' . $file_prefix . '.js' );
		}

		/**
		 * Add Localize variables
		 *
		 * @param  array $localize_vars Localize variables array.
		 * @return array
		 */
		function localize_variables( $localize_vars ) {

			$option = array(
				'location'  => 'ast-advanced-hook-location',
				'exclusion' => 'ast-advanced-hook-exclusion',
				'users'     => 'ast-advanced-hook-users',
			);

			$result         = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions( 'astra-advanced-hook', $option );
			$counter_header = 0;
			$counter_footer = 0;

			foreach ( $result as $post_id => $post_data ) {
				$post_type = get_post_type();

				if ( 'astra-advanced-hook' != $post_type ) {
					$header = get_post_meta( $post_id, 'ast-advanced-hook-header', true );
					$footer = get_post_meta( $post_id, 'ast-advanced-hook-footer', true );
					$layout = get_post_meta( $post_id, 'ast-advanced-hook-layout', false );

					if ( 0 == $counter_header && isset( $layout[0] ) && 'header' == $layout[0] ) {
						$localize_vars['hook_sticky_header']            = isset( $header['sticky'] ) ? $header['sticky'] : '';
						$localize_vars['hook_shrink_header']            = isset( $header['shrink'] ) ? $header['shrink'] : '';
						$localize_vars['hook_sticky_header_on_devices'] = isset( $header['sticky-header-on-devices'] ) ? $header['sticky-header-on-devices'] : '';

						$localize_vars['hook_custom_header_break_point'] = apply_filters( 'astra_custom_header_break_point', 921 );

						$counter_header++;
					}

					if ( 0 == $counter_footer && isset( $layout[0] ) && 'footer' == $layout[0] ) {
						$localize_vars['hook_sticky_footer']             = isset( $footer['sticky'] ) ? $footer['sticky'] : '';
						$localize_vars['hook_sticky_footer_on_devices']  = isset( $footer['sticky-footer-on-devices'] ) ? $footer['sticky-footer-on-devices'] : '';
						$localize_vars['hook_custom_footer_break_point'] = apply_filters( 'astra_custom_footer_break_point', 921 );

						$counter_footer++;
					}
				}
			}

			return $localize_vars;
		}

		/**
		 * Add Body Classes
		 *
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		function body_classes( $classes ) {
			// Apply Above Below header layout class to the body.
				$option = array(
					'location'  => 'ast-advanced-hook-location',
					'exclusion' => 'ast-advanced-hook-exclusion',
					'users'     => 'ast-advanced-hook-users',
				);

			$result  = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions( 'astra-advanced-hook', $option );
			$counter = 0;
			foreach ( $result as $post_id => $post_data ) {
				$post_type = get_post_type();

				if ( 'astra-advanced-hook' != $post_type ) {
					$footer = get_post_meta( $post_id, 'ast-advanced-hook-footer', true );
					$layout = get_post_meta( $post_id, 'ast-advanced-hook-layout', false );

					if ( 0 == $counter && isset( $layout[0] ) && 'footer' == $layout[0] ) {

						if ( isset( $footer['sticky'] ) && 'enabled' == $footer['sticky'] && isset( $footer['sticky-footer-on-devices'] ) && ( 'desktop' == $footer['sticky-footer-on-devices'] || 'both' == $footer['sticky-footer-on-devices'] ) && ! wp_is_mobile() ) {
							$classes[] = 'ast-footer-sticky-active';
						}
						if ( isset( $footer['sticky'] ) && 'enabled' == $footer['sticky'] && isset( $footer['sticky-footer-on-devices'] ) && 'mobile' == $footer['sticky-footer-on-devices'] && wp_is_mobile() ) {
							$classes[] = 'ast-footer-sticky-active';
						}
						$counter++;
					}
				}
			}

			return $classes;
		}

		/**
		 * Dynamic CSS
		 *
		 * @param  string $dynamic_css          Astra Dynamic CSS.
		 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
		 * @return string
		 */
		function astra_ext_advanced_hooks_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {
			/**
			 * - Variable Declaration
			 */
			$page_width = '100%';
			$parse_css  = '';
			$layout     = astra_get_option( 'site-layout', 'ast-full-width-layout' );

			// set page width depending on site layout.
			if ( 'ast-box-layout' == $layout ) {
				$page_width = astra_get_option( 'site-layout-box-width' ) . 'px';
			}

			/* Box Layout CSS */
			if ( 'ast-box-layout' == $layout ) :
				$box_css    = array(
					'.ast-custom-header, .ast-custom-footer' => array(
						'max-width'    => $page_width,
						'margin-left'  => 'auto',
						'margin-right' => 'auto',
					),
				);
				$parse_css .= astra_parse_css( $box_css );
			endif;
			return $dynamic_css . $parse_css;
		}

	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Astra_Ext_Advanced_Hooks_Markup::get_instance();
