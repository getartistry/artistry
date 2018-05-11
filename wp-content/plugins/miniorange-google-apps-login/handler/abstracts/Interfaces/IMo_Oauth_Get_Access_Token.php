<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 19-04-2018
 * Time: 09:57
 */

Interface IMo_Oauth_Get_Access_Token{
	/**
	 * Get the access token from Oauth Server
	 * @param $app_name
	 * @param $app_details
	 *
	 * @return mixed
	 */
	function get_access_token($app_name,$app_details);

	/**
	 * Validates the access token came from Oauth-Server.
	 * @param $api_response
	 *
	 * @return mixed
	 */
	function validate_access_token($api_response);

}