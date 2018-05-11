<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 12-02-2018
 * Time: 12:26
 */

class Mo_Oauth_App_Configuration {

	private $scope;
	private $client_id;
	private $client_secret;
	private $client_appname;
	private $existing_applist = array();
	private $new_app;

	private $supported_app_list = array( "facebook", "google", "windows", "eveonline" );


	function __construct() {
		$this->initialize_data();
	}

	function initialize_data() {

		if(!isset($_POST['option']))return;

		if (  Mo_GSuite_Utility::isBlank( $_POST['mo_oauth_client_secret'] ) || Mo_GSuite_Utility::isBlank( $_POST['mo_oauth_client_id'] ) ) {
			do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'OAUTH_CUSTOMIZATION_SETTINGS_SAVED' ), 'SUCCESS' );
			return;
		}

		$this->scope          = stripslashes( sanitize_text_field( $_POST['mo_oauth_scope'] ) );
		$this->client_id      = stripslashes( sanitize_text_field( $_POST['mo_oauth_client_id'] ) );
		$this->client_secret  = stripslashes( sanitize_text_field( $_POST['mo_oauth_client_secret'] ) );
		$this->client_appname = stripslashes( sanitize_text_field( $_POST['mo_oauth_app_name'] ) );

		$this->existing_applist = get_mo_gsuite_option( 'mo_oauth_apps_list_test' );
		$this->new_app = $this->app_already_exists();
		$this->add_app();
	}

	function app_already_exists() {

		if ( Mo_GSuite_Utility::isBlank( $this->existing_applist ) ) {
			return;
		}
		if ( array_key_exists( $this->client_appname, $this->existing_applist ) ) {
			return $this->existing_applist[ $this->client_appname ];
		}
	}

	function add_app() {

		if ( ! Mo_GSuite_Utility::isBlank( $this->new_app ) ) {
			if ( MO_IS_FREE_PLUGIN && sizeof( $this->existing_applist ) > 0 && Mo_GSuite_Utility::isBlank($this->app_already_exists()) ) {

				do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'OAUTH_CLIENT_ID_ERROR' ), 'ERROR' );
				return;
			}

		}
		$this->configure_new_app();
	}

	function configure_new_app() {

		$this->new_app['clientid']     = $this->client_id;
		$this->new_app['clientsecret'] = $this->client_secret;
		$this->new_app['scope']        = $this->scope;
		$this->new_app['redirecturi']  = site_url() . '/oauthcallback';

		if ( ! Mo_GSuite_Utility::isBlank( $this->existing_applist ) || in_array( $this->client_appname, $this->supported_app_list ) ) {
			$app_authorization_data  = $this->set_authorization_values( $this->client_appname );
			$authorizeurl            = $app_authorization_data['authorize_url'];
			$accesstokenurl          = $app_authorization_data['acess_token_url'];
			$resourceownerdetailsurl = $app_authorization_data['resource_owner_details'];
		} else {
			$authorizeurl            = stripslashes( sanitize_text_field( $_POST['mo_oauth_authorizeurl'] ) );
			$accesstokenurl          = stripslashes( sanitize_text_field( $_POST['mo_oauth_accesstokenurl'] ) );
			$resourceownerdetailsurl = stripslashes( sanitize_text_field( $_POST['mo_oauth_resourceownerdetailsurl'] ) );
			$appname                 = stripslashes( sanitize_text_field( $_POST['mo_oauth_custom_app_name'] ) );
		}
		$this->new_app['authorizeurl']            = $authorizeurl;
		$this->new_app['accesstokenurl']          = $accesstokenurl;
		$this->new_app['resourceownerdetailsurl'] = $resourceownerdetailsurl;

		$this->existing_applist[ $this->client_appname ] = $this->new_app;

		update_mo_gsuite_option( 'mo_oauth_apps_list_test', $this->existing_applist );

		wp_redirect( site_url() . '/wp-admin/admin.php?page=mogalsettings' );
	}

	function set_authorization_values( $client_appname ) {
		$authorization_data      = array();
		$authorizeurl            = "";
		$accesstokenurl          = "";
		$resourceownerdetailsurl = "";
		//TODO:: COMPLETE FOR THE EVEONLINE
		switch ( $client_appname ) {
			case 'facebook':
				$authorizeurl            = Mo_Gsuite_Constants::FACEBOOK_AUTHORIZE_URL;
				$accesstokenurl          = Mo_Gsuite_Constants::FACEBOOK_ACCESS_TOKEN_URL;
				$resourceownerdetailsurl = Mo_Gsuite_Constants::FACEBOOK_RESOURCE_OWNER_DETAILS_URL;
				break;

			case 'google':
				$authorizeurl            = Mo_Gsuite_Constants::GOOGLE_AUTHORIZE_URL;
				$accesstokenurl          = Mo_Gsuite_Constants::GOOGLE_ACCESS_TOKEN_URL;
				$resourceownerdetailsurl = Mo_Gsuite_Constants::GOOGLE_RESOURCE_OWNER_DETAILS_URL;
				break;

			case 'windows':
				$authorizeurl            = Mo_Gsuite_Constants::WINDOWS_AUTHORIZE_URL;
				$accesstokenurl          = Mo_Gsuite_Constants::WINDOWS_ACCESS_TOKEN_URL;
				$resourceownerdetailsurl = Mo_Gsuite_Constants::WINDOWS_RESOURCE_OWNER_DETAILS_URL;
				break;
		}

		$authorization_data = array(
			'authorize_url'          => $authorizeurl,
			'acess_token_url'        => $accesstokenurl,
			'resource_owner_details' => $resourceownerdetailsurl
		);

		return $authorization_data;

	}
}

