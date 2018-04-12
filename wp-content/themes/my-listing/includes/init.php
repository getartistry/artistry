<?php

/*
 * Configure theme textdomain, supported features, nav menus, etc.
 */
add_action( 'after_setup_theme', function() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Add support for the WooCommerce plugin.
	add_theme_support( 'woocommerce' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// WP Job Manager templates support.
	add_theme_support( 'job-manager-templates' );

	// Set content width
	if ( ! isset( $content_width ) ) $content_width = 550;

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus([
		'primary' => esc_html__( 'Primary Menu', 'my-listing' ),
		'footer' => esc_html__( 'Footer Menu', 'my-listing' ),
	]);

	// Allow shortcodes in menu item labels.
	add_filter( 'wp_nav_menu_items', 'do_shortcode' );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	]);

	add_theme_support( 'custom-background', [
		'default-color' => '#fafafa',
	]);
});


/*
 * Register theme sidebars.
 */
add_action( 'widgets_init', function() {
	register_sidebar([
		'name'          => __( 'Footer', 'my-listing' ),
		'id'            => 'footer',
		'before_widget' => '<div class="col-md-4 col-sm-6 col-xs-12 c_widget woocommerce reveal">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="c_widget_title"><h5>',
		'after_title'   => '</h5></div>',
		]);

	register_sidebar([
		'name'          => __( 'Sidebar', 'my-listing' ),
		'id'            => 'sidebar',
		'before_widget' => '<div class="element c_widget woocommerce reveal">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="pf-head"><div class="title-style-1"><h5>',
		'after_title'   => '</h5></div></div>',
		]);

	do_action( 'case27_widgets_init' );
});


add_action( 'init', function() {
	do_action( 'case27_register_post_types' );
	do_action( 'case27_register_taxonomies' );
});
