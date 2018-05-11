<?php

$url = Mo_Gsuite_Constants::HOSTNAME . '/moas/login' . '?redirectUrl=' . Mo_Gsuite_Constants::HOSTNAME . '/moas/viewlicensekeys';

if ( get_mo_gsuite_option( 'mo_gsuite_customer_validation_registration_status' ) == 'MO_OTP_DELIVERED_SUCCESS'
     || get_mo_gsuite_option( 'mo_gsuite_customer_validation_registration_status' ) == 'MO_OTP_VALIDATION_FAILURE'
     || get_mo_gsuite_option( 'mo_gsuite_customer_validation_registration_status' ) == 'MO_OTP_DELIVERED_FAILURE' ) {
	$admin_phone = get_mo_gsuite_option( 'mo_gsuite_customer_validation_transactionId' ) ? get_mo_gsuite_option( 'mo_gsuite_customer_validation_transactionId' ) : "";
	include MOV_GSUITE_DIR . 'views/account/verify.php';
} else if ( get_mo_gsuite_option( 'mo_gsuite_customer_validation_verify_customer' ) == 'true' || ( get_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_email' ) && ! get_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_customer_key' ) ) ) {
	$admin_email = get_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_email' ) ? get_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_email' ) : "";
	include MOV_GSUITE_DIR . 'views/account/login.php';
} else if ( ! Mo_GSuite_Utility::micr() ) {
	delete_site_option( 'password_mismatch' );
	update_mo_gsuite_option( 'mo_gsuite_customer_validation_new_registration', 'true' );
	$current_user = wp_get_current_user();
	$admin_phone  = get_mo_gsuite_option( 'mo_gsuite_customer_validation_transactionId' ) ? get_mo_gsuite_option( 'mo_gsuite_customer_validation_transactionId' ) : "";
	include MOV_GSUITE_DIR . 'views/account/register.php';
} else {
	$customer_id = get_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_customer_key' );
	$api_key     = get_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_api_key' );
	$token       = get_mo_gsuite_option( 'mo_gsuite_customer_validation_customer_token' );
	$plan_type   = Mo_GSuite_Utility::micv() ? 'otp_recharge_plan' : 'wp_otp_verification_basic_plan';
	$vl          = Mo_GSuite_Utility::mclv();

	include MOV_GSUITE_DIR . 'views/account/profile.php';
}