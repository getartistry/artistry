<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 27-04-2018
 * Time: 14:37
 */

class Mo_Gsuite_Generate_Metadata extends Base_Request_action {

	private $option_value = 'mosaml_metadata';

	/**
	 * This function will be the first to get request data,
	 * This function will be used to process the request data before actual business logic.
	 * @return mixed
	 */
	function handle_request_data() {
		if ( ! $this->validate_request_data($_REQUEST) ) {
			return;
		}

		$this->route_request_data($_REQUEST);
	}

	/**
	 * This fuction will be used to do the validation on request data.
	 * @return mixed
	 */
	function validate_request_data( $getData ) {

		if ( ! isset( $getData['option'] ) || strcasecmp( $this->option_value, $getData['option'] ) != 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * This function will be used to route the request data for business logic.
	 * @return mixed
	 */
	function route_request_data( $getData ) {
		$this->generate_metadata();
	}

	function generate_metadata() {

		$sp_base_url = get_option( 'mo_saml_sp_base_url' );
		if ( empty( $sp_base_url ) ) {
			$sp_base_url = site_url();
		}
		if ( substr( $sp_base_url, - 1 ) == '/' ) {
			$sp_base_url = substr( $sp_base_url, 0, - 1 );
		}
		$sp_entity_id = get_option( 'mo_saml_sp_entity_id' );
		if ( empty( $sp_entity_id ) ) {
			$sp_entity_id = $sp_base_url . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
		}

		$entity_id = $sp_entity_id;
		$acs_url   = $sp_base_url . '/';/*
	$certificate = file_get_contents( plugin_dir_path( __FILE__ ) . 'resources' . DIRECTORY_SEPARATOR . 'sp-certificate.crt' );*/


		$certificate = file_get_contents( MOV_GSUITE_DIR . 'resources/saml-resources/miniorange_sp_cert.cer' );

		$certificate = Mo_SAML_Utilities::desanitize_certificate( $certificate );
		ob_clean();
		header( 'Content-Type: text/xml' );
		echo '<?xml version="1.0"?>
<md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" validUntil="2020-10-28T23:59:59Z" cacheDuration="PT1446808792S" entityID="' . $entity_id . '">
  <md:SPSSODescriptor AuthnRequestsSigned="false" WantAssertionsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
    <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress</md:NameIDFormat>
    <md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="' . $acs_url . '" index="1"/>
  </md:SPSSODescriptor>
  <md:Organization>
    <md:OrganizationName xml:lang="en-US">miniOrange</md:OrganizationName>
    <md:OrganizationDisplayName xml:lang="en-US">miniOrange</md:OrganizationDisplayName>
    <md:OrganizationURL xml:lang="en-US">http://miniorange.com</md:OrganizationURL>
  </md:Organization>
  <md:ContactPerson contactType="technical">
    <md:GivenName>miniOrange</md:GivenName>
    <md:EmailAddress>info@miniorange.com</md:EmailAddress>
  </md:ContactPerson>
  <md:ContactPerson contactType="support">
    <md:GivenName>miniOrange</md:GivenName>
    <md:EmailAddress>info@miniorange.com</md:EmailAddress>
  </md:ContactPerson>
</md:EntityDescriptor>';
		exit;

	}
}
new Mo_Gsuite_Generate_Metadata;