<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 26-04-2018
 * Time: 14:01
 */

class Mo_Gsuite_SAML_role_mapping extends BasePostAction {

	private $option_value='login_widget_saml_role_mapping';

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
		if(!isset($getData['option'])||strcasecmp($this->option_value,$getData['option'])!=0)return false;
		return true;
	}

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	function route_post_data( $getData ) {
		if (!get_option('mo_saml_free_version')) {
			if(isset($getData['saml_am_dont_allow_unlisted_user_role'])) {
				update_mo_gsuite_option('saml_am_default_user_role', false);
				update_mo_gsuite_option('saml_am_dont_allow_unlisted_user_role', 'checked');
			} else {
				update_mo_gsuite_option('saml_am_default_user_role', $getData['saml_am_default_user_role']);
				update_mo_gsuite_option('saml_am_dont_allow_unlisted_user_role', 'unchecked');
			}
			$wp_roles = new WP_Roles();
			$roles = $wp_roles->get_names();
			$role_mapping='';
			foreach ($roles as $role_value => $role_name) {
				$attr = 'saml_am_group_attr_values_' . $role_value;
				$role_mapping[$role_value] = stripslashes($getData[$attr]);
			}
			update_mo_gsuite_option('saml_am_role_mapping', $role_mapping);
		} else {
			update_mo_gsuite_option('saml_am_default_user_role', $getData['saml_am_default_user_role']);
		}

		do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_ROLE_MAPPING_SAVED' ), 'SUCCESS' );

	}
}
new Mo_Gsuite_SAML_role_mapping;