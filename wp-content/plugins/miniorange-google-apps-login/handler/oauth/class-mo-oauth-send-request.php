<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 18-04-2018
 * Time: 18:59
 */

class Mo_Oauth_Send_Request extends Base_Request_action implements IMo_Oauth_Request{

	/**
	 * This function will be the first to get request data,
	 * This function will be used to process the request data before actual business logic.
	 * @return mixed
	 */

	function handle_request_data() {
		if(Mo_GSuite_Utility::isBlank($_REQUEST)||!isset($_REQUEST['option']))return;
		if(!$this->validate_request_data($_REQUEST)) return;
		$this->route_request_data($_REQUEST);
	}

	/**
	 * This function will be used to route the request data for business logic.
	 * @return mixed
	 */
	function route_request_data($getData) {
		switch($getData['option']){
			case 'oauthredirect':
				$this->send_oauth_request($getData);
				break;
		}
	}

	/**
	 * This function will send out or hit the Oauth Url.
	 * @param $getData
	 */
	function send_oauth_request($getData){
		$app_name=$getData['app_name'];

		$app_configured= get_mo_gsuite_option('mo_oauth_apps_list_test');
		$oauth_request_uri= $this->build_oauth_request($app_configured);

		if(Mo_GSuite_Utility::isBlank($oauth_request_uri)) return;

		Mo_GSuite_Utility::checkSession();
		$_SESSION['oauth2state'] = base64_encode($app_name);
		$_SESSION['appname'] = $app_name;
		header('Location: ' . $oauth_request_uri);
		exit;
	}

	/**
	 * This fuction will be used to do the validation on request data.
	 * @return mixed
	 */
	function validate_request_data($getData) {
		return true;
	}

	/**
	 * This function will build the request URI to hit.
	 * @see https://developers.google.com/actions/identity/oauth2-code-flow
	 * Handle User Sign in section.
	 *
	 *
	 */
	function build_oauth_request($getData) {
		$uri_format= '##AUTHRISATION_URL_STRING##client_id=##CLIENT_ID_STRING##&redirect_uri=##REDIRECT_URI_STRING##&state=##STATE_STRING##&scope=##REQUESTED_SCOPE_STRING##&response_type=code';//See the link in the phpDocs.

		//ToDo make the app_name generalised.

		//The breakup of the URL is done intentionally. for the debugging. Do not remove this.
		$app_name='google';
		$authrisation_url=$getData[$app_name]['authorizeurl'];
		if(!strpos($authrisation_url,'?'))
			$authrisation_url.='?';

		// Authrization URL Replacement.
		$uri_format=str_replace('##AUTHRISATION_URL_STRING##',trim($authrisation_url),$uri_format);

		//Client Id Replacement.
		$client_id=$getData[$app_name]['clientid'];
		$uri_format=str_replace('##CLIENT_ID_STRING##',trim($client_id),$uri_format);

		//redirecturi Replacement.
		$redirect_uri=$getData[$app_name]['redirecturi'];
		$uri_format=str_replace('##REDIRECT_URI_STRING##',trim($redirect_uri),$uri_format);

		//State String Replacement
		$state=base64_encode($app_name);
		$uri_format=str_replace('##STATE_STRING##',trim($state),$uri_format);

		//Scope string Replacement
		$scope=$getData[$app_name]['scope'];
		$uri_format=str_replace('##REQUESTED_SCOPE_STRING##',trim($scope),$uri_format);

		return $uri_format;
	}
}
new Mo_Oauth_Send_Request;