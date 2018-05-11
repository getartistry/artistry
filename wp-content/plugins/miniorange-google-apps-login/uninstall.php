<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_site_option( 'mo_oauth_apps_list_test' );
delete_site_option( 'mo_gsuite_customer_validation_admin_email' );
delete_site_option( 'mo_gsuite_customer_validation_company_name' );
delete_site_option( 'mo_gsuite_customer_validation_first_name' );
delete_site_option( 'mo_customer_validation_last_name' );
delete_site_option( 'mo_gsuite_select_saml_oauth' );
$users = get_users( array() );
foreach ( $users as $user ) {
	$attachment_id = get_user_meta( $user->ID, 'mo_oauth_avatar_manager_custom_avatar', true );
	if ( ! empty( $attachment_id ) ) {
		mo_oauth_avatar_manager_delete_avatar($attachment_id);
	}
	delete_user_meta($user->ID, 'user_eveonline_character_name');
	delete_user_meta($user->ID, 'user_eveonline_corporation_name');
	delete_user_meta($user->ID, 'user_eveonline_alliance_name');
}

delete_option('host_name');
delete_option('mo_oauth_icon_width');
delete_option('mo_oauth_icon_height');
delete_option('mo_oauth_icon_margin');
delete_option('mo_oauth_icon_configure_css');


delete_option('mo_gsuite_customer_validation_admin_customer_key');
delete_option('mo_gsuite_customer_validation_admin_api_key');
delete_option('mo_gsuite_customer_validation_customer_token');
delete_option('mo_gsuite_oauth_google_enable');
delete_option('mo_oauth_google_scope');
delete_option('mo_oauth_google_client_id');
delete_option('mo_oauth_google_client_secret');
delete_option('mo_oauth_google_message');
delete_option('mo_oauth_facebook_enable');
delete_option('mo_oauth_facebook_scope');
delete_option('mo_oauth_facebook_client_id');
delete_option('mo_oauth_facebook_client_secret');
delete_option('mo_oauth_facebook_message');
delete_option('mo_oauth_eveonline_enable');
delete_option('mo_oauth_new_customer');
delete_option('mo_oauth_eveonline_scope');
delete_option('mo_oauth_eveonline_client_id');
delete_option('mo_oauth_eveonline_client_secret');
delete_option('mo_oauth_eveonline_message');
delete_option('message');
delete_option('mo_eve_api_key');
delete_option('mo_eve_verification_code');
delete_option('mo_eve_allowed_corps');
delete_option('mo_eve_allowed_alliances');
delete_option('mo_eve_allowed_char_name');
delete_option('new_registration');
delete_option('mo_oauth_registration_status');



if (! is_multisite ()) {
	// delete all stored key-value pairs
	delete_option ( 'mo_saml_host_name' );
	delete_option ( 'mo_saml_enable_cloud_broker' );
	delete_option ( 'mo_saml_new_registration' );
	delete_option ( 'mo_saml_admin_phone' );
	delete_option ( 'mo_gsuite_customer_validation_admin_email' );
	delete_option ( 'mo_saml_admin_password' );
	delete_option ( 'mo_saml_verify_customer' );
	delete_option ( 'mo_saml_admin_customer_key' );
	delete_option ( 'mo_saml_admin_api_key' );
	delete_option ( 'mo_saml_customer_token' );
	delete_option ( 'mo_saml_message' );
	delete_option ( 'mo_saml_registration_status' );
	delete_option ( 'saml_idp_config_id' );
	delete_option ( 'saml_identity_name' );
	delete_option ( 'saml_login_url' );
	delete_option ( 'saml_logout_url' );
	delete_option ( 'saml_issuer' );
	delete_option ( 'saml_x509_certificate' );
	delete_option ( 'saml_response_signed' );
	delete_option ( 'saml_assertion_signed' );
	delete_option ( 'saml_am_first_name' );
	delete_option ( 'saml_am_username' );
	delete_option ( 'saml_am_email' );
	delete_option ( 'saml_am_last_name' );
	delete_option ( 'saml_am_default_user_role' );
	delete_option ( 'saml_am_role_mapping' );
	delete_option ( 'saml_am_group_name' );
	delete_option ( 'mo_saml_idp_config_complete' );
	delete_option ( 'mo_saml_enable_login_redirect' );
	delete_option ( 'mo_saml_allow_wp_signin' );
	delete_option ( 'saml_am_account_matcher' );
	delete_option ( 'mo_saml_transactionId' );
	delete_option ( 'mo_saml_force_authentication' );
	delete_option ( 'saml_am_dont_allow_unlisted_user_role' );
	delete_option ( 'mo_saml_free_version' );
	delete_option ( 'mo_saml_admin_company' );
	delete_option ( 'mo_saml_admin_first_name' );
	delete_option ( 'mo_saml_admin_last_name' );
	delete_option('mo_proxy_host');
	delete_option('mo_proxy_username');
	delete_option('mo_proxy_port');
	delete_option('mo_proxy_password');
	delete_option('mo_saml_show_mo_idp_message');

	$users = get_users( array() );
	foreach ( $users as $user ) {
		delete_user_meta($user->ID, 'mo_saml_session_index');
		delete_user_meta($user->ID, 'mo_saml_name_id');
	}
} else {
	global $wpdb;
	$blog_ids = $wpdb->get_col ( "SELECT blog_id FROM $wpdb->blogs" );
	$original_blog_id = get_current_blog_id ();

	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog ( $blog_id );
		// delete all your options
		// E.g: delete_option( {option name} );
		delete_option ( 'mo_saml_host_name' );
		delete_option ( 'mo_saml_enable_cloud_broker' );
		delete_option ( 'mo_saml_new_registration' );
		delete_option ( 'mo_saml_admin_phone' );
		delete_option ( 'mo_gsuite_customer_validation_admin_email' );
		delete_option ( 'mo_saml_admin_password' );
		delete_option ( 'mo_saml_verify_customer' );
		delete_option ( 'mo_saml_admin_customer_key' );
		delete_option ( 'mo_saml_admin_api_key' );
		delete_option ( 'mo_saml_customer_token' );
		delete_option ( 'mo_saml_message' );
		delete_option ( 'mo_saml_registration_status' );
		delete_option ( 'saml_idp_config_id' );
		delete_option ( 'saml_identity_name' );
		delete_option ( 'saml_login_url' );
		delete_option ( 'saml_logout_url' );
		delete_option ( 'saml_issuer' );
		delete_option ( 'saml_x509_certificate' );
		delete_option ( 'saml_response_signed' );
		delete_option ( 'saml_assertion_signed' );
		delete_option ( 'saml_am_first_name' );
		delete_option ( 'saml_am_username' );
		delete_option ( 'saml_am_email' );
		delete_option ( 'saml_am_last_name' );
		delete_option ( 'saml_am_default_user_role' );
		delete_option ( 'saml_am_role_mapping' );
		delete_option ( 'saml_am_group_name' );
		delete_option ( 'mo_saml_idp_config_complete' );
		delete_option ( 'mo_saml_enable_login_redirect' );
		delete_option ( 'mo_saml_allow_wp_signin' );
		delete_option ( 'saml_am_account_matcher' );
		delete_option ( 'mo_saml_transactionId' );
		delete_option ( 'mo_saml_force_authentication' );
		delete_option ( 'saml_am_dont_allow_unlisted_user_role' );
		delete_option ( 'mo_saml_free_version' );
		delete_option('mo_saml_show_mo_idp_message');
		$users = get_users( array() );
		foreach ( $users as $user ) {
			delete_user_meta($user->ID, 'mo_saml_session_index');
			delete_user_meta($user->ID, 'mo_saml_name_id');
		}
	}
	switch_to_blog ( $original_blog_id );
}

?>