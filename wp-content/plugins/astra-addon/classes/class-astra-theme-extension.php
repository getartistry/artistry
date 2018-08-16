<?php
/**
 * Astra Theme Extension
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Astra_Theme_Extension' ) ) {

	/**
	 * Astra_Theme_Extension initial setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Theme_Extension {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Member Variable
		 *
		 * @var options
		 */
		public static $options;

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
		 * Constructor
		 */
		public function __construct() {

			// Activation hook.
			register_activation_hook( ASTRA_EXT_FILE, array( $this, 'activation_reset' ) );

			// deActivation hook.
			register_deactivation_hook( ASTRA_EXT_FILE, array( $this, 'deactivation_reset' ) );

			// Includes Required Files.
			$this->includes();

			if ( is_admin() ) {

				add_action( 'admin_notices', array( $this, 'min_theme_version__error' ) );

				add_filter( 'astra_menu_options', array( $this, 'extension_menu_options' ), 9, 1 );

				// Enqueue Admin Scripts.
				add_action( 'astra_admin_settings_scripts', array( $this, 'admin_scripts' ) );

				// Ajax requests.
				add_action( 'wp_ajax_astra_addon_activate_module', array( $this, 'activate_module' ) );
				add_action( 'wp_ajax_astra_addon_deactivate_module', array( $this, 'deactivate_module' ) );

				add_action( 'wp_ajax_astra_addon_bulk_activate_modules', array( $this, 'bulk_activate_modules' ) );
				add_action( 'wp_ajax_astra_addon_bulk_deactivate_modules', array( $this, 'bulk_deactivate_modules' ) );

				add_action( 'wp_ajax_astra_addon_clear_cache', array( $this, 'clear_cache' ) );
			}

			add_action( 'init', array( $this, 'addons_action_hooks' ), 1 );
			add_action( 'after_setup_theme', array( $this, 'setup' ) );

			add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ) );
			add_action( 'customize_register', array( $this, 'customize_register_before_theme' ), 5 );
			add_action( 'customize_register', array( $this, 'addon_customize_register' ), 99 );
			add_action( 'customize_preview_init', array( $this, 'preview_init' ), 1 );

			add_filter( 'body_class', array( $this, 'body_classes' ), 11, 1 );

			// Load textdomain.
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'plugins_loaded', array( $this, 'common_plugin_dependent_files' ) );
			add_action( 'wpml_loaded', array( $this, 'wpml_compatibility' ) );

			// Astra Addon List filter.
			add_filter( 'astra_addon_list', array( $this, 'astra_addon_list' ) );
			add_filter( 'astra_quick_settings', array( $this, 'astra_addon_quick_settings' ) );
			add_action( 'astra_addon_bulk_action', array( $this, 'astra_addon_bulk_action_markup' ) );

			add_action( 'plugin_action_links_' . ASTRA_EXT_BASE, array( $this, 'action_links' ) );

			if ( Astra_Ext_White_Label_Markup::show_branding() ) {
				add_action( 'astra_welcome_page_right_sidebar_before', array( $this, 'addon_licence_form' ) );
			} else {
				// if White Lebel settings is selected to Hide setting.
				add_action( 'astra_welcome_page_content_after', array( $this, 'addon_licence_form' ) );
			}

			// Redirect if old addon screen rendered.
			add_action( 'admin_init', array( $this, 'redirect_addon_listing_page' ) );
		}

		/**
		 * Astra Addon action hooks
		 *
		 * @return void
		 */
		function addons_action_hooks() {

			$activate_transient   = get_transient( 'astra_addon_activated_transient' );
			$deactivate_transient = get_transient( 'astra_addon_deactivated_transient' );

			if ( false != $activate_transient ) {
				do_action( 'astra_addon_activated', $activate_transient );
				delete_transient( 'astra_addon_activated_transient' );
			}

			if ( false != $deactivate_transient ) {
				do_action( 'astra_addon_deactivated', $deactivate_transient );
				delete_transient( 'astra_addon_deactivated_transient' );
			}
		}

		/**
		 * Activate module
		 */
		function activate_module() {

			check_ajax_referer( 'astra-addon-module-nonce', 'nonce' );

			$module_id                = sanitize_text_field( $_POST['module_id'] );
			$extensions               = Astra_Ext_Extension::get_enabled_addons();
			$extensions[ $module_id ] = $module_id;
			$extensions               = array_map( 'esc_attr', $extensions );
			Astra_Admin_Helper::update_admin_settings_option( '_astra_ext_enabled_extensions', $extensions );

			if ( 'http2' == $module_id ) {
				Astra_Admin_Helper::update_admin_settings_option( '_astra_ext_http2', true );
			}

			set_transient( 'astra_addon_activated_transient', $module_id );

			echo $module_id;

			die();
		}

		/**
		 * Deactivate module
		 */
		function deactivate_module() {

			check_ajax_referer( 'astra-addon-module-nonce', 'nonce' );
			$module_id                = sanitize_text_field( $_POST['module_id'] );
			$extensions               = Astra_Ext_Extension::get_enabled_addons();
			$extensions[ $module_id ] = false;
			$extensions               = array_map( 'esc_attr', $extensions );
			Astra_Admin_Helper::update_admin_settings_option( '_astra_ext_enabled_extensions', $extensions );

			if ( 'http2' == $module_id ) {
				Astra_Admin_Helper::delete_admin_settings_option( '_astra_ext_http2' );
			}

			set_transient( 'astra_addon_deactivated_transient', $module_id );

			echo $module_id;

			die();
		}

		/**
		 * Activate all module
		 */
		function bulk_activate_modules() {

			check_ajax_referer( 'astra-addon-module-nonce', 'nonce' );

			// Get all extensions.
			$all_extensions = array_map( 'sanitize_text_field', Astra_Ext_Extension::get_addons() );
			$new_extensions = array();

			// Set all extension to enabled.
			foreach ( $all_extensions  as $slug => $value ) {
				$new_extensions[ $slug ] = $slug;
			}

			// Escape attrs.
			$new_extensions = array_map( 'esc_attr', $new_extensions );

			// Update new_extensions.
			Astra_Admin_Helper::update_admin_settings_option( '_astra_ext_enabled_extensions', $new_extensions );

			Astra_Admin_Helper::update_admin_settings_option( '_astra_ext_http2', true );

			set_transient( 'astra_addon_activated_transient', $new_extensions );

			echo 'success';

			die();
		}

		/**
		 * Deactivate all module
		 */
		function bulk_deactivate_modules() {

			check_ajax_referer( 'astra-addon-module-nonce', 'nonce' );

			// Get all extensions.
			$old_extensions = array_map( 'sanitize_text_field', Astra_Ext_Extension::get_enabled_addons() );
			$new_extensions = array();

			// Set all extension to enabled.
			foreach ( $old_extensions  as $slug => $value ) {
				$new_extensions[ $slug ] = false;
			}

			// Escape attrs.
			$new_extensions = array_map( 'esc_attr', $new_extensions );

			// Update new_extensions.
			Astra_Admin_Helper::update_admin_settings_option( '_astra_ext_enabled_extensions', $new_extensions );

			Astra_Admin_Helper::delete_admin_settings_option( '_astra_ext_http2' );

			set_transient( 'astra_addon_deactivated_transient', $new_extensions );

			echo 'success';

			die();
		}

		/**
		 * Clear assets cache.
		 */
		function clear_cache() {

			Astra_Minify::refresh_assets();

			wp_send_json_success();
		}

		/**
		 * Add Body Classes
		 *
		 * @param  array $classes Body Class Array.
		 * @return array
		 */
		function body_classes( $classes ) {

			// Current Astra Addon version.
			$classes[] = esc_attr( 'astra-addon-' . ASTRA_EXT_VER );

			return $classes;
		}


		/**
		 * Load Astra Pro Text Domain.
		 * This will load the translation textdomain depending on the file priorities.
		 *      1. Global Languages /wp-content/languages/astra-addon/ folder
		 *      2. Local dorectory /wp-content/plugins/astra-addon/languages/ folder
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			// Default languages directory for Astra Pro.
			$lang_dir = ASTRA_EXT_DIR . 'languages/';

			/**
			 * Filters the languages directory path to use for AffiliateWP.
			 *
			 * @param string $lang_dir The languages directory path.
			 */
			$lang_dir = apply_filters( 'astra_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter.
			global $wp_version;

			$get_locale = get_locale();

			if ( $wp_version >= 4.7 ) {
				$get_locale = get_user_locale();
			}

			/**
			 * Language Locale for Astra Pro
			 *
			 * @var $get_locale The locale to use. Uses get_user_locale()` in WordPress 4.7 or greater,
			 *                  otherwise uses `get_locale()`.
			 */
			$locale = apply_filters( 'plugin_locale', $get_locale, 'astra-addon' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'astra-addon', $locale );

			// Setup paths to current locale file.
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/astra-addon/ folder.
				load_textdomain( 'astra-addon', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/astra-addon/languages/ folder.
				load_textdomain( 'astra-addon', $mofile_local );
			} else {
				// Load the default language files.
				load_plugin_textdomain( 'astra-addon', false, $lang_dir );
			}
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @param   mixed $links Plugin Action links.
		 * @return  array
		 */
		function action_links( $links = array() ) {

			$action_links = array(
				'settings' => '<a href="' . esc_url( admin_url( 'themes.php?page=astra' ) ) . '" aria-label="' . esc_attr__( 'View Astra Pro settings', 'astra-addon' ) . '">' . esc_html__( 'Settings', 'astra-addon' ) . '</a>',
			);

			return array_merge( $action_links, $links );

		}

		/**
		 * Activation Reset
		 */
		function activation_reset() {

			add_rewrite_endpoint( 'partial', EP_PERMALINK );
			// flush rewrite rules.
			flush_rewrite_rules();

			// Force check graupi bundled products.
			update_site_option( 'bsf_force_check_extensions', true );

			if ( is_network_admin() ) {
				$branding = get_site_option( '_astra_ext_white_label' );
			} else {
				$branding = get_option( '_astra_ext_white_label' );
			}

			if ( isset( $branding['astra-agency']['hide_branding'] ) && false != $branding['astra-agency']['hide_branding'] ) {

				$branding['astra-agency']['hide_branding'] = false;

				if ( is_network_admin() ) {

					update_site_option( '_astra_ext_white_label', $branding );

				} else {
					update_option( '_astra_ext_white_label', $branding );
				}
			}
		}

		/**
		 * Deactivation Reset
		 */
		function deactivation_reset() {
			// flush rewrite rules.
			flush_rewrite_rules();
		}

		/**
		 * Includes
		 */
		function includes() {
			require_once ASTRA_EXT_DIR . 'classes/helper-functions.php';
			require_once ASTRA_EXT_DIR . 'classes/class-astra-admin-helper.php';
			require_once ASTRA_EXT_DIR . 'classes/astra-theme-compatibility-functions.php';
			require_once ASTRA_EXT_DIR . 'classes/customizer/class-astra-addon-customizer.php';
			require_once ASTRA_EXT_DIR . 'classes/modules/target-rule/class-astra-target-rules-fields.php';
			require_once ASTRA_EXT_DIR . 'classes/modules/menu-sidebar/class-astra-menu-sidebar-animation.php';
			require_once ASTRA_EXT_DIR . 'classes/class-astra-ext-extension.php';
			require_once ASTRA_EXT_DIR . 'classes/class-astra-templates.php';

			// White Lebel.
			require_once ASTRA_EXT_DIR . 'classes/class-astra-ext-white-label-markup.php';
		}

		/**
		 * Add extension option in menu page
		 *
		 * @param  array $actions   Array of actions.
		 * @return array            Return the actions.
		 */
		function extension_menu_options( $actions ) {

			$actions['addons'] = array(
				'label' => esc_html__( 'Addons', 'astra-addon' ),
				'show'  => ! is_network_admin(),
			);
			return $actions;
		}


		/**
		 * Admin Scripts
		 */
		function admin_scripts() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		}

		/**
		 * Admin Scripts Callback
		 */
		function enqueue_admin_scripts() {

			// Styles.
			wp_enqueue_style( 'ast-ext-admin-settings', ASTRA_EXT_URI . 'admin/assets/css/ast-ext-admin-settings.css', array(), ASTRA_EXT_VER );

			// Scripts.
			wp_enqueue_script( 'astra-ext-admin-settings', ASTRA_EXT_URI . 'admin/assets/js/ast-ext-admin-settings.js', array( 'jquery-ui-tooltip' ), ASTRA_EXT_VER );

			$options = array(
				'ajax_nonce' => wp_create_nonce( 'astra-addon-module-nonce' ),
				'activate'   => __( 'Activate', 'astra-addon' ),
				'deactivate' => __( 'Deactivate', 'astra-addon' ),
			);

			wp_localize_script( 'astra-ext-admin-settings', 'astraAddonModules', $options );
		}

		/**
		 * After Setup Theme
		 */
		function setup() {

			if ( ! defined( 'ASTRA_THEME_VERSION' ) ) {
				return;
			}

			require_once ASTRA_EXT_DIR . 'classes/astra-common-functions.php';
			require_once ASTRA_EXT_DIR . 'classes/class-astra-minify.php';
			require_once ASTRA_EXT_DIR . 'classes/class-astra-ext-model.php';
		}

		/**
		 * Enqueues the needed CSS/JS for the customizer.
		 *
		 * @since 1.0
		 * @return void
		 */
		function controls_scripts() {

			wp_enqueue_style( 'ast-ext-admin-settings', ASTRA_EXT_URI . 'admin/assets/css/customizer-controls.css', array(), ASTRA_EXT_VER );
			wp_enqueue_script( 'ast-ext-admin-settings', ASTRA_EXT_URI . 'admin/assets/js/customizer-controls.js', array(), ASTRA_EXT_VER );

		}

		/**
		 * Customizer Preview Init
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function preview_init() {

			if ( SCRIPT_DEBUG ) {
				$js_path = 'assets/js/unminified/ast-addon-customizer-preview.js';
			} else {
				$js_path = 'assets/js/minified/ast-addon-customizer-preview.min.js';
			}

			wp_enqueue_script( 'astra-addon-customizer-preview-js', ASTRA_EXT_URI . $js_path, array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_EXT_VER, true );
		}


		/**
		 * Base on addon activation section registered.
		 *
		 * @since 1.0.0
		 * @param object $wp_customize customizer object.
		 * @return void
		 */
		function customize_register_before_theme( $wp_customize ) {

			if ( ! defined( 'ASTRA_THEME_VERSION' ) ) {
				return;
			}

			if ( ! class_exists( 'Astra_WP_Customize_Section' ) ) {
				wp_die( 'You are using an older version of the Astra theme. Please update the Astra theme to the latest version.' );
			}

			$addons = Astra_Ext_Extension::get_enabled_addons();

			// Update the Customizer Sections under Layout.
			if ( false != $addons['header-sections'] ) {
				$wp_customize->add_section(
					new Astra_WP_Customize_Section(
						$wp_customize, 'section-mobile-primary-header-layout',
						array(
							'title'    => __( 'Primary Header', 'astra-addon' ),
							'panel'    => 'panel-layout',
							'section'  => 'section-mobile-header',
							'priority' => 10,
						)
					)
				);
			}

			// Update the Customizer Sections under Typography.
			if ( false != $addons['typography'] ) {

				$wp_customize->add_section(
					new Astra_WP_Customize_Section(
						$wp_customize, 'section-header-typo-group',
						array(
							'title'    => __( 'Header', 'astra-addon' ),
							'panel'    => 'panel-typography',
							'priority' => 20,
						)
					)
				);

				add_filter(
					'astra_customizer_primary_header_typo', function( $header_arr ) {

						$header_arr['section'] = 'section-header-typo-group';

						return $header_arr;
					}
				);

			}
		}

		/**
		 * Register Customizer Control.
		 *
		 * @since 1.0.2
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function addon_customize_register( $wp_customize ) {

			if ( function_exists( 'WP_Customize_Themes_Panel' ) ) {

				$wp_customize->add_panel(
					new WP_Customize_Themes_Panel(
						$this, 'themes', array(
							'title'       => astra_get_theme_name(),
							'description' => (
							'<p>' . __( 'Looking for a theme? You can search or browse the WordPress.org theme directory, install and preview themes, then activate them right here.', 'astra-addon' ) . '</p>' .
							'<p>' . __( 'While previewing a new theme, you can continue to tailor things like widgets and menus, and explore theme-specific options.', 'astra-addon' ) . '</p>'
							),
							'capability'  => 'switch_themes',
							'priority'    => 0,
						)
					)
				);
			}
		}

		/**
		 * WPML Compatibility.
		 *
		 * @since 1.1.0
		 */
		function wpml_compatibility() {

			require_once ASTRA_EXT_DIR . 'compatibility/class-astra-wpml-compatibility.php';
		}

		/**
		 * Common plugin dependent file which dependd on other plugins.
		 *
		 * @since 1.1.0
		 */
		function common_plugin_dependent_files() {

			// If plugin - 'Ubermenu' not exist then return.
			if ( class_exists( 'UberMenu' ) ) {
				require_once ASTRA_EXT_DIR . 'compatibility/class-astra-ubermenu-pro.php';
			}
		}

		/**
		 * Check compatible theme version.
		 *
		 * @since 1.2.0
		 */
		function min_theme_version__error() {

			$astra_global_options = get_option( 'astra-settings' );

			if ( isset( $astra_global_options['theme-auto-version'] ) ) {

				if ( version_compare( $astra_global_options['theme-auto-version'], ASTRA_THEME_MIN_VER ) < 0 ) {

					$astra_theme_name = 'Astra';
					if ( function_exists( 'astra_get_theme_name' ) ) {
						$astra_theme_name = astra_get_theme_name();
					}

					$class   = 'notice notice-error';
					$message = sprintf(
						/* translators: %1$1s: Theme Name, %2$2s: Minimum Required version of the Astra Theme */
						__( 'Please update %1$1s Theme to at least Version %2$2s.', 'astra-addon' ), $astra_theme_name, ASTRA_THEME_MIN_VER
					);

					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
				}
			}
		}


		/**
		 * Modified Astra Quick settings links update based on the activated plugins
		 *
		 * @since 1.2.1
		 * @param array $quick_settings quick settings list.
		 * @return array $quick_settings Updated quick settings list.
		 */
		function astra_addon_quick_settings( $quick_settings = array() ) {
			$enabled_extensions = Astra_Ext_Extension::get_addons();
			// Update the quick setting link for header group.
			$headers_addons = array( 'header-sections', 'transparent-header', 'sticky-header' );

			foreach ( $enabled_extensions as $addon_slug => $value ) {
				if ( ! in_array( $addon_slug, $headers_addons ) || ! Astra_Ext_Extension::is_active( $addon_slug ) ) {
					continue;
				}
				$quick_settings['header']['quick_url'] = admin_url( 'customize.php?autofocus[section]=section-header-group' );
			}
			return $quick_settings;
		}

		/**
		 * Modified Astra Addon List
		 *
		 * @since 1.2.1
		 * @param array $addons Astra addon list.
		 * @return array $addons Updated Astra addon list.
		 */
		function astra_addon_list( $addons = array() ) {

			$enabled_extensions = Astra_Ext_Extension::get_addons();
			$extension_slugs    = array_keys( $enabled_extensions );
			$extension_slugs[]  = 'white-label';

			foreach ( $addons as $addon_slug => $value ) {
				if ( ! in_array( $addon_slug, $extension_slugs ) ) {
					continue;
				}
				$class = 'deactive';
				$links = array(
					array(
						'link_class' => 'ast-activate-module',
						'link_text'  => __( 'Activate', 'astra-addon' ),
						'link_url'   => '',
					),
				);

				if ( Astra_Ext_Extension::is_active( $addon_slug ) ) {
					$class = 'active';
					$links = array(
						array(
							'link_class' => 'ast-deactivate-module',
							'link_text'  => __( 'Deactivate', 'astra-addon' ),
							'link_url'   => '',
						),
					);
				}

				switch ( $addon_slug ) {
					case 'advanced-hooks':
							$links[] = array(
								'link_class' => 'advanced-module',
								'link_text'  => __( 'Settings', 'astra-addon' ),
								'link_url'   => admin_url( '/edit.php?post_type=astra-advanced-hook' ),
							);
						break;
					case 'advanced-headers':
							$links[] = array(
								'link_class' => 'advanced-module',
								'link_text'  => __( 'Settings', 'astra-addon' ),
								'link_url'   => admin_url( '/edit.php?post_type=astra_adv_header' ),
							);
						break;

					case 'white-label':
							$class   = 'white-label';
							$links   = false;
							$links[] = array(
								'link_class'   => 'advanced-module',
								'link_text'    => __( 'Settings', 'astra-addon' ),
								'link_url'     => Astra_Admin_Settings::get_page_url( $addon_slug ),
								'target_blank' => false,
							);
						break;

					case 'woocommerce':
						$class .= ' woocommerce';
						if ( ! class_exists( 'WooCommerce' ) ) {
							$class .= ' ast-disable ast-modules-not-activated';
							$links  = array(
								array(
									'link_class' => 'ast-deactivate-module',
									'link_text'  => __( 'WooCommerce not activated.', 'astra-addon' ),
									'link_url'   => '',
								),
							);
						}

						break;

					case 'learndash':
						$class .= ' learndash';
						if ( ! class_exists( 'SFWD_LMS' ) ) {
							$class .= ' ast-disable ast-modules-not-activated';
							$links  = array(
								array(
									'link_class' => 'ast-deactivate-module',
									'link_text'  => __( 'LearnDash not activated.', 'astra-addon' ),
									'link_url'   => '',
								),
							);
						}
						break;

					case 'lifterlms':
						$class .= ' lifterlms';
						if ( ! class_exists( 'LifterLMS' ) ) {
							$class .= ' ast-disable ast-modules-not-activated';
							$links  = array(
								array(
									'link_class' => 'ast-deactivate-module',
									'link_text'  => __( 'LifterLMS not activated.', 'astra-addon' ),
									'link_url'   => '',
								),
							);
						}
						break;
				}

				$addons[ $addon_slug ]['links'] = $links;
				$addons[ $addon_slug ]['class'] = $class;

				// Don't show White Label tab if white label branding is hidden.
				if ( ! Astra_Ext_White_Label_Markup::show_branding() ) {
					unset( $addons['white-label'] );
				}
			}

			return $addons;
		}

		/**
		 * Astra Addon Bulk action
		 *
		 * @since 1.2.1
		 */
		function astra_addon_bulk_action_markup() {
			?>
				<div class="ast-bulk-actions-wrap">
					<a class="bulk-action ast-activate-all button"> <?php esc_html_e( 'Activate All', 'astra-addon' ); ?> </a>
					<a class="bulk-action ast-deactivate-all button"> <?php esc_html_e( 'Deactivate All', 'astra-addon' ); ?> </a>
				</div>
			<?php
		}

		/**
		 * Astra Header Top Right info
		 *
		 * @since 1.2.1
		 */
		function astra_header_top_right_content() {
			$top_links = apply_filters(
				'astra_header_top_links', array(
					'astra-theme-info' => array(
						'title' => __( 'Stylish, Lightning Fast & Easily Customizable!', 'astra-addon' ),
					),
				)
			);

		}

		/**
		 * Redirect to astra welcome page if visited old Astra Addon Listing page
		 *
		 * @since 1.2.1
		 * @return void
		 */
		public function redirect_addon_listing_page() {

			global $pagenow;
			/* Check current admin page. */

			if ( 'themes.php' == $pagenow && isset( $_GET['action'] ) && 'addons' == $_GET['action'] ) {
				wp_redirect( admin_url( '/themes.php?page=astra' ), 301 );
				exit;
			}
		}

		/**
		 * Addon Activation / Deactivation markup
		 *
		 * @since 1.2.1
		 * @return void
		 */
		public function addon_licence_form() {

			if ( is_multisite() ) {
				$white_label = get_site_option( '_astra_ext_white_label' );
			} else {
				$white_label = get_option( '_astra_ext_white_label' );
			}

			$theme_name = __( 'Astra Pro License', 'astra-addon' );

			if ( ! empty( $white_label['astra']['name'] ) ) {
				/* translators: %s: Theme name */
				$theme_name = sprintf( __( '%s License', 'astra-addon' ), $white_label['astra']['name'] );
			}

			/* translators: %s: Theme name */
			$not_active_status = sprintf( __( 'Please enter your valid %s license key to receive updates and support.', 'astra-addon' ), $theme_name );

			?>
			<div class="postbox">
				<h2 class="hndle ast-normal-cusror"><span><?php echo esc_html( $theme_name ); ?></span></h2>
				<div class="inside">
				<?php
					$bsf_product_id = bsf_extract_product_id( ASTRA_EXT_DIR );
					$args           = array(
						'product_id'                       => $bsf_product_id,
						'button_text_activate'             => esc_html__( 'Activate License', 'astra-addon' ),
						'button_text_deactivate'           => esc_html__( 'Deactivate License', 'astra-addon' ),
						'license_form_title'               => '',
						'license_deactivate_status'        => esc_html__( 'Your license is not active!', 'astra-addon' ),
						'license_activate_status'          => esc_html__( 'Your license is activated!', 'astra-addon' ),
						'submit_button_class'              => 'astra-product-license button-default',
						'form_class'                       => 'form-wrap bsf-license-register-' . esc_attr( $bsf_product_id ),
						'bsf_license_form_heading_class'   => 'astra-license-heading',
						'bsf_license_active_class'         => 'success-message',
						'bsf_license_not_activate_message' => 'license-error',
						'size'                             => 'regular',
						'bsf_license_allow_email'          => false,
						'bsf_license_active_status'        => __( 'Active!', 'astra-addon' ),
						'bsf_license_not_active_status'    => $not_active_status,
					);
					echo bsf_license_activation_form( $args );
				?>
				</div>
			</div>
			<?php
		}

	}
}

/**
 *  Prepare if class 'Astra_Customizer_Loader' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
Astra_Theme_Extension::get_instance();
