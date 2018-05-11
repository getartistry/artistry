<?php

$otp_success_email = get_mo_gsuite_option( "mo_otp_success_email_message" ) ? get_mo_gsuite_option( 'mo_otp_success_email_message' ) : Mo_GSuite_Messages::showMessage( 'OTP_SENT_EMAIL' );
$otp_success_phone = get_mo_gsuite_option( "mo_otp_success_phone_message" ) ? get_mo_gsuite_option( 'mo_otp_success_phone_message' ) : Mo_GSuite_Messages::showMessage( 'OTP_SENT_PHONE' );
$otp_error_phone   = get_mo_gsuite_option( "mo_otp_error_phone_message" ) ? get_mo_gsuite_option( 'mo_otp_error_phone_message' ) : Mo_GSuite_Messages::showMessage( 'ERROR_OTP_PHONE' );

include MOV_GSUITE_DIR . 'views/messages.php';