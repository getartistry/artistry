<?php

// Load all modules
$et_builder_module_files = glob( ET_BUILDER_DIR . 'module/*.php' );

if ( ! $et_builder_module_files ) {
	return;
}

/**
 * Fires before the builder's module classes are loaded.
 *
 * @since 3.0.77
 */
do_action( 'et_builder_modules_load' );

foreach ( $et_builder_module_files as $module_file ) {
	require_once( $module_file );
}

/**
 * Fires after the builder's module classes are loaded.
 *
 * @since 3.0.77
 */
do_action( 'et_builder_modules_loaded' );
