<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

if (! is_multisite ()) {
	// delete all stored key-value pairs
	delete_option ( 'mo_ga_sso_host_name' );
	delete_option ( 'mo_ga_sso_new_registration' );
	delete_option ( 'mo_ga_sso_admin_phone' );
	delete_option ( 'mo_ga_sso_admin_email' );
	delete_option ( 'mo_ga_sso_admin_password' );
	delete_option ( 'mo_ga_sso_verify_customer' );
	delete_option ( 'mo_ga_sso_admin_customer_key' );
	delete_option ( 'mo_ga_sso_admin_api_key' );
	delete_option ( 'mo_ga_sso_customer_token' );
	delete_option ( 'mo_ga_sso_message' );
	delete_option ( 'mo_ga_sso_registration_status' );
	delete_option ( 'mo_ga_sso_saml_idp_config_id' );
	delete_option ( 'mo_ga_sso_saml_identity_name' );
	delete_option ( 'mo_ga_sso_saml_login_url' );
	delete_option ( 'mo_ga_sso_saml_logout_url' );
	delete_option ( 'mo_ga_sso_saml_issuer' );
	delete_option ( 'mo_ga_sso_saml_x509_certificate' );
	delete_option ( 'mo_ga_sso_saml_response_signed' );
	delete_option ( 'mo_ga_sso_saml_assertion_signed' );
	delete_option ( 'mo_ga_sso_first_name' );
	delete_option ( 'mo_ga_sso_username' );
	delete_option ( 'mo_ga_sso_email' );
	delete_option ( 'mo_ga_sso_last_name' );
	delete_option ( 'mo_ga_sso_default_user_role' );
	delete_option ( 'mo_ga_sso_role_mapping' );
	delete_option ( 'mo_ga_sso_group_name' );
	delete_option ( 'mo_ga_sso_idp_config_complete' );
	delete_option ( 'mo_ga_sso_enable_login_redirect' );
	delete_option ( 'mo_ga_sso_allow_wp_signin' );
	delete_option ( 'mo_ga_sso_account_matcher' );
	delete_option ( 'mo_ga_sso_transactionId' );
	delete_option ( 'mo_ga_sso_force_authentication' );
	delete_option ( 'mo_ga_sso_dont_allow_unlisted_user_role' );
	delete_option ( 'mo_ga_sso_admin_company' );
	delete_option ( 'mo_ga_sso_admin_first_name' );
	delete_option ( 'mo_ga_sso_admin_last_name' );
	
	$users = get_users( array() );
	foreach ( $users as $user ) {
		delete_user_meta($user->ID, 'mo_ga_sso_session_index');
		delete_user_meta($user->ID, 'mo_ga_sso_name_id');
	}
} else {
	global $wpdb;
	$blog_ids = $wpdb->get_col ( "SELECT blog_id FROM $wpdb->blogs" );
	$original_blog_id = get_current_blog_id ();
	
	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog ( $blog_id );
		// delete all your options
		// E.g: delete_option( {option name} );
		delete_option ( 'mo_ga_sso_host_name' );
		delete_option ( 'mo_ga_sso_new_registration' );
		delete_option ( 'mo_ga_sso_admin_phone' );
		delete_option ( 'mo_ga_sso_admin_email' );
		delete_option ( 'mo_ga_sso_admin_password' );
		delete_option ( 'mo_ga_sso_verify_customer' );
		delete_option ( 'mo_ga_sso_admin_customer_key' );
		delete_option ( 'mo_ga_sso_admin_api_key' );
		delete_option ( 'mo_ga_sso_customer_token' );
		delete_option ( 'mo_ga_sso_message' );
		delete_option ( 'mo_ga_sso_registration_status' );
		delete_option ( 'mo_ga_sso_saml_idp_config_id' );
		delete_option ( 'mo_ga_sso_saml_identity_name' );
		delete_option ( 'mo_ga_sso_saml_login_url' );
		delete_option ( 'mo_ga_sso_saml_logout_url' );
		delete_option ( 'mo_ga_sso_saml_issuer' );
		delete_option ( 'mo_ga_sso_saml_x509_certificate' );
		delete_option ( 'mo_ga_sso_saml_response_signed' );
		delete_option ( 'mo_ga_sso_saml_assertion_signed' );
		delete_option ( 'mo_ga_sso_first_name' );
		delete_option ( 'mo_ga_sso_username' );
		delete_option ( 'mo_ga_sso_email' );
		delete_option ( 'mo_ga_sso_last_name' );
		delete_option ( 'mo_ga_sso_default_user_role' );
		delete_option ( 'mo_ga_sso_role_mapping' );
		delete_option ( 'mo_ga_sso_group_name' );
		delete_option ( 'mo_ga_sso_idp_config_complete' );
		delete_option ( 'mo_ga_sso_enable_login_redirect' );
		delete_option ( 'mo_ga_sso_allow_wp_signin' );
		delete_option ( 'mo_ga_sso_account_matcher' );
		delete_option ( 'mo_ga_sso_transactionId' );
		delete_option ( 'mo_ga_sso_force_authentication' );
		delete_option ( 'mo_ga_sso_dont_allow_unlisted_user_role' );
		$users = get_users( array() );
		foreach ( $users as $user ) {
			delete_user_meta($user->ID, 'mo_ga_sso_session_index');
			delete_user_meta($user->ID, 'mo_ga_sso_name_id');
		}
	}
	switch_to_blog ( $original_blog_id );
}
?>