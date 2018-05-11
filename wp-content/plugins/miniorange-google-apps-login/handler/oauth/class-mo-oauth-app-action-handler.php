<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 12-02-2018
 * Time: 18:48
 */

Class Mo_Oauth_App_Action_handler extends Base_Request_action implements IMo_Oauth_Update_App ,IMo_Oauth_Delete_App ,IMo_Oauth_Add_App {

	/**
	 * This function will be the first to get request data,
	 * This function will be used to process the request data before actual business logic.
	 * @return mixed
	 */
	function handle_request_data() {
		if(!$this->validate_request_data($_REQUEST)) return;
		$this->route_request_data($_REQUEST);

	}

	/**
	 * Route data according to the action received.
	 * @param $getData
	 *
	 * @return mixed|void
	 */
	function route_request_data($getData) {
		$appname = $getData['oauth_appname'];
		switch ( $getData['action_name'] ) {
			case 'delete':
				$this->delete_app( $appname );
				break;

			case 'attribute_mapping':
				break;

			case 'role_mapping':
				break;

			case 'edit':
				$this->update_app( $appname );
				break;

			default:

				break;

		}

	}

	/**
	 * @param $appname
	 *
	 * @return mixed|void
	 */
	function delete_app( $appname ) {
		$appslist = get_mo_gsuite_option( 'mo_oauth_apps_list_test' );
		foreach ( $appslist as $key => $app ) {
			if ( $appname == $key ) {
				unset( $appslist[ $key ] );
				if ( $appname == "eveonline" ) {
					update_mo_gsuite_option( 'mo_oauth_eveonline_enable', 0 );
				}
			}
		}
		update_mo_gsuite_option( 'mo_oauth_apps_list_test', $appslist );
		do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'OAUTH_APP_DELETE_ACTION_SUCCESS' ), 'SUCCESS' );
		$return = array(
			'message' => 'success',
			'url'     => '/wp-admin/admin.php?page=mogalsettings',
			'action'  => 'delete'
		);
		wp_send_json( $return );
	}

	/**
	 * @param $appname
	 *
	 * @return mixed|void
	 */
	function update_app( $appname ) {
		$return = array(
			'message' => 'success',
			'url'     => '/wp-admin/admin.php?page=mogalsettings',
			'action'  => 'edit'
		);
		wp_send_json( $return );
	}


	/**
	 * Adds the app to the Oauth Configuration.
	 *
	 * @return mixed
	 */
	function add_app() {
		new Mo_Oauth_App_Configuration;
	}



	/**
	 * This fuction will be used to do the validation on request data.
	 * @return mixed
	 */
	function validate_request_data( $getData ) {
		if ( ! array_key_exists( 'option', $_REQUEST ) ) {
			return false;
		}
		if ( ! array_key_exists( 'oauth_appname', $_REQUEST ) && ! array_key_exists( 'action_name', $_REQUEST ) ) {
			return false;
		}

		return true;
	}

}

new Mo_Oauth_App_Action_handler;