<?php

/**
 * Incudes functionality for license management, such as activation
 * and deactivation.
 */
class USIN_Remote_License{

	protected static $remote_url = 'http://usersinsights.com/';

	/**
	 * Actuvates a license via a remote request to the UsersInsights site.
	 * @param  string $license_key the license key
	 * @param  string $module_id   the module id
	 * @return array              the result of the request for activation
	 */
	public static function activate($license_key, $module_id){
		$args = array( 
			'usinr_action'=> 'activate_license', 
			'license' 	=> $license_key, 
			'usin_key' => $module_id, // the name of our product in EDD
			'url'       => home_url()
		);

		return self::send_request($args);
	}

	/**
	 * Deactivates a license via a remote request to the UsersInsights site.
	 * @param  string $license_key the license key
	 * @param  string $module_id   the module id
	 * @return array              the result of the request for deactivation
	 */
	public static function deactivate($license_key, $module_id){
		$args = array( 
			'usinr_action'=> 'deactivate_license', 
			'license' 	=> $license_key, 
			'usin_key' => $module_id, // the name of our product in EDD
			'url'       => home_url()
		);

		return self::send_request($args);
	}

	
	public static function load_status($license_key, $module_id){
		$args = array( 
			'usinr_action'=> 'check_license', 
			'license' 	=> $license_key, 
			'usin_key' => $module_id, // the name of our product in EDD
			'url'       => home_url()
		);

		return self::send_request($args);
	}


	protected static function send_request($args){
		// Call the custom API.
		$response = wp_remote_get( 
			add_query_arg( $args, self::$remote_url ), 
			array( 'timeout' => 15, 'sslverify' => false ) 
		);

		if ( is_wp_error( $response ) ){
			return new WP_Error('request_failed', __('Remote HTTP request failed. Please try again later.', 'usin'));
		}

		return json_decode( wp_remote_retrieve_body( $response ) );
	}

}