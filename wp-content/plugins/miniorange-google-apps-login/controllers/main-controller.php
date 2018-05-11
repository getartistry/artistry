<?php

$registerd        = Mo_GSuite_Utility::micr();
$plan             = Mo_GSuite_Utility::micv();
$disabled         = ! $registerd ? "disabled" : "";
$current_user     = wp_get_current_user();
$email            = get_mo_gsuite_option( "mo_gsuite_customer_validation_admin_email" );
$phone            = get_mo_gsuite_option( "mo_gsuite_customer_validation_transactionId" );
$controller       = MOV_GSUITE_DIR . 'controllers/';
$nonce            = Mo_Gsuite_Constants::FORM_NONCE;
$controller_oauth = MOV_GSUITE_DIR . 'controllers/oauth';
$controller_saml  = MOV_GSUITE_DIR . 'controllers/saml';

include $controller . 'navbar.php';

echo "<div class='mo-gsuite-opt-content'>";

if ( isset( $_GET['page'] ) ) {


	switch ( $_GET['page'] ) {
		case 'mogalsettings':
			include $controller . 'settings.php';
			break;

		case 'galoginaccount':
			include $controller . 'account.php';
			break;


		case 'gsuitepricing':
			include $controller . 'pricing.php';
			break;

		case 'configuration_oauth':
			include $controller_oauth . '/oauth-configuration.php';
			break;


		case 'updateapp_oauth':
			include $controller_oauth . '/oauth-configuration-update-app.php';
			break;


		case 'customization_oauth':
			include $controller_oauth . '/oauth-customization.php';
			break;


		case 'signinsettings_oauth':
			include $controller_oauth . '/oauth-signinsetting.php';
			break;
		case 'mapping_oauth':
			include $controller_oauth . '/oauth-mapping.php';
			break;

		case 'report_oauth':
			include $controller_oauth . '/oauth-report.php';
			break;


		/***********************************************************************************************
		 *                                    SAML
		 ************************************************************************************************/

		case 'service_provider_saml':
			include $controller_saml . '/saml-sp.php';
			break;

		case 'identity_provider_saml':
			include $controller_saml . '/saml-idp-setup.php';
			break;

		case 'sign_in_setting_saml':
			include $controller_saml . '/saml-sign-in-setting.php';
			break;

		case 'mapping_saml':
			include $controller_saml . '/saml-mapping.php';
			break;

		case 'saml_import_export_config':
			include $controller_saml . '/saml-import-export.php';
			break;

		case 'proxy_setup':
			include $controller_saml . '/saml-proxy-setup.php';
			break;

		default:
			include $controller . 'settings.php';
			break;

	}
	
	

	do_action( 'mo_otp_verification_add_on_controller' );

	if ( ! in_array( $_GET['page'], array( "gsuitepricing") ) ) {
		include $controller . 'support.php';
	}

}

echo "</div>";
/**/