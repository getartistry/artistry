<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 27-04-2018
 * Time: 16:07
 */

class Mo_Gsuite_SAML_Response_Handler extends Base_Request_action {

	private $response_parameter_key='SAMLResponse';
	/**
	 * This function will get the post parameters for the first time. This function will be used for manipulation.
	 * @return mixed
	 */
	function handle_request_data() {
		if(!$this->validate_request_data($_POST))return;
		$this->route_request_data($_POST);
	}

	/**
	 * This function is used to check if the post data has some validation errors.
	 * @return mixed
	 */
	function validate_request_data( $getData ) {

		if(Mo_GSuite_Utility::isBlank($getData))return false;
		return array_key_exists($this->response_parameter_key,$getData);

	}

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	//ToDo :- Understand the complete flow and beautify this.
	function route_request_data( $getData ) {

		$samlResponse = $_POST['SAMLResponse'];

		if(array_key_exists('RelayState', $_POST) && !empty( $_POST['RelayState'] ) && $_POST['RelayState'] != '/') {
			$relayState = $_POST['RelayState'];
		} else {
			$relayState = '';
		}

		$samlResponse = base64_decode($samlResponse);

		$document = new DOMDocument();
		$document->loadXML($samlResponse);
		$samlResponseXml = $document->firstChild;

		$doc = $document->documentElement;
		$xpath = new DOMXpath($document);
		$xpath->registerNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
		$xpath->registerNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

		$status = $xpath->query('/samlp:Response/samlp:Status/samlp:StatusCode', $doc);
		$statusString = $status->item(0)->getAttribute('Value');
		$StatusMessage=$xpath->query('/samlp:Response/samlp:Status/samlp:StatusMessage', $doc)->item(0);
		if(!empty($StatusMessage))
			$StatusMessage = $StatusMessage->nodeValue;

		$statusArray = explode(':',$statusString);
		if(array_key_exists(7, $statusArray)){
			$status = $statusArray[7];
		}
		if($relayState=='displaySAMLResponse'){
			mo_saml_show_SAML_log($samlResponse,$relayState);
		}

		if($status!="Success"){
			show_status_error($status,$relayState,$StatusMessage);
		}

		$certFromPlugin = maybe_unserialize(get_option('saml_x509_certificate'));

		$acsUrl = site_url() .'/';
		$samlResponse = new SAML2_Response($samlResponseXml);
		$responseSignatureData = $samlResponse->	getSignatureData();
		$assertionSignatureData = current($samlResponse->getAssertions())->getSignatureData();
		if(empty($assertionSignatureData) && empty($responseSignatureData) ) {

			if($relayState=='testValidate'){

				$Error_message=mo_options_error_constants::Error_no_certificate;
				$Cause_message = mo_options_error_constants::Cause_no_certificate;
				echo '<div style="font-family:Calibri;padding:0 3%;">
			<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
			<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error:'.$Error_message.' </strong></p>
			<p>Please contact your administrator and report the following error:</p>
			<p><strong>Possible Cause: '.$Cause_message.'</strong></p>
			
			</div></div>';
				mo_saml_download_logs($Error_message,$Cause_message);

				exit;
			}
			else
			{
				wp_die('We could not sign you in. Please contact administrator','Error: Invalid SAML Response');
			}
		}
//		checking for certificates from given list
		if(is_array($certFromPlugin)) {
			foreach ($certFromPlugin as $key => $value) {
				$certfpFromPlugin = XMLSecurityKey::getRawThumbprint($value);

				/* convert to UTF-8 character encoding*/
				$certfpFromPlugin = iconv("UTF-8", "CP1252//IGNORE", $certfpFromPlugin);

				/* remove whitespaces */
				$certfpFromPlugin = preg_replace('/\s+/', '', $certfpFromPlugin);

				/* Validate signature */
				if(!empty($responseSignatureData)) {
					$validSignature = Mo_SAML_Utilities ::processResponse($acsUrl, $certfpFromPlugin, $responseSignatureData, $samlResponse, $key, $relayState);
				}

				if(!empty($assertionSignatureData)) {
					$validSignature = Mo_SAML_Utilities ::processResponse($acsUrl, $certfpFromPlugin, $assertionSignatureData, $samlResponse, $key, $relayState);
				}

				if($validSignature)
					break;
			}
		} else {
			$certfpFromPlugin = XMLSecurityKey::getRawThumbprint($certFromPlugin);

			/* convert to UTF-8 character encoding*/
			$certfpFromPlugin = iconv("UTF-8", "CP1252//IGNORE", $certfpFromPlugin);

			/* remove whitespaces */
			$certfpFromPlugin = preg_replace('/\s+/', '', $certfpFromPlugin);

			/* Validate signature */
			if(!empty($responseSignatureData)) {
				$validSignature = Mo_SAML_Utilities ::processResponse($acsUrl, $certfpFromPlugin, $responseSignatureData, $samlResponse, 0, $relayState);
			}

			if(!empty($assertionSignatureData)) {
				$validSignature = Mo_SAML_Utilities ::processResponse($acsUrl, $certfpFromPlugin, $assertionSignatureData, $samlResponse, 0, $relayState);
			}
		}

		if($responseSignatureData)
			$saml_required_certificate=$responseSignatureData['Certificates'][0];
		elseif($assertionSignatureData)
			$saml_required_certificate=$assertionSignatureData['Certificates'][0];

		if(!$validSignature) {
			if($relayState=='testValidate'){

				$Error_message=mo_options_error_constants::Error_wrong_certificate;
				$Cause_message = mo_options_error_constants::Cause_wrong_certificate;
				$pem = "-----BEGIN CERTIFICATE-----<br>" .
				       chunk_split($saml_required_certificate, 64) .
				       "<br>-----END CERTIFICATE-----";
				echo '<div style="font-family:Calibri;padding:0 3%;">';
				echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
			<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error:'.$Error_message.' </strong></p>
			<p>Please contact your administrator and report the following error:</p>
			<p><strong>Possible Cause: '.$Cause_message.'</strong></p>
			<p><strong>Certificate found in SAML Response: </strong><font face="Courier New";font-size:10pt><br><br>'.$pem.'</p></font>
					</div>
                    </div>';
				mo_saml_download_logs($Error_message,$Cause_message);
				exit;
			}
			else
			{
				wp_die('We could not sign you in. Please contact administrator','Error: Invalid SAML Response');
			}
		}


		// verify the issuer and audience from saml response
		$issuer = get_option('saml_issuer');
		$spEntityId = site_url().'/wp-content/plugins/miniorange-saml-20-single-sign-on/';;

		Mo_SAML_Utilities ::validateIssuerAndAudience($samlResponse,$spEntityId, $issuer, $relayState);

		$ssoemail = current(current($samlResponse->getAssertions())->getNameId());
		$attrs = current($samlResponse->getAssertions())->getAttributes();
		$attrs['NameID'] = array("0" => $ssoemail);
		$sessionIndex = current($samlResponse->getAssertions())->getSessionIndex();

		mo_saml_checkMapping($attrs,$relayState,$sessionIndex);
	}



}
new Mo_Gsuite_SAML_Response_Handler;