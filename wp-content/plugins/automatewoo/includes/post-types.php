<?php

namespace AutomateWoo;

/**
 * @class Post_Types
 */
class Post_Types {


	static function init() {
		add_action( 'init', [ __CLASS__, 'register_post_types' ], 5 );
		add_action( 'init', [ __CLASS__, 'register_post_status' ] );
	}


	static function register_post_types() {
		register_post_type( 'aw_workflow',
			apply_filters( 'automatewoo_register_post_type_aw_workflow', [
					'labels'              => [
						'name'               => __( 'Workflows', 'automatewoo' ),
						'singular_name'      => __( 'Workflow', 'automatewoo' ),
						'menu_name'          => _x( 'Workflows', 'Admin menu name', 'automatewoo' ),
						'add_new'            => __( 'Add Workflow', 'automatewoo' ),
						'add_new_item'       => __( 'Add New Workflow', 'automatewoo' ),
						'edit'               => __( 'Edit', 'automatewoo' ),
						'edit_item'          => __( 'Edit Workflow', 'automatewoo' ),
						'new_item'           => __( 'New Workflow', 'automatewoo' ),
						'view'               => __( 'View Workflow', 'automatewoo' ),
						'view_item'          => __( 'View Workflow', 'automatewoo' ),
						'search_items'       => __( 'Search Workflows', 'automatewoo' ),
						'not_found'          => __( 'No Workflows found', 'automatewoo' ),
						'not_found_in_trash' => __( 'No Workflows found in trash', 'automatewoo' ),
					],
					'public' => false,
					'show_ui'             => true,
					'capability_type'     => 'shop_order',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'show_in_menu'        => false,
					'hierarchical'        => false,
					'show_in_nav_menus'   => false,
					'rewrite'             => false,
					'query_var'           => false,
					'supports'            => [ 'title', 'page-attributes' ],
					'has_archive'         => false,
				]
			)
		);

		do_action('automatewoo_after_register_post_types');
	}


	/**
	 *
	 */
	static function register_post_status() {
		register_post_status( 'aw-disabled', [
			'label' => __( 'Disabled', 'automatewoo' ),
			'public' => true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop( 'Disabled <span class="count">(%s)</span>', 'Disabled <span class="count">(%s)</span>', 'automatewoo' ),
		]);
	}

}


