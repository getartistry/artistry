<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Integration_Mad_Mimi
 * @since 2.7
 */
class Integration_Mad_Mimi extends Integration {

	/** @var string */
	public $integration_id = 'mad-mimi';

	/** @var string */
	private $username;

	/** @var string */
	private $api_key;

	/** @var string  */
	private $api_root = 'https://api.madmimi.com';


	/**
	 * @param $username
	 * @param $api_key
	 */
	function __construct( $username, $api_key ) {
		$this->username = $username;
		$this->api_key = $api_key;
	}


	/**
	 * Automatically logs errors
	 *
	 * @param $method
	 * @param $endpoint
	 * @param $args
	 *
	 * @return Remote_Request
	 */
	function request( $method, $endpoint, $args = [] ) {
		$args['username'] = $this->username;
		$args['api_key'] = $this->api_key;

		$request_args = [
			'headers' => [
				'Accept' => 'application/json'
			],
			'timeout' => 10,
			'method' => $method,
			'sslverify' => false
		];

		$url = $this->api_root . $endpoint;
		$url = add_query_arg( $args, $url );

		$request = new Remote_Request( $url, $request_args );

		if ( $request->is_failed() ) {
			$this->log( $request->get_error_message() );
		}
		elseif ( ! $request->is_http_success_code() ) {
			$this->log(
				$request->get_response_code() . ' ' . $request->get_response_message()
				. '. Method: ' . $method
				. '. Endpoint: ' . $endpoint
				. '. Response body: ' . print_r( $request->get_body(), true ) );
		}

		return $request;
	}


	function build_csv($arr) {
		$csv = "";
		$keys = array_keys($arr);
		foreach ($keys as $key => $value) {
			$value = esc_attr($value);
			$csv .= $value . ",";
		}
		$csv = substr($csv, 0, -1);
		$csv .= "\n";
		foreach ($arr as $key => $value) {
			$value = esc_attr($value);
			$csv .= $value . ",";
		}
		$csv = substr($csv, 0, -1);
		$csv .= "\n";
		return $csv;
	}


}
