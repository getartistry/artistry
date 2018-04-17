<?php

add_action( 'case27_shortcodes_init', function( $shortcodes ) {
	$shortcodes->register([
		CASE27_PLUGIN_DIR . '/shortcodes/icon.php',
		CASE27_PLUGIN_DIR . '/shortcodes/button.php',
		CASE27_PLUGIN_DIR . '/shortcodes/format.php',
		CASE27_PLUGIN_DIR . '/shortcodes/search-form.php',
		CASE27_PLUGIN_DIR . '/shortcodes/categories.php',
		CASE27_PLUGIN_DIR . '/shortcodes/quick-search.php',
		]);
});