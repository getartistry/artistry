<?php

/**
 * Basic child theme functions
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */



/**
 * Enqueue parent theme styles.
 * Do not remove this, or your child theme will not work unless you include a @import rule in your child stylesheet.
 */
function dce_enqueue_parent_styles() {
    wp_enqueue_style( 'divi-parent', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'dce_enqueue_parent_styles' );


/**
 * Compatibility with older versions that enqueued styles directly from the child theme´s functions.php file.
 */
function remove_dce_enqueue_styles() {
	if ( has_action( 'wp_enqueue_scripts', 'dce_enqueue_styles' ) ) {
		remove_action( 'wp_enqueue_scripts', 'dce_enqueue_styles' );
	}
}
add_action( 'after_setup_theme', 'remove_dce_enqueue_styles' );
