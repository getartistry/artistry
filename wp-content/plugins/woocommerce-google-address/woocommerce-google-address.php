<?php

/**
 * Plugin Name: Google Address Autocomplete for WooCommerce
 * Description: Helps the user to select a valid address during checkout based on Google Place search
 * Version: 2.3.4
 * Author: MB Création
 * Text Domain: woogoogad
 * Domain Path: /languages/
 * Author URI: http://www.mbcreation.com
 * License: http://codecanyon.net/licenses/regular_extended
 * Plugin URI: http://codecanyon.net/item/google-address-autocomplete-for-woocommerce/7208221
 */

// Required Classes
require_once('class.front.php');

// Loader
function WooCommerce_Google_Address_Loader()
{
	if(class_exists('Woocommerce')) {		
		load_plugin_textdomain('woogoogad', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

		if(get_option('mbc_woogoogad_api_key')!='')
			$GLOBALS['WooCommerce_Google_Address_Plugin_Front'] = new WooCommerce_Google_Address_Plugin_Front();
	}
	
} //WooCommerce_Google_Address_Loader

add_action( 'plugins_loaded' , 'WooCommerce_Google_Address_Loader');

// Auto updatder
add_action( 'admin_init', 'mbc_woogoogad_autoupdate' ); 
function mbc_woogoogad_autoupdate()
{
	if(!class_exists('WPMBC_AutoUpdate'))
		require_once ( dirname(__FILE__).'/wp_autoupdate.php' );

	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_current_version = $plugin_data['Version'];
	$plugin_remote_path = 'http://www.mbcreation.com/plugin/woocommerce-google-address/';	
	$plugin_slug = plugin_basename( __FILE__ );
	new WPMBC_AutoUpdate ( $plugin_current_version, $plugin_remote_path, $plugin_slug );	
}


// Api key required

add_filter( 'woocommerce_general_settings', 'mbc_woogoogad_shop_option', 9999 );


function mbc_woogoogad_shop_option($settings){


	$settings[] = array( 'name' => 'Google Address Autocomplete', 'type' => 'title', 'desc' => '', 'id' => 'mbc_woogoogad_shop_option_settings' );
					
	$settings[] = array(
		'title'   => __( 'Google Address Autocomplete API key', 'woogoogad' ),
		'desc'    => __( 'API key (https://developers.google.com/maps/documentation/javascript/get-api-key#key)', 'woogoogad' ),
		'id'      => 'mbc_woogoogad_api_key',
		'default' => '',
		'type'    => 'text',
		
	);

	$settings[] = array( 'type' => 'sectionend', 'id' => 'mbc_woogoogad_shop_option_settings');

	return $settings;


}

add_filter('woogoogad_gg_api_other_parameters','mbc_woogoogad_load_api_key', 9999 );


function mbc_woogoogad_load_api_key($param){

	if( get_option('mbc_woogoogad_api_key')!='' ) :

		$param = $param.'&key='.get_option('mbc_woogoogad_api_key');

	endif;

	return $param;

}