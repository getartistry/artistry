<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'jet_menu_avada_styles', 0 );
add_filter( 'wp_nav_menu_items', 'jet_menu_avada_fix_header_search', 999, 2 );

/**
 * Make header search in avada theme compatible with JetMenu
 * @return [type] [description]
 */
function jet_menu_avada_fix_header_search( $items, $args ) {
	if ( ! isset( $args->menu_class ) || 'jet-menu' !== $args->menu_class ) {
		return $items;
	}

	$items = str_replace(
		array(
			'fusion-custom-menu-item fusion-main-menu-search',
			'fusion-main-menu-icon',
		),
		array(
			'fusion-custom-menu-item fusion-main-menu-search jet-menu-item jet-simple-menu-item jet-regular-item jet-responsive-menu-item',
			'fusion-main-menu-icon top-level-link',
		),
		$items
	);

	return $items;

}

/**
 * Enqueue avada compatibility styles
 *
 * @return void
 */
function jet_menu_avada_styles() {
	wp_enqueue_style(
		'jet-menu-avada',
		jet_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		jet_menu()->get_version()
	);
}
