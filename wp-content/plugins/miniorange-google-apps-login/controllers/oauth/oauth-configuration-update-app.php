<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 13-02-2018
 * Time: 13:15
 */
$supported_app_list = array( "facebook", "google", "windows", "eveonline" );

$appname  = "google";
$appslist = get_mo_gsuite_option( 'mo_oauth_apps_list_test' );
if ( ! array_key_exists( $appname, $appslist ) ) {
	return;
}

$currentappname = array_key_exists( $appname, $appslist ) ? $appname : "";
$currentapp     = array_key_exists( $appname, $appslist ) ? $appslist[ $appname ] : "";
$is_other_app = false;
if (! in_array( $currentappname, $supported_app_list )) {
	$is_other_app = true;
	include MOV_GSUITE_DIR . 'views/oauth/oauth-configuration-update-app-custom.php';
} else {
	include MOV_GSUITE_DIR . 'views/oauth/oauth-configuration-update-app-standard.php';
}

