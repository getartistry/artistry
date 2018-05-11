<?php
/**
 * GoogleAnalyticsAPI.
 *
 * @package convertpro
 */

/**
 * GoogleAnalyticsAPI
 * Simple class which provides methods to set up OAuth 2.0 with Google and query the Google Analytics API v3 with PHP.
 *
 * CURL is required.
 *
 * There are two possibilities to get the Oauth 2.0 tokens from Google:
 * 1) OAuth 2.0 for Web Applications (end-user involved)
 * 2) OAuth 2.0 for Server to Server Applications (openssl required)
 *
 * Please note that this class does not handle error codes returned from Google. But the the http status code
 * is returned along with the data. You can check for the array-key 'status_code', which should be 200 if everything worked.
 *
 * See the readme on GitHub for instructions and examples how to use the class
 *
 * @author Stefan Wanzenried
 * @copyright Stefan Wanzenried
 * <www.everchanging.ch>
 *
 * @version 1.1
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
class GoogleAnalyticsAPI {

	const API_URL           = 'https://www.googleapis.com/analytics/v3/data/ga';
	const WEBPROPERTIES_URL = 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties';
	const PROFILES_URL      = 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles';

	/**
	 * Variable
	 *
	 * @var auth
	 */
	public $auth = null;
	/**
	 * Variable
	 *
	 * @var access_token
	 */
	protected $access_token = '';
	/**
	 * Variable
	 *
	 * @var account_id
	 */
	protected $account_id = '';
	/**
	 * Variable
	 *
	 * @var assoc
	 */
	protected $assoc = true;

	/**
	 * Variable
	 *
	 * @var default_query_params
	 */
	protected $default_query_params = array();


	/**
	 * Constructor
	 *
	 * @access public
	 * @throws Exception Return.
	 * @param String $auth (default: 'web') 'web' for Web-applications with end-users involved, 'service' for service applications (server-to-server).
	 */
	public function __construct( $auth = 'web' ) {

		if ( ! function_exists( 'curl_init' ) ) {
			throw new Exception( 'The curl extension for PHP is required.' );
		}
		$this->auth                 = ( 'web' == $auth ) ? new GoogleOauthWeb() : new GoogleOauthService();
		$this->default_query_params = array(
			'start-date' => date( 'Y-m-d', strtotime( '-1 month' ) ),
			'end-date'   => date( 'Y-m-d' ),
			'metrics'    => 'ga:visits',
		);

	}

	/**
	 * Function Name: __set.
	 * Function Description: Set function.
	 *
	 * @param array  $key array parameter.
	 * @throws Exception Return.
	 * @param string $value string parameter.
	 */
	public function __set( $key, $value ) {

		switch ( $key ) {
			case 'auth':
				if ( ( $value instanceof GoogleOauth ) == false ) {
					throw new Exception( 'auth needs to be a subclass of GoogleOauth' );
				}
				$this->auth = $value;
				break;
			case 'default_query_params':
				$this->set_default_query_params( $value );
				break;
			default:
				$this->{$key} = $value;
		}

	}

	/**
	 * Function Name: set_access_token.
	 * Function Description: Set access token function.
	 *
	 * @param string $token string parameter.
	 */
	public function set_access_token( $token ) {
		$this->access_token = $token;
	}

	/**
	 * Function Name: set_account_id.
	 * Function Description: Set account id function.
	 *
	 * @param int $id int parameter.
	 */
	public function set_account_id( $id ) {
		$this->account_id = $id;
	}

	/**
	 * Set default query parameters
	 * Useful settings: start-date, end-date, max-results
	 *
	 * @access public
	 * @param array() $params Query parameters.
	 */
	public function set_default_query_params( array $params ) {
		$params                     = array_merge( $this->default_query_params, $params );
		$this->default_query_params = $params;
	}


	/**
	 * Return objects from json_decode instead of arrays
	 *
	 * @access public
	 * @param mixed $bool true to return objects.
	 */
	public function return_objects( $bool ) {
		$this->assoc = ! $bool;
		$this->auth->return_objects( $bool );
	}


	/**
	 * Query the Google Analytics API
	 *
	 * @access public
	 * @param array $params (default: array()) Query parameters.
	 * @return array data
	 */
	public function query( $params = array() ) {
		return $this->_query( $params );
	}


	/**
	 * Get all WebProperties
	 *
	 * @access public
	 * @throws Exception Return.
	 * @return array data
	 */
	public function get_web_properties() {

		if ( ! $this->access_token ) {
			throw new Exception( 'You must provide an accessToken' );
		}

		$data = Http::curl(
			self::WEBPROPERTIES_URL, array(
				'access_token' => $this->access_token,
			)
		);
		return json_decode( $data, $this->assoc );

	}


	/**
	 * Get all Profiles
	 *
	 * @access public
	 * @throws Exception Return.
	 * @return array data
	 */
	public function get_profiles() {

		if ( ! $this->access_token ) {
			throw new Exception( 'You must provide an accessToken' );
		}

		$data = Http::curl(
			self::PROFILES_URL, array(
				'access_token' => $this->access_token,
			)
		);
		return json_decode( $data, $this->assoc );

	}

	/**
	 * **************************************************************************************************************************
	 *
	 * The following methods implement queries for the most useful statistics, seperated by topics: Audience/Content/Traffic Sources
	 *****************************************************************************************************************************/

	/**
	 * Function Name: get_visits_by_date.
	 * Function Description: Get visits by date function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_visits_by_date( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:date',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_audience_statistics.
	 * Function Description: Get audience statistics function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_audience_statistics( $params = array() ) {

		$defaults = array(
			'metrics' => 'ga:visitors,ga:newVisits,ga:percentNewVisits,ga:visits,ga:bounces,ga:pageviews,ga:visitBounceRate,ga:timeOnSite,ga:avgTimeOnSite',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_visits_by_countries.
	 * Function Description: Get visits by countries function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_visits_by_countries( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:country',
			'sort'       => '-ga:visits',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_visits_by_cities.
	 * Function Description: Get visits by cities function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_visits_by_cities( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:city',
			'sort'       => '-ga:visits',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_visits_by_languages.
	 * Function Description: Get visits by languages function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_visits_by_languages( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:language',
			'sort'       => '-ga:visits',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_visits_by_system_browsers.
	 * Function Description: Get visits by system browsers function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_visits_by_system_browsers( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:browser',
			'sort'       => '-ga:visits',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_visits_by_system_os.
	 * Function Description: Get visits by system os function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_visits_by_system_os( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:operatingSystem',
			'sort'       => '-ga:visits',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_visits_by_system_resolutions.
	 * Function Description: Get visits by system resolutions function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_visits_by_system_resolutions( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:screenResolution',
			'sort'       => '-ga:visits',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_visits_by_mobile_os.
	 * Function Description: Get visits by mobile os function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_visits_by_mobile_os( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:operatingSystem',
			'sort'       => '-ga:visits',
			'segment'    => 'gaid::-11',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_visits_by_mobile_resolutions.
	 * Function Description: Get visits by mobile resolutions function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_visits_by_mobile_resolutions( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:screenResolution',
			'sort'       => '-ga:visits',
			'segment'    => 'gaid::-11',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_pageviews_by_date.
	 * Function Description: Get pageviews by date function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_pageviews_by_date( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:pageviews',
			'dimensions' => 'ga:date',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_content_statistics.
	 * Function Description: Get content statistics function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_content_statistics( $params = array() ) {

		$defaults = array(
			'metrics' => 'ga:pageviews,ga:uniquePageviews',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_content_top_pages.
	 * Function Description: Get content top pages function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_content_top_pages( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:pageviews',
			'dimensions' => 'ga:pagePath',
			'sort'       => '-ga:pageviews',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_traffic_sources.
	 * Function Description: Get traffic sources function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_traffic_sources( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:medium',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_keywords.
	 * Function Description: Get keywords function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_keywords( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:keyword',
			'sort'       => '-ga:visits',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: get_referral_traffic.
	 * Function Description: Get referral traffic function.
	 *
	 * @param array $params Array parameter.
	 */
	public function get_referral_traffic( $params = array() ) {

		$defaults = array(
			'metrics'    => 'ga:visits',
			'dimensions' => 'ga:source',
			'sort'       => '-ga:visits',
		);
		$_params  = array_merge( $defaults, $params );
		return $this->_query( $_params );

	}

	/**
	 * Function Name: _query.
	 * Function Description: Query function.
	 *
	 * @param array $params Array parameter.
	 * @throws Exception Return.
	 */
	protected function _query( $params = array() ) {

		if ( ! $this->access_token || ! $this->account_id ) {
			throw new Exception( 'You must provide the accessToken and an accountId' );
		}
		$_params      = array_merge(
			$this->default_query_params, array(
				'access_token' => $this->access_token,
				'ids'          => $this->account_id,
			)
		);
		$query_params = array_merge( $_params, $params );
		$data         = Http::curl( self::API_URL, $query_params );
		return json_decode( $data, $this->assoc );

	}

}


/**
 * Abstract Auth class
 */
abstract class GoogleOauth {

	const TOKEN_URL = 'https://accounts.google.com/o/oauth2/token';
	const SCOPE_URL = 'https://www.googleapis.com/auth/analytics.readonly';

	/**
	 * Variable
	 *
	 * @var assoc
	 */
	protected $assoc = true;
	/**
	 * Variable
	 *
	 * @var client_id
	 */
	protected $client_id = '';

	/**
	 * Function Name: __set.
	 * Function Description: Setting.
	 *
	 * @param array $key Key array.
	 * @param array $value Array values.
	 */
	public function __set( $key, $value ) {
		$this->{$key} = $value;
	}


	/**
	 * Function Name: set_client_id.
	 * Function Description: Set client id.
	 *
	 * @param array $id Client ID.
	 */
	public function set_client_id( $id ) {
		$this->client_id = $id;
	}

	/**
	 * Function Name: return_objects.
	 * Function Description: Return objects.
	 *
	 * @param bool $bool Object true or false.
	 */
	public function return_objects( $bool ) {
		$this->assoc = ! $bool;
	}

	/**
	 * To be implemented by the subclasses
	 */
	/**
	 * Function Name: get_access_token.
	 * Function Description: Get access token function.
	 *
	 * @param int $data data value.
	 */
	public function get_access_token( $data = null ) {}

}


/**
 * Oauth 2.0 for service applications requiring a private key
 * openssl extension for PHP is required!
 *
 * @extends GoogleOauth
 */
class GoogleOauthService extends GoogleOauth {

	const MAX_LIFETIME_SECONDS = 3600;
	const GRANT_TYPE           = 'urn:ietf:params:oauth:grant-type:jwt-bearer';

	/**
	 * Variable
	 *
	 * @var email
	 */
	protected $email = '';
	/**
	 * Variable
	 *
	 * @var private_key
	 */
	protected $private_key = null;
	/**
	 * Variable
	 *
	 * @var password
	 */
	protected $password = 'notasecret';

	/**
	 * Constructor
	 *
	 * @access public
	 * @throws Exception Return.
	 * @param string $client_id (default: '') Client-ID of your project from the Google APIs console.
	 * @param string $email (default: '') E-Mail address of your project from the Google APIs console.
	 * @param mixed  $private_key (default: null) Path to your private key file (*.p12).
	 */
	public function __construct( $client_id = '', $email = '', $private_key = null ) {
		if ( ! function_exists( 'openssl_sign' ) ) {
			throw new Exception( 'openssl extension for PHP is needed.' );
		}
		$this->client_id   = $client_id;
		$this->email       = $email;
		$this->private_key = $private_key;
	}

	/**
	 * Function Name: setEmail.
	 * Function Description: Set email.
	 *
	 * @param string $email Emial id.
	 */
	public function setEmail( $email ) {
		$this->email = $email;
	}

	/**
	 * Function Name: setPrivateKey.
	 * Function Description: Set private key.
	 *
	 * @param int $key Key value.
	 */
	public function setPrivateKey( $key ) {
		$this->private_key = $key;
	}


	/**
	 * Get the accessToken in exchange with the JWT
	 *
	 * @access public
	 * @throws Exception Return.
	 * @param mixed $data (default: null) No data needed in this implementation.
	 * @return array Array with keys: access_token, expires_in
	 */
	public function get_access_token( $data = null ) {

		if ( ! $this->client_id || ! $this->email || ! $this->private_key ) {
			throw new Exception( 'You must provide the clientId, email and a path to your private Key' );
		}

		$jwt = $this->generateSignedJWT();

		$params = array(
			'grant_type' => self::GRANT_TYPE,
			'assertion'  => $jwt,
		);

		$auth = Http::curl( GoogleOauth::TOKEN_URL, $params, true );
		return json_decode( $auth, $this->assoc );

	}


	/**
	 * Generate and sign a JWT request
	 * See: https://developers.google.com/accounts/docs/OAuth2ServiceAccount
	 *
	 * @access protected
	 * @throws Exception Return.
	 */
	protected function generateSignedJWT() {

		// Check if a valid privateKey file is provided.
		if ( ! file_exists( $this->private_key ) || ! is_file( $this->private_key ) ) {
			throw new Exception( 'Private key does not exist' );
		}

		// Create header, claim and signature.
		$header = array(
			'alg' => 'RS256',
			'typ' => 'JWT',
		);

		$t      = time();
		$params = array(
			'iss'   => $this->email,
			'scope' => GoogleOauth::SCOPE_URL,
			'aud'   => GoogleOauth::TOKEN_URL,
			'exp'   => $t + self::MAX_LIFETIME_SECONDS,
			'iat'   => $t,
		);

		$encodings = array(
			base64_encode( json_encode( $header ) ),
			base64_encode( json_encode( $params ) ),
		);

		// Compute Signature.
		$input  = implode( '.', $encodings );
		$certs  = array();
		$pkcs12 = file_get_contents( $this->private_key );
		if ( ! openssl_pkcs12_read( $pkcs12, $certs, $this->password ) ) {
			throw new Exception( 'Could not parse .p12 file' );
		}
		if ( ! isset( $certs['pkey'] ) ) {
			throw new Exception( 'Could not find private key in .p12 file' );
		}
		$key_id = openssl_pkey_get_private( $certs['pkey'] );
		if ( ! openssl_sign( $input, $sig, $key_id, 'sha256' ) ) {
			throw new Exception( 'Could not sign data' );
		}

		// Generate JWT.
		$encodings[] = base64_encode( $sig );
		$jwt         = implode( '.', $encodings );
		return $jwt;

	}

}




/**
 * Oauth 2.0 for web applications
 *
 * @extends GoogleOauth
 */
class GoogleOauthWeb extends GoogleOauth {

	const AUTH_URL   = 'https://accounts.google.com/o/oauth2/auth';
	const REVOKE_URL = 'https://accounts.google.com/o/oauth2/revoke';

	/**
	 * Variable
	 *
	 * @var client_secret
	 */
	protected $client_secret = '';
	/**
	 * Variable
	 *
	 * @var redirect_uri
	 */
	protected $redirect_uri = '';


	/**
	 * Constructor
	 *
	 * @access public
	 * @param string $client_id (default: '') Client-ID of your web application from the Google APIs console.
	 * @param string $client_secret (default: '') Client-Secret of your web application from the Google APIs console.
	 * @param string $redirect_uri (default: '') Redirect URI to your app - must match with an URL provided in the Google APIs console.
	 */
	public function __construct( $client_id = '', $client_secret = '', $redirect_uri = '' ) {
		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_uri  = $redirect_uri;
	}

	/**
	 * Function Name: setClientSecret.
	 * Function Description: Set client secret value.
	 *
	 * @param int $secret value.
	 */
	public function setClientSecret( $secret ) {
		$this->client_secret = $secret;
	}

	/**
	 * Function Name: setRedirectUri.
	 * Function Description: Set redirect uri.
	 *
	 * @param int $uri value.
	 */
	public function setRedirectUri( $uri ) {
		$this->redirect_uri = $uri;
	}

	/**
	 * Build auth url
	 * The user has to login with his Google Account and give your app access to the Analytics API
	 *
	 * @access public
	 * @throws Exception Return.
	 * @param array $params Custom parameters.
	 * @return string The auth login-url
	 */
	public function buildAuthUrl( $params = array() ) {

		if ( ! $this->client_id || ! $this->redirect_uri ) {
			throw new Exception( 'You must provide the clientId and a redirectUri' );
		}

		$defaults = array(
			'response_type'   => 'code',
			'client_id'       => $this->client_id,
			'redirect_uri'    => $this->redirect_uri,
			'scope'           => GoogleOauth::SCOPE_URL,
			'access_type'     => 'offline',
			'approval_prompt' => 'force',
		);
		$params   = array_merge( $defaults, $params );
		$url      = self::AUTH_URL . '?' . http_build_query( $params );
		return $url;

	}


	/**
	 * Get the AccessToken in exchange with the code from the auth along with a refreshToken
	 *
	 * @access public
	 * @throws Exception Return.
	 * @param int $data value.
	 * @return array Array with the following keys: access_token, refresh_token, expires_in
	 */
	public function get_access_token( $data = null ) {

		if ( ! $this->client_id || ! $this->client_secret || ! $this->redirect_uri ) {
			throw new Exception( 'You must provide the clientId, clientSecret and a redirectUri' );
		}

		$params = array(
			'code'          => $data,
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
			'redirect_uri'  => $this->redirect_uri,
			'grant_type'    => 'authorization_code',
		);

		$auth = Http::curl( GoogleOauth::TOKEN_URL, $params, true );
		return json_decode( $auth, $this->assoc );

	}


	/**
	 * Get a new accessToken with the refreshToken
	 *
	 * @access public
	 * @throws Exception Return.
	 * @param mixed $refresh_token The refreshToken.
	 * @return array Array with the following keys: access_token, expires_in
	 */
	public function refreshAccessToken( $refresh_token ) {
		if ( ! $this->client_id || ! $this->client_secret ) {
			throw new Exception( 'You must provide the clientId and clientSecret' );
		}

		$params = array(
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
			'refresh_token' => $refresh_token,
			'grant_type'    => 'refresh_token',
		);

		$auth = Http::curl( GoogleOauth::TOKEN_URL, $params, true );
		return json_decode( $auth, $this->assoc );

	}


	/**
	 * Revoke access
	 *
	 * @access public
	 * @param mixed $token accessToken or refreshToken.
	 */
	public function revokeAccess( $token ) {

		$params = array(
			'token' => $token,
		);
		$data   = Http::curl( self::REVOKE_URL, $params );
		return json_decode( $data, $this->assoc );
	}


}



/**
 * Send data with curl
 */
class Http {


	/**
	 * Send http requests with curl
	 *
	 * @access public
	 * @static
	 * @param mixed $url The url to send data.
	 * @param array $params (default: array()) Array with key/value pairs to send.
	 * @param bool  $post (default: false) True when sending with POST.
	 */
	public static function curl( $url, $params = array(), $post = false ) {

		if ( empty( $url ) ) {
			return false;
		}

		if ( ! $post && ! empty( $params ) ) {
			$url = $url . '?' . http_build_query( $params );
		}
		$curl = curl_init( $url );
		if ( $post ) {
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $params );
		}
		curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );

		$data = curl_exec( $curl );

		$http_code = (int) curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		// Add the status code to the json data, useful for error-checking.
		$data = preg_replace( '/^{/', '{"http_code":' . $http_code . ',', $data );
		curl_close( $curl );
		return $data;

	}

}


