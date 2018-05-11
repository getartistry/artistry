<?php
/**
 * Cp_V2_Post_Type.
 *
 * @package ConvertPro
 */

if ( ! class_exists( 'Cp_V2_Post_Type' ) ) {

	/**
	 * Class bsf menu.
	 */
	class Cp_V2_Post_Type {

		/**
		 * The unique instance of the plugin.
		 *
		 * @var view_actions
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
		 * Class bsf menu.
		 */
		function __construct() {

			add_action( 'init', array( $this, 'post_type_setup' ), 15 );

		}

		/**
		 * Setup new post type "cp_popups"
		 *
		 * @since 0.0.1
		 */
		function post_type_setup() {

			$style_name = '';

			if ( isset( $_GET['post'] ) && '' !== $_GET['post'] ) {
				$style_id   = (int) $_GET['post'];
				$style_name = get_the_title( $style_id );
			}

			register_post_type(
				CP_CUSTOM_POST_TYPE, array(
					/* translators: %s CPRO_BRANDING_NAME */
					'label'               => sprintf( __( '%s Popups', 'convertpro' ), CPRO_BRANDING_NAME ),
					'labels'              => array(
						/* translators: %s CPRO_BRANDING_NAME */
						'name'               => sprintf( __( '%s Popups', 'convertpro' ), CPRO_BRANDING_NAME ),
						/* translators: %s CPRO_BRANDING_NAME */
						'menu_name'          => sprintf( esc_attr( '%s', 'convertpro' ), CPRO_BRANDING_NAME ),
						'singular_name'      => __( 'Popup', 'convertpro' ),
						'add_new'            => __( 'Add New Popup', 'convertpro' ),
						'all_items'          => __( 'Popups', 'convertpro' ),
						'add_new_item'       => __( 'Add New Popup', 'convertpro' ),
						'edit_item'          => $style_name . ' - Edit Call-to-action',
						'new_item'           => __( 'New Popup', 'convertpro' ),
						'view_item'          => __( 'View Popup', 'convertpro' ),
						'search_items'       => __( 'Search Popups', 'convertpro' ),
						'not_found'          => __( 'No popups found', 'convertpro' ),
						'not_found_in_trash' => __( 'No popups found in Trash', 'convertpro' ),
					),
					'hierarchical'        => false,
					'public'              => false,
					'exclude_from_search' => true,
					'publicly_queryable'  => true,
					'show_in_nav_menus'   => false,
					'show_ui'             => true,
					'capability_type'     => 'cp_popup',
					'show_in_admin_bar'   => false,
					'show_in_menu'        => false,
					'supports'            => array( 'title', 'editor', 'revisions' ),
				)
			);

			/* Register campaign taxonomy */

			register_taxonomy(
				CP_CAMPAIGN_TAXONOMY,
				CP_CUSTOM_POST_TYPE,
				array(
					'label'        => __( 'Campaign', 'convertpro' ),
					'rewrite'      => false,
					'public'       => false,
					'show_in_menu' => false,
					'show_in_rest' => true,
					'capabilities' => array(
						'manage_terms' => 'manage_cp_popup_terms',
						'edit_terms'   => 'edit_cp_popup_terms',
						'delete_terms' => 'delete_cp_popup_terms',
						'assign_terms' => 'assign_cp_popup_terms',
					),
				)
			);

			register_taxonomy_for_object_type( CP_CAMPAIGN_TAXONOMY, CP_CUSTOM_POST_TYPE );

			/* Register campaign category */

			$labels = array(
				'all_items'    => __( 'All Connections', 'convertpro' ),
				'add_new_item' => __( 'Add New Connection', 'convertpro' ),
				'not_found'    => __( 'No connections found', 'convertpro' ),
			);

			register_taxonomy(
				CP_CONNECTION_TAXONOMY,
				CP_CUSTOM_POST_TYPE,
				array(
					'label'             => __( 'API Connections', 'convertpro' ),
					'labels'            => $labels,
					'rewrite'           => false,
					'public'            => false,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_menu'      => false,
					'show_in_rest'      => true,
				)
			);

			register_taxonomy_for_object_type( CP_CONNECTION_TAXONOMY, CP_CUSTOM_POST_TYPE );
		}
	}

	$cp_v2_post_type = Cp_V2_Post_Type::get_instance();
}
