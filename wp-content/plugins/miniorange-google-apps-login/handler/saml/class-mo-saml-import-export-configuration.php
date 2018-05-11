<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 19-02-2018
 * Time: 19:06
 */
//ToDo: check for all the option names here.
class Mo_SAML_Import_Export_Configuration extends BasePostAction {

	private $option_value='mo_saml_export';
/*	function __construct() {
		define( "Tab_Class_Names", serialize( array(
			"SSO_Login"         => 'mo_options_enum_sso_login',
			"Identity_Provider" => 'mo_options_enum_identity_provider',
			"Service_Provider"  => 'mo_options_enum_service_provider',
			"Attribute_Mapping" => 'mo_options_enum_attribute_mapping',
			"Role_Mapping"      => 'mo_options_enum_role_mapping',
			"Proxy_Setup"       => 'mo_options_enum_proxy_setup'
		) ) );

		$this->_process_import_export();
	}*/



	private function _process_import_export() {
		$tab_class_name      = unserialize( Tab_Class_Names );
		$configuration_array = array();
		foreach ( $tab_class_name as $key => $value ) {
			$configuration_array[ $key ] = $this->mo_get_configuration_array( $value );
		}
		$configuration_array["Version_dependencies"]=$this->mo_get_version_informations();
		header( "Content-Disposition: attachment; filename=miniorange-saml-config.json" );
		echo( json_encode( $configuration_array, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) );
		exit;
	}


	function mo_get_configuration_array( $class_name ) {
		$class_object = call_user_func( $class_name . '::getConstants' );
		$mo_array = array();
		foreach ( $class_object as $key => $value ) {
			$mo_option_exists=get_mo_gsuite_option($value);

			if($mo_option_exists){
				if(@unserialize($mo_option_exists)!==false){
					$mo_option_exists = unserialize($mo_option_exists);
				}
				$mo_array[ $key ] = $mo_option_exists;
			}
		}
		return $mo_array;
	}


	function mo_update_configuration_array( $configuration_array ) {
		$tab_class_name = unserialize( Tab_Class_Names );
		foreach ( $tab_class_name as $tab_name => $class_name ) {
			foreach ( $configuration_array[ $tab_name ] as $key => $value ) {
				$option_string = constant( "$class_name::$key" );
				$mo_option_exists = get_option($option_string);
				if ( $mo_option_exists) {
					if(is_array($value))
						$value = serialize($value);
					update_mo_gsuite_option( $option_string, $value );
				}
			}
		}

	}


	function mo_get_version_informations(){
		$array_version = array();
		$array_version["PHP_version"] = phpversion();
		$array_version["Wordpress_version"] = get_bloginfo('version');
		$array_version["OPEN_SSL"] = mo_saml_is_openssl_installed();
		$array_version["CURL"] = mo_saml_is_curl_installed();

		return $array_version;

	}

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
		if(!isset($getData['option'])||strcasecmp($getData['option'],$this->option_value)!=0)
			return false;

		return true;
	}

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	function route_post_data( $getData ) {
		define( "Tab_Class_Names", serialize( array(
			"SSO_Login"         => 'mo_options_enum_sso_login',
			"Identity_Provider" => 'mo_options_enum_identity_provider',
			"Service_Provider"  => 'mo_options_enum_service_provider',
			"Attribute_Mapping" => 'mo_options_enum_attribute_mapping',
			"Role_Mapping"      => 'mo_options_enum_role_mapping',
			"Proxy_Setup"       => 'mo_options_enum_proxy_setup'
		) ) );

		$this->_process_import_export();
	}
}
new Mo_SAML_Import_Export_Configuration;

 class mo_options_enum_sso_login extends BasicEnum {
	 const Relay_state = 'mo_saml_relay_state';
	 const Redirect_Idp = 'mo_saml_registered_only_access';
	 const Force_authentication = 'mo_saml_force_authentication';
	 const Enable_access_RSS = 'mo_saml_enable_rss_access';
	 const Auto_redirect = 'mo_saml_enable_login_redirect';
 }

class mo_options_enum_identity_provider extends BasicEnum{
	const Broker_service ='mo_saml_enable_cloud_broker';
	const SP_Base_Url='mo_saml_sp_base_url';
	const SP_Entity_ID = 'mo_saml_sp_entity_id';
}


class mo_options_enum_service_provider extends BasicEnum{
	const Identity_name ='saml_identity_name';
	const Login_binding_type='saml_login_binding_type';
	const Login_URL = 'saml_login_url';
	const Logout_binding_type = 'saml_logout_binding_type';
	const Logout_URL = 'saml_logout_url';
	const Issuer = 'saml_issuer';
	const X509_certificate = 'saml_x509_certificate';
	const Request_signed = 'saml_request_signed';
}

class mo_options_enum_attribute_mapping extends BasicEnum{
	const Attribute_Username ='saml_am_username';
	const Attribute_Email = 'saml_am_email';
	const Attribute_First_name ='saml_am_first_name';
	const Attribute_Last_name = 'saml_am_last_name';
	const Attribute_Group_name ='saml_am_group_name';
	const Attribute_Custom_mapping = 'mo_saml_custom_attrs_mapping';
	const Attribute_Account_matcher = 'saml_am_account_matcher';
}

class mo_options_enum_role_mapping extends BasicEnum{
	const Role_do_not_auto_create_users = 'mo_saml_dont_create_user_if_role_not_mapped';
	const Role_do_not_assign_role_unlisted = 'saml_am_dont_allow_unlisted_user_role';
	const Role_do_not_update_existing_user = 'saml_am_dont_update_existing_user_role';
	const Role_default_role ='saml_am_default_user_role';
	const Roles = 'saml_am_default_user_role';
}

class mo_options_enum_proxy_setup extends BasicEnum{
	const Proxy_host = 'mo_saml_proxy_host';
	const Proxy_port = 'mo_proxy_port';
	const Proxy_username = 'mo_proxy_username';
	const Proxy_password = 'mo_proxy_password';
}

class mo_options_error_constants extends BasicEnum{
	const Error_no_certificate = "Unable to find a certificate .";
	const Cause_no_certificate = "No signature found in SAML Response or Assertion. Please sign at least one of them.";
	const Error_wrong_certificate = "Unable to find a certificate matching the configured fingerprint.";
	const Cause_wrong_certificate = "X.509 Certificate field in plugin does not match the certificate found in SAML Response.";
	const Error_invalid_audience = "Invalid Audience URI.";
	const Cause_invalid_audience = "The value of 'Audience URI' field on Identity Provider's side is incorrect";
	const Error_issuer_not_verfied = "Issuer cannot be verified.";
	const Cause_issuer_not_verfied = "IdP Entity ID configured and the one found in SAML Response do not match";
}

