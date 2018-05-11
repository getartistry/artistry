<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 27-04-2018
 * Time: 14:49
 */

class Mo_Gsuite_SAML_Request_Handler extends Base_Request_action {

	private $option_value = array( 'saml_user_login', 'testConfig', 'getsamlrequest', 'getsamlresponse' );

	/**
	 * This function will be the first to get request data,
	 * This function will be used to process the request data before actual business logic.
	 * @return mixed
	 */
	function handle_request_data() {

		if(!$this->validate_request_data($_REQUEST))return;

		$this->route_request_data($_REQUEST);
	}

	/**
	 * This function will be used to route the request data for business logic.
	 * @return mixed
	 */
	function route_request_data( $getData ) {
		//Check if this test configuration request and user logged in
		if(!$this->is_this_test_configuration_request($getData)&&is_user_logged_in()) return;

		$this->send_saml_request($getData);
	}

	/**
	 * This fuction will be used to do the validation on request data.
	 * @return mixed
	 */
	function validate_request_data( $getData ) {
		if(Mo_GSuite_Utility::isBlank($getData)||! isset( $getData['option'] ))return false;

		if (!in_array($getData['option'],$this->option_value)){
			return false;
		}

		return true;
	}

	/**
	 * Check if this is test configuration request
	 * @param $getData
	 *
	 * @return bool True for test configuration
	 *              False for not test configuration
	 */
	function is_this_test_configuration_request($getData){
		return strcasecmp('saml_user_login',$getData['option'])==0?false:true;
	}

	/**
	 * This function will send back relay_state.
	 * @param $option option value in request
	 *
	 * @return string relaystate url
	 */
	function get_send_relay_state($option){
		$sendRelayState='';

		switch($option){
			case 'testConfig':
				$sendRelayState = 'testValidate';
				break;

			case 'getsamlrequest':
				$sendRelayState = 'displaySAMLRequest';
				break;

			case 'getsamlresponse':
				$sendRelayState = 'displaySAMLResponse';
				break;
			default:
				if(isset( $_REQUEST['redirect_to'])){
					$sendRelayState = $_REQUEST['redirect_to'];
				}else{
					$sendRelayState = saml_get_current_page_url();
				}
				break;
		}
		return $sendRelayState;
	}


	function send_saml_request($getData){
		if(mo_saml_is_sp_configured()){
			$send_relay_state=$this->get_send_relay_state($getData['option']);
		}
		$force_authn = get_option('mo_saml_force_authentication');
		$acsUrl = site_url()."/";
		$issuer = site_url().'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
		$sp_entity_id = get_option('mo_saml_sp_entity_id');
		$ssoUrl = get_option("saml_login_url");
		$samlRequest = Mo_SAML_Utilities ::createAuthnRequest($acsUrl, $issuer,$ssoUrl, $force_authn);


		//Check if the relay estate is to show saml_logs or actual request.
		if(strcasecmp($send_relay_state,'displaySAMLRequest')==0){
			//This has exit code in place. So control will never comeback from here.
			mo_saml_show_SAML_log(Mo_SAML_Utilities ::createSAMLRequest($acsUrl, $sp_entity_id, $ssoUrl, $force_authn),$send_relay_state);
		}else{
			$redirect_url=$this->generate_redirect_url($ssoUrl,$samlRequest,$send_relay_state);
			header('Location: '.$redirect_url);
			exit();
		}
	}

	/**
	 * This function will generate redirect_url for saml request to be sent to idp.
	 * @param $ssoUrl
	 * @param $samlRequest
	 * @param $sendRelayState
	 *
	 * @return string
	 */
	function generate_redirect_url($ssoUrl,$samlRequest,$sendRelayState) {
		if (strcasecmp(get_option('mo_saml_enable_cloud_broker'),'true')==0){
			$redirect = get_option('host_name')."/moas/rest/saml/request?id=".get_option('mo_gsuite_customer_validation_admin_customer_key')."&returnurl=".urlencode( site_url() . '/?option=readsamllogin&redirect_to=' . urlencode ($sendRelayState) );
		}else{
			if ( strpos( $ssoUrl, '?' ) !== false ) {
				$ssoUrl .= '&';
			} else {
				$ssoUrl .= '?';
			}
			$redirect_url = trim($ssoUrl).'SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode( $sendRelayState );
		}
		return $redirect_url;
	}
}
new Mo_Gsuite_SAML_Request_Handler;