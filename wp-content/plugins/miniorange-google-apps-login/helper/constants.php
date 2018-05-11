<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mo_Gsuite_Constants {
	const DEFAULT_CUSTOMER_KEY = "16555";
	const DEFAULT_API_KEY = "fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq";
	const PCODE = "UHJlbWl1bSBQbGFuIC0gV1AgT1RQIFZFUklGSUNBVElPTg==";
	const BCODE = "RG8gaXQgWW91cnNlbGYgUGxhbiAtIFdQIE9UUCBWRVJJRklDQVRJT04=";
	const CCODE = "bWluaU9yYW5nZSBTTVMvU01UUCBHYXRld2F5IC0gV1AgT1RQIFZFUklGSUNBVElPTg==";

	//const HOSTNAME = "https://test.miniorange.in";
	const HOSTNAME= "https://auth.miniorange.com";
	const FROM_EMAIL = "no-reply@miniorange.com";
	const SUPPORT_EMAIL = "info@miniorange.com";
	const HEADER_CONTENT_TYPE = "Content-Type: text/html";
	const SUCCESS = "SUCCESS";
	const FAILURE = "FAILURE";
	const AREA_OF_INTEREST = "WP Google Apps Login";
	const APPLICATION_NAME = "wp_google_apps_login_free";
	const PATTERN_PHONE = '/^[\+]\d{1,4}\d{7,12}$|^[\+]\d{1,4}[\s]\d{7,12}$/';
	const PATTERN_COUNTRY_CODE = '/^[\+]\d{1,4}.*/';
	const PATTERN_SPACES_HYPEN = '/([\(\) -]+)/';
	const ERROR_JSON_TYPE = 'error';
	const SUCCESS_JSON_TYPE = 'success';
	const FORM_NONCE = "mo_form_settings";
	const SAML_HELP_URL= "https://faq.miniorange.com/kb/saml-single-sign-on/";
	const OAUTH_HELP_URL= "https://faq.miniorange.com/kb/oauth-openid-connect/";
	/* 
	############################## FACEBOOK CONSTANTS ##################
	*/

	const FACEBOOK_AUTHORIZE_URL = "https://www.facebook.com/dialog/oauth";
	const FACEBOOK_ACCESS_TOKEN_URL = "https://graph.facebook.com/v2.8/oauth/access_token";
	const FACEBOOK_RESOURCE_OWNER_DETAILS_URL = "https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=";

	const WINDOWS_AUTHORIZE_URL = "https://login.live.com/oauth20_authorize.srf";

	const WINDOWS_ACCESS_TOKEN_URL = "https://login.live.com/oauth20_token.srf";
	const WINDOWS_RESOURCE_OWNER_DETAILS_URL = "https://apis.live.net/v5.0/me";

	const GOOGLE_AUTHORIZE_URL = 'https://accounts.google.com/o/oauth2/auth';
	const GOOGLE_ACCESS_TOKEN_URL = "https://www.googleapis.com/oauth2/v3/token";
	const GOOGLE_RESOURCE_OWNER_DETAILS_URL = "https://www.googleapis.com/plus/v1/people/me";

	const MO_IDP_ENTITY_ID= 'https://auth.miniorange.com/moas';

	###########################SAML######################
	const PATTERN_IDP_NAME			= '/^\w*$/';

	const OAUTH_WIDGET_NAME= 'Login with OAuth';

}

new Mo_Gsuite_Constants;