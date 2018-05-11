<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mo_GSuite_PolyLangStrings {
	public function __construct() {
		define( "MO_GSUITE_POLY_STRINGS", serialize( array(

			'OTP_SENT_PHONE'         => Mo_GSuite_Messages::showMessage( 'OTP_SENT_PHONE' ),
			'OTP_SENT_EMAIL'         => Mo_GSuite_Messages::showMessage( 'OTP_SENT_EMAIL' ),
			'ERROR_OTP_EMAIL'        => Mo_GSuite_Messages::showMessage( 'ERROR_OTP_EMAIL' ),
			'ERROR_OTP_PHONE'        => Mo_GSuite_Messages::showMessage( 'ERROR_OTP_PHONE' ),
			'ERROR_PHONE_FORMAT'     => Mo_GSuite_Messages::showMessage( 'ERROR_PHONE_FORMAT' ),
			'CHOOSE_METHOD'          => Mo_GSuite_Messages::showMessage( 'CHOOSE_METHOD' ),
			'PLEASE_VALIDATE'        => Mo_GSuite_Messages::showMessage( 'PLEASE_VALIDATE' ),
			'ERROR_PHONE_BLOCKED'    => Mo_GSuite_Messages::showMessage( 'ERROR_PHONE_BLOCKED' ),
			'ERROR_EMAIL_BLOCKED'    => Mo_GSuite_Messages::showMessage( 'ERROR_EMAIL_BLOCKED' ),
			'INVALID_OTP'            => Mo_GSuite_Messages::showMessage( 'INVALID_OTP' ),
			'EMAIL_MISMATCH'         => Mo_GSuite_Messages::showMessage( 'EMAIL_MISMATCH' ),
			'PHONE_MISMATCH'         => Mo_GSuite_Messages::showMessage( 'PHONE_MISMATCH' ),
			'ENTER_PHONE'            => Mo_GSuite_Messages::showMessage( 'ENTER_PHONE' ),
			'ENTER_EMAIL'            => Mo_GSuite_Messages::showMessage( 'ENTER_EMAIL' ),
			'ENTER_PHONE_CODE'       => Mo_GSuite_Messages::showMessage( 'ENTER_PHONE_CODE' ),
			'ENTER_EMAIL_CODE'       => Mo_GSuite_Messages::showMessage( 'ENTER_EMAIL_CODE' ),
			'ENTER_VERIFY_CODE'      => Mo_GSuite_Messages::showMessage( 'ENTER_VERIFY_CODE' ),
			'PHONE_VALIDATION_MSG'   => Mo_GSuite_Messages::showMessage( 'PHONE_VALIDATION_MSG' ),
			'MO_REG_ENTER_PHONE'     => Mo_GSuite_Messages::showMessage( 'MO_REG_ENTER_PHONE' ),
			'UNKNOWN_ERROR'          => Mo_GSuite_Messages::showMessage( 'UNKNOWN_ERROR' ),
			'PHONE_NOT_FOUND'        => Mo_GSuite_Messages::showMessage( 'PHONE_NOT_FOUND' ),
			'REGISTER_PHONE_LOGIN'   => Mo_GSuite_Messages::showMessage( 'REGISTER_PHONE_LOGIN' ),
			'DEFAULT_SMS_TEMPLATE'   => Mo_GSuite_Messages::showMessage( 'DEFAULT_SMS_TEMPLATE' ),
			'EMAIL_SUBJECT'          => Mo_GSuite_Messages::showMessage( 'EMAIL_SUBJECT' ),
			'DEFAULT_EMAIL_TEMPLATE' => Mo_GSuite_Messages::showMessage( 'DEFAULT_EMAIL_TEMPLATE' ),
			'DEFAULT_BOX_HEADER'     => 'Validate OTP (One Time Passcode)',
			'GO_BACK'                => '&larr; Go Back',
			'RESEND_OTP'             => 'Resend OTP',
			'VALIDATE_OTP'           => 'Validate OTP',
			'VERIFY_CODE'            => 'Verify Code',
			'SEND_OTP'               => 'Send OTP',
			'VALIDATE_PHONE_NUMBER'  => 'Validate your Phone Number',
			'VERIFY_CODE_DESC'       => 'Enter Verification Code',

		) ) );
	}
}

new Mo_GSuite_PolyLangStrings;