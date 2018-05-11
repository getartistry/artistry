<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mo_GSuite_Messages {
	function __construct() {
		//created an array instead of messages instead of constant variables for Translation reasons.
		define( "MO_GSUITE_MESSAGES", serialize( array(
			//General Messages
			"OTP_SENT_PHONE"         => __( "A OTP (One Time Passcode) has been sent to ##phone## Please enter the OTP in the field below to verify your phone." ),
			"OTP_SENT_EMAIL"         => __( "A One Time Passcode has been sent to ##email## Please enter the OTP below to verify your Email Address. If you cannot see the email in your inbox, make sure to check your SPAM folder." ),
			"ERROR_OTP_EMAIL"        => __( "There was an error in sending the OTP. Please enter a valid email id or contact site Admin." ),
			"ERROR_OTP_PHONE"        => __( "There was an error in sending the OTP to the given Phone Number. Please Try Again or contact site Admin." ),
			"ERROR_PHONE_FORMAT"     => __( "##phone## is not a valid phone number. Please enter a valid Phone Number. E.g:+1XXXXXXXXXX" ),
			"CHOOSE_METHOD"          => __( "Please select one of the methods below to verify your account. A One time passcode will be sent to the selected method." ),
			"PLEASE_VALIDATE"        => __( "You need to verify yourself in order to submit this form" ),
			"ERROR_PHONE_BLOCKED"    => __( "##phone## has been blocked by the user. Please Try a different number or Contact site Admin." ),
			"ERROR_EMAIL_BLOCKED"    => __( "##email## has been blocked by the user. Please Try a different email or Contact site Admin." ),

			//ToolTip Messages
			"FORM_NOT_AVAIL_HEAD"    => __( "MY FORM IS NOT IN THE LIST" ),
			"FORM_NOT_AVAIL_BODY"    => __( "We are actively adding support for more forms. Please contact us using the support form on your right or email us at info@miniorange.com. While contacting us please include enough information about your registration form and how you intend to use this plugin. We will respond promptly." ),
			"CHANGE_SENDER_ID_BODY"  => __( "SenderID/Number is gateway specific. You will need to use your own SMS gateway for this." ),
			"CHANGE_SENDER_ID_HEAD"  => __( "CHANGE SENDER ID / NUMBER" ),
			"CHANGE_EMAIL_ID_BODY"   => __( "Sender Email is gateway specific. You will need to use your own Email gateway for this." ),
			"CHANGE_EMAIL_ID_HEAD"   => __( "CHANGE SENDER EMAIL ADDRESS" ),
			"INFO_HEADER"            => __( "WHAT DOES THIS MEAN?" ),
			"META_KEY_HEADER"        => __( "WHAT IS A META KEY?" ),
			"META_KEY_BODY"          => __( "WordPress stores addtional user data like phone number, age etc in the usermeta table in a key value pair. MetaKey is the key against which the additional value is stored in the usermeta table." ),
			"ENABLE_BOTH_BODY"       => __( "New users can validate their Email or Phone Number using either Email or Phone Verification.s They will be prompted during registration to choose one of the two verification methods." ),
			"COUNTRY_CODE_HEAD"      => __( "DON'T WANT USERS TO ENTER THEIR COUNTRY CODE?" ),
			"COUNTRY_CODE_BODY"      => __( "Choose the default country code that will be appended to the phone number entered by the users. This will allow your users to enter their phone numbers in the phone field without a country code." ),
			"WC_GUEST_CHECKOUT_HEAD" => __( "WHAT IS GUEST CHECKOUT?" ),
			"WC_GUEST_CHECKOUT_BODY" => __( "Verify customer's phone number or email address only when he is not logged in during checkout ( is a guest user )." ),

			//Support Query Messages
			"SUPPORT_FORM_VALUES"    => __( "Please submit your query along with email." ),
			"SUPPORT_FORM_SENT"      => __( "Thanks for getting in touch! We shall get back to you shortly." ),
			"SUPPORT_FORM_ERROR"     => __( "Your query could not be submitted. Please try again." ),

			//Setting Messages
			"SETTINGS_SAVED"         => __( "Settings saved successfully. You can go to your registration form page to test the plugin." ),
			"REG_ERROR"              => __( "Please register an account before trying to enable OTP verification for any form." ),
			"MSG_TEMPLATE_SAVED"     => __( "Settings saved successfully." ),
			"SMS_TEMPLATE_SAVED"     => __( "Your SMS configurations are saved successfully." ),
			"EMAIL_TEMPLATE_SAVED"   => __( "Your email configurations are saved successfully." ),
			"CUSTOM_MSG_SENT"        => __( "Message sent successfully" ),
			"CUSTOM_MSG_SENT_FAIL"   => __( "Error sending message. ERROR : {{error}}" ),
			"EXTRA_SETTINGS_SAVED"   => __( "Settings saved successfully." ),

			//Ninja Form Messages
			"NINJA_FORM_FIELD_ERROR" => __( "Please fill in the form id and field id of your Ninja Form" ),
			"NINJA_CHOOSE"           => __( "Please choose a Verification Method for Ninja Form." ),

			//Common AJAX Form Error Messages
			"EMAIL_MISMATCH"         => __( "The email OTP was sent to and the email in contact submission do not match." ),
			"PHONE_MISMATCH"         => __( "The phone number OTP was sent to and the phone number in contact submission do not match." ),
			"ENTER_PHONE"            => __( "You will have to provide a Phone Number before you can verify it." ),
			"ENTER_EMAIL"            => __( "You will have to provide an Email Address before you can verify it." ),

			//Contact Form 7 messages
			"CF7_PROVIDE_EMAIL_KEY"  => __( "Please Enter the name of the email address field you created in User Contact Form 7." ),
			"CF7_CHOOSE"             => __( "Please choose a Verification Method for Contact Form 7." ),

			//BuddyPress Form Messages
			"BP_PROVIDE_FIELD_KEY"   => __( "Please Enter the Name of the phone number field you created in BuddyPress." ),
			"BP_CHOOSE"              => __( "Please choose a Verification Method for BuddyPress Registration Form." ),

			//Ultimate Member Registration Messages
			"UM_CHOOSE"              => __( "Please choose a Verification Method for Ultimate Member Registration Form." ),

			//Event Registration Messages
			"EVENT_CHOOSE"           => __( "Please choose a Verification Method for Event Registration Form." ),

			//UserUltra Messages
			"UULTRA_PROVIDE_FIELD"   => __( "Please Enter the Field Key of the phone number field you created in Users Ultra Registration form." ),
			"UULTRA_CHOOSE"          => __( "Please choose a Verification Method for Users Ultra Registration Form." ),

			//CRF Messages
			"CRF_PROVIDE_PHONE_KEY"  => __( "Please Enter the label name of the phone number field you created in Custom User Registration form." ),
			"CRF_PROVIDE_EMAIL_KEY"  => __( "Please Enter the label name of the email number field you created in Custom User Registration form." ),
			"CRF_CHOOSE"             => __( "Please choose a Verification Method for Custom User Registration Form." ),

			//Simplr Form Messages
			"SMPLR_PROVIDE_FIELD"    => __( "Please Enter the Field Key of the phone number field you created in Simplr User Registration form." ),
			"SIMPLR_CHOOSE"          => __( "Please choose a Verification Method for Simplr User Registration Form." ),

			//UserProfile Made Easy Messages
			"UPME_PROVIDE_PHONE_KEY" => __( "Please Enter the Field Key of the phone number field you created in User Profile Made Easy Registration form." ),
			"UPME_CHOOSE"            => __( "Please choose a Verification Method for User Profile Made Easy Registration Form." ),

			//Profile Builder Messages
			"PB_PROVIDE_PHONE_KEY"   => __( "Please Enter the Field Key of the phone number field you created in Profile Builder Registration form." ),
			"PB_CHOOSE"              => __( "Please choose a Verification Method for Profile Builder Registration Form." ),

			//Pie Registration Form Messages
			"PIE_PROVIDE_PHONE_KEY"  => __( "Please Enter the Meta Key of the phone field." ),
			"PIE_CHOOSE"             => __( "Please choose a Verification Method for Pie Registration Form." ),

			//WooCommerce Messages
			"ENTER_PHONE_CODE"       => __( "Please enter the verification code sent to your phone" ),
			"ENTER_EMAIL_CODE"       => __( "Please enter the verification code sent to your email address" ),
			"ENTER_VERIFY_CODE"      => __( "Please verify yourself before submitting the form." ),
			"PHONE_VALIDATION_MSG"   => __( "Enter your mobile number below for verification :" ),
			"WC_CHOOSE_METHOD"       => __( "Please choose a Verification Method for Woocommerce Default Registration Form." ),
			"WC_SOCIAL_CHOOSE"       => __( "Please choose a Verification Method for Woocommerce Checkout Registration Form." ),

			//Theme My Login Messages
			"TMLM_CHOOSE"            => __( "Please choose a Verification Method for Theme My Login Registration Form." ),

			//Default Registration Form
			"ENTER_PHONE_DEFAULT"    => __( "ERROR: Please enter a valid phone number." ),
			"WP_CHOOSE_METHOD"       => __( "Please choose a Verification Method for WordPress Default Registration Form." ),

			//UserPro Registration Form
			"USERPRO_CHOOSE"         => __( "Please choose a Verification Method for UserPro Registration Form." ),
			"USERPRO_VERIFY"         => __( "Please verify yourself before submitting the form." ),

			//Registration Messages
			"PASS_LENGTH"            => __( "Choose a password with minimum length 6." ),
			"PASS_MISMATCH"          => __( "Password and Confirm Password do not match." ),
			"OTP_SENT"               => __( "A passcode has been sent to {{method}}. Please enter the otp below to verify your account." ),
			"ERR_OTP"                => __( "There was an error in sending OTP. Please click on Resend OTP link to resend the OTP." ),
			"REG_SUCCESS"            => __( "Your account has been retrieved successfully." ),
			"ACCOUNT_EXISTS"         => __( "You already have an account with miniOrange. Please enter a valid password." ),
			"REG_COMPLETE"           => __( "Registration complete!" ),
			"INVALID_OTP"            => __( "Invalid one time passcode. Please enter a valid passcode." ),
			"RESET_PASS"             => __( "You password has been reset successfully and sent to your registered email. Please check your mailbox." ),
			"REQUIRED_FIELDS"        => __( "Please enter all the required fields" ),
			"REQUIRED_OTP"           => __( "Please enter a value in OTP field." ),
			"INVALID_SMS_OTP"        => __( "There was an error in sending sms. Please Check your phone number." ),
			"NEED_UPGRADE_MSG"       => __( "You have not upgraded yet. Check licensing tab to upgrade to premium version." ),
			"VERIFIED_LK"            => __( "Your license is verified. You can now setup the plugin." ),
			"LK_IN_USE"              => __( "License key you have entered has already been used. Please enter a key which has not been used before on any other instance or if you have exhausted all your keys then check licensing tab to buy more." ),
			"INVALID_LK"             => __( "You have entered an invalid license key. Please enter a valid license key." ),
			"REG_REQUIRED"           => __( "Please complete your registration to save configuration." ),

			//common messages
			"UNKNOWN_ERROR"          => __( "Error processing your request. Please try again." ),
			"MO_REG_ENTER_PHONE"     => __( "Phone with country code eg. +1xxxxxxxxxx" ),

			//License Messages
			"UPGRADE_MSG"            => __( "Thank you. You have upgraded to {{plan}}." ),
			"FREE_PLAN_MSG"          => __( "You are on our FREE plan. Check Licensing Tab to learn how to upgrade." ),
			"TRANS_LEFT_MSG"         => __( "You have <b><i>{{email}} Email Transactions</i></b> and <b><i>{{phone}} Phone Transactions</i></b> remaining." ),
			//'<a href="{{syncurl}}" class="button button-primary">SYNC</a>';,
			"YOUR_GATEWAY_HEADER"    => __( "WHAT DO YOU MEAN BY YOUR GATEWAY? WHEN DO I OPT FOR THIS PLAN?" ),
			"YOUR_GATEWAY_BODY"      => __( "Your Gateway means that you have your own SMS or Email Gateway for delivering OTP to the user's email or phone. The plugin will handle OTP generation and verification but your existing gateway would be used to deliver the message to the user. <br/><br/>Hence, the One Time Cost of the plugin. <b><i>NOTE:</i></b> You will still need to pay SMS and Email delivery charges to your gateway separately." ),
			"MO_GATEWAY_HEADER"      => __( "WHAT DO YOU MEAN BY miniOrange GATEWAY? WHEN DO I OPT FOR THIS PLAN?" ),
			"MO_GATEWAY_BODY"        => __( "miniOrange Gateway means that you want the complete package of OTP generation, delivery ( to user's phone or email ) and verification. Opt for this plan when you don't have your own SMS or Email gateway for message delivery. <br/><br/> <b><i>NOTE:</i></b> SMS Delivery charges depend on the country you want to send the OTP to. Click on the Upgrade Now button below and select your country to see the full pricing." ),

			//Gravity Forms Messages
			"GRAVITY_CHOOSE"         => __( "Please choose a Verification Method for Gravity Form." ),

			//WP Login Form Messages
			"PHONE_NOT_FOUND"        => __( "Sorry, but you don't have a registered phone number." ),
			"REGISTER_PHONE_LOGIN"   => __( "A new security system has been enabled for you. Please register your phone to continue." ),

			//WP Member messages
			"WP_MEMBER_CHOOSE"       => __( "Please choose a Verification Method for WP Member Form." ),

			//Ultimate Membership Pro
			"UMPRO_VERIFY"           => __( "Please verify yourself before submitting the form." ),
			"UMPRO_CHOOSE"           => __( "Please choose a verification method for Ultimate Membership Pro form." ),

			//Classify Theme
			"CLASSIFY_THEME"         => __( "Please choose a Verification Method for Classify Theme." ),

			//Reales Theme
			"REALES_THEME"           => __( "Please choose a Verification Method for Reales WP Theme." ),

			//WP Default Login
			"WP_LOGIN_MISSING_KEY"   => __( "Please provide a meta key value for users phone numbers." ),
			"PHONE_EXISTS"           => __( "Phone Number is already in use. Please use another number." ),

			//WP Comments
			"WPCOMMNENT_CHOOSE"      => __( "Please choose a Verification Method for WordPress Comments Form" ),

			//FormCraft Error
			"FORMCRAFT_CHOOSE"       => __( "Please choose a Verification Method for FormCraft Form" ),
			"FORMCRAFT_FIELD_ERROR"  => __( "Please fill in the form id and field id of your FormCraft Form" ),

			//wpeMember form
			"WPEMEMBER_CHOOSE"       => __( "Please choose a Verification Method for WpEmember Registration Form" ),

			//DocDirectTheme
			"DOC_DIRECT_VERIFY"      => __( "Please verify yourself before submitting the form" ),
			"DCD_ENTER_VERIFY_CODE"  => __( "Please enter a verification code to verify yourself" ),
			"DOC_DIRECT_CHOOSE"      => __( "Please choose a Verification Method for DocDirect Theme." ),

			//Wp Forms
			"WPFORM_FIELD_ERROR"     => __( "Please check if you have provided all the required information for WP Forms." ),

			//Caldera Forms
			"CALDERA_FIELD_ERROR"    => __( "Please check if you have provided all the required information for Caldera Forms." ),

			//Popup Template Messages
			"REQUIRED_TAGS"          => __( "NOTE: Please make sure that the template has the {{TAG}} tag. It is necessary for the popup to work." ),
			"TEMPLATE_SAVED"         => __( "Template Saved Successfully." ),

			//for onprem plugin
			"DEFAULT_SMS_TEMPLATE"   => __( "Dear Customer, Your OTP is ##otp##. Use this Passcode to complete your transaction. Thank you." ),
			"EMAIL_SUBJECT"          => __( "Your Requested One Time Passcode" ),
			"DEFAULT_EMAIL_TEMPLATE" => __( "Dear Customer, \n\nYour One Time Passcode for completing your transaction is: ##otp##\nPlease use this Passcode to complete your transaction. Do not share this Passcode with anyone.\n\nThank You,\nminiOrange Team." ),

			#########################################################################################################
			/*OAUTH MESSAGES*/
			#########################################################################################################

			"OAUTH_CUSTOMIZATION_SETTINGS_SAVED" => __( "Customization settings saved." ),

			"OAUTH_CUSTOMIZATION_SETTINGS_RESET_SUCCESS"=>__("Your Customization Settings Are Reset Successfully"),

			"OAUTH_MORE_THAN_ONE_APP_ERROR"              => __( "Free Version Allows only one app. Please upgrade to allow more than one app. " ),

			"OAUTH_CLIENT_ID_ERROR"              => __( "Please enter valid Client ID and Client Secret." ),
			"OAUTH_APP_DELETE_ACTION_SUCCESS"    => __( "App deleted Successfully" ),
			"OAUTH_APP_DELETE_ACTION_ERROR"      => __( "Something went wrong while deleting the app" ),


			############################################################################
			/*SAML MESSAGES*/
			##########################################################################

			"SAML_SIGN_IN_OPTION_SAVED" =>__("Sign in options updated."),
			"SAML_INVALID_METADATA_FILE" =>__("Please provide a valid metadata file."),

			"SAML_SIGN_IN_OPTION_SAVED" =>__("Sign in options updated."),

			"SAML_SP_SAVED_SUCESS"=>__("Service Provider details saved successfully."),
			"SAML_IDP_SAVED_SUCESS" =>__("Identity Provider details saved successfully."),

			"SAML_SP_VALIDATION_ERROR"=>__("All the fields are required. Please enter valid entries."),

			"SAML_SP_PREG_MATCH_ERROR"=>__("Please match the requested format for Identity Provider Name. Only alphabets, numbers and underscore is allowed."),

			"SAML_SP_INVALID_CERTIFICATE"=>__("Invalid certificate: Please provide a valid certificate."),
			
			"SAML_PROXY_SETTING_SAVED_SUCCESS"=>__("Proxy settings saved successfully"),

			"SAML_PROXY_SETTING_RESET_SUCCESS"=>__("Proxy settings reset successfully"),


			"SAML_ATTRIBUTE_MAPPING_SAVED"=> __("Attribute Mapping details saved successfully"),

			"SAML_ROLE_MAPPING_SAVED"=> __("ROLE Mapping details saved successfully"),

			"POST_ARRAY_EMPTY"=> __("One Or few Fields are Empty. Please Submit Again"),


		) ) );
	}

	public static function showMessage( $messageKeys, $data = array() ) {
		$displayMessage = "";
		$messageKeys    = explode( " ", $messageKeys );
		$messages       = unserialize( MO_GSUITE_MESSAGES );
		foreach ( $messageKeys as $messageKey ) {
			if ( Mo_GSuite_Utility::isBlank( $messageKey ) ) {
				return $displayMessage;
			}
			$formatMessage = mo_gsuite_( $messages[ $messageKey ] );
			foreach ( $data as $key => $value ) {
				$formatMessage = str_replace( "{{" . $key . "}}", $value, $formatMessage );
			}
			$displayMessage .= $formatMessage;
		}

		return $displayMessage;
	}
}

new Mo_GSuite_Messages;

class Mo_Gsuite_Display_Messages {
	private $message;
	private $type;

	function __construct( $message, $type ) {
		$this->_message = $message;
		$this->_type    = $type;
		add_action( 'admin_notices', array( $this, 'render' ) );
	}

	function render() {
		switch ( $this->_type ) {
			case 'CUSTOM_MESSAGE':
				echo mo_gsuite_( $this->_message );
				break;
			case 'NOTICE':
				echo '	<div style="margin-top:1%;" class="is-dismissible notice notice-warning"> <p>' . mo_gsuite_( $this->_message ) . '</p> </div>';
				break;
			case 'ERROR':
				$this->mo_gsuite_failed_message();
				break;
			case 'SUCCESS':
				$this->mo_gsuite_success_message();
				break;
		}
	}

	function mo_gsuite_success_message() {

		echo '<script>
				var message="'.$this->_message.'";
				
				jQuery(document).ready(function() {
				  jQuery("#mo_gsuite_messages").append("<div style=\'margin-top:1%;\' class=\'notice notice-success is-dismissible\'> <p>"+message+"</p></div>");
				})
             </script>';
	}

	function mo_gsuite_failed_message() {

		echo '<script>
				var message="'.$this->_message.'";
				jQuery(document).ready(function() {
				  jQuery("#mo_gsuite_messages").append("<div style=\'margin-top:1%;\' class=\'notice notice-error is-dismissible\'> <p>"+message+"</p></div>");
				})
             </script>';
	}

}
