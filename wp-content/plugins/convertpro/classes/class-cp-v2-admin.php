<?php
/**
 * Main builder admin class.
 *
 * @package ConvertPro
 */

/**
 * Class bsf menu.
 */
final class CP_V2_Admin {
	/**
	 * The unique instance of the plugin.
	 *
	 * @var parent_page_slug
	 */
	private static $instance;

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
	private function __construct() {

		add_action( 'admin_init', array( $this, 'redirect_on_activation' ) );
		add_action( 'admin_print_scripts', array( $this, 'deregister_scripts' ), 11 );

		add_filter( 'plugin_action_links_' . CP_V2_DIR_NAME, array( $this, 'action_links' ), 10, 5 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 100 );
		add_action( 'mce_external_plugins', array( $this, 'load_tiny_scripts' ), 10 );
		add_action( 'admin_footer', array( $this, 'edit_post_type_screen' ), 10 );
		add_action( 'current_screen', array( $this, 'init_framework_components' ) );
		add_filter( 'user_can_richedit', array( $this, 'enable_tinyeditor' ), 50 );
		add_filter( 'bsf_allow_beta_updates_convertpro', array( $this, 'cpro_beta_updates_check' ) );
	}

	/**
	 * Function Name: deregister_scripts.
	 * Function Description: Deregister scripts which conflicts with Convert Pro
	 *
	 * @since 1.1.3
	 */
	function deregister_scripts() {

		$screen = get_current_screen();

		if ( isset( $screen->base ) && strpos( $screen->base, CP_PRO_SLUG ) !== false ) {
			// Deregister clinky plugin script.
			wp_dequeue_script( 'yoast_ga_admin' );
		}
	}

	/**
	 * Function Name: cpro_beta_updates_check.
	 * Function Description: Turn on the Beta updates for Convert Pro.
	 *
	 * @since 1.0.4
	 */
	function cpro_beta_updates_check() {

		$beta_update_option = esc_attr( get_option( 'cpro_beta_updates' ) );

		$beta_enable = ! $beta_update_option ? false : true;

		if ( true == $beta_enable ) {
			return true;
		}

			return false;
	}

	/**
	 * Function Name: enable_tinyeditor.
	 * Function Description: Turn on the rich tiny editor for Convert Pro pages.
	 *
	 * @param bool $wp_rich_edit wp editor.
	 * @since 1.0.0
	 */
	function enable_tinyeditor( $wp_rich_edit ) {

		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();

			if ( ! empty( $screen ) ) {
				if ( isset( $screen->base ) && 'post' == $screen->base && CP_CUSTOM_POST_TYPE == $screen->post_type ) {

					$wp_rich_edit = true;
				}
			}
		}

		return $wp_rich_edit;
	}

	/**
	 * Redirect on activation hook
	 *
	 * @since 1.0
	 */
	function redirect_on_activation() {

		if ( get_option( 'convert_pro_redirect' ) == true ) {

			update_option( 'convert_pro_redirect', false );

			if ( ! is_multisite() ) :
				$this->redirect_to_home();
			endif;
		}
	}

	/**
	 * Redirect to Convertro plugin home page after updating menu position
	 */
	function redirect_to_home() {

		// Menu position.
		$position      = esc_attr( get_option( 'bsf_menu_position' ) );
		$menu_position = ! $position ? 'middle' : $position;

		$is_top_level_page = in_array( $menu_position, array( 'top', 'middle', 'bottom' ), true );

		// If menu is at top level.
		if ( $is_top_level_page ) {
			$url = admin_url( 'admin.php?page=' . CP_PRO_SLUG );
		} else {
			if ( strpos( $menu_position, '?' ) !== false ) {
				$query_var = '&page=' . CP_PRO_SLUG;
			} else {
				$query_var = '?page=' . CP_PRO_SLUG;
			}
			$url = admin_url( $menu_position . $query_var );
		}

		wp_redirect( $url );
		exit;
	}

	/**
	 * Function Name: action_links.
	 * Function Description: Adds settings link in plugins action.
	 *
	 * @param string $actions string parameter.
	 * @param string $plugin_file string parameter.
	 */
	function action_links( $actions, $plugin_file ) {
		static $plugin;

		if ( ! isset( $plugin ) ) {
			$plugin = plugin_basename( __FILE__ );
		}
		if ( $plugin == $plugin_file ) {
			$settings = array(
				/* translators: %s link */
				'settings' => sprintf( __( '<a href="%s">Settings</a>', 'convertpro' ), admin_url( 'admin.php?page=' . CP_PRO_SLUG . '&view=settings' ) ),
			);
			$actions  = array_merge( $settings, $actions );
		}
		return $actions;
	}

	/**
	 * Function Name: admin_scripts.
	 * Function Description: Load scripts and styles on admin area of convertPro.
	 *
	 * @param string $hook string parameter.
	 */
	function admin_scripts( $hook ) {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'cp-admin-style', CP_V2_BASE_URL . 'assets/admin/css/convertplug-admin.css' );

		$current_screen = get_current_screen();

		global $post;

		if ( strpos( $hook, CP_PRO_SLUG ) !== false ) {

			wp_dequeue_script( 'yoast_ga_admin' );

			wp_enqueue_script( 'thickbox' );

			wp_enqueue_style( 'cp-frosty-style', CP_V2_BASE_URL . 'assets/admin/css/frosty.css' );
			wp_enqueue_script( 'cp-frosty-script', CP_V2_BASE_URL . 'assets/modules/js/frosty.js', false, CP_V2_VERSION, true );
			wp_enqueue_script( 'cp-dashboard-script', CP_V2_BASE_URL . 'assets/admin/js/dashboard.js', false, CP_V2_VERSION, true );

			wp_enqueue_script( 'convert-select2', CP_V2_BASE_URL . 'assets/admin/js/select2.js', false, CP_V2_VERSION, true );

			wp_localize_script(
				'cp-dashboard-script', 'cp_ajax', array(
					'url'             => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'      => wp_create_nonce( 'cp_ajax_nonce' ),
					'refresh_btn_txt' => __( 'Clear Cache', 'convertpro' ),
					'loading_txt'     => __( 'Clearing cache...', 'convertpro' ),
					'cleared_cache'   => __( 'Cleared', 'convertpro' ),
				)
			);

			wp_enqueue_style( 'cp-animate', CP_V2_BASE_URL . 'assets/modules/css/animate.css' );

			wp_enqueue_style( 'css-select2', CP_V2_BASE_URL . 'assets/admin/css/select2.min.css' );
			wp_enqueue_style( 'cp-switch-style', CP_V2_BASE_URL . 'assets/admin/css/switch.css' );
			wp_enqueue_script( 'cp-switch-script', CP_V2_BASE_URL . 'assets/admin/js/switch.js', false, CP_V2_VERSION, true );
			wp_enqueue_style( 'cp-dashboard-style', CP_V2_BASE_URL . 'assets/admin/css/dashboard.css' );
		}

		$dev_mode = get_option( 'cp_dev_mode' );

		if ( ( 'edit' == $current_screen->base && CP_CUSTOM_POST_TYPE == $current_screen->post_type ) || ( ( 'post-new.php' == $hook || 'post.php' == $hook ) && ( isset( $post->post_type ) && CP_CUSTOM_POST_TYPE == $post->post_type ) ) ) {

			if ( ( isset( $_GET['post'] ) && isset( $_GET['action'] ) && 'edit' == $_GET['action'] ) || 'add' == $current_screen->action ) {

				// Fix for colorpicker conflict with fusion builder plugin.
				wp_dequeue_script( 'wp-color-picker-alpha' );

				// Fix for date timepicker conflict with themify plugin.
				wp_dequeue_script( 'themify-plupload' );
				wp_dequeue_script( 'themify-metabox' );

				// Fix for JS conflict with NextGen Gallery.
				wp_dequeue_script( 'frame_event_publisher' );

				wp_enqueue_media();

				// This script removes files related to WP SEO Meta plugin.
				// We are doing this since they have a conflict with private custom post type post.
				if ( wp_script_is( 'm-wp-seo-metabox', 'enqueued' ) ) {
					wp_dequeue_script( 'm-wp-seo-metabox' );
				}
				if ( wp_script_is( 'metaseo-cliffpyles', 'enqueued' ) ) {
					wp_dequeue_script( 'metaseo-cliffpyles' );
				}

				// developer mode.
				if ( '1' == $dev_mode ) {

					// array of styles to enqueue in customizer.
					$styles = array(
						'convert-admin'                 => CP_V2_BASE_URL . 'assets/admin/css/admin.css',
						'css-select2'                   => CP_V2_BASE_URL . 'assets/admin/css/select2.min.css',
						'cp-pscroll-style'              => CP_V2_BASE_URL . 'assets/admin/css/perfect-scrollbar.min.css',
						'cp-frosty-style'               => CP_V2_BASE_URL . 'assets/admin/css/frosty.css',
						'cp-animate'                    => CP_V2_BASE_URL . 'assets/modules/css/animate.css',
						'cp-customizer-style'           => CP_V2_BASE_URL . 'assets/admin/css/cp-customizer.css',
						'cp-bootstrap-datetimepicker-style' => CP_V2_BASE_URL . 'assets/admin/css/bootstrap-datetimepicker.min.css',
						'cp-bootstrap-standalone-style' => CP_V2_BASE_URL . 'assets/admin/css/bootstrap-datetimepicker-standalone.min.css',
						'cp-rotation-style'             => CP_V2_BASE_URL . 'assets/admin/css/jquery.ui.rotatable.css',
						'cp-component-style'            => CP_V2_BASE_URL . 'assets/admin/css/component.css',
						'cp-tiny-style'                 => CP_V2_BASE_URL . 'assets/admin/css/cp-tinymce.css',
					);

					$styles = apply_filters( 'cp_customizer_styles', $styles );

					do_action( 'cp_before_load_scripts' );

					foreach ( $styles as $handle => $src ) {
						wp_enqueue_style( $handle, $src );
					}

					wp_enqueue_style( 'cp-switch-style', CP_V2_BASE_URL . 'assets/admin/css/switch.css' );

					wp_enqueue_script( 'cp-switch-script', CP_V2_BASE_URL . 'assets/admin/js/switch.js', false, CP_V2_VERSION, true );

					// scripts to enqueue in customizer ( defined source and dependencies ).
					$scripts = array(
						'cp-jquery-cookie'         => CP_V2_BASE_URL . 'assets/admin/js/jquery.cookies.js',
						'cp-frosty-script'         => CP_V2_BASE_URL . 'assets/modules/js/frosty.js',
						'convert-select2'          => CP_V2_BASE_URL . 'assets/admin/js/select2.js',
						'cp-helper-functions-js'   => CP_V2_BASE_URL . 'assets/admin/js/cp-helper-functions.js',
						'cp-moment-script'         => array(
							'src' => CP_V2_BASE_URL . 'assets/admin/js/moment-with-locales.js',
							'dep' => array( 'jquery' ),
						),
						'cp-datetimepicker-script' => array(
							'src' => CP_V2_BASE_URL . 'assets/admin/js/bootstrap-datetimepicker.min.js',
							'dep' => array( 'cp-moment-script' ),
						),
						'cp-perfect-scroll-js'     => CP_V2_BASE_URL . 'assets/admin/js/perfect-scrollbar.jquery.js',
						'cp-proptotypes-js'        => array(
							'src' => CP_V2_BASE_URL . 'assets/admin/js/cp-proptotypes.js',
							'dep' => array( 'cp-helper-functions-js' ),
						),
						'cp-panel-layers-js'       => array(
							'src' => CP_V2_BASE_URL . 'assets/admin/js/cp-panel-layers.js',
							'dep' => array( 'cp-helper-functions-js' ),
						),
						'cp-edit-panel-js'         => array(
							'src' => CP_V2_BASE_URL . 'assets/admin/js/cp-edit-panel.js',
							'dep' => array( 'cp-helper-functions-js' ),
						),
						'cp-panel-steps-js'        => array(
							'src' => CP_V2_BASE_URL . 'assets/admin/js/cp-panel-steps.js',
							'dep' => array( 'cp-helper-functions-js' ),
						),
						'cp-backbone-model-js'     => array(
							'src' => CP_V2_BASE_URL . 'assets/admin/js/cp-backbone-panel.js',
							'dep' => array( 'cp-helper-functions-js' ),
						),
						'cp-mobile-editor'         => CP_V2_BASE_URL . 'assets/admin/js/cp-mobile-editor.js',
						'cp-design-area'           => CP_V2_BASE_URL . 'assets/admin/js/cp-design-area.js',
						'cp-field-events'          => array(
							'src' => CP_V2_BASE_URL . 'assets/admin/js/cp-field-events.js',
							'dep' => array( 'backbone' ),
						),
						'cp-sidepanel-js'          => array(
							'src' => CP_V2_BASE_URL . 'assets/admin/js/cp-sidepanel.js',
							'dep' => array( 'backbone' ),
						),
						'cp-rotation-script'       => CP_V2_BASE_URL . 'assets/admin/js/jquery.ui.rotatable.js',
						'cp-modal-effect'          => CP_V2_BASE_URL . 'assets/admin/js/modalEffects.js',
					);

					$scripts = apply_filters( 'cp_customizer_scripts', $scripts );

					foreach ( $scripts as $slug => $script ) {

						$src        = $script;
						$dependency = array( 'jquery' );

						if ( is_array( $script ) ) {
							$dependency = $script['dep'];
							$src        = $script['src'];
						}

						wp_enqueue_script( $slug, $src, $dependency, CP_V2_VERSION, true );
					}

					wp_localize_script(
						'cp-helper-functions-js', 'cp_customizer_vars',
						array(
							'admin_img_url'         => CP_V2_BASE_URL . 'assets/admin/img',
							'timer_labels'          => __( 'Years', 'convertpro' ) . ',' . __( 'Months', 'convertpro' ) . ',' . __( 'Weeks', 'convertpro' ) . ',' . __( 'Days', 'convertpro' ) . ',' . __( 'Hours', 'convertpro' ) . ',' . __( 'Minutes', 'convertpro' ) . ',' . __( 'Seconds', 'convertpro' ),
							'timer_labels_singular' => __( 'Year', 'convertpro' ) . ',' . __( 'Month', 'convertpro' ) . ',' . __( 'Week', 'convertpro' ) . ',' . __( 'Day', 'convertpro' ) . ',' . __( 'Hour', 'convertpro' ) . ',' . __( 'Minute', 'convertpro' ) . ',' . __( 'Second', 'convertpro' ),
						)
					);

				} else {

					// array of styles to enqueue in customizer.
					$styles = array(
						'convert-admin-css' => CP_V2_BASE_URL . 'assets/admin/css/admin.min.css',
					);

					$styles = apply_filters( 'cp_customizer_styles', $styles );

					foreach ( $styles as $handle => $src ) {
						wp_enqueue_style( $handle, $src );
					}

					// scripts to enqueue in customizer ( defined source and dependencies ).
					$scripts = array(
						'convert-admin-js' => CP_V2_BASE_URL . 'assets/admin/js/admin.min.js',
					);

					$scripts = apply_filters( 'cp_customizer_scripts', $scripts );

					foreach ( $scripts as $slug => $script ) {

						$src        = $script;
						$dependency = array( 'jquery' );

						if ( is_array( $script ) ) {
							$dependency = $script['dep'];
							$src        = $script['src'];
						}

						wp_enqueue_script( $slug, $src, $dependency, CP_V2_VERSION, true );
					}

					wp_localize_script(
						'convert-admin-js', 'cp_customizer_vars',
						array(
							'admin_img_url'         => CP_V2_BASE_URL . 'assets/admin/img',
							'timer_labels'          => __( 'Years', 'convertpro' ) . ',' . __( 'Months', 'convertpro' ) . ',' . __( 'Weeks', 'convertpro' ) . ',' . __( 'Days', 'convertpro' ) . ',' . __( 'Hours', 'convertpro' ) . ',' . __( 'Minutes', 'convertpro' ) . ',' . __( 'Seconds', 'convertpro' ),
							'timer_labels_singular' => __( 'Year', 'convertpro' ) . ',' . __( 'Month', 'convertpro' ) . ',' . __( 'Week', 'convertpro' ) . ',' . __( 'Day', 'convertpro' ) . ',' . __( 'Hour', 'convertpro' ) . ',' . __( 'Minute', 'convertpro' ) . ',' . __( 'Second', 'convertpro' ),
						)
					);
				}

				wp_localize_script(
					'jquery', 'cp_admin_ajax',
					array(
						'url'               => admin_url( 'admin-ajax.php' ),
						'admin_img_url'     => CP_V2_BASE_URL . 'assets/admin/img',
						'assets_url'        => CP_V2_BASE_URL . 'assets/',
						'mobileIncludeOpt'  => Cp_V2_Model::$mobile_include_opt,
						'stepdependentOpts' => Cp_V2_Model::$step_dependent_options,
					)
				);
			}
		}

		wp_localize_script(
			'jquery', 'cp_pro',
			array(
				'group_filters'               => __( 'Specific Pages, Posts, Categories or Taxonomies.', 'convertpro' ),
				'post_types'                  => __( 'Select post types', 'convertpro' ),
				'hidden_field_text'           => __( 'This is a hidden field.This text will not appear at frontend.', 'convertpro' ),
				'click_here'                  => __( 'Click Here', 'convertpro' ),
				'search_settings'             => __( 'Search Settings...', 'convertpro' ),
				'search_mailer'               => __( 'Search Mailer...', 'convertpro' ),
				'search_elements'             => __( 'Search Shapes...', 'convertpro' ),
				'refreshed'                   => __( 'Refreshed', 'convertpro' ),
				'use_this'                    => __( 'Use This', 'convertpro' ),
				'try_again'                   => __( 'Try Again', 'convertpro' ),
				/* translators: %s link */
				'confirm_delete_design'       => __( 'Are you sure you want to delete this call-to-action?', 'convertpro' ),
				'select_diff_camp'            => __( 'Please select different campaign to process.', 'convertpro' ),
				'empty_campaign'              => __( 'Campaign name cannot be empty.', 'convertpro' ),
				'already_exists_camp'         => __( 'This name is already registered! Please try again using a different name.', 'convertpro' ),
				'empty_design'                => __( 'Design name cannot be empty.', 'convertpro' ),
				'deleting'                    => __( 'Deleting...', 'convertpro' ),
				'saving'                      => __( 'Saving...', 'convertpro' ),
				'duplicate'                   => __( 'Duplicate', 'convertpro' ),
				'duplicating'                 => __( 'Duplicating...', 'convertpro' ),
				'creating'                    => __( 'Creating...', 'convertpro' ),
				'saved'                       => __( 'SAVED', 'convertpro' ),
				'save_changes'                => __( 'SAVE CHANGES', 'convertpro' ),
				'not_connected_to_mailer'     => __( 'This form is not connected with any mailer service! Please contact web administrator.', 'convertpro' ),
				'step_delete_confirmation'    => __( 'Do you really want to delete Step', 'convertpro' ),
				'ruleset_delete_confirmation' => __( 'Are you sure you want to delete this ruleset?', 'convertpro' ),
			)
		);

		do_action( 'cp_admin_settinga_scripts', $current_screen );
	}

	/**
	 * Function Name: load_tiny_scripts.
	 * Function Description: Load tiny scripts.
	 *
	 * @param string $mce_plugins string parameter.
	 */
	function load_tiny_scripts( $mce_plugins ) {

		$current_screen = get_current_screen();

		if ( isset( $current_screen->post_type ) && CP_CUSTOM_POST_TYPE == $current_screen->post_type ) {

			$plugpath    = CP_V2_BASE_URL . 'assets/admin/js/tinymce/plugins/';
			$mce_plugins = (array) $mce_plugins;

			$this->plugins = $this->get_all_plugins();

			foreach ( $this->plugins as $plugin ) {
				$mce_plugins[ "$plugin" ] = $plugpath . $plugin . '/plugin.min.js';
			}
		}

		return $mce_plugins;
	}

	/**
	 * Function Name: get_all_plugins.
	 * Function Description: get all plugins.
	 */
	private function get_all_plugins() {
		return array(
			'anchor',
			'save',
			'link',
			'nonbreaking',
			'visualblocks',
			'visualchars',
		);
	}

	/**
	 * Load customizer on edit post and new post screen
	 *
	 * @since 1.0
	 */
	function edit_post_type_screen() {

		$current_screen = get_current_screen();

		if ( null !== $current_screen ) {

			// Display only if it is edit post or new post screen.
			if ( ( CP_CUSTOM_POST_TYPE == $current_screen->post_type && isset( $_GET['post'] ) ) ||
				( CP_CUSTOM_POST_TYPE == $current_screen->post_type && isset( $current_screen->action ) && 'add' == $current_screen->action ) ) {

				$style_id = isset( $_GET['post'] ) ? esc_attr( $_GET['post'] ) : '';
				$type     = get_post_meta( $style_id, 'cp_module_type', true );

				if ( false == $type || 'undefined' == $style_id || '' == $style_id ) {
					$type = isset( $_GET['type'] ) ? esc_attr( $_GET['type'] ) : 'modal_popup';
				}

				$types_dir = CP_FRAMEWORK_DIR . 'types/';
				$file_path = str_replace( '_', '-', $type );
				$file_path = 'class-cp-' . $file_path;

				// load module class.
				if ( file_exists( $types_dir . $file_path . '.php' ) ) {
					require_once( $types_dir . $file_path . '.php' );
				}

				require_once CP_V2_BASE_DIR . 'framework/style-options.php';
				require_once CP_V2_BASE_DIR . 'framework/edit.php';
			}

			if ( isset( $_REQUEST['page'] ) && isset( $_REQUEST['view'] ) && strpos( $_REQUEST['page'], CP_PRO_SLUG ) !== false && 'template' == $_REQUEST['view'] ) {

				if ( isset( $_REQUEST['cp_debug'] ) ) {
				?>
					<a href="#" style="position: absolute; bottom: 10px; right: auto; top: auto; left: 170px; z-index: 999" data-modal-type="<?php echo esc_attr( $_REQUEST['type'] ); ?>" class="cp-btn-primary cp-sm-btn cp-button-style cp-remove-local-templates"><?php _e( 'Delete Template Data', 'convertpro' ); ?></a>;

				<?php
				}

				$hide_template_ref_link = esc_attr( get_option( 'cpro_hide_refresh_template' ) );

				if ( '1' !== $hide_template_ref_link ) {
				?>
					<a href="#" style="position: absolute; bottom: 10px; right: 10px; top: auto; left: auto; z-index: 999" data-modal-type="<?php echo esc_attr( $_REQUEST['type'] ); ?>" class="cp-btn-primary cp-sm-btn cp-button-style cp-refresh-templates"><?php _e( 'Refresh Cloud Templates', 'convertpro' ); ?></a>';
					<?php
				}
			}
		}
	}

	/**
	 * Function Name: init_framework_components.
	 * Function Description: Initialize framework components and load jquery UI libraries.
	 *
	 * @param string $current_screen string parameter.
	 */
	function init_framework_components( $current_screen ) {

		if ( ( 'add' == $current_screen->action && CP_CUSTOM_POST_TYPE == $current_screen->post_type )
			|| ( CP_CUSTOM_POST_TYPE == $current_screen->post_type
				&& ( isset( $_GET['action'] ) && 'edit' == $_GET['action'] && 'post' == $current_screen->base ) ) ) {

			// include WordPress jQuery inbulit scripts and styles.
			$styles = array(
				'wp-color-picker',
				'thickbox',
			);

			$scripts = array(
				'thickbox',
				'jquery',
				'wp-color-picker',
				'jquery-ui-core',
				'jquery-ui-widget',
				'jquery-ui-draggable',
				'jquery-ui-droppable',
				'jquery-ui-resizable',
				'jquery-ui-tabs',
				'jquery-ui-autocomplete',
			);

			foreach ( $styles as $style ) {
				wp_enqueue_style( $style );
			}

			foreach ( $scripts as $script ) {
				wp_enqueue_script( $script );
			}
		}
	}
}

$cp_v2_admin = CP_V2_Admin::get_instance();

