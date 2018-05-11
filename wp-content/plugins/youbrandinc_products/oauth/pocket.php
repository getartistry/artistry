<?php
/**
 * Example of retrieving an authentication token of the Pocket service.
 *
 * @author     Christian Mayer <thefox21at@gmail.com>
 * @copyright  Copyright (c) 2014 Christian Mayer <thefox21at@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

use OAuth\OAuth2\Service\Pocket;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Client\CurlClient;

/**
 * Bootstrap the example
 */
require_once __DIR__.'/bootstrap.php';

$step = isset($_GET['step']) ? (int)$_GET['step'] : null;
$code = isset($_GET['code']) ? $_GET['code'] : null;
$service = isset($_GET['service']) ? $_GET['service'] : null;

if (!$pocket_access_data) {
	$action_link = '<a href="' . $currentUri->getRelativeUri() . '?page=youbrandinc-oauth&service=Pocket&step=1">Connect Pocket Account</a>';
}
if($service=='Pocket') {
	if (!$pocket_access_data) {
// Session storage
		$storage = new Session();

// Setup the credentials for the requests
		$code_param =
		$credentials = new Credentials(
			$servicesCredentials['pocket']['key'],
			null, // Pocket API doesn't have a secret key. :S
			admin_url('admin.php?page=youbrandinc-oauth&service=Pocket') . ($code ? '&step=3&code=' . $code : '')
			//"http://localhost/plugin_dev/wp-admin/admin.php?page=youbrandinc-oauth&service=pocket" . ($code ? '&step=3&code=' . $code : '')
		//$currentUri->getAbsoluteUri().($code ? '?step=3&code='.$code : '')
		);

// Instantiate the Pocket service using the credentials, http client and storage mechanism for the token
		$pocketService = $serviceFactory->createService('Pocket', $credentials, $storage);

		$connected_text = "Not Connected";
		switch ($step) {
			default:

				break;

			case 1:
				$code = $pocketService->requestRequestToken();
				//header('Location: ' . $currentUri->getRelativeUri() . '?page=youbrandinc-oauth&service=pocket&step=2&code=' . $code);
				echo("<script>location.href = '". $currentUri->getRelativeUri() . "?page=youbrandinc-oauth&service=Pocket&step=2&code=" . $code . "'</script>");
				break;

			case 2:
				$url = $pocketService->getAuthorizationUri(array('request_token' => $code));
				//header('Location: ' . $url);
				echo("<script>location.href = '". $url . "'</script>");
				break;

			case 3:
				$token = $pocketService->requestAccessToken($code);
				$accessToken = $token->getAccessToken();
				$extraParams = $token->getExtraParams();
				$cs_pocket_access_data = array(
					'username' => $extraParams['username'],
					'access_token' => $accessToken
				);
				update_option('cs_pocket_access_data', $cs_pocket_access_data);
				if ($accessToken) {
					//$action_link = 'User: ' . $extraParams['username'] . '<br />';
					$action_link = $pocket_reset_link;

				}
				$connected_text = '<i class="fa fa-circle good" aria-hidden="true"></i> Connected: <i>' . $cs_pocket_access_data['username'] . '</i>';
				//ybi_oauth_message
				//echo("<script>jQuery('#ybi_oauth_message').html('<div class=\"notice updated is-dismissible\" ><p>".$service." Connected</p></div>');</script>");
				ybi_print_jquery_notice_message($service . ' Connected', 'updated');

				//$connection_text .= 'Access Token: '.$accessToken;
				break;
		}
	}
}