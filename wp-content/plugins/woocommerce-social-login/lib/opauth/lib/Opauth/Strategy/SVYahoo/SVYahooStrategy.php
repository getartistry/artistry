<?php
/**
 * Yahoo strategy for Opauth
 * About Opauth:
 * http://opauth.org
 * How Yahoo OAuth2 works:
 * https://developer.yahoo.com/oauth2/guide/flows_authcode/
 * About Yahoo User ID (GUID)
 * https://developer.yahoo.com/social/rest_api_guide/ysocial_apis-guids.html
 *
 * @package           Opauth.YahooStrategy
 * @since             1.6.0
 * @copyright         Copyright (c) 2015 SkyVerge, Inc.
 */

/** @noinspection ClassOverridesFieldOfSuperClassInspection */
class SVYahooStrategy extends OpauthStrategy {

	/** Provider name */
	const PROVIDER_NAME = 'Yahoo';

	/** Request Authentication endpoint */
	const AUTH_ENDPOINT = 'https://api.login.yahoo.com/oauth2/request_auth';

	/** Get Token endpoint */
	const TOKEN_ENDPOINT = 'https://api.login.yahoo.com/oauth2/get_token';

	/** Get User Profile endpoint */
	const PROFILE_ENDPOINT = 'https://social.yahooapis.com/v1/user/%s/profile';

	/**
	 * Compulsory config keys, listed as non-associative arrays
	 */
	public $expects = array( 'client_id', 'client_secret' );

	/**
	 * Optional config keys, without predefining any default values.
	 */
	public $optionals = array( 'redirect_uri', 'state' );

	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 */
	public $defaults = array(
		'redirect_uri' => '{complete_url_to_strategy}oauth2callback',
	);

	/**
	 * Auth request
	 */
	public function request() {
		$url    = self::AUTH_ENDPOINT;
		$params = array(
			'client_id'     => $this->strategy['client_id'],
			'redirect_uri'  => $this->strategy['redirect_uri'],
			'response_type' => 'code',
		);

		foreach ( $this->optionals as $key ) {
			if ( ! empty( $this->strategy[ $key ] ) ) {
				$params[ $key ] = $this->strategy[ $key ];
			}
		}

		self::clientGet( $url, $params );
	}

	/**
	 * Internal callback, after OAuth
	 */
	public function oauth2callback() {

		if ( empty( $_GET['code'] ) ) {
			$error = array(
				'provider' => self::PROVIDER_NAME,
				'code'     => 'oauth2callback_error',
				'message'  => 'No authorization code received',
				'raw'      => $_GET
			);

			$this->errorCallback( $error );

			return;
		}

		$code = $_GET['code'];
		$url  = self::TOKEN_ENDPOINT;

		$args     = array(
			'method'  => 'POST',
			'headers' => array(
				'Authorization' => 'Basic ' .
				                   base64_encode( $this->strategy['client_id'] . ':' .
				                                  $this->strategy['client_secret'] )
			),
			'body'    => array(
				'code'         => $code,
				'redirect_uri' => $this->strategy['redirect_uri'],
				'grant_type'   => 'authorization_code'
			),
		);

		$response = wp_safe_remote_request( $url, $args );

		if ( is_wp_error( $response ) ) {
			$error = array(
				'provider' => self::PROVIDER_NAME,
				'code'     => 'remote_request_error',
				'raw'      => $response->get_error_message()
			);
			$this->errorCallback( $error );

			return;
		}

		$responseHeaders = wp_remote_retrieve_body( $response );

		$results = json_decode( $response['body'] );

		if ( ! empty( $results ) && ! empty( $results->access_token ) && ! empty( $results->xoauth_yahoo_guid ) ) {
			$user_info = $this->userinfo( $results->access_token, $results->xoauth_yahoo_guid );

			$email = $this->user_email( $user_info );

			if ( ! $email ) {
				$error = array(
					'provider' => self::PROVIDER_NAME,
					'code'     => 'no_email_error',
					'message'  => 'No email in the user profile',
					'raw'      => array(
						'response' => $response,
						'headers'  => $responseHeaders
					)
				);

				$this->errorCallback( $error );

				return;

			}

			$this->auth = array(

				'uid'         => $user_info->profile->guid,
				'info'        => array(
					'nickname'   => $user_info->profile->nickname,
					'first_name' => $user_info->profile->givenName,
					'last_name'  => $user_info->profile->familyName,
					'location'   => $user_info->profile->location,
					'image'      => $user_info->profile->image->imageUrl,
					'profileUrl' => $user_info->profile->profileUrl,
					'email'      => $email
				),
				'credentials' => array(
					'token'   => $results->access_token,
					'expires' => date( 'c', time() + $results->expires_in )
				),
				'raw'         => $user_info
			);

			$this->callback();
		} else {
			$error = array(
				'provider' => self::PROVIDER_NAME,
				'code'     => 'access_token_error',
				'message'  => 'Failed when attempting to obtain access token',
				'raw'      => array(
					'response' => $response,
					'headers'  => $responseHeaders
				)
			);

			$this->errorCallback( $error );
		}
	}

	/**
	 * Get the user info
	 *
	 * @param string $access_token      Access token
	 * @param string $xoauth_yahoo_guid Yahoo User ID (GUID)
	 * @return stdClass Parsed JSON results
	 */
	private function userinfo( $access_token, $xoauth_yahoo_guid ) {

		$url = sprintf( self::PROFILE_ENDPOINT, $xoauth_yahoo_guid );

		$data = array( 'format' => 'json' );

		$options['http']['header'] = array(
			'Content-Type'  => 'application/json',
			'Authorization' => "Bearer {$access_token}",
		);

		$responseHeaders = '';

		$user_info_json = self::serverGet( $url, $data, $options, $responseHeaders );

		if ( empty( $user_info_json ) ) {
			$user_info_json = '{}';
			$error          = array(
				'provider' => self::PROVIDER_NAME,
				'code'     => 'userinfo_error',
				'message'  => 'Failed when attempting to query for user information',
				'raw'      => array(
					'response' => $user_info_json,
					'headers'  => $responseHeaders
				)
			);

			$this->errorCallback( $error );
		}

		return json_decode( $user_info_json );
	}

	/**
	 * Note: email(s) are only returned if the Yahoo App has the
	 * "Profiles - Read/write public/private" permissions
	 * --
	 * Emails appears in the user profile in the form of
	 *
	 * @example
	 *      <code>
	 *      array(
	 *      0 => stdClass
	 *      -> handle = 'email1@example.com'
	 *      -> id = 123
	 *      -> primary = false
	 *      -> type = "HOME"
	 *      </code>
	 *      1 => stdClass
	 *      -> handle = 'email2@example.com'
	 *      -> id = 456
	 *      -> primary = true
	 *      -> type = "HOME"
	 *      </code>
	 *      )
	 * @param stdClass $user_info Obtained via $this->userinfo() call
	 * @return string Email or empty string if not retrieved
	 */
	protected function user_email( $user_info ) {

		$email = '';
		foreach ( $user_info->profile->emails as $email_object ) {
			if ( $email_object->primary ) {
				$email = $email_object->handle;
				break;
			}
		}

		return $email;
	}

} // class
