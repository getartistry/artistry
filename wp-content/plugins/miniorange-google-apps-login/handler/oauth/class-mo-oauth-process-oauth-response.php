<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 19-04-2018
 * Time: 02:33
 */
//TODO Implement Error Codes.

class Mo_Oauth_Process_Oauth_Response extends Base_Request_action implements IMo_Oauth_Get_Access_Token ,IMo_Oauth_Get_Resource_Owner{

	/**
	 * This function will be the first to get request data,
	 * This function will be used to process the request data before actual business logic.
	 * @return mixed
	 */
	function handle_request_data() {
		//ToDo move the validation part to validate request data.
		if ( Mo_GSuite_Utility::isBlank( $_REQUEST ) ) {
			return;
		}
		if ( strpos( $_SERVER['REQUEST_URI'], "/oauthcallback" ) !== false ) {
			if ( ! $this->validate_request_data( $_REQUEST ) ) {
				return;
			}
			$this->route_request_data( $_REQUEST );
		}
	}

	/**
	 * This fuction will be used to do the validation on request data.
	 * @return mixed
	 */
	function validate_request_data( $getData ) {
		if ( ! isset( $_REQUEST['code'] ) ) {
			if ( isset( $_GET['error_description'] ) ) {
				die( $_GET['error_description'] );
			} else if ( isset( $_GET['error'] ) ) {
				die( $_GET['error'] );
			}

			return false;
		}

		return true;
	}

	/**
	 * This function will be used to route the request data for business logic.
	 * @return mixed
	 */
	function route_request_data( $getData ) {
		$this_app_name = "";
		Mo_GSuite_Utility::checkSession();

		//As the value for the state is bookkeeping.Whatever you send you will get back.

		$this_app_name = base64_encode( $_GET['state'] );

		if ( Mo_GSuite_Utility::isBlank( $this_app_name ) ) {
			die( 'No Request Found For This App' );

			return;
		}

		$app_configured = get_mo_gsuite_option( 'mo_oauth_apps_list_test' );
		$app_name       = 'google';

		$access_token = $this->get_access_token( $app_name, $app_configured[ $app_name ] );

		$resource_owner_details= $this->get_resource_owner($access_token,$app_configured[$app_name]['resourceownerdetailsurl']);

		$resource_owner_email=isset($resource_owner_details['emails'])?$resource_owner_details['emails'][0]['value']:'';

		$resource_owner_displayname=isset($resource_owner_details['displayName'])?$resource_owner_details['displayName']:'';

		$resource_owner_firstname=isset($resource_owner_details['name']['givenName'])?$resource_owner_details['name']['givenName']:'';

		$resource_owner_lastname=isset($resource_owner_details['name']['familyName'])?$resource_owner_details['name']['familyName']:'';

		$this->login_or_register_user($resource_owner_email,$resource_owner_displayname,$resource_owner_firstname,$resource_owner_lastname);

	}

	/**
	 * Login the resource owner if already present with email address or create a user.
	 * @param $user_email
	 * @param $user_displayname
	 */
	function login_or_register_user($user_email,$user_displayname,$user_firstname,$user_lastname){

		$user=Mo_GSuite_Utility::isBlank(get_user_by('login',$user_email))?get_user_by( 'email', $user_email):get_user_by('login',$user_email);

		if(!Mo_GSuite_Utility::isBlank($user)){
			$user_id= $user->data->ID;
		}
		else{
			//create new user
			$random_password = wp_generate_password( 10, false );

			if(is_email($user_email))
				$user_id = wp_create_user( $user_email, $random_password, $user_email );
			else
				$user_id = wp_create_user( $user_email, $random_password);

			$user = get_user_by( 'login', $user_email);

			wp_update_user( array( 'ID' => $user_id, 'first_name' => $user_firstname ) );
			wp_update_user( array( 'ID' => $user_id, 'last_name' => $user_lastname ) );

			wp_update_user( array( 'ID' => $user_id, 'display_name' => $user_displayname ) );

		}

		if($user_id) {
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id );
			$user = get_user_by( 'ID', $user_id );
			do_action( 'wp_login', $user->user_login, $user );
			wp_redirect( home_url() );
			exit;
		}


	}

	/**
	 * Fetch the acess token.
	 * 1. Makes Api Call
	 * 2. Validates the acess token
	 * 3. return access token
	 * @param $app_name
	 * @param $app_details
	 */
	function get_access_token( $app_name, $app_details ) {
		$access_token_url = $app_details['accesstokenurl'];
		$grant_type       = 'authorization_code';//We do not use the refresh token flow.
		$client_id        = $app_details['clientid'];
		$client_secret    = $app_details['clientsecret'];
		$redirect_uri     = $app_details['redirecturi'];

		$api_response = $this->call_access_token_api( $access_token_url, $grant_type, $client_id, $client_secret, $_GET['code'], $redirect_uri );

		$api_response= json_decode($api_response,TRUE);

		if(!$this->validate_access_token($api_response))return;

		$access_token = $api_response["access_token"];
		return $access_token;
	}

	/**
	 * This will make the API call to get the access token from google.
	 * @param $tokenendpoint
	 * @param $grant_type
	 * @param $clientid
	 * @param $clientsecret
	 * @param $code
	 * @param $redirect_url
	 *
	 * @return mixed
	 */
	function call_access_token_api( $tokenendpoint, $grant_type, $clientid, $clientsecret, $code, $redirect_url ) {
		$ch = curl_init( $tokenendpoint );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_POST, true );

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

		curl_setopt( $ch, CURLOPT_POSTFIELDS, 'redirect_uri=' . $redirect_url  . '&grant_type=' . $grant_type . '&client_id=' . $clientid . '&client_secret=' . $clientsecret . '&code=' . $code );
		$content = curl_exec( $ch );
		return $content;
	}

	/**
	 * Validates the access token against API Call.
	 *
	 * @param $api_response
	 *
	 * @return bool
	 */
	function validate_access_token($api_response) {

		if(!isset($api_response['error'])&&!isset($api_response['error_description'])){
			if(!isset($api_response['access_token'])){
				die('ERROR CODE:- 001. Invalid response received from OAuth Provider. Contact your administrator for more details');
			}
			return true;
		}
		die("Error ".$api_response['error']. " Error Description ".$api_response['error_description']);
	}

	/** Get the resource owner
	 * @param $access_token
	 * @param $resource_owner_uri
	 *
	 * @return mixed|void
	 */
	function get_resource_owner($access_token,$resource_owner_uri) {

		$resource_owner_details=$this->call_resource_owner_api($access_token,$resource_owner_uri);
		$resource_owner_details=json_decode($resource_owner_details,TRUE);
		if(!$this->validate_resource_owner($resource_owner_details))return;
		return $resource_owner_details;

	}

	/**
	 * Call resource owner api
	 * @param $access_token
	 * @param $resource_owner_uri
	 *
	 * @return mixed
	 */
	function call_resource_owner_api($access_token,$resource_owner_uri){
		$ch = curl_init($resource_owner_uri);

		/*  GET /mycontent HTTP/1.1
			    Host: myservice.example.com
				Content-Type: application/x-www-form-urlencoded
				Authorization: Bearer ACCESS_TOKEN*/

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer '.$access_token
		));

		$content = curl_exec($ch);

		if(curl_error($ch)){
			echo "<b>Response : </b><br>";print_r($content);echo "<br><br>";
			exit( curl_error($ch) );
		}

		return $content;
	}

	/**
	 * Validates the resource owner details.
	 * @param $api_response
	 *
	 * @return bool
	 */
	function validate_resource_owner($api_response) {
		if(isset($api_response['error'])){
			if(isset($api_response['error_description'])){
				die("Error " . $api_response['error'] . " Error Description " . $api_response['error_description']);
				exit;	
			}else{
				die("Error " . $api_response['error']);
				exit;
			}
		}
		return true;
	}
}

new Mo_Oauth_Process_Oauth_Response;