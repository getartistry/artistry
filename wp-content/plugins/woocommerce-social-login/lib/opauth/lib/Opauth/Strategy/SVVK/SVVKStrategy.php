<?php
/**
 * VK (VKontakte) strategy for Opauth
 * Development info:
 * http://vk.com/dev/sites
 * Scopes:
 * http://vk.com/dev/permissions
 * API versions:
 * http://vk.com/dev/versions
 *
 * @package           Opauth.SVVKStrategy
 * @since             1.6.0
 * @copyright         Copyright (c) 2015 SkyVerge, Inc.
 */

/** @noinspection ClassOverridesFieldOfSuperClassInspection */
class SVVKStrategy extends OpauthStrategy {

	/** Provider name */
	const PROVIDER_NAME = 'VK';

	/** API version */
	const API_VERSION = '5.35';

	/** Request Authentication endpoint */
	const AUTH_ENDPOINT = 'https://oauth.vk.com/authorize';

	/** Get Token endpoint */
	const TOKEN_ENDPOINT = 'https://oauth.vk.com/access_token';

	/** Get User Profile endpoint */
	const PROFILE_ENDPOINT = 'https://api.vk.com/method/users.get';

	/**
	 * Compulsory config keys, listed as non-associative arrays
	 */
	public $expects = array( 'app_id', 'app_secret' );

	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 */
	public $defaults = array(
		'redirect_uri' => '{complete_url_to_strategy}oauth2callback',
		'scope'        => 'email',
	);

	/**
	 * Auth request
	 */
	public function request() {
		$url  = self::AUTH_ENDPOINT;
		$data = array(
			'client_id'     => $this->strategy['app_id'],
			'scope'         => $this->strategy['scope'],
			'redirect_uri'  => $this->strategy['redirect_uri'],
			'response_type' => 'code',
			'v'             => self::API_VERSION
		);

		self::clientGet( $url, $data );
	}

	/**
	 * Internal callback to get the code and request que authorization token, after VK's OAuth
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


		$url             = self::TOKEN_ENDPOINT;
		$data            = array(
			'client_id'     => $this->strategy['app_id'],
			'client_secret' => $this->strategy['app_secret'],
			'code'          => $_GET['code'],
			'redirect_uri'  => $this->strategy['redirect_uri'],
		);
		$responseHeaders = '';

		$response = self::serverGet( $url, $data, null, $responseHeaders );

		if ( empty( $response ) ) {
			$error = array(
				'provider' => self::PROVIDER_NAME,
				'code'     => 'access_token_error',
				'message'  => 'Failed when attempting to get access token',
				'raw'      => array(
					'headers' => $responseHeaders
				)
			);

			$this->errorCallback( $error );

			return;
		}

		$results = json_decode( $response, true );

		if ( isset( $results->error ) ) {
			$error = array(
				'provider' => self::PROVIDER_NAME,
				'code'     => 'access_token_error',
				'message'  => 'Failed when attempting to get access token',
				'raw'      => array(
					'error' => $results->error
				)
			);

			$this->errorCallback( $error );

			return;
		}

		$user_info = $this->userinfo( $results['access_token'], $results['user_id'] )
			             ->response['0'];

		$this->auth = array(
			'provider'    => self::PROVIDER_NAME,
			'uid'         => $user_info->uid,
			'info'        => array(),
			'credentials' => array(
				'token'   => $results['access_token'],
				'expires' => date( 'c', time() + $results['expires_in'] )
			),
			'raw'         => $user_info
		);

		$this->mapProfile( $user_info, 'first_name', 'info.first_name' );
		$this->mapProfile( $user_info, 'last_name', 'info.last_name' );
		$this->mapProfile( $user_info, 'screen_name', 'info.nickname' );
		$this->mapProfile( $user_info, 'photo_100', 'info.image' );

		if ( ! empty( $results['email'] ) ) {
			$this->auth['info']['email'] = $results['email'];
		}

		$this->callback();

	}

	/**
	 * Get the user info
	 *
	 * @param string $access_token Access token
	 * @param string $uid          VK User ID
	 * @return stdClass Parsed JSON results
	 */
	private function userinfo( $access_token, $uid ) {
		$url  = self::PROFILE_ENDPOINT;
		$data = array(
			'access_token' => $access_token,
			'uid'          => $uid,
			'fields'       => 'uid, first_name, last_name, screen_name, photo_100'
		);

		$responseHeaders = '';
		$user_info_json  = self::serverGet( $url, $data, null, $responseHeaders );

		if ( empty( $user_info_json ) ) {
			$user_info_json = '{}';
			$error          = array(
				'provider' => self::PROVIDER_NAME,
				'code'     => 'userinfo_error',
				'message'  => 'Failed when attempting to query for user information',
				'raw'      => array(
					'response' => $access_token,
					'headers'  => $responseHeaders
				)
			);

			$this->errorCallback( $error );
		}

		return json_decode( $user_info_json );

	}

} // class
