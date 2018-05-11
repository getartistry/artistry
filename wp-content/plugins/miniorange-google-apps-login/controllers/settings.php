<?php
$page_list     = admin_url() . 'edit.php?post_type=page';

/*
 * If the current plugin loaded is oauth load oauth landing page or SAML Landing page.
 * */
if ( strcasecmp( get_mo_gsuite_option( 'mo_gsuite_select_saml_oauth' ), 'true' ) == 0 ) {
	include MOV_GSUITE_DIR . 'controllers/oauth/oauth-configuration.php';
	/*if ( sizeof( get_mo_gsuite_option( 'mo_oauth_apps_list_test' ) ) > 0 ) {
		if ( !isset($_REQUEST['action']) ) {
			include MOV_GSUITE_DIR . 'controllers/oauth/oauth-configured-applist.php';
		} else if ( strcasecmp( $_REQUEST['action'], 'updateapp' ) == 0){
			include MOV_GSUITE_DIR . 'controllers/oauth/oauth-configuration-update-app.php';
		}
	} else {
		include MOV_GSUITE_DIR . 'controllers/oauth/oauth-configuration.php';
	}*/
} else {
	include MOV_GSUITE_DIR . 'controllers/saml/saml-idp-setup.php';
}
