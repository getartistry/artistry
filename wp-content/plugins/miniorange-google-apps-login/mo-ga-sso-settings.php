<?php
/*
Plugin Name: Login with Google Apps
Plugin URI: http://miniorange.com/
Description: Google Apps Login provides simple secure login to your WordPress site via user's Google Apps account using SAML.(ACTIVE SUPPORT)
Version: 5.0.7
Author: miniOrange
Author URI: http://miniorange.com/
*/
require('actions/mo-login-ga-sso-widget.php');
require('actions/mo-ga-sso-registration-action.php');
require('pages/mo-ga-sso-main-page.php');
require('resources/mo-ga-sso-saml-utility.php');
require('saml2/Response.php');
require('saml2/Assertion.php');
require('includes/lib/encryption.php');
require('includes/lib/xmlseclibs.php');
class mo_ga_sso_login {
	
	function __construct() {
		add_action( 'admin_menu', array( $this, 'miniorange_ga_sso_menu' ) );
		add_action( 'admin_init', array( $this, 'miniorange_login_widget_ga_sso_save_settings' ) );		
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_ga_sso_settings_style' ) );
		register_deactivation_hook(__FILE__, array( $this, 'mo_ga_sso_deactivate'));
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_ga_sso_settings_script' ) );		
		remove_action( 'admin_notices', array( $this, 'mo_ga_sso_success_message') );
		remove_action( 'admin_notices', array( $this, 'mo_ga_sso_error_message') );
		add_action('wp_authenticate', array( $this, 'mo_ga_sso_authenticate' ) );
		add_action('login_form', array( $this, 'mo_ga_sso_modify_login_form' ) );
		add_shortcode( 'MO_ga_sso_FORM', array($this, 'mo_get_ga_sso_shortcode') );
	}
	
	function  mo_login_widget_ga_sso_options () {
		global $wpdb;
		update_option( 'mo_ga_sso_host_name', 'https://auth.miniorange.com' );
		$host_name = get_option('mo_ga_sso_host_name');
		$token = get_option('mo_ga_sso_saml_x509_certificate');
		mo_register_ga_sso();
	}
	
	function mo_ga_sso_success_message() {
		$class = "error";
		$message = get_option('mo_ga_sso_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}

	function mo_ga_sso_error_message() {
		$class = "updated";
		$message = get_option('mo_ga_sso_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}
		
	public function mo_ga_sso_deactivate() {
		if(!is_multisite()) {
			//delete all customer related key-value pairs
			delete_option('mo_ga_sso_host_name');
			delete_option('mo_ga_sso_new_registration');
			delete_option('mo_ga_sso_admin_phone');
			delete_option('mo_ga_sso_admin_password');
			delete_option('mo_ga_sso_verify_customer');
			delete_option('mo_ga_sso_admin_customer_key');
			delete_option('mo_ga_sso_admin_api_key');
			delete_option('mo_ga_sso_customer_token');
			delete_option('mo_ga_sso_message');
			delete_option('mo_ga_sso_registration_status');		
			delete_option('mo_ga_sso_idp_config_complete');
			delete_option('mo_ga_sso_transactionId');
		} else {
			global $wpdb;
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();
			
			foreach ( $blog_ids as $blog_id )
			{
				switch_to_blog( $blog_id );
				//delete all your options
				//E.g: delete_option( {option name} );
				delete_option('mo_ga_sso_host_name');
				delete_option('mo_ga_sso_new_registration');
				delete_option('mo_ga_sso_admin_phone');
				delete_option('mo_ga_sso_admin_password');
				delete_option('mo_ga_sso_verify_customer');
				delete_option('mo_ga_sso_admin_customer_key');
				delete_option('mo_ga_sso_admin_api_key');
				delete_option('mo_ga_sso_customer_token');
				delete_option('mo_ga_sso_message');
				delete_option('mo_ga_sso_registration_status');
				delete_option('mo_ga_sso_idp_config_complete');
				delete_option('mo_ga_sso_transactionId');
			}
			switch_to_blog( $original_blog_id );
		}
	}	
	
	private function mo_ga_sso_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_ga_sso_success_message') );
		add_action( 'admin_notices', array( $this, 'mo_ga_sso_error_message') );
	}
	function mo_ga_sso_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'mo_ga_sso_error_message') );
		add_action( 'admin_notices', array( $this, 'mo_ga_sso_success_message') );
	}
	function mo_ga_sso_settings_style() {
		wp_enqueue_style( 'mo_ga_sso_admin_settings_style', plugins_url( 'includes/css/style_settings.css?ver=3.7', __FILE__ ) );
		wp_enqueue_style( 'mo_ga_sso_admin_settings_phone_style', plugins_url( 'includes/css/phone.css', __FILE__ ) );
		wp_enqueue_style( 'mo_saml_wpb-fa', plugins_url( 'includes/css/font-awesome.min.css', __FILE__ ) );

	}
	function mo_ga_sso_settings_script() {
		wp_enqueue_script( 'mo_ga_sso_admin_settings_script', plugins_url( 'includes/js/settings.js', __FILE__ ) );
		wp_enqueue_script( 'mo_ga_sso_admin_settings_phone_script', plugins_url('includes/js/phone.js', __FILE__ ) );
	}
	function miniorange_login_widget_ga_sso_save_settings(){
		if ( current_user_can( 'manage_options' )){ 
			
		if(isset($_POST['option']) and $_POST['option'] == "login_widget_ga_sso_save_settings"){
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Save Identity Provider Configuration failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
			
			//validation and sanitization
			$mo_ga_sso_saml_identity_name = '';
			$mo_ga_sso_saml_login_url = '';
			$mo_ga_sso_saml_issuer = '';
			$mo_ga_sso_saml_x509_certificate = '';
			if( $this->mo_ga_sso_check_empty_or_null( $_POST['mo_ga_sso_saml_identity_name'] ) || $this->mo_ga_sso_check_empty_or_null( $_POST['mo_ga_sso_saml_login_url'] ) || $this->mo_ga_sso_check_empty_or_null( $_POST['mo_ga_sso_saml_issuer'] )  ) {
				update_option( 'mo_ga_sso_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_ga_sso_show_error_message();
				return;
			} else if(!preg_match("/^\w*$/", $_POST['mo_ga_sso_saml_identity_name'])) {
				update_option( 'mo_ga_sso_message', 'Please match the requested format for Identity Provider Name. Only alphabets, numbers and underscore is allowed.');
				$this->mo_ga_sso_show_error_message();
				return;
			} else{
				$mo_ga_sso_saml_identity_name = trim( $_POST['mo_ga_sso_saml_identity_name'] );
				$mo_ga_sso_saml_login_url = trim( $_POST['mo_ga_sso_saml_login_url'] );
				$mo_ga_sso_saml_issuer = trim( $_POST['mo_ga_sso_saml_issuer'] );
				$mo_ga_sso_saml_x509_certificate = trim( $_POST['mo_ga_sso_saml_x509_certificate'] );
			}
			
			update_option('mo_ga_sso_saml_identity_name', $mo_ga_sso_saml_identity_name);
			update_option('mo_ga_sso_saml_login_url', $mo_ga_sso_saml_login_url);
			update_option('mo_ga_sso_saml_issuer', $mo_ga_sso_saml_issuer);
			update_option('mo_ga_sso_saml_x509_certificate', $mo_ga_sso_saml_x509_certificate);	
			if(isset($_POST['mo_ga_sso_saml_response_signed']))
				{
				update_option('mo_ga_sso_saml_response_signed' , 'checked');
				}
			else
				{
				update_option('mo_ga_sso_saml_response_signed' , 'Yes');
				}
			if(isset($_POST['mo_ga_sso_saml_assertion_signed']))
				{
				update_option('mo_ga_sso_saml_assertion_signed' , 'checked');
				}
			else
				{
				update_option('mo_ga_sso_saml_assertion_signed' , 'Yes');
				}
			
			
				update_option('mo_ga_sso_saml_x509_certificate', Mo_Ga_Saml_Utility::sanitize_certificate( $mo_ga_sso_saml_x509_certificate ) );
				update_option('mo_ga_sso_message', 'Identity Provider details saved successfully.');
				$this->mo_ga_sso_show_success_message();
			
		}
		//Save Attribute Mapping
		if(isset($_POST['option']) and $_POST['option'] == "login_widget_ga_sso_attribute_mapping"){
			
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Save Attribute Mapping failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
		
			update_option('mo_ga_sso_first_name', stripslashes($_POST['mo_ga_sso_first_name']));
			update_option('mo_ga_sso_last_name', stripslashes($_POST['mo_ga_sso_last_name']));
			update_option('mo_ga_sso_account_matcher', stripslashes($_POST['mo_ga_sso_account_matcher']));
			update_option('mo_ga_sso_message', 'Attribute Mapping details saved successfully');
			$this->mo_ga_sso_show_success_message();
		
		}
		//Save Role Mapping
		if(isset($_POST['option']) and $_POST['option'] == "login_widget_ga_sso_role_mapping"){
			
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Save Role Mapping failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
		
			update_option('mo_ga_sso_default_user_role', $_POST['mo_ga_sso_default_user_role']);
			
			update_option('mo_ga_sso_message', 'Role Mapping details saved successfully.');
			$this->mo_ga_sso_show_success_message();
		}
		
		if( isset( $_POST['option'] ) and $_POST['option'] == "mo_ga_sso_register_customer" ) {	//register the admin to miniOrange
		
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Registration failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
			
			//validation and sanitization
			$email = '';
			$company = '';
			$first_name = '';
			$last_name = '';
			$phone = '';
			$password = '';
			$confirmPassword = '';
			if( $this->mo_ga_sso_check_empty_or_null( $_POST['email'] ) || $this->mo_ga_sso_check_empty_or_null( $_POST['password'] ) || $this->mo_ga_sso_check_empty_or_null( $_POST['confirmPassword'] ) || $this->mo_ga_sso_check_empty_or_null( $_POST['company'] )) {
				update_option( 'mo_ga_sso_message', 'Please enter the required fields.');
				$this->mo_ga_sso_show_error_message();
				return;
			} else if( strlen( $_POST['password'] ) < 6 || strlen( $_POST['confirmPassword'] ) < 6){
				update_option( 'mo_ga_sso_message', 'Choose a password with minimum length 6.');
				$this->mo_ga_sso_show_error_message();
				return;
			} else{
				$email = sanitize_email( $_POST['email'] );
				$company = sanitize_text_field( $_POST['company'] );
				$first_name = sanitize_text_field( $_POST['first_name'] );
				$last_name = sanitize_text_field( $_POST['last_name'] );
				$phone = sanitize_text_field( $_POST['phone'] );
				$password = sanitize_text_field( $_POST['password'] );
				$confirmPassword = sanitize_text_field( $_POST['confirmPassword'] );
			}
			update_option( 'mo_ga_sso_admin_email', $email );
			update_option( 'mo_ga_sso_admin_phone', $phone );
			update_option( 'mo_ga_sso_admin_company', $company );
			update_option( 'mo_ga_sso_admin_first_name', $first_name );
			update_option( 'mo_ga_sso_admin_last_name', $last_name );
			if( strcmp( $password, $confirmPassword) == 0 ) {
				update_option( 'mo_ga_sso_admin_password', $password );
				$email = get_option('mo_ga_sso_admin_email');
				$customer = new Mo_Ga_Login_Registration_Action();
				$content = json_decode($customer->check_customer(), true);
				if( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND') == 0 ){
					$content = json_decode($customer->send_otp_token($email, ''), true);
					if(strcasecmp($content['status'], 'SUCCESS') == 0) {
						update_option( 'mo_ga_sso_message', ' A one time passcode is sent to ' . get_option('mo_ga_sso_admin_email') . '. Please enter the otp here to verify your email.');
						update_option('mo_ga_sso_transactionId',$content['txId']);
						update_option('mo_ga_sso_registration_status','MO_OTP_DELIVERED_SUCCESS_EMAIL');
						$this->mo_ga_sso_show_success_message();
					}else{
						update_option('mo_ga_sso_message','There was an error in sending email. Please verify your email and try again.');
						update_option('mo_ga_sso_registration_status','MO_OTP_DELIVERED_FAILURE_EMAIL');
						$this->mo_ga_sso_show_error_message();
					}
				}else{
					$this->get_current_customer();
				}
				
			} else {
				update_option( 'mo_ga_sso_message', 'Passwords do not match.');
				delete_option('mo_ga_sso_verify_customer');
				$this->mo_ga_sso_show_error_message();
			}
	
		}
		if(isset($_POST['option']) and $_POST['option'] == "mo_ga_sso_validate_otp"){
			
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Validate OTP failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}

			//validation and sanitization
			$otp_token = '';
			if( $this->mo_ga_sso_check_empty_or_null( $_POST['otp_token'] ) ) {
				update_option( 'mo_ga_sso_message', 'Please enter a value in otp field.');
				//update_option('mo_ga_sso_registration_status','MO_OTP_VALIDATION_FAILURE');
				$this->mo_ga_sso_show_error_message();
				return;
			} else{
				$otp_token = sanitize_text_field( $_POST['otp_token'] );
			}

			$customer = new Mo_Ga_Login_Registration_Action();
			$content = json_decode($customer->validate_otp_token(get_option('mo_ga_sso_transactionId'), $otp_token ),true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {

					$this->create_customer();
			}else{
				update_option( 'mo_ga_sso_message','Invalid one time passcode. Please enter a valid otp.');
				//update_option('mo_ga_sso_registration_status','MO_OTP_VALIDATION_FAILURE');
				$this->mo_ga_sso_show_error_message();
			}
		}
		if( isset( $_POST['option'] ) and $_POST['option'] == "mo_ga_sso_verify_customer" ) {	//register the admin to miniOrange
		
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Login failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
			
			//validation and sanitization
			$email = '';
			$password = '';
			if( $this->mo_ga_sso_check_empty_or_null( $_POST['email'] ) || $this->mo_ga_sso_check_empty_or_null( $_POST['password'] ) ) {
				update_option( 'mo_ga_sso_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_ga_sso_show_error_message();
				return;
			} else{
				$email = sanitize_email( $_POST['email'] );
				$password = sanitize_text_field( $_POST['password'] );
			}
		
			update_option( 'mo_ga_sso_admin_email', $email );
			update_option( 'mo_ga_sso_admin_password', $password );
			$customer = new Mo_Ga_Login_Registration_Action();
			$content = $customer->get_customer_key();
			$customerKey = json_decode( $content, true );
			if( json_last_error() == JSON_ERROR_NONE ) {
				update_option( 'mo_ga_sso_admin_customer_key', $customerKey['id'] );
				update_option( 'mo_ga_sso_admin_api_key', $customerKey['apiKey'] );
				update_option( 'mo_ga_sso_customer_token', $customerKey['token'] );
				update_option( 'mo_ga_sso_admin_phone', $customerKey['phone'] );
				$certificate = get_option('mo_ga_sso_saml_x509_certificate');
				update_option('mo_ga_sso_admin_password', '');
				update_option( 'mo_ga_sso_message', 'Customer retrieved successfully');
				update_option('mo_ga_sso_registration_status' , 'Existing User');
				delete_option('mo_ga_sso_verify_customer');
				$this->mo_ga_sso_show_success_message(); 
			} else {
				update_option( 'mo_ga_sso_message', 'Invalid username or password. Please try again.');
				$this->mo_ga_sso_show_error_message();		
			}
			update_option('mo_ga_sso_admin_password', '');
		}else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_ga_sso_contact_us_query_option" ) {
			
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Query submit failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
			
			// Contact Us query
			$email = $_POST['mo_ga_sso_contact_us_email'];
			$phone = $_POST['mo_ga_sso_contact_us_phone'];
			$query = $_POST['mo_ga_sso_contact_us_query'];
			$customer = new Mo_Ga_Login_Registration_Action();
			if ( $this->mo_ga_sso_check_empty_or_null( $email ) || $this->mo_ga_sso_check_empty_or_null( $query ) ) {
				update_option('mo_ga_sso_message', 'Please fill up Email and Query fields to submit your query.');
				$this->mo_ga_sso_show_error_message();
			} else {
				$submited = $customer->submit_contact_us( $email, $phone, $query );
				if ( $submited == false ) {
					update_option('mo_ga_sso_message', 'Your query could not be submitted. Please try again.');
					$this->mo_ga_sso_show_error_message();
				} else {
					update_option('mo_ga_sso_message', 'Thanks for getting in touch! We shall get back to you shortly.');
					$this->mo_ga_sso_show_success_message();
				}
			}
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_ga_sso_resend_otp_email" ) {
			
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Resend OTP failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
			$email = get_option ( 'mo_ga_sso_admin_email' );
		    $customer = new Mo_Ga_Login_Registration_Action();
			$content = json_decode($customer->send_otp_token($email, ''), true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
					update_option( 'mo_ga_sso_message', ' A one time passcode is sent to ' . get_option('mo_ga_sso_admin_email') . ' again. Please check if you got the otp and enter it here.');
					update_option('mo_ga_sso_transactionId',$content['txId']);
					update_option('mo_ga_sso_registration_status','MO_OTP_DELIVERED_SUCCESS_EMAIL');
					$this->mo_ga_sso_show_success_message();
			}else{
					update_option('mo_ga_sso_message','There was an error in sending email. Please click on Resend OTP to try again.');
					update_option('mo_ga_sso_registration_status','MO_OTP_DELIVERED_FAILURE_EMAIL');
					$this->mo_ga_sso_show_error_message();
			}
		} else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_ga_sso_resend_otp_phone" ) {
			
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Resend OTP failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
			$phone = get_option('mo_ga_sso_admin_phone');
		    $customer = new Mo_Ga_Login_Registration_Action();
			$content = json_decode($customer->send_otp_token('', $phone, FALSE, TRUE), true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
					update_option( 'mo_ga_sso_message', ' A one time passcode is sent to ' . $phone . ' again. Please check if you got the otp and enter it here.');
					update_option('mo_ga_sso_transactionId',$content['txId']);
					update_option('mo_ga_sso_registration_status','MO_OTP_DELIVERED_SUCCESS_PHONE');
					$this->mo_ga_sso_show_success_message();
			}else{
					update_option('mo_ga_sso_message','There was an error in sending email. Please click on Resend OTP to try again.');
					update_option('mo_ga_sso_registration_status','MO_OTP_DELIVERED_FAILURE_PHONE');
					$this->mo_ga_sso_show_error_message();
			}
		} 
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_ga_sso_go_back" ){
				update_option('mo_ga_sso_registration_status','');
				update_option('mo_ga_sso_verify_customer', '');
				delete_option('mo_ga_sso_new_registration');
				delete_option('mo_ga_sso_admin_email');
				delete_option('mo_ga_sso_admin_phone');
		} else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_ga_sso_register_with_phone_option" ) {
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Resend OTP failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
			$phone = sanitize_text_field($_POST['phone']);
			$phone = str_replace(' ', '', $phone);
			$phone = str_replace('-', '', $phone);
			update_option('mo_ga_sso_admin_phone', $phone);
			$customer = new Mo_Ga_Login_Registration_Action();
			$content = json_decode($customer->send_otp_token('', $phone, FALSE, TRUE), true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				update_option( 'mo_ga_sso_message', ' A one time passcode is sent to ' . get_option('mo_ga_sso_admin_phone') . '. Please enter the otp here to verify your email.');
				update_option('mo_ga_sso_transactionId',$content['txId']);
				update_option('mo_ga_sso_registration_status','MO_OTP_DELIVERED_SUCCESS_PHONE');
				$this->mo_ga_sso_show_success_message();
			}else{
				update_option('mo_ga_sso_message','There was an error in sending SMS. Please click on Resend OTP to try again.');
				update_option('mo_ga_sso_registration_status','MO_OTP_DELIVERED_FAILURE_PHONE');
				$this->mo_ga_sso_show_error_message();
			}
		} 
		else if( isset( $_POST['option']) and $_POST['option'] == "mo_ga_sso_force_authentication_option") {
			if(mo_ga_sso_is_sp_configured()) {
				if(array_key_exists('mo_ga_sso_force_authentication', $_POST)) {
					$enable_redirect = $_POST['mo_ga_sso_force_authentication'];
				} else {
					$enable_redirect = 'false';
				}				
				if($enable_redirect == 'true') {
					update_option('mo_ga_sso_force_authentication', 'true');
				} else {
					update_option('mo_ga_sso_force_authentication', '');
				}
				update_option( 'mo_ga_sso_message', 'Sign in options updated.');
				$this->mo_ga_sso_show_success_message();
			} else {
				update_option( 'mo_ga_sso_message', 'Please complete <a href="' . add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] ) . '" />Service Provider</a> configuration first.');
				$this->mo_ga_sso_show_error_message();
			}
		} else if( isset( $_POST['option']) and $_POST['option'] == "mo_ga_sso_enable_login_redirect_option") {
			if(mo_ga_sso_is_sp_configured()) {
				if(array_key_exists('mo_ga_sso_enable_login_redirect', $_POST)) {
					$enable_redirect = $_POST['mo_ga_sso_enable_login_redirect'];
				} else {
					$enable_redirect = 'false';
				}				
				if($enable_redirect == 'true') {
					update_option('mo_ga_sso_enable_login_redirect', 'true');
				} else {
					update_option('mo_ga_sso_enable_login_redirect', '');
					update_option('mo_ga_sso_allow_wp_signin', '');
				}
				update_option( 'mo_ga_sso_message', 'Sign in options updated.');
				$this->mo_ga_sso_show_success_message();
			} else {
				update_option( 'mo_ga_sso_message', 'Please complete <a href="' . add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] ) . '" />Service Provider</a> configuration first.');
				$this->mo_ga_sso_show_error_message();
			}
		} else if(isset($_POST['option']) && $_POST['option'] == 'mo_ga_sso_forgot_password_form_option'){
			if(!mo_ga_sso_is_curl_installed()) {
				update_option( 'mo_ga_sso_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Resend OTP failed.');
				$this->mo_ga_sso_show_error_message();
				return;
			}
			
			$email = get_option('mo_ga_sso_admin_email');
			
			$customer = new Mo_Ga_Login_Registration_Action();
			$content = json_decode($customer->mo_ga_sso_forgot_password($email),true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0){
				update_option( 'mo_ga_sso_message','Your password has been reset successfully. Please enter the new password sent to ' . $email . '.');
				$this->mo_ga_sso_show_success_message();
			}else{
				update_option( 'mo_ga_sso_message','An error occured while processing your request. Please Try again.');
				$this->mo_ga_sso_show_error_message();
			}
		}
		}
	}
	
	function create_customer(){
		$customer = new Mo_Ga_Login_Registration_Action();
		$customerKey = json_decode( $customer->create_customer(), true );
		if( strcasecmp( $customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0 ) {
					$this->get_current_customer();
		} else if( strcasecmp( $customerKey['status'], 'SUCCESS' ) == 0 ) {
			update_option( 'mo_ga_sso_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_ga_sso_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_ga_sso_customer_token', $customerKey['token'] );
			update_option('mo_ga_sso_admin_password', '');
			update_option( 'mo_ga_sso_message', 'Thank you for registering with miniorange.');
			update_option('mo_ga_sso_registration_status','');
			delete_option('mo_ga_sso_verify_customer');
			delete_option('mo_ga_sso_new_registration');
			$this->mo_ga_sso_show_success_message();
			wp_redirect(admin_url().'admin.php?page=mo_ga_sso_settings&tab=licensing');
		}
		update_option('mo_ga_sso_admin_password', '');
	}

	function get_current_customer(){
		$customer = new Mo_Ga_Login_Registration_Action();
		$content = $customer->get_customer_key();
		$customerKey = json_decode( $content, true );
		if( json_last_error() == JSON_ERROR_NONE ) {
			update_option( 'mo_ga_sso_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_ga_sso_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_ga_sso_customer_token', $customerKey['token'] );
			update_option('mo_ga_sso_admin_password', '' );
			$certificate = get_option('mo_ga_sso_saml_x509_certificate');
			update_option( 'mo_ga_sso_message', 'Your account has been retrieved successfully.' );
			delete_option('mo_ga_sso_verify_customer');
			delete_option('mo_ga_sso_new_registration');
			$this->mo_ga_sso_show_success_message();
			wp_redirect(admin_url().'admin.php?page=mo_ga_sso_settings&tab=licensing');
		} else {
			update_option( 'mo_ga_sso_message', 'You already have an account with miniOrange. Please enter a valid password.');
			update_option('mo_ga_sso_verify_customer', 'true');
			delete_option('mo_ga_sso_new_registration');
			$this->mo_ga_sso_show_error_message();
		}
	}

	public function mo_ga_sso_check_empty_or_null( $value ) {
		if( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}
	
	function miniorange_ga_sso_menu() {
		//Add miniOrange SAML SSO
		$page = add_menu_page( 'MO SAML Settings ' . __( 'Configure SAML Identity Provider for SSO', 'mo_ga_sso_settings' ), 'Google Apps Login', 'administrator', 'mo_ga_sso_settings', array( $this, 'mo_login_widget_ga_sso_options' ), plugin_dir_url(__FILE__) . 'images/miniorange.png' );
	}

	
	function mo_ga_sso_redirect_for_authentication( $relay_state ) {
		
			if(mo_ga_sso_is_sp_configured() && !is_user_logged_in()) {
				$sendRelayState = $relay_state;
				$ssoUrl = get_option("mo_ga_sso_saml_login_url");
				$force_authn = get_option('mo_ga_sso_force_authentication');
				$acsUrl = site_url()."/";
				$issuer = plugins_url('/',__FILE__);
				$samlRequest = Mo_Ga_Saml_Utility::createAuthnRequest($acsUrl, $issuer, $force_authn);
				$redirect = $ssoUrl;
				if (strpos($ssoUrl,'?') !== false) {
					$redirect .= '&';
				} else {
					$redirect .= '?';
				}
				$redirect .= 'SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($sendRelayState);
		
				header('Location: '.$redirect);
				exit();
			}
		
	}
	
	function mo_ga_sso_authenticate() {
		$redirect_to = '';
		if( get_option('mo_ga_sso_enable_login_redirect') == 'true' ) {
			if( isset($_GET['loggedout']) && $_GET['loggedout'] == 'true' ) {
				header('Location: ' . site_url());
				exit();
			} elseif ( get_option('mo_ga_sso_allow_wp_signin') == 'true' ) {
				if( ( isset($_GET['mo_ga_sso_saml_sso']) && $_GET['mo_ga_sso_saml_sso'] == 'false' ) || ( isset($_POST['mo_ga_sso_saml_sso']) && $_POST['mo_ga_sso_saml_sso'] == 'false' ) ) {
					return;
				} elseif ( isset( $_REQUEST['redirect_to']) ) {
					$redirect_to = $_REQUEST['redirect_to'];
					if( strpos( $redirect_to, 'wp-admin') !== false && strpos( $redirect_to, 'mo_ga_sso_saml_sso=false') !== false) {
						return;
					} 
				}
			}
			if ( isset( $_REQUEST['redirect_to']) ) {
				$redirect_to = $_REQUEST['redirect_to'];
			}
			$this->mo_ga_sso_redirect_for_authentication( $redirect_to );
		}
	}
	
	function mo_ga_sso_modify_login_form() {
		echo '<input type="hidden" name="mo_ga_sso_saml_sso" value="false">'."\n";
	}
	
	function mo_get_ga_sso_shortcode(){
		if(!is_user_logged_in()){
			if(mo_ga_sso_is_sp_configured()){
				
					$html="<a href='".site_url()."/?option=mo_ga_sso_saml_user_login' >Login with ".get_option('mo_ga_sso_saml_identity_name').".</a>";
				
			}else
				$html = 'SP is not configured.';
		}
		else
			$html = 'Hello, '.wp_get_current_user()->display_name.' | <a href='.wp_logout_url(site_url()).'>Logout</a>';
		return $html;
	}
}
new mo_ga_sso_login;