<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Integration_Campaign_Monitor
 * @since 3.0
 */
class Integration_Campaign_Monitor extends Integration {

	/** @var string */
	public $integration_id = 'campaign-monitor';

	/** @var string */
	private $api_key;

	/** @var string */
	private $client_id;

	/** @var string  */
	private $api_root = 'https://api.createsend.com/api/v3.1';


	/**
	 * @param string $api_key
	 * @param string|false $client_id client ID is not required to support legacy action
	 */
	function __construct( $api_key, $client_id = false ) {
		$this->api_key = $api_key;
		$this->client_id = $client_id;
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
		$request_args = [
			'headers' => [
				'Authorization' => 'Basic ' . base64_encode( $this->api_key . ':x' ),
				'Accept' => 'application/json'
			],
			'timeout' => 10,
			'method' => $method,
			'sslverify' => false
		];

		$url = $this->api_root . $endpoint;

		switch ( $method ) {
			case 'GET':
			case 'DELETE':
				$url = add_query_arg( array_map( 'urlencode', $args ), $url );
				break;

			default:
				$request_args['body'] = json_encode( $args );
				break;
		}

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


	/**
	 * @return array
	 */
	function get_lists() {
		if ( ! $this->client_id ) {
			return [];
		}

		if ( $cache = Cache::get_transient( 'campaign_monitor_lists' ) ) {
			return $cache;
		}

		$request = $this->request( 'GET', "/clients/{$this->client_id}/lists.json" );
		$lists = $request->get_body();
		$clean = [];

		if ( ! $request->is_successful() ) {
			return [];
		}

		foreach( $lists as $list ) {
			$clean[ $list['ListID'] ] = $list['Name'];
		}

		Cache::set_transient( 'campaign_monitor_lists', $clean, 0.15 );

		return $clean;
	}


	/**
	 * Clear cached data
	 */
	function clear_cache_data() {
		Cache::delete_transient( 'campaign_monitor_lists' );
	}


}
