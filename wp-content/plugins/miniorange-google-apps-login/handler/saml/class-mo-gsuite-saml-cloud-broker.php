<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 26-04-2018
 * Time: 14:17
 */

class Mo_Gsuite_SAML_Cloud_broker extends BasePostAction {

	private $option_value='mo_saml_enable_cloud_broker';
	/**
	 * This function will get the post parameters for the first time. This function will be used for manipulation.
	 * @return mixed
	 */
	function handle_post_data() {
		if(!$this->validate_post_data($_POST))return;
		$this->route_post_data($_POST);
	}

	/**
	 * This function is used to check if the post data has some validation errors.
	 * @return mixed
	 */
	function validate_post_data( $getData ) {
		if(!isset($getData['option'])||strcasecmp($this->option_value,$getData['option'])!=0)return false;
		return true;
	}

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	function route_post_data( $getData ) {
		$cloud_broker= $getData['mo_saml_enable_cloud_broker'];

		switch ($cloud_broker){
			case "true":
				update_mo_gsuite_option('mo_saml_enable_cloud_broker', 'true');
				break;

			case "miniorange":
				self::_save_miniorange_as_cloud_broker();
				break;
			default:
				update_mo_gsuite_option('mo_saml_enable_cloud_broker', 'false');
				break;
		}

		do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SIGN_IN_OPTION_SAVED' ), 'SUCCESS' );

	}

	private static  function _save_miniorange_as_cloud_broker(){
		update_mo_gsuite_option('mo_saml_enable_cloud_broker', 'miniorange');
		//edited here
		update_mo_gsuite_option('saml_identity_name', 'miniOrange');
		delete_option('saml_login_url');
		delete_option('saml_issuer');
		delete_option('saml_x509_certificate');

		/*if(!mo_saml_is_curl_installed()) {
			update_mo_gsuite_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Registration failed.');
			$this->mo_saml_show_error_message();
			return;
		}*/

		//$customer = new Customersaml();
		/*$content = $customer->set_customer_app_policy();
		$customerKey = json_decode( $content, true );
		if( json_last_error() == JSON_ERROR_NONE ) {
			update_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_customer_key', $customerKey['id'] );
			update_mo_gsuite_option( 'mo_saml_admin_api_key', $customerKey['apiKey'] );
			update_mo_gsuite_option( 'mo_gsuite_customer_validation_customer_token', $customerKey['token'] );
			update_mo_gsuite_option( 'mo_saml_admin_phone', $customerKey['phone'] );
			$certificate = get_option('saml_x509_certificate');
			if(empty($certificate)) {
				update_mo_gsuite_option( 'mo_saml_free_version', 1 );
			}
			update_mo_gsuite_option('mo_saml_admin_password', '');
			update_mo_gsuite_option( 'mo_saml_message', 'Customer retrieved successfully');
			update_mo_gsuite_option('mo_saml_registration_status' , 'Existing User');
			delete_option('mo_saml_verify_customer');
			$this->mo_saml_show_success_message();
		}*/
	}

}
new Mo_Gsuite_SAML_Cloud_broker;