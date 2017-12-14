<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://artistry.ink/tony
 * @since      1.0.0
 *
 * @package    Keep_Logged_In
 * @subpackage Keep_Logged_In/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Keep_Logged_In
 * @subpackage Keep_Logged_In/includes
 * @author     Anthony O Connell <tony@artistry.ink>
 */
class Keep_Logged_In_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'keep-logged-in',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
