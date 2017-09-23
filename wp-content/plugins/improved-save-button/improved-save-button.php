<?php
/*
Plugin Name: Improved Save Button
Description: Adds a new "Save" button to the Post Edit screen that saves the post and immediately takes you to your next action: the previous page, the next/previous post, the posts list, the post's frontend, etc.
Author: Label Blanc
Version: 1.2.1
Author URI: http://www.labelblanc.ca
Domain Path: /
Text Domain: improved-save-button
*/

/**
 * Copyright 2017 Label Blanc (http://www.labelblanc.ca/)
 *
 * This file is part of the "Improved Save Button"
 * Wordpress plugin.
 *
 * The "Improved Save Button" Wordpress plugin
 * is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * All the PHP files of the plugin
 * @var array
 */
$lib_files_to_include = array(
	'class-lb-save-and-then-utils.php',
	'class-lb-save-and-then-settings.php',
	'class-lb-save-and-then-post-edit.php',
	'class-lb-save-and-then-post-save.php',
	'class-lb-save-and-then-messages.php',
	'class-lb-save-and-then-actions.php',
	'class-lb-save-and-then-action.php',
);

// Include all the PHP files of the plugin
foreach ( $lib_files_to_include as $file_name ) {
	require_once( plugin_dir_path( __FILE__ ) . 'lib' . DIRECTORY_SEPARATOR . $file_name );
}

/**
 * PHP files of the actions that come with the plugin
 * @var array
 */
$actions_files_to_include = array(
	'class-lb-save-and-then-action-new.php',
	'class-lb-save-and-then-action-list.php',
	'class-lb-save-and-then-action-view.php',
	'class-lb-save-and-then-action-view-popup.php',
	'class-lb-save-and-then-action-next.php',
	'class-lb-save-and-then-action-previous.php',
	'class-lb-save-and-then-action-duplicate.php',
	'class-lb-save-and-then-action-return.php',
);

// Include all the actions php files
foreach ( $actions_files_to_include as $file_name ) {
	require_once( plugin_dir_path( __FILE__ ) . 'actions' . DIRECTORY_SEPARATOR . $file_name );
}

if( !class_exists( 'LB_Save_And_Then' ) ) {

/**
 * Main class. Mainly calls the setup function of other classes and
 * define the list of 'actions'.
 */
class LB_Save_And_Then {

	/**
	 * Main entry point of the plugin. Calls the setup function
	 * of the other classes.
	 */
	static function setup() {
		LB_Save_And_Then_Settings::setup();
		LB_Save_And_Then_Post_Edit::setup();
		LB_Save_And_Then_Post_Save::setup();
		LB_Save_And_Then_Messages::setup();
		LB_Save_And_Then_Actions::setup();

		if( self::requires_language_loading() ) {
			// Priority 1, because the settings page is also on
			// admin_init and uses translations
			add_action( 'admin_init', array( get_called_class(), 'load_languages' ), 1 );
		}

		add_action( 'lbsat_load_actions', array( get_called_class(), 'load_default_actions' ) );
	}

	/**
	 * Returns the localized name of the plugin
	 * @return string
	 */
	static function get_localized_name() {
		$plugin_data = get_plugin_data( __FILE__, false, true );
		return $plugin_data['Name'];
	}

	/**
	 * Called by the lbsat_load_actions filter. Loads all the
	 * actions that come by default with the plugin.
	 */
	static function load_default_actions( $actions ) {
		$default_actions_classes = array(
			'LB_Save_And_Then_Action_New',
			'LB_Save_And_Then_Action_Duplicate',
			'LB_Save_And_Then_Action_List',
			'LB_Save_And_Then_Action_Return',
			'LB_Save_And_Then_Action_Next',
			'LB_Save_And_Then_Action_Previous',
			'LB_Save_And_Then_Action_View',
			'LB_Save_And_Then_Action_View_Popup',
		);

		foreach ( $default_actions_classes as $class_name ) {
			$actions[] = new $class_name();
		}

		return $actions;
	}

	/**
	 * Loads the language file for the admin. Must be called in the
	 * 'admin_init' hook, since it uses get_plugin_data() and this
	 * function is loaded once all admin files are included.
	 */
	static function load_languages() {
		$plugin_data = get_plugin_data( __FILE__, false, true );
		$path = dirname( LB_Save_And_Then_Utils::plugin_main_file_basename() );
		$path .= $plugin_data['DomainPath'];
		load_plugin_textdomain( $plugin_data['TextDomain'], false, $path );
	}

	/**
	 * Returns the full path of the plugin's main file (this file).
	 * Used in the utils
	 *
	 * @return string
	 */
	static function get_main_file_path() {
		return __FILE__;
	}

	/**
	 * Returns true if this Wordpress version requires loading
	 * of language files. It is not required since version 4.6
	 *
	 * @return boolean
	 */
	static function requires_language_loading() {
		global $wp_version;

		if( ! isset( $wp_version ) ) {
			return true;
		}

		list( $version ) = explode( '-', $wp_version );

		if( version_compare( $version, '4.6', '>=') ) {
			return false;
		}

		return true;
	}

} // end class

} // end if( class_exists() )

LB_Save_And_Then::setup();
