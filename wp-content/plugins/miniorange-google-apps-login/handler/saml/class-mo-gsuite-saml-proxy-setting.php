<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 26-04-2018
 * Time: 10:54
 */

class Mo_Gsuite_SAML_Proxy_Setting extends BasePostAction {


	/**
	 * @var string this forms option.
	 */
	private $option='mo_saml_save_proxy_setting';
	/**
	 * @var array Array of the action.
	 */
	private $action_value= array('mo_gsuite_proxy_setting_reset','mo_gsuite_proxy_setting_save');

	/**
	 * This function will get the post parameters for the first time. This function will be used for manipulation.
	 * @return mixed
	 */
	function handle_post_data() {

		if(!$this->validate_post_data($_POST)) return;

		$this->route_post_data($_POST);
	}

	/**
	 * This function is used to check if the post data has some validation errors.
	 * @return mixed
	 */
	function validate_post_data( $getData ) {
		if(!isset($getData['option']) || !strcasecmp($this->option,$getData['option'])==0)return false;

		if(!isset($getData['action'])||!in_array($getData['action'],$this->action_value)) return false;

		if(strcasecmp($getData['action'],'mo_gsuite_proxy_setting_save')==0) {
			if ( $this->check_if_post_empty( $getData ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	function route_post_data( $getData ) {

		switch ($getData['action']){
			case 'mo_gsuite_proxy_setting_reset':
				$this->reset_proxy_setting();

				break;

			case 'mo_gsuite_proxy_setting_save':
				$this->save_proxy_setting();
				break;
		}

	}

	/**
	 * This function will save the proxy settings.
	 * @param $getData
	 */
	function save_proxy_setting($getData){
		update_mo_gsuite_option( 'mo_saml_proxy_host', $getData['mo_saml_proxy_host'] );
		error_log('mo_poxy_host');
		update_mo_gsuite_option( 'mo_proxy_port', $getData['mo_proxy_port'] );
		update_mo_gsuite_option( 'mo_proxy_username', $getData['mo_proxy_username'] );
		update_mo_gsuite_option( 'mo_proxy_password', $getData['mo_proxy_password'] );

		do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_PROXY_SETTING_SAVED_SUCCESS' ), 'SUCCESS' );
	}

	/**
	 * This function will reset the proxy setting setting everything to null.
	 */
	function reset_proxy_setting(){
			update_mo_gsuite_option( 'mo_saml_proxy_host','' );
			error_log('mo_poxy_host');
			update_mo_gsuite_option( 'mo_proxy_port', '' );
			update_mo_gsuite_option( 'mo_proxy_username', '' );
			update_mo_gsuite_option( 'mo_proxy_password', '' );

			do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_PROXY_SETTING_RESET_SUCCESS' ), 'SUCCESS' );
	}
}

new Mo_Gsuite_SAML_Proxy_Setting;