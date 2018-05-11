<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 09-02-2018
 * Time: 16:16
 */

class MO_Control_Handler {

	function __construct() {
		add_action( 'admin_init', array( $this, 'redirect_control' ) );
	}

	function redirect_control() {
		if ( Mo_GSuite_Utility::isBlank( $_POST ) || ! isset( $_POST['option'] ) ) {
			return;
		}

		switch ( $_POST['option'] ) {

			case "mo_gal_validation_contact_us_query_option":
				$this->_mo_validation_support_query($_POST['query_email'],$_POST['query_phone'],$_POST['query']);
				break;

			case "mo_oauth_add_app":
				//ToDo change this to work with basePostAction
				new Mo_Oauth_App_Configuration;
				break;

			default:
				break;
		}

	}

	/**
	 * This function processes the support form data beforing sending it to the
	 * server.
	 *
	 * @param $email - the email of the admin to contact
	 * @param $query - the query of the admin
	 * @param $phone - the phone number if provided of the admin
	 */
	function _mo_validation_support_query($email,$phone,$query)
	{
		if( empty($email) || empty($query) )
		{
			do_action('mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage('SUPPORT_FORM_VALUES'),'ERROR');
			return;
		}

		$query 	  = sanitize_text_field( $query );
		$email 	  = sanitize_text_field( $email );
		$phone 	  = sanitize_text_field( $phone );

		$submited = Mo_GSuite_Curl::submit_contact_us( $email, $phone, $query );

		if(json_last_error() == JSON_ERROR_NONE && $submited)
		{
			do_action('mo_gsuite_registration_show_message',Mo_GSuite_Messages::showMessage('SUPPORT_FORM_SENT'),'SUCCESS');
			return;
		}

		do_action('mo_gsuite_registration_show_message',Mo_GSuite_Messages::showMessage('SUPPORT_FORM_ERROR'),'ERROR');
	}
}


new MO_Control_Handler;