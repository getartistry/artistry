<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 19-02-2018
 * Time: 14:21
 */


class Mo_SAML_Login_Widget_Setting extends BasePostAction {


	private $option_value='login_widget_saml_save_settings';
	/**
	 * This function will get the post parameters for the first time. This function will be used for manipulation.
	 * @return mixed
	 */
	function handle_post_data() {

		if(!$this->validate_post_data($_POST))return ;
		$this->route_post_data($_POST);
	}

	/**
	 * This function is used to check if the post data has some validation errors.
	 * @return mixed
	 */
	function validate_post_data( $getData ) {

		if(!isset($getData['option'])||strcasecmp($getData['option'],$this->option_value)!=0)
			return false;

		if(!isset($getData['saml_identity_name'])||Mo_GSuite_Utility::isBlank($getData['saml_identity_name'])
			||!isset($getData['saml_issuer'])||Mo_GSuite_Utility::isBlank($getData['saml_issuer'])
			||!isset($getData['saml_login_url'])||Mo_GSuite_Utility::isBlank($getData['saml_login_url'])
			||!isset($getData['saml_x509_certificate'])||Mo_GSuite_Utility::isBlank($getData['saml_x509_certificate'])
		) {
			do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SP_VALIDATION_ERROR' ), 'ERROR' );
			return false;
		} else if(!preg_match(Mo_Gsuite_Constants::PATTERN_IDP_NAME,$getData['saml_identity_name'])){
			do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SP_PREG_MATCH_ERROR' ), 'ERROR' );
			return false;
		}

		return true;
	}

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	function route_post_data( $getData ) {

		if(!$this->_process_x509_certificate($getData['saml_x509_certificate'])){
			return;
		}
		$this->save_this_forms_option($getData);
		do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SP_SAVED_SUCESS' ), 'SUCCESS' );
	}

	/*Saves the form options*/
	private function save_this_forms_option($getData){


		update_mo_gsuite_option( 'saml_identity_name', $getData['saml_identity_name'] );
		update_mo_gsuite_option( 'saml_login_url', $getData['saml_login_url']);

		update_mo_gsuite_option( 'saml_issuer', $getData['saml_issuer'] );

		update_mo_gsuite_option('saml_x509_certificate', maybe_serialize($getData['saml_x509_certificate']));

		update_mo_gsuite_option('saml_response_signed',isset($getData['saml_response_signed'])?'checked':'');

		update_mo_gsuite_option('saml_assertion_signed',isset($getData['saml_assertion_signed'])?'checked':'');

	}

	private function _process_x509_certificate($saml_x509_certificate){
		foreach ( $saml_x509_certificate as $key => $value ) {
			if ( empty( $value ) ) {
				unset( $saml_x509_certificate[ $key ] );
			} else {
				$saml_x509_certificate[ $key ] = Mo_SAML_Utilities ::sanitize_certificate( $value );

				if ( ! @openssl_x509_read( $saml_x509_certificate[ $key ] ) ) {

					do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SP_INVALID_CERTIFICATE' ), 'ERROR' );

					return false;
				}
			}
		}
		if ( empty( $saml_x509_certificate) ) {
			do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SP_INVALID_CERTIFICATE' ), 'ERROR' );


			return false;
		}
		return true;
	}

}
new Mo_SAML_Login_Widget_Setting;

/*private function _validate_post_data(){
		if(Mo_GSuite_Utility::isBlank($this->saml_issuer)||Mo_GSuite_Utility::isBlank($this->saml_login_url )||Mo_GSuite_Utility::isBlank($this->saml_identity_name)||Mo_GSuite_Utility::isBlank($this->saml_x509_certificate)){
			do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SP_VALIDATION_ERROR' ), 'ERROR' );
			return false;
		}
		else if(!preg_match(Mo_Gsuite_Constants::PATTERN_IDP_NAME,$this->saml_identity_name)){
			do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SP_PREG_MATCH_ERROR' ), 'ERROR' );
			return false;
		}
		return true;

	}*/
/*if(Mo_GSuite_Utility::isBlank($this->saml_issuer)||Mo_GSuite_Utility::isBlank($this->saml_login_url )||Mo_GSuite_Utility::isBlank($this->saml_identity_name)||Mo_GSuite_Utility::isBlank($this->saml_x509_certificate)){
	do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SP_VALIDATION_ERROR' ), 'ERROR' );
	return false;
}
else if(!preg_match(Mo_Gsuite_Constants::PATTERN_IDP_NAME,$this->saml_identity_name)){
	do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_SP_PREG_MATCH_ERROR' ), 'ERROR' );
	return false;
}*/

/**
 * Mo_SAML_Login_Widget_Setting constructor. This will route the data;
 */
/*public function __construct() {
	$this->saml_identity_name=trim( $_POST['saml_identity_name'] );
	$this->saml_login_url=trim( $_POST['saml_login_url'] );
	$this->saml_issuer=trim( $_POST['saml_issuer'] );
	$this->saml_x509_certificate = ( $_POST['saml_x509_certificate'] );

	$this->_route_data($_POST);
}*/

/**
 * @param $get_data
 */
/*	private function _route_data($get_data){
		//ToDo saml is curl installed verify.


		if(!$this->_process_x509_certificate()){
			return;
		}

	}*/
