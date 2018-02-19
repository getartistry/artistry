<?php

/**
 * Divi Children Engine Customizer control
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


/**
 * DCE settings initialization
 */
 
function dce_settings_init() {

	if ( ! get_theme_mod( 'dce_settings_init' ) ) {
	
		$init_settings = array(
			'dce_locked'			=> 0,
			'dce_css_output_source'	=> 'customize',
		);

		foreach ( $init_settings as $setting => $value ) {
			$mod_value = get_theme_mod( $setting );
			if ( ! isset( $mod_value ) ) {
				set_theme_mod( $setting, $value );
			}
		}
		
		set_theme_mod( 'dce_settings_init', 1 );
		
	}

}
add_action( 'after_setup_theme', 'dce_settings_init' );


/**
 * Load all the necessary Divi Children Engine Customizer files
 */

if ( is_user_logged_in() ) {
	require_once( DCE_PATH . '/dce-customizer/dce-kirki/dce-kirki.php' );
	require_once( DCE_PATH . '/dce-customizer/panels.php' );
	require_once( DCE_PATH . '/dce-customizer/sections.php' );
	require_once( DCE_PATH . '/dce-customizer/settings.php' );
}


/**
 * Allow the Divi Children Engine Customizer output
 */

require_once( DCE_PATH . '/dce-customizer/output.php' );


/**
 * Enqueue JavaScript file used for Customizer preview of some specific settings
 */

function dce_customizer_live_preview() {
	wp_enqueue_script( 'dce-customizer-preview', DCE_URL . 'dce-customizer/js/dce-preview.js', array( 'jquery', 'customize-preview' ), true );
}
add_action( 'customize_preview_init', 'dce_customizer_live_preview' );


/**
 * Load Divi Children Engine custom scripts, if any
 */

// require_once( DCE_PATH . '/dce-customizer/scripts.php' );


/**
 * Update theme settings, if needed
 */

// require_once( DCE_PATH . '/dce-customizer/updater.php' );


