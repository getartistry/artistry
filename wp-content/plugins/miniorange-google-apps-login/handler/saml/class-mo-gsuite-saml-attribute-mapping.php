<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 26-04-2018
 * Time: 13:38
 */

class Mo_Gsuite_SAML_Attribute_Mapping extends BasePostAction {

	private $option_value='login_widget_saml_attribute_mapping';

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
		if(Mo_GSuite_Utility::isBlank( $getData ) ||!isset($getData['option'])||strcasecmp($this->option_value,$getData['option'])!=0)return false;
		//ToDo strict validation for the post data.
		return true;
	}

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	function route_post_data( $getData ) {

		if (!get_option('mo_saml_free_version')) {
			update_mo_gsuite_option('saml_am_username', stripslashes($getData['saml_am_username']));
			update_mo_gsuite_option('saml_am_email', stripslashes($getData['saml_am_email']));
			update_mo_gsuite_option('saml_am_group_name', stripslashes($getData['saml_am_group_name']));
		}

		update_mo_gsuite_option('saml_am_first_name', stripslashes($getData['saml_am_first_name']));
		update_mo_gsuite_option('saml_am_last_name', stripslashes($getData['saml_am_last_name']));
		update_mo_gsuite_option('saml_am_account_matcher', stripslashes($getData['saml_am_account_matcher']));

		do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_ATTRIBUTE_MAPPING_SAVED' ), 'SUCCESS' );
	}
}
new Mo_Gsuite_SAML_Attribute_Mapping;