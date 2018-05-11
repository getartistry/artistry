<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 27-04-2018
 * Time: 11:21
 */

class Mo_Gsuite_Handle_Upload_Metadata extends BasePostAction {

	private $option_value='mo_saml_upload_metadata';
	/**
	 * This function will get the post parameters for the first time. This function will be used for manipulation.
	 * @return mixed
	 */
	function handle_post_data() {
		if(!$this->validate_post_data($_POST))return;
		$this->route_post_data($_POST);

	}

	/**
	 * This function is used to check if the post data has some validation errors.
	 * @return mixed
	 */
	function validate_post_data( $getData ) {
		if(!isset($getData['option'])||strcasecmp($this->option_value,$getData['option'])!=0)return false;
		return true;
	}

	/**
	 * This function is used to route the data after all the validation and data manipulation.
	 * @return mixed
	 */
	function route_post_data( $getData ) {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		if ( isset($_FILES['metadata_file']) || isset($getData['metadata_url'])) {
			if(!empty($_FILES['metadata_file']['tmp_name'])) {
				$file = @file_get_contents( $_FILES['metadata_file']['tmp_name']);
			} else {
				$url=filter_var($_POST['metadata_url'],FILTER_SANITIZE_URL);
				$ch=curl_init();

				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
				$file = curl_exec($ch);

				curl_close($ch);
			}
			self::_upload_metadata($file);
		}
	}

	/**
	 * Does the action of upload metadata.
	 * @param $file
	 */
	private static function _upload_metadata($file)
	{

		$old_error_handler = set_error_handler(array('Mo_Gsuite_Handle_Upload_Metadata','handleXmlError'));
		$document = new DOMDocument();

		@$document->loadXML($file);
		restore_error_handler();
		$first_child = $document->firstChild;
		if(!empty($first_child)) {
			$metadata = new Mo_Idp_Metadata_Reader($document);
			$identity_providers = $metadata->getIdentityProviders();
			if(empty($identity_providers)) {
				do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_INVALID_METADATA_FILE' ), 'ERROR' );
				return;
			}

			foreach($identity_providers as $key => $idp){
				//$saml_identity_name = preg_match("/^[a-zA-Z0-9-\._ ]+/", $idp->getIdpName()) ? $idp->getIdpName() : "";
				$saml_identity_name=$_POST['saml_identity_metadata_provider'];

				$saml_login_url = $idp->getLoginURL('HTTP-Redirect');

				$saml_issuer = $idp->getEntityID();
				$saml_x509_certificate = $idp->getSigningCertificate();

				update_mo_gsuite_option('saml_identity_name', $saml_identity_name);

				update_mo_gsuite_option('saml_login_url', $saml_login_url);


				update_mo_gsuite_option('saml_issuer', $saml_issuer);
				//certs already sanitized in Metadata Reader
				update_mo_gsuite_option('saml_x509_certificate', maybe_serialize($saml_x509_certificate));
				break;
			}

			do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_IDP_SAVED_SUCESS' ), 'SUCCESS' );
		} else {
			do_action( 'mo_gsuite_registration_show_message', Mo_GSuite_Messages::showMessage( 'SAML_INVALID_METADATA_FILE' ), 'ERROR' );
		}
	}

	static function handleXmlError($errno, $errstr, $errfile, $errline){
		if ($errno==E_WARNING && (substr_count($errstr,"DOMDocument::loadXML()")>0)) {
			return;
		} else {
			return false;
		}
	}

	public static function _isValidXML($xml) {
		$doc = @simplexml_load_string($xml);
		if ($doc) {
			return true; //this is valid
		} else {
			return false; //this is not valid
		}
	}

}
new Mo_Gsuite_Handle_Upload_Metadata;