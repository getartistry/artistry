<?php
/*
Plugin Name: Simply Login Register
Plugin URI: https://wordpress.org/plugins/simply-login-regiser/
Description: A simple wordpress plugin for create custom login and register form by shortcode. 
Version: 2.0
Author: Anshul Labs
Author URI: http://anshullabs.xyz
License: GPL2
*/
/*
Copyright 2012  Anshul Labs (email : hello@anshullabs.xyz)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


define('SLR_VERSION', '2.0');
define('SLR_FILE', basename(__FILE__));
define('SLR_NAME', str_replace('.php', '', SLR_FILE));
define('SLR_PATH', plugin_dir_path(__FILE__));
define('SLR_URL', plugin_dir_url(__FILE__));

if(!class_exists('SLR_Plugin'))
{
	class SLR_Plugin
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$SLR_Plugin_Settings = new SLR_Plugin_Settings();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=slr_plugin_setting">Settings</a>';
			array_unshift($links, $settings_link);
			$settings_link1 = '<a href="http://www.paypal.me/anshulgangrade" rel="nofollow">Donate</a>';
			array_unshift($links, $settings_link1);
			return $links;
		}

	} // END class SLR_Plugin
} // END if(!class_exists('SLR_Plugin'))

if(class_exists('SLR_Plugin')){

	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('SLR_Plugin', 'activate'));
	register_deactivation_hook(__FILE__, array('SLR_Plugin', 'deactivate'));

	// instantiate the plugin class
	$slr_plugin = new SLR_Plugin();
}