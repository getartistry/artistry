<?php

add_action( 'case27_register_post_types', function() {
	// Listing Types.
	$labels = [
		'name'               => _x( 'Listing Types', 'post type general name', 'my-listing' ),
		'singular_name'      => _x( 'Listing Type', 'post type singular name', 'my-listing' ),
		'menu_name'          => _x( 'Listing Types', 'admin menu', 'my-listing' ),
		'name_admin_bar'     => _x( 'Listing Type', 'add new on admin bar', 'my-listing' ),
		'add_new'            => _x( 'Add New', 'Listing Type', 'my-listing' ),
		'add_new_item'       => __( 'Add New Listing Type', 'my-listing' ),
		'new_item'           => __( 'New Listing Type', 'my-listing' ),
		'edit_item'          => __( 'Edit Listing Type', 'my-listing' ),
		'view_item'          => __( 'View Listing Type', 'my-listing' ),
		'all_items'          => __( 'Listing Types', 'my-listing' ),
		'search_items'       => __( 'Search Listing Types', 'my-listing' ),
		'parent_item_colon'  => __( 'Parent Listing Types:', 'my-listing' ),
		'not_found'          => __( 'No Listing Types found.', 'my-listing' ),
		'not_found_in_trash' => __( 'No Listing Types found in Trash.', 'my-listing' )
	];

	$args = [
		'labels'             => $labels,
		'description'        => __( 'Create custom listing types to enhance the directory theme experience for listings of different purposes.', 'my-listing' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'case27/listing-tools.php',
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'case27_listing_type' ],
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 150,
		'supports'           => [ 'title', 'thumbnail' ],
	];

	register_post_type( 'case27_listing_type', $args );

	// Reports.
	$labels = [
		'name'               => _x( 'Reports', 'post type general name', 'my-listing' ),
		'singular_name'      => _x( 'Report', 'post type singular name', 'my-listing' ),
		'menu_name'          => _x( 'Reports', 'admin menu', 'my-listing' ),
		'name_admin_bar'     => _x( 'Report', 'add new on admin bar', 'my-listing' ),
		'add_new'            => _x( 'Add New', 'Report', 'my-listing' ),
		'add_new_item'       => __( 'Add New Report', 'my-listing' ),
		'new_item'           => __( 'New Report', 'my-listing' ),
		'edit_item'          => __( 'Edit Report', 'my-listing' ),
		'view_item'          => __( 'View Report', 'my-listing' ),
		'all_items'          => __( 'Reports', 'my-listing' ),
		'search_items'       => __( 'Search Reports', 'my-listing' ),
		'parent_item_colon'  => __( 'Parent Report:', 'my-listing' ),
		'not_found'          => __( 'No Reports found.', 'my-listing' ),
		'not_found_in_trash' => __( 'No Reports found in Trash.', 'my-listing' )
	];

	$args = [
		'labels'             => $labels,
		'description'        => __( 'Allow users to report listings.', 'my-listing' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'case27/listing-tools.php',
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'case27_report' ],
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 180,
		'supports'           => [ 'title' ],
	];

	register_post_type( 'case27_report', $args );
});