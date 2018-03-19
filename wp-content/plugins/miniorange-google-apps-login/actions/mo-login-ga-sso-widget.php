<?php



class mo_ga_login_wid extends WP_Widget {
	public function __construct() {
		$identityName = get_option('mo_ga_sso_saml_identity_name');
		parent::__construct(
	 		'mo_ga_sso_saml_Login_Widget',
			'Login with ' . $identityName,
			array( 'description' => __( 'This is a miniOrange SAML login widget.', 'mosaml' ),
					'customize_selective_refresh' => true,
				)
		);
	 }

	
	public function widget( $args, $instance ) {
		extract( $args );
		
		$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $wid_title ) )
			echo $args['before_title'] . $wid_title . $args['after_title'];
			$this->loginForm();
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}


	public function form( $instance ) {
		$wid_title = '';
		if(array_key_exists('wid_title', $instance))
			$wid_title = $instance[ 'wid_title' ];
		?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title:'); ?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('wid_title'); ?>" name="<?php echo $this->get_field_name('wid_title'); ?>" type="text" value="<?php echo $wid_title; ?>" />
		</p>
		<?php 
	}
	
	public function loginForm(){
		global $post;
		
		if(!is_user_logged_in()){
		?>
		<script>
		function submitSamlForm(){ document.getElementById("login").submit(); }
		</script>
		<form name="login" id="login" method="post" action="">
		<input type="hidden" name="option" value="mo_ga_sso_saml_user_login" />

		<font size="+1" style="vertical-align:top;"> </font><?php
		$identity_provider = get_option('mo_ga_sso_saml_identity_name');
		
		if(!empty($identity_provider)){
			echo '<a href="#" onClick="submitSamlForm()">Login with ' . $identity_provider . '</a></form>';
		}else
			echo "Please configure the miniOrange SAML Plugin first.";
		
		if( ! $this->mo_ga_sso_check_empty_or_null_val(get_option('mo_ga_sso_redirect_error_code')))
		{

			echo '<div></div><div title="Login Error"><font color="red">We could not sign you in. Please contact your Administrator.</font></div>';
				
				delete_option('mo_ga_sso_redirect_error_code');
				delete_option('mo_ga_sso_redirect_error_reason'); 
		}
		
		?>
		
		<a href="http://miniorange.com/strong_auth" style="display:none">Strong Authentication Solution</a>
		<a href="http://miniorange.com/single-sign-on-sso" style="display:none">Single Sign On LDAP</a>
		<a href="http://miniorange.com/fraud" style="display:none">Fraud Prevention Solution</a>
		<a href="http://miniorange.com/cloud-identity-broker-service" style="display:none">Cloud Identity broker service</a>
		
			</ul>
		</form>
		<?php 
		} else {
		$current_user = wp_get_current_user();
		$link_with_username = __('Hello, ','mosaml').$current_user->display_name;
		?>
		<?php echo $link_with_username;?> | <a href="<?php echo wp_logout_url(site_url()); ?>" title="<?php _e('Logout','mosaml');?>"><?php _e('Logout','mosaml');?></a></li>
		<?php 
		}
	}
	
	public function mo_ga_sso_check_empty_or_null_val( $value ) {
	if( ! isset( $value ) || empty( $value ) ) {
		return true;
	}
	return false;
	}
} 

function mo_ga_login_settings_script_widget() {
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'mo_ga_sso_admin_settings_script_widget', plugins_url().'/miniorange-google-apps-login/includes/js/settings.js' );
}

function mo_ga_login_settings_style_widget() {
	wp_enqueue_style( 'mo_ga_sso_admin_settings_style', plugins_url().'/miniorange-google-apps-login/includes/css/jquery.ui.css' );
}

function mo_ga_login_validate(){
	if((isset($_REQUEST['option']) && $_REQUEST['option'] == 'mo_ga_sso_saml_user_login') || (isset($_REQUEST['option']) && $_REQUEST['option'] == 'testConfig')){
		if(mo_ga_sso_is_sp_configured()) {
			if($_REQUEST['option'] == 'testConfig')
				$sendRelayState = 'testValidate';
			else if ( isset( $_REQUEST['redirect_to']) ) 
				$sendRelayState = $_REQUEST['redirect_to'];
			else
				$sendRelayState = site_url();
			$ssoUrl = get_option("mo_ga_sso_saml_login_url");
			$force_authn = get_option('mo_ga_sso_force_authentication');
			$acsUrl = site_url()."/";
			$issuer = plugins_url().'/miniorange-google-apps-login/';
			$samlRequest = Mo_Ga_Saml_Utility::createAuthnRequest($acsUrl, $issuer, $force_authn);
			$redirect = $ssoUrl;
			if (strpos($ssoUrl,'?') !== false) {
				$redirect .= '&';
			} else {
				$redirect .= '?';
			}
			$redirect .= 'SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($sendRelayState);
			header('Location: '.$redirect);
			exit();
		}
	}
	if( array_key_exists('SAMLResponse', $_POST) && !empty($_POST['SAMLResponse']) ) {
		
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
		
		
		$certFromPlugin = get_option('mo_ga_sso_saml_x509_certificate');
		$certfpFromPlugin = XMLSecurityKey::getRawThumbprint($certFromPlugin);
		
		$acsUrl = site_url() .'/';
		$samlResponse = new SAML2_Response($samlResponseXml);
		
		$responseSignatureData = $samlResponse->getSignatureData();
		$assertionSignatureData = current($samlResponse->getAssertions())->getSignatureData();
		
		/* convert to UTF-8 character encoding*/
		$certfpFromPlugin = iconv("UTF-8", "CP1252//IGNORE", $certfpFromPlugin);
		
		/* remove whitespaces */
		$certfpFromPlugin = preg_replace('/\s+/', '', $certfpFromPlugin);	
		
		$responseSignedOption = get_option('mo_ga_sso_saml_response_signed');
		$assertionSignedOption = get_option('mo_ga_sso_saml_assertion_signed');
		
		/* Validate signature */
		if($responseSignedOption == 'checked') {
				$validSignature = Mo_Ga_Saml_Utility::processResponse($acsUrl, $certfpFromPlugin, $responseSignatureData, $samlResponse);
				if($validSignature === FALSE) {
					echo "Invalid signature in the SAML Response.";
					exit;
				}
			}
			
		if($assertionSignedOption == 'checked') {
			$validSignature = Mo_Ga_Saml_Utility::processResponse($acsUrl, $certfpFromPlugin, $assertionSignatureData, $samlResponse);
			if($validSignature === FALSE) {
				echo "Invalid signature in the SAML Assertion.";
				exit;
			}
		}
		
		// verify the issuer and audience from saml response
		$issuer = get_option('mo_ga_sso_saml_issuer');
		$spEntityId = plugins_url().'/miniorange-google-apps-login/';
	
		Mo_Ga_Saml_Utility::validateIssuerAndAudience($samlResponse,$spEntityId, $issuer);
		
		$ssoemail = current(current($samlResponse->getAssertions())->getNameId());
		$attrs = current($samlResponse->getAssertions())->getAttributes();
		$attrs['NameID'] = array("0" => $ssoemail);
		$sessionIndex = current($samlResponse->getAssertions())->getSessionIndex();
		
		mo_ga_sso_checkMapping($attrs,$relayState,$sessionIndex);
	}
			
	if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'readsamllogin' ) !== false ) {
		// Get the email of the user.
		require_once dirname(__FILE__) . '/includes/lib/encryption.php';
		
		if(isset($_POST['STATUS']) && $_POST['STATUS'] == 'ERROR')
		{
			update_option('mo_ga_sso_redirect_error_code', $_POST['ERROR_REASON']);
			update_option('mo_ga_sso_redirect_error_reason' , $_POST['ERROR_MESSAGE']);
		}
		else if(isset($_POST['STATUS']) && $_POST['STATUS'] == 'SUCCESS'){
			$redirect_to = '';
			if(isset($_REQUEST['redirect_to']) && !empty($_REQUEST['redirect_to']) && $_REQUEST['redirect_to'] != '/') {
				$redirect_to = $_REQUEST['redirect_to'];
			}

			delete_option('mo_ga_sso_redirect_error_code');
			delete_option('mo_ga_sso_redirect_error_reason');
			
			try {
				
				//Get enrypted user_email
				$emailAttribute = get_option('mo_ga_sso_email');
				$usernameAttribute = get_option('mo_ga_sso_username');
				$firstName = get_option('mo_ga_sso_first_name');
				$lastName = get_option('mo_ga_sso_last_name');
				$groupName = get_option('mo_ga_sso_group_name');
				$defaultRole = get_option('mo_ga_sso_default_user_role');
				$dontAllowUnlistedUserRole = get_option('mo_ga_sso_dont_allow_unlisted_user_role');
				$checkIfMatchBy = get_option('mo_ga_sso_account_matcher');
				$user_email = '';
				$userName = '';
				//Attribute mapping. Check if Match/Create user is by username/email:
				
				$firstName = str_replace(".", "_", $firstName);
				$firstName = str_replace(" ", "_", $firstName);
				if(!empty($firstName) && array_key_exists($firstName, $_POST) ) {
					$firstName = $_POST[$firstName];
				}
				
				$lastName = str_replace(".", "_", $lastName);
				$lastName = str_replace(" ", "_", $lastName);
				if(!empty($lastName) && array_key_exists($lastName, $_POST) ) {
					$lastName = $_POST[$lastName];
				}
				
				$usernameAttribute = str_replace(".", "_", $usernameAttribute);
				$usernameAttribute = str_replace(" ", "_", $usernameAttribute);
				if(!empty($usernameAttribute) && array_key_exists($usernameAttribute, $_POST)) {
					$userName = $_POST[$usernameAttribute];
				} else {
					$userName = $_POST['NameID'];
				}
				
				$user_email = str_replace(".", "_", $emailAttribute);
				$user_email = str_replace(" ", "_", $emailAttribute);
				if(!empty($emailAttribute) && array_key_exists($emailAttribute, $_POST)) {
					$user_email = $_POST[$emailAttribute];
				} else {
					$user_email = $_POST['NameID'];
				}
				
				$groupName = str_replace(".", "_", $groupName);
				$groupName = str_replace(" ", "_", $groupName);
				if(!empty($groupName) && array_key_exists($groupName, $_POST) ) {
					$groupName = $_POST[$groupName];
				}
				
				if(empty($checkIfMatchBy)) {
					$checkIfMatchBy = "email";
				}
			
				
				
				//Get customer token as a key to decrypt email
				$key = get_option('mo_ga_sso_customer_token');
			
				if(isset($key) || trim($key) != '')
				{
					$deciphertext = AESEncryption::decrypt_data($user_email, $key);							 
					$user_email = $deciphertext;				
				}
				
				//Decrypt firstname and lastName and username
				
				if(!empty($firstName) && !empty($key))
				{
					$decipherFirstName = AESEncryption::decrypt_data($firstName, $key);	
					$firstName = $decipherFirstName;
				}
				if(!empty($lastName) && !empty($key))
				{
					$decipherLastName = AESEncryption::decrypt_data($lastName, $key);	
					$lastName = $decipherLastName;
				}
				if(!empty($userName) && !empty($key))
				{
					$decipherUserName = AESEncryption::decrypt_data($userName, $key);	
					$userName = $decipherUserName;
				}
				if(!empty($groupName) && !empty($key))
				{
					$decipherGroupName = AESEncryption::decrypt_data($groupName, $key);	
					$groupName = $decipherGroupName;
				}
			}
			catch (Exception $e) {
				echo sprintf("An error occurred while processing the SAML Response.");
				exit;
			}
			$groupArray = array ( $groupName );
			mo_ga_sso_login_user($user_email,$firstName,$lastName,$userName, $groupArray, $dontAllowUnlistedUserRole, $defaultRole,$redirect_to, $checkIfMatchBy);
		}

	}	
}

function mo_ga_sso_checkMapping($attrs,$relayState,$sessionIndex){
	try {
		//Get enrypted user_email
		$emailAttribute = get_option('mo_ga_sso_email');
		$usernameAttribute = get_option('mo_ga_sso_username');
		$firstName = get_option('mo_ga_sso_first_name');
		$lastName = get_option('mo_ga_sso_last_name');
		$groupName = get_option('mo_ga_sso_group_name');
		$defaultRole = get_option('mo_ga_sso_default_user_role'); 
		$dontAllowUnlistedUserRole = get_option('mo_ga_sso_dont_allow_unlisted_user_role');
		$checkIfMatchBy = get_option('mo_ga_sso_account_matcher');
		$user_email = '';
		$userName = '';
		
		//Attribute mapping. Check if Match/Create user is by username/email:
		if(!empty($attrs)){
			if(!empty($firstName) && array_key_exists($firstName, $attrs))
				$firstName = $attrs[$firstName][0];
			else
				$firstName = '';

			if(!empty($lastName) && array_key_exists($lastName, $attrs))
				$lastName = $attrs[$lastName][0];
			else
				$lastName = '';

			if(!empty($usernameAttribute) && array_key_exists($usernameAttribute, $attrs))
				$userName = $attrs[$usernameAttribute][0];
			else
				$userName = $attrs['NameID'][0];

			if(!empty($emailAttribute) && array_key_exists($emailAttribute, $attrs))
				$user_email = $attrs[$emailAttribute][0];
			else
				$user_email = $attrs['NameID'][0];
			
			if(!empty($groupName) && array_key_exists($groupName, $attrs))
				$groupName = $attrs[$groupName];
			else
				$groupName = array();
			
			if(empty($checkIfMatchBy)) {
				$checkIfMatchBy = "email";
			}
			
		}

		if($relayState=='testValidate'){
			mo_ga_sso_show_test_result($firstName,$lastName,$user_email,$groupName,$attrs);
		}else{
			mo_ga_sso_login_user($user_email, $firstName, $lastName, $userName, $groupName, $dontAllowUnlistedUserRole, $defaultRole, $relayState, $checkIfMatchBy, $sessionIndex, $attrs['NameID'][0]);
		}

	}
	catch (Exception $e) {
		echo sprintf("An error occurred while processing the SAML Response.");
		exit;
	}
}



function mo_ga_sso_show_test_result($firstName,$lastName,$user_email,$groupName,$attrs){
	ob_end_clean();
	echo '<div style="font-family:Calibri;padding:0 3%;">';
	if(!empty($user_email)){
		echo '<div style="color: #3c763d;
				background-color: #dff0d8; padding:2%;margin-bottom:20px;text-align:center; border:1px solid #AEDB9A; font-size:18pt;">TEST SUCCESSFUL</div>
				<div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="'. plugin_dir_url(__FILE__) . '../images/green_check.png"></div>';
	}else{
		echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;">TEST FAILED</div>
				<div style="color: #a94442;font-size:14pt; margin-bottom:20px;">WARNING: Some Attributes Did Not Match.</div>
				<div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="'. plugin_dir_url(__FILE__) . '../images/wrong.png"></div>';
	}
		echo '<span style="font-size:14pt;"><b>Hello</b>, '.$user_email.'</span><br/><p style="font-weight:bold;font-size:14pt;margin-left:1%;">ATTRIBUTES RECEIVED:</p>
				<table style="border-collapse:collapse;border-spacing:0; display:table;width:100%; font-size:14pt;background-color:#EDEDED;">
				<tr style="text-align:center;"><td style="font-weight:bold;border:2px solid #949090;padding:2%;">ATTRIBUTE NAME</td><td style="font-weight:bold;padding:2%;border:2px solid #949090; word-wrap:break-word;">ATTRIBUTE VALUE</td></tr>';
	if(!empty($attrs))
		foreach ($attrs as $key => $value)
			echo "<tr><td style='font-weight:bold;border:2px solid #949090;padding:2%;'>" .$key . "</td><td style='padding:2%;border:2px solid #949090; word-wrap:break-word;'>" .implode("<hr/>",$value). "</td></tr>";
		else
			echo "No Attributes Received.";
		echo '</table></div>';
		echo '<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
		exit;
}

function mo_ga_sso_login_user($user_email, $firstName, $lastName, $userName, $groupName, $dontAllowUnlistedUserRole, $defaultRole, $relayState, $checkIfMatchBy, $sessionIndex = '', $nameId = ''){
	if($checkIfMatchBy == 'username' && username_exists( $userName ) ) {
		$user 	= get_user_by('login', $userName);
		$user_id = $user->ID;
		if( !empty($firstName) )
		{
			$user_id = wp_update_user( array( 'ID' => $user_id, 'first_name' => $firstName ) );
		}
		if( !empty($lastName) )
		{
			$user_id = wp_update_user( array( 'ID' => $user_id, 'last_name' => $lastName ) );
		}
		
		wp_set_auth_cookie( $user_id, true );
		
		if(!empty($relayState))
			wp_redirect( $relayState );
		else
			wp_redirect( site_url() );
		exit;
		
	} elseif(email_exists( $user_email )) {
		
		$user 	= get_user_by('email', $user_email );
		$user_id = $user->ID;
		if( !empty($firstName) )
		{
			$user_id = wp_update_user( array( 'ID' => $user_id, 'first_name' => $firstName ) );
		}
		if( !empty($lastName) )
		{
			$user_id = wp_update_user( array( 'ID' => $user_id, 'last_name' => $lastName ) );
		}
		
		wp_set_auth_cookie( $user_id, true );
	
		if(!empty($relayState))
			wp_redirect( $relayState );
		else
			wp_redirect( site_url() );
		exit;
		
	} elseif ( !username_exists( $userName ) && !email_exists( $user_email ) ) {
		$random_password = wp_generate_password( 10, false );
		if(!empty($userName))
		{
			$user_id = wp_create_user( $userName, $random_password, $user_email );
		}
		else
		{
			$user_id = wp_create_user( $user_email, $random_password, $user_email );
		}
		
		
		if(!empty($defaultRole)) {
			$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $defaultRole ) );
		}
		
		if(!empty($firstName))
		{
			$user_id = wp_update_user( array( 'ID' => $user_id, 'first_name' => $firstName ) );
		}
		if(!empty($lastName))
		{
			$user_id = wp_update_user( array( 'ID' => $user_id, 'last_name' => $lastName ) );
		}
		wp_set_auth_cookie( $user_id, true );
		if(!empty($relayState))
			wp_redirect($relayState);
		else
			wp_redirect(site_url());
		exit;
	}
}

function mo_ga_sso_is_customer_registered() {
	$email 			= get_option('mo_ga_sso_admin_email');
	$customerKey 	= get_option('mo_ga_sso_admin_customer_key');
	if( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget( "mo_ga_login_wid" );' ) );
add_action( 'wp_enqueue_scripts', 'mo_ga_login_settings_style_widget' );
add_action( 'wp_enqueue_scripts', 'mo_ga_login_settings_script_widget' );
add_action( 'init', 'mo_ga_login_validate' );
?>