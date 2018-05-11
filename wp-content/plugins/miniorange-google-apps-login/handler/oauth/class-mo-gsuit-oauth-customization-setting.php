<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 30-03-2018
 * Time: 14:39
 */

class Mo_Gsuite_Oauth_Customization_Setting extends BasePostAction {

	private $option='mo_oauth_app_customization';
	private $action=array('mo_oauth_customization_reset','mo_oauth_app_customization_submit');

	function __construct() {
		parent::__construct();
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
	function validate_post_data( $getData ){

		if(Mo_GSuite_Utility::isBlank($getData))return false;

		if(!isset($getData['option']) || strcasecmp($getData['option'],$this->option)!=0){
			return false;
		}

		if(!isset($getData['action'])||!in_array($getData['action'],$this->action))return false;

		return true;
	}

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	function route_post_data( $getData ){

		switch ($getData['action']){
			case 'mo_oauth_customization_reset':
				$this->reset_customization_setting($getData);
				break;

			case 'mo_oauth_app_customization_submit':
				$this->save_customization_setting($getData);
				break;
		}

	}

	/**
	 * Saves the customization settings.
	 * @param $getData
	 */
	function save_customization_setting($getData){
		update_mo_gsuite_option( 'mo_oauth_icon_width', stripslashes( sanitize_text_field( $getData['mo_oauth_icon_width'] ) ) );
		update_mo_gsuite_option( 'mo_oauth_icon_height', stripslashes( sanitize_text_field( $getData['mo_oauth_icon_height'] ) ) );
		update_mo_gsuite_option( 'mo_oauth_icon_margin', stripslashes( sanitize_text_field( $getData['mo_oauth_icon_margin'] ) ) );
		update_mo_gsuite_option( 'mo_oauth_icon_configure_css', stripcslashes( sanitize_text_field( $getData['mo_oauth_icon_configure_css'] ) ) );

		do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'OAUTH_CUSTOMIZATION_SETTINGS_SAVED' ), 'SUCCESS' );
	}
	/**
	 * This function will reset customization settings if the reset button is clicked
	 */
	function reset_customization_setting(){
		update_mo_gsuite_option( 'mo_oauth_icon_width', '' );
		update_mo_gsuite_option( 'mo_oauth_icon_height', '' );
		update_mo_gsuite_option( 'mo_oauth_icon_margin', '' );
		update_mo_gsuite_option( 'mo_oauth_icon_configure_css', '' );

		do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'OAUTH_CUSTOMIZATION_SETTINGS_RESET_SUCCESS' ), 'SUCCESS' );
	}

	/**
	 * This function checks if the post data is blank will be helpful in validation purpose.
	 * @param $getData
	 *
	 * @return bool
	 */
	function check_if_post_data_blank($getData){
		foreach ($getData as $key=>$value){
			if(Mo_GSuite_Utility::isBlank($value))return true;
		}
		return false;
	}
}
new  Mo_Gsuite_Oauth_Customization_Setting;