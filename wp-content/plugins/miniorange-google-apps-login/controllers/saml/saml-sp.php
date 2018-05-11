<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 15-02-2018
 * Time: 17:16
 */

//include MOV_GSUITE_DIR."views/saml/saml-upload-metadata.php";


if(isset($_REQUEST['action'])&&strcasecmp($_REQUEST['action'],'upload_metadata')==0){
	include MOV_GSUITE_DIR . "views/saml/saml-upload-metadata.php";

}else {
	global $wpdb;
	$entity_id = get_option( 'entity_id' );
	if ( ! $entity_id ) {
		$entity_id = 'https://auth.miniorange.com/moas';
	}
	$sso_url = get_option( 'sso_url' );
	$cert_fp = get_option( 'cert_fp' );

	$upload_metadata_url=admin_url().'admin.php?page=service_provider_saml&tab=save&action=upload_metadata';/*."admin.php?page=service_provider_saml&tab=save&action=upload_metadata";*/

//Broker Service
	$saml_identity_name    = get_option( 'saml_identity_name' );
	$saml_login_url        = get_option( 'saml_login_url' );
	$saml_issuer           = get_option( 'saml_issuer' );
	$saml_x509_certificate = maybe_unserialize( get_option( 'saml_x509_certificate' ) );
	$saml_x509_certificate = ! is_array( $saml_x509_certificate ) ? array( 0 => $saml_x509_certificate ) : $saml_x509_certificate;
	$saml_response_signed  = get_option( 'saml_response_signed' );
	if ( $saml_response_signed == null ) {
		$saml_response_signed = 'checked';
	}
	$saml_assertion_signed = get_option( 'saml_assertion_signed' );
	if ( $saml_assertion_signed == null ) {
		$saml_assertion_signed = 'Yes';
	}

	$idp_config = get_option( 'mo_saml_idp_config_complete' );

	include MOV_GSUITE_DIR . "views/saml/saml-sp.php";
}