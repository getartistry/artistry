<?php

/**
 * Divi Children Engine
 *
 * Version: 3.0.6
 * 
 * Copyright (C) 2014 - 2017, Luis Alejandre - luis@divi4u.com
 * http://divi4u.com
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */


define( 'DCE_VERSION', '3.0.6' );

define( 'DCE_PATH', dirname( __FILE__ ) );
define( 'DCE_URL', get_stylesheet_directory_uri() . '/divi-children-engine/' );
define( 'DCE_IMAGES_URL', DCE_URL . 'dce-customizer/images/' );

$dce_files = array(
	'basic-functions' 		=> 'functions',
	'customizer-setup'		=> 'dce-customizer',
	'dce-functions' 		=> 'functions',
	'custom-functions'		=> 'functions',
	'divi-mod-functions'	=> 'functions',
	'divi-fixes'			=> 'functions',
	'custom-selectors'		=> 'custom-selectors',
	'dce-customizer'		=> 'dce-customizer',
);

foreach ( $dce_files as $file => $folder ) {
	require_once( DCE_PATH . '/' . $folder . '/' . $file . '.php' );
}

?>
