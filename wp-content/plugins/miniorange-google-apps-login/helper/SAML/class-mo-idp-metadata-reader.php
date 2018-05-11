<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 16-02-2018
 * Time: 17:52
 */

class Mo_Idp_Metadata_Reader {


	private $identityProviders;
	private $serviceProviders;

	public function __construct(DOMNode $xml = NULL){

		$this->identityProviders = array();
		$this->serviceProviders = array();

		$entityDescriptors = Mo_SAML_Utilities ::xpQuery($xml, './saml_metadata:EntityDescriptor');

		//print_r($entityDescriptors);exit;

		foreach ($entityDescriptors as $entityDescriptor) {
			$idpSSODescriptor = Mo_SAML_Utilities ::xpQuery($entityDescriptor, './saml_metadata:IDPSSODescriptor');

			if(isset($idpSSODescriptor) && !empty($idpSSODescriptor)){
				array_push($this->identityProviders,new Mo_SAML_Identity_Provider($entityDescriptor));
			}
			//TODO: add sp descriptor
		}
	}

	public function getIdentityProviders(){
		return $this->identityProviders;
	}

	public function getServiceProviders(){
		return $this->serviceProviders;
	}

}
