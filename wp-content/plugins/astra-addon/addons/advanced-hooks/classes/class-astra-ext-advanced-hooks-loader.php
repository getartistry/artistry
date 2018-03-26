<?php
/**
 * Advanced Hooks - Loader.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Ext_Advanced_Hooks_Loader' ) ) {

	/**
	 * Astra Advanced Hooks Initialization
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Advanced_Hooks_Loader {


		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Member Variable
		 *
		 * @var $_actions
		 */
		public static $_action = 'advanced-hooks';

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

			add_action( 'init', array( $this, 'advanced_hooks_post_type' ) );
			add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 100 );
			add_action( 'astra_addon_activated', array( $this, 'astra_addon_activated_callback' ) );
			add_filter( 'postbox_classes_astra-advanced-hook-advanced-hook-settings', array( $this, 'add_class_to_metabox' ) );

			// Remove Meta box of astra settings.
			add_action( 'do_meta_boxes', array( $this, 'remove_astra_meta_box' ) );
			add_filter( 'post_updated_messages', array( $this, 'custom_post_type_post_update_messages' ) );

			if ( is_admin() ) {
				add_action( 'manage_astra-advanced-hook_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
				// Filters.
				add_filter( 'manage_astra-advanced-hook_posts_columns', array( $this, 'column_headings' ) );
			}

			// Actions.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_filter( 'fl_builder_post_types', array( $this, 'bb_builder_compatibility' ), 10, 1 );
		}

		/**
		 * Adds or removes list table column headings.
		 *
		 * @param array $columns Array of columns.
		 * @return array
		 */
		static public function column_headings( $columns ) {

			unset( $columns['date'] );

			$columns['advanced_hook_action']        = __( 'Action', 'astra-addon' );
			$columns['advanced_hook_display_rules'] = __( 'Display Rules', 'astra-addon' );
			$columns['date']                        = __( 'Date', 'astra-addon' );

			return $columns;
		}

		/**
		 * Adds the custom list table column content.
		 *
		 * @since 1.0
		 * @param array $column Name of column.
		 * @param int   $post_id Post id.
		 * @return void
		 */
		public function column_content( $column, $post_id ) {

			if ( 'advanced_hook_action' == $column ) {
				$layout = get_post_meta( $post_id, 'ast-advanced-hook-layout', true );

				if ( 'hooks' === $layout ) {
					$action = get_post_meta( $post_id, 'ast-advanced-hook-action', true );
				} else {
					$action = $layout;
				}

				echo $action;
			} elseif ( 'advanced_hook_display_rules' == $column ) {

				$locations = get_post_meta( $post_id, 'ast-advanced-hook-location', true );
				if ( ! empty( $locations ) ) {
					echo '<div class="ast-advanced-hook-location-wrap" style="margin-bottom: 5px;">';
					echo '<strong>Display: </strong>';
					$this->column_display_location_rules( $locations );
					echo '</div>';
				}

				$locations = get_post_meta( $post_id, 'ast-advanced-hook-exclusion', true );
				if ( ! empty( $locations ) ) {
					echo '<div class="ast-advanced-hook-exclusion-wrap" style="margin-bottom: 5px;">';
					echo '<strong>Exclusion: </strong>';
					$this->column_display_location_rules( $locations );
					echo '</div>';
				}

				$users = get_post_meta( $post_id, 'ast-advanced-hook-users', true );
				if ( isset( $users ) && is_array( $users ) ) {
					$user_label = array();
					foreach ( $users as $user ) {
						$user_label[] = Astra_Target_Rules_Fields::get_user_by_key( $user );
					}
					echo '<div class="ast-advanced-hook-users-wrap">';
					echo '<strong>Users: </strong>';
					echo join( ', ', $user_label );
					echo '</div>';
				}
			}
		}

		/**
		 * Get Markup of Location rules for Display rule column.
		 *
		 * @param array $locations Array of locations.
		 * @return void
		 */
		public function column_display_location_rules( $locations ) {

			$location_label = array();
			$index          = array_search( 'specifics', $locations['rule'] );
			if ( false !== $index && ! empty( $index ) ) {
				unset( $locations['rule'][ $index ] );
			}

			if ( isset( $locations['rule'] ) && is_array( $locations['rule'] ) ) {
				foreach ( $locations['rule'] as $location ) {
					$location_label[] = Astra_Target_Rules_Fields::get_location_by_key( $location );
				}
			}
			if ( isset( $locations['specific'] ) && is_array( $locations['specific'] ) ) {
				foreach ( $locations['specific'] as $location ) {
					$location_label[] = Astra_Target_Rules_Fields::get_location_by_key( $location );
				}
			}

			echo join( ', ', $location_label );
		}

		/**
		 * Custom post type rewrite rules.
		 */
		function astra_addon_activated_callback() {
			$this->advanced_hooks_post_type();
			flush_rewrite_rules();
		}

		/**
		 * Add Custom Class to setting meta box
		 *
		 * @param array $classes Array of meta box classes.
		 * @return array $classes updated body classes.
		 */
		function add_class_to_metabox( $classes ) {
			$classes[] = 'advanced-hook-meta-box-wrap';
				return $classes;
		}

		/**
		 * Remove astra setting meta box
		 */
		function remove_astra_meta_box() {
			remove_meta_box( 'astra_settings_meta_box', 'astra-advanced-hook', 'side' );
		}

		/**
		 * Create Astra Advanced Hooks custom post type
		 */
		function advanced_hooks_post_type() {
			$labels = array(
				'name'          => esc_html_x( 'Custom Layouts', 'advanced-hooks general name', 'astra-addon' ),
				'singular_name' => esc_html_x( 'Custom Layout', 'advanced-hooks singular name', 'astra-addon' ),
				'search_items'  => esc_html__( 'Search Custom Layouts', 'astra-addon' ),
				'all_items'     => esc_html__( 'All Custom Layouts', 'astra-addon' ),
				'edit_item'     => esc_html__( 'Edit Custom Layout', 'astra-addon' ),
				'view_item'     => esc_html__( 'View Custom Layout', 'astra-addon' ),
				'add_new'       => esc_html__( 'Add New', 'astra-addon' ),
				'update_item'   => esc_html__( 'Update Custom Layout', 'astra-addon' ),
				'add_new_item'  => esc_html__( 'Add New', 'astra-addon' ),
				'new_item_name' => esc_html__( 'New Custom Layout Name', 'astra-addon' ),
			);
			$args   = array(
				'labels'              => $labels,
				'show_in_menu'        => false,
				'public'              => true,
				'show_ui'             => true,
				'query_var'           => true,
				'can_export'          => true,
				'show_in_admin_bar'   => true,
				'exclude_from_search' => true,
				'supports'            => apply_filters( 'astra_advanced_hooks_supports', array( 'title', 'editor', 'elementor' ) ),
			);

			register_post_type( 'astra-advanced-hook', apply_filters( 'astra_advanced_hooks_post_type_args', $args ) );
		}

		/**
		 * Register the admin menu for Custom Layouts
		 *
		 * @since  1.2.1
		 *         Moved the menu under Appearance -> Custom Layouts
		 */
		public function register_admin_menu() {
			add_submenu_page(
				'themes.php',
				__( 'Custom Layouts', 'astra-addon' ),
				__( 'Custom Layouts', 'astra-addon' ),
				'edit_pages',
				'edit.php?post_type=astra-advanced-hook'
			);
		}

		/**
		 * Enqueues scripts and styles for the theme layout
		 * post type on the WordPress admin edit post screen.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function admin_enqueue_scripts() {

			global $pagenow;
			global $post;

			$screen = get_current_screen();

			if ( ( 'post-new.php' == $pagenow || 'post.php' == $pagenow ) && 'astra-advanced-hook' == $screen->post_type ) {
				// Styles.
				wp_enqueue_media();

				// Scripts.
				if ( SCRIPT_DEBUG ) {
					wp_enqueue_style( 'advanced-hook-admin-edit', ASTRA_EXT_ADVANCED_HOOKS_URL . 'assets/css/unminified/astra-advanced-hooks-admin-edit.css', null, ASTRA_EXT_VER );
					wp_enqueue_script( 'advanced-hook-admin-edit', ASTRA_EXT_ADVANCED_HOOKS_URL . 'assets/js/unminified/advanced-hooks.js', array( 'jquery', 'jquery-ui-tooltip' ), ASTRA_EXT_VER );
				} else {
					wp_enqueue_style( 'advanced-hook-admin-edit', ASTRA_EXT_ADVANCED_HOOKS_URL . 'assets/css/minified/astra-advanced-hooks-admin-edit.min.css', null, ASTRA_EXT_VER );
					wp_enqueue_script( 'advanced-hook-admin-edit', ASTRA_EXT_ADVANCED_HOOKS_URL . 'assets/js/minified/advanced-hooks.min.js', array( 'jquery', 'jquery-ui-tooltip' ), ASTRA_EXT_VER );
				}
			}
		}

		/**
		 * Add Update messages for any custom post type
		 *
		 * @param array $messages Array of default messages.
		 */
		function custom_post_type_post_update_messages( $messages ) {

			$custom_post_type = get_post_type( get_the_ID() );

			if ( 'astra-advanced-hook' == $custom_post_type ) {

				$obj                           = get_post_type_object( $custom_post_type );
				$singular_name                 = $obj->labels->singular_name;
				$messages[ $custom_post_type ] = array(
					0  => '', // Unused. Messages start at index 1.
					/* translators: %s: singular custom post type name */
					1  => sprintf( __( '%s updated.', 'astra-addon' ), $singular_name ),
					/* translators: %s: singular custom post type name */
					2  => sprintf( __( 'Custom %s updated.', 'astra-addon' ), $singular_name ),
					/* translators: %s: singular custom post type name */
					3  => sprintf( __( 'Custom %s deleted.', 'astra-addon' ), $singular_name ),
					/* translators: %s: singular custom post type name */
					4  => sprintf( __( '%s updated.', 'astra-addon' ), $singular_name ),
					/* translators: %1$s: singular custom post type name ,%2$s: date and time of the revision */
					5  => isset( $_GET['revision'] ) ? sprintf( __( '%1$s restored to revision from %2$s', 'astra-addon' ), $singular_name, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
					/* translators: %s: singular custom post type name */
					6  => sprintf( __( '%s published.', 'astra-addon' ), $singular_name ),
					/* translators: %s: singular custom post type name */
					7  => sprintf( __( '%s saved.', 'astra-addon' ), $singular_name ),
					/* translators: %s: singular custom post type name */
					8  => sprintf( __( '%s submitted.', 'astra-addon' ), $singular_name ),
					/* translators: %s: singular custom post type name */
					9  => sprintf( __( '%s scheduled for.', 'astra-addon' ), $singular_name ),
					/* translators: %s: singular custom post type name */
					10 => sprintf( __( '%s draft updated.', 'astra-addon' ), $singular_name ),
				);
			}

			return $messages;
		}

		/**
		 * Add page builder support to Advanced hook.
		 *
		 * @param array $value Array of post types.
		 */
		function bb_builder_compatibility( $value ) {

			$value[] = 'astra-advanced-hook';

			return $value;
		}
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Astra_Ext_Advanced_Hooks_Loader::get_instance();
