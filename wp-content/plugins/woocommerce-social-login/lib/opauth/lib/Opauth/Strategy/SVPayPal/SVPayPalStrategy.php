<?php

/**
 * PayPal strategy for Opauth
 * based on https://developer.paypal.com/docs/integration/direct/identity/log-in-with-paypal/
 *
 * More information on Opauth: http://opauth.org
 *
 * @copyright    Copyright (c) 2014 SkyVerge, Inc.
 * @link         http://opauth.org
 * @package      Opauth.PayPalStrategy
 * @license      MIT License
 * @version      1.0
 */
class SVPayPalStrategy extends OpauthStrategy {


	/** live authentication endpoint */
	const LIVE_AUTH_ENDPOINT = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';

	/** sandbox authentication endpoint */
	const SANDBOX_AUTH_ENDPOINT = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';

	/** live API endpoint */
	const LIVE_API_ENDPOINT = 'https://api.paypal.com/v1/identity/openidconnect';

	/** sandbox API endpoint */
	const SANDBOX_API_ENDPOINT = 'https://api.sandbox.paypal.com/v1/identity/openidconnect';

	/** @var string API endpoint for specified environment */
	private $api_endpoint;

	/** @var string Auth endpoint for specified environment */
	private $auth_endpoint;

	/** @var array require config keys */
	public $expects = array( 'client_id', 'client_secret',  );

	/** @var array optional config keys */
	public $optionals = array( 'redirect_uri', 'scope', 'environment' );

	/** @var array optional configuration with defaults */
	public $defaults = array(
		'redirect_uri' => '{complete_url_to_strategy}oauth2callback',
		'scope'        => 'openid profile email',
		'environment'  => 'live',
	);


	/**
	 * Setup strategy
	 *
	 * @since 1.0
	 * @param array $strategy Strategy-specific configuration
	 * @param array $env Safe env values from Opauth, with critical parameters stripped out
	 */
	public function __construct( $strategy, $env ) {

		parent::__construct( $strategy, $env );

		$this->api_endpoint =  ('live' === $this->strategy['environment'] ) ? self::LIVE_API_ENDPOINT : self::SANDBOX_API_ENDPOINT;
		$this->auth_endpoint = ( 'live' === $this->strategy['environment'] ) ? self::LIVE_AUTH_ENDPOINT : self::SANDBOX_AUTH_ENDPOINT;
	}


	/**
	 * Make the initial auth request to PayPal
	 *
	 * @link https://developer.paypal.com/docs/api/#obtain-users-consent
	 * @since 1.0
	 */
	public function request() {

		$params = array(
			'client_id'     => $this->strategy['client_id'],
			'redirect_uri'  => $this->strategy['redirect_uri'],
			'response_type' => 'code',
			'scope'         => $this->strategy['scope']
		);

		// merge optional params with defaults
		foreach ( $this->optionals as $key ) {
			if ( ! empty( $this->strategy[ $key ] ) ) {
				$params[ $key ] = $this->strategy[ $key ];
			}
		}

		$this->clientGet( $this->auth_endpoint, $params );
	}


	/**
	 * Exchange auth token for permanent access token and request user info
	 *
	 * @link https://developer.paypal.com/docs/api/#identity
	 * @since 1.0
	 */
	public function oauth2callback() {

		if ( ! empty( $_GET['code'] ) ) {

			// set params for requesting access token
			$params = array(
				'client_id'     => $this->strategy['client_id'],
				'client_secret' => $this->strategy['client_secret'],
				'grant_type'    => 'authorization_code',
				'code'          => $_GET['code'],
				'redirect_uri'  => $this->strategy['redirect_uri'],
			);

			// get access token
			$response = $this->serverPost( "{$this->api_endpoint}/tokenservice", $params, null, $headers );

			$results = json_decode( $response );

			if ( ! empty( $results ) && ! empty( $results->access_token ) ) {

				$userinfo = $this->userinfo( $results->access_token );

				$this->auth = array(
					'uid'         => $userinfo['user_id'],
					'info'        => array(),
					'credentials' => array(
						'token'   => $results->access_token,
						'expires' => date( 'c', time() + $results->expires_in )
					),
					'raw'         => $userinfo
				);

				if ( ! empty( $results->refresh_token ) ) {
					$this->auth['credentials']['refresh_token'] = $results->refresh_token;
				}

				$this->mapProfile( $userinfo, 'name', 'info.name' );
				$this->mapProfile( $userinfo, 'email', 'info.email' );
				$this->mapProfile( $userinfo, 'given_name', 'info.first_name' );
				$this->mapProfile( $userinfo, 'family_name', 'info.last_name' );

				$this->callback();
			} else {
				$error = array(
					'code'    => 'access_token_error',
					'message' => 'Failed when attempting to obtain access token',
					'raw'     => array(
						'response' => $response,
						'headers'  => $headers
					)
				);

				$this->errorCallback( $error );
			}

		} else {
			$error = array(
				'code' => 'oauth2callback_error',
				'raw'  => $_GET
			);

			$this->errorCallback( $error );
		}
	}


	/**
	 * Queries PayPal's API for user info
	 *
	 * @link https://developer.paypal.com/docs/api/#get-user-information
	 * @link https://developer.paypal.com/docs/integration/direct/identity/attributes/
	 *
	 * @since 1.0
	 * @param string $access_token
	 * @return array Parsed JSON results
	 */
	private function userinfo( $access_token ) {

		$options['http']['header'] = array(
			'Content-Type'  => 'application/json',
			'Authorization' => "Bearer {$access_token}",
		);

		$userinfo = $this->serverGet( "{$this->api_endpoint}/userinfo", array( 'schema' => 'openid' ), $options, $headers );

		if ( ! empty( $userinfo ) ) {
			return $this->recursiveGetObjectVars( json_decode( $userinfo ) );
		} else {
			$error = array(
				'code'    => 'userinfo_error',
				'message' => 'Failed when attempting to query for user information',
				'raw'     => array(
					'response' => $userinfo,
					'headers'  => $headers
				)
			);

			$this->errorCallback( $error );
		}
	}
}
