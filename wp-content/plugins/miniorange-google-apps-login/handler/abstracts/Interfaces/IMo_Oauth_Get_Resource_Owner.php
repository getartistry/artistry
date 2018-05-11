<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 19-04-2018
 * Time: 10:48
 */

Interface IMo_Oauth_Get_Resource_Owner{
	/**
	 * Second Call for the oauth server.Send Out access token and send get the resource owner details.
	 * @param $access_token
	 * @param $request_owner_uri
	 *
	 * @return mixed
	 */
	function get_resource_owner($access_token,$request_owner_uri);

	/**
	 * Validates the access token received for errors. We can stop and send out related exceptions.
	 * @param $resource_owner_details
	 *
	 * @return mixed
	 */
	function validate_resource_owner($resource_owner_details);
}