<?php

/**
 * Fired during plugin activation
 *
 * @link       codepixelzmedia.com.np
 * @since      1.0.0
 *
 * @package    Woodivi
 * @subpackage Woodivi/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woodivi
 * @subpackage Woodivi/includes
 * @author     CodePixelzMedia <wordpress.enthusiast@gmail.com>
 */
class Woodivi_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		/**
		* Check if WooCommerce and
		**/
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) || ! is_plugin_active( 'divi-builder/divi-builder.php' ) ) {
			// Deactivate the plugin
			deactivate_plugins(__FILE__);
			// Throw an error in the wordpress admin console
			$error_message = __('This plugin requires <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a> &amp; <a href="http://www.elegantthemes.com/">Divi Builder</a> plugins to be active!', 'woodivi');
			die($error_message);
		}

	}

}
