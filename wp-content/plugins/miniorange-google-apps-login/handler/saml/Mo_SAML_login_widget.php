<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 23-02-2018
 * Time: 10:18
 */

require_once MOV_GSUITE_DIR . 'includes/lib/encryption.php';

function mo_login_validate(){

	if( isset( $_REQUEST['option'] ) and strpos( $_REQUEST['option'], 'readsamllogin' ) !== false ) {
		// Get the email of the user.
		require_once dirname(__FILE__) . '/includes/lib/encryption.php';

		if(isset($_POST['STATUS']) && $_POST['STATUS'] == 'ERROR')
		{
			update_option('mo_saml_redirect_error_code', $_POST['ERROR_REASON']);
			update_option('mo_saml_redirect_error_reason' , $_POST['ERROR_MESSAGE']);
		}
		else if(isset($_POST['STATUS']) && $_POST['STATUS'] == 'SUCCESS'){
			$redirect_to = '';
			if(isset($_REQUEST['redirect_to']) && !empty($_REQUEST['redirect_to']) && $_REQUEST['redirect_to'] != '/') {
				$redirect_to = htmlentities($_REQUEST['redirect_to']);
			}

			delete_option('mo_saml_redirect_error_code');
			delete_option('mo_saml_redirect_error_reason');

			try {

				//Get enrypted user_email
				$emailAttribute = get_option('saml_am_email');
				$usernameAttribute = get_option('saml_am_username');
				$firstName = get_option('saml_am_first_name');
				$lastName = get_option('saml_am_last_name');
				$groupName = get_option('saml_am_group_name');
				$defaultRole = get_option('saml_am_default_user_role');
				$dontAllowUnlistedUserRole = get_option('saml_am_dont_allow_unlisted_user_role');
				$checkIfMatchBy = get_option('saml_am_account_matcher');
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

				//Decrypt email now.

				//Get customer token as a key to decrypt email
				$key = get_option('mo_gsuite_customer_validation_customer_token');

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
			mo_saml_login_user($user_email,$firstName,$lastName,$userName, $groupArray, $dontAllowUnlistedUserRole, $defaultRole,$redirect_to, $checkIfMatchBy);
		}

	}
}

function mo_saml_checkMapping($attrs,$relayState,$sessionIndex){
	try {
		//Get enrypted user_email
		$emailAttribute = get_option('saml_am_email');
		$usernameAttribute = get_option('saml_am_username');
		$firstName = get_option('saml_am_first_name');
		$lastName = get_option('saml_am_last_name');
		$groupName = get_option('saml_am_group_name');
		$defaultRole = get_option('saml_am_default_user_role');
		$dontAllowUnlistedUserRole = get_option('saml_am_dont_allow_unlisted_user_role');
		$checkIfMatchBy = get_option('saml_am_account_matcher');
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
			mo_saml_show_test_result($firstName,$lastName,$user_email,$groupName,$attrs);
		}else{
			mo_saml_login_user($user_email, $firstName, $lastName, $userName, $groupName, $dontAllowUnlistedUserRole, $defaultRole, $relayState, $checkIfMatchBy, $sessionIndex, $attrs['NameID'][0]);
		}

	}
	catch (Exception $e) {
		echo sprintf("An error occurred while processing the SAML Response.");
		exit;
	}
}

function mo_saml_show_test_result($firstName,$lastName,$user_email,$groupName,$attrs){
	ob_end_clean();
	echo '<div style="font-family:Calibri;padding:0 3%;">';
	if(!empty($user_email)){
		echo '<div style="color: #3c763d;
				background-color: #dff0d8; padding:2%;margin-bottom:20px;text-align:center; border:1px solid #AEDB9A; font-size:18pt;">TEST SUCCESSFUL</div>
				<div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="'. MOV_GSUITE_URL . 'includes/images/SAML/green_check.png"></div>';
	}else{
		echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;">TEST FAILED</div>
				<div style="color: #a94442;font-size:14pt; margin-bottom:20px;">WARNING: Some Attributes Did Not Match.</div>
				<div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="'. MOV_GSUITE_URL . 'includes/images/SAML/green_check.png"></div>';
	}
	$matchAccountBy = get_option('saml_am_account_matcher')?get_option('saml_am_account_matcher'):'email';
	if($matchAccountBy=='email' && !filter_var($attrs['NameID'][0], FILTER_VALIDATE_EMAIL))
	{
		echo '<p><font color="#FF0000" style="font-size:14pt">(Warning: The NameID value is not a valid Email ID)</font></p>';
	}
	echo '<span style="font-size:14pt;"><b>Hello</b>, '.$user_email.'</span>';


	echo'<br/><p style="font-weight:bold;font-size:14pt;margin-left:1%;">ATTRIBUTES RECEIVED:</p>
				<table style="border-collapse:collapse;border-spacing:0; display:table;width:100%; font-size:14pt;background-color:#EDEDED;">
				<tr style="text-align:center;"><td style="font-weight:bold;border:2px solid #949090;padding:2%;">ATTRIBUTE NAME</td><td style="font-weight:bold;padding:2%;border:2px solid #949090; word-wrap:break-word;">ATTRIBUTE VALUE</td></tr>';

	if(!empty($attrs)){
		foreach ($attrs as $key => $value)

			echo "<tr><td style='font-weight:bold;border:2px solid #949090;padding:2%;'>" .$key . "</td><td style='padding:2%;border:2px solid #949090; word-wrap:break-word;'>" .implode("<hr/>",$value). "</td></tr>";
	}
	else
		echo "No Attributes Received.";
	echo '</table></div>';
	echo '<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
	exit;
}

function mo_saml_login_user($user_email, $firstName, $lastName, $userName, $groupName, $dontAllowUnlistedUserRole, $defaultRole, $relayState, $checkIfMatchBy, $sessionIndex = '', $nameId = ''){
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

		if (!get_option('mo_saml_free_version')) {
			// Assign role
			$current_user = get_user_by('id', $user_id);
			$role_mapping = get_option('saml_am_role_mapping');
			if(!empty($groupName) && !empty($role_mapping)) {
				$role_to_assign = '';
				$found = false;
				foreach ($role_mapping as $role_value => $group_names) {
					$groups = explode(";", $group_names);
					foreach ($groups as $group) {
						if(in_array($group, $groupName, TRUE)) {
							$found = true;
							$current_user->add_role($role_value);
						}
					}
				}

				if($found !== true && !empty($dontAllowUnlistedUserRole) && $dontAllowUnlistedUserRole == 'checked') {
					$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => false ) );
				} elseif($found !== true && !empty($defaultRole)) {
					$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $defaultRole ) );
				}
			} elseif (!empty($dontAllowUnlistedUserRole) && strcmp( $dontAllowUnlistedUserRole, 'checked') == 0) {
				$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => false ) );
			} elseif(!empty($defaultRole)) {
				$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $defaultRole ) );
			} else {
				$defaultRole = get_option('default_role');
				$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $defaultRole ) );
			}
		} else {
			if(!empty($defaultRole)) {
				$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $defaultRole ) );
			}
		}
		if(!empty($firstName))
		{
			$user_id = wp_update_user( array( 'ID' => $user_id, 'first_name' => $firstName ) );
		}
		if(!empty($lastName))
		{
			$user_id = wp_update_user( array( 'ID' => $user_id, 'last_name' => $lastName ) );
		}
		if(!empty($firstName)&&!empty($lastName))
		{
			$user_id = wp_update_user( array( 'ID' => $user_id, 'display_name' => $firstName." ".$lastName ) );
		}

		wp_set_auth_cookie( $user_id, true );
		if(!empty($relayState))
			wp_redirect($relayState);
		else
			wp_redirect(site_url());
		exit;
	}
	elseif ( username_exists( $userName ) && !email_exists( $user_email ) ){
		wp_die("Registration has failed as a user with the same username already exists in WordPress. Please ask your administrator to create an account for you with a unique username.","Error");
	}
}

function mo_saml_is_gsuite_customer_registered() {
	$email 			= get_option('mo_gsuite_customer_validation_admin_email');
	$customerKey 	= get_option('mo_gsuite_customer_validation_admin_customer_key');
	if( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
		return 0;
	} else {
		return 1;
	}
}

function show_status_error($statusCode,$relayState,$statusmessage){
	if($relayState=='testValidate'){

		echo '<div style="font-family:Calibri;padding:0 3%;">';
		echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong> Invalid SAML Response Status.</p>
                <p><strong>Causes</strong>: Identity Provider has sent \''.$statusCode.'\' status code in SAML Response. Please check IdP logs.</p>
								<p><strong>Reason</strong>: '.get_status_message($statusCode).'</p> ';
		if(!empty($statusmessage))
			echo '<p><strong>Status Message in the SAML Response:</strong> <br/>'.$statusmessage.'</p><br>';
		echo '
                </div>

                <div style="margin:3%;display:block;text-align:center;">
                <div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
		exit;
	}
	else{
		wp_die('We could not sign you in. Please contact your Administrator','Error:Invalid SAML Response Status');
	}

}

function get_status_message($statusCode){
	switch($statusCode){
		case 'Requester':
			return 'The request could not be performed due to an error on the part of the requester.';
			break;
		case 'Responder':
			return 'The request could not be performed due to an error on the part of the SAML responder or SAML authority.';
			break;
		case 'VersionMismatch':
			return 'The SAML responder could not process the request because the version of the request message was incorrect.';
			break;
		default:
			return 'Unknown';
	}
}
function mo_saml_show_SAML_log($samlRequestXML,$type){

	//header("Content-Type: text/plain");
	header("Content-Type: text/html");
	$doc = new DOMDocument();
	$doc->preserveWhiteSpace = false;
	$doc->formatOutput = true;
	$doc->loadXML($samlRequestXML);
	//var_dump($samlRequestXML);
	if($type=='displaySAMLRequest')
		$show_value='SAML Request';
	else
		$show_value='SAML Response';
	$out = $doc->saveXML();

	$out1 = htmlentities($out);
	//var_dump($out1);
	$xml   = simplexml_load_string( $out );
	//var_dump($xml);
	$json  = json_encode( $xml );
	$array = json_decode( $json );
	$url = MO_GSUITE_SAML_CSS_URL ;
	echo '<link rel=\'stylesheet\' id=\'mo_saml_admin_settings_style-css\'  href=\''.$url.'\' type=\'text/css\' media=\'all\' />
            <xsl:output indent="yes"/>
			<div class="mo-display-logs" >
			
			<p type="text" id="SAML_type">'.$show_value.'</p></div>
			
			<div type="text" id="SAML_display" class="mo-display-block"><pre class=\'brush: xml;\'>'.$out1 . '</pre>
			</div>
			<br>
			<div style="margin:3%;display:block;text-align:center;">
            
			<div style="margin:3%;display:block;text-align:center;" >
            <button id="after_copied"  hidden style="text-align:center;margin:auto;padding:1%;width:100px;background: grey none repeat scroll 0% 0%;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #aaa6a5;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;">Copied</button>
			</div>
			<button id="copy" onclick="copyDivToClipboard()"  style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;">Copy</button>
			&nbsp;
               <input id="dwn-btn" style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Download" 
               ">
			</div>
			</div>
			
		
			';

	ob_end_flush();?>

	<script>

        function copyDivToClipboard() {
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById("SAML_display").textContent);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            document.getElementById("after_copied").style.display = 'block';

        }

        function download(filename, text) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:Application/octet-stream;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
        }

        document.getElementById("dwn-btn").addEventListener("click", function () {

            var filename = document.getElementById("SAML_type").textContent+".xml";
            var node = document.getElementById("SAML_display");
            htmlContent = node.innerHTML;
            text = node.textContent;
            console.log(text);
            download(filename, text);
        }, false);





	</script>
	<?php
	exit;
}
function saml_get_current_page_url() {
	$http_host = $_SERVER['HTTP_HOST'];
	if(substr($http_host, -1) == '/') {
		$http_host = substr($http_host, 0, -1);
	}
	$request_uri = $_SERVER['REQUEST_URI'];
	if(substr($request_uri, 0, 1) == '/') {
		$request_uri = substr($request_uri, 1);
	}
	if (strpos($request_uri, '?option=saml_user_login') !== false) {
		return strtok($_SERVER["REQUEST_URI"],'?');;
	}
	$is_https = (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') == 0);
	$relay_state = 'http' . ($is_https ? 's' : '') . '://' . $http_host . '/' . $request_uri;
	return $relay_state;
}
add_action( 'init', 'mo_login_validate' );
?>