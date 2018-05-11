<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 12-02-2018
 * Time: 10:42
 */
/*This function will render the page whichever needed. */
include MOV_GSUITE_DIR . "views/oauth/instructions/class-mo-oauth-instruction-html.php";
/*check if you have already configured apps*//*
var_dump(sizeof( get_mo_gsuite_option( 'mo_oauth_apps_list_test' ) ));exit;*/
if (  !Mo_GSuite_Utility::isBlank(get_mo_gsuite_option( 'mo_oauth_apps_list_test' )) && sizeof( get_mo_gsuite_option( 'mo_oauth_apps_list_test' ) ) > 0 ) {
	/*check if the action is set to be edit. Accordingly render the page.*/
	if ( !isset($_REQUEST['action']) ){
		include MOV_GSUITE_DIR . 'controllers/oauth/oauth-configured-applist.php';
	} else if ( strcasecmp( $_REQUEST['action'], 'edit' ) == 0){
		include MOV_GSUITE_DIR . 'controllers/oauth/oauth-configuration-update-app.php';
	}
} else {
	/*If no app is configured load the landing page.*/
	include MOV_GSUITE_DIR . 'views/oauth/oauth-configuration.php';
}

