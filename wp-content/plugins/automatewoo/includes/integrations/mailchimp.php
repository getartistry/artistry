<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Integration_Mailchimp
 */
class Integration_Mailchimp extends Integration {

	/** @var string */
	public $integration_id = 'mailchimp';

	/** @var string */
	private $api_key;

	/** @var string  */
	private $api_root = 'https://<dc>.api.mailchimp.com/3.0';


	/**
	 * @param $api_key
	 */
	function __construct( $api_key ) {
		$this->api_key = $api_key;
		list(, $data_center ) = explode( '-', $this->api_key );
		$this->api_root = str_replace( '<dc>', $data_center, $this->api_root );
	}


	/**
	 * @param $method
	 * @param $endpoint
	 * @param array $args
	 * @param bool $log_errors
	 * @return Remote_Request
	 */
	function request( $method, $endpoint, $args = [], $log_errors = true ) {

		$request_args = [
			'headers' => [
				'Authorization' => 'Basic ' . base64_encode( 'anystring:' . $this->api_key )
			],
			'timeout' => 15,
			'method' => $method,
			'sslverify' => false
		];

		$url = $this->api_root . $endpoint;

		switch ( $method ) {
			case 'GET':
				$url = add_query_arg( array_map( 'urlencode', $args ), $url );
				break;

			default:
				$request_args['body'] = json_encode( $args );
				break;
		}

		$request = new Remote_Request( $url, $request_args );

		if ( $log_errors ) {
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
		}

		return $request;
	}



	/**
	 * @return array
	 */
	function get_lists() {

		$cache = Cache::get_transient( 'mailchimp_lists' );

		if ( $cache ) {
			return $cache;
		}

		$request = $this->request( 'GET', '/lists', [
			'count' => 100,
		]);

		$clean_lists = [];

		if ( $request->is_successful() ) {
			$body = $request->get_body();

			if ( is_array( $body['lists'] ) ) {
				foreach( $body['lists'] as $list ) {
					$clean_lists[ $list['id'] ] = $list['name'];
				}
			}
		}

		Cache::set_transient( 'mailchimp_lists', $clean_lists, 0.15 );

		return $clean_lists;
	}


	/**
	 * @param $list_id
	 * @return array
	 */
	function get_list_fields( $list_id ) {

		if ( ! $list_id ) {
			return [];
		}

		$cache_key = "mailchimp_list_fields_$list_id";

		if ( $cache = Cache::get_transient( $cache_key ) ) {
			return (array) $cache;
		}

		$request = $this->request( 'GET', "/lists/$list_id/merge-fields", [
			'count' => 100,
		]);

		if ( ! $request->is_successful() ) {
			return [];
		}

		$body = $request->get_body();
		$fields = isset( $body['merge_fields'] ) ? $body['merge_fields'] : [];

		Cache::set_transient( $cache_key, $fields, 0.15 );

		return $fields;
	}


	/**
	 * @param $list_id
	 * @return array
	 */
	function get_list_interest_categories( $list_id ) {
		if ( ! $list_id ) {
			return [];
		}

		$cache_key = "mc_list_interests_$list_id";

		if ( $cache = Cache::get_transient( $cache_key ) ) {
			return (array) $cache;
		}

		$data = [];

		$request = $this->request( 'GET', "/lists/$list_id/interest-categories", [
			'count' => 100,
		]);

		if ( ! $request->is_successful() ) {
			return [];
		}

		$body = $request->get_body();

		$categories = isset( $body['categories'] ) ? (array) $body['categories'] : [];

		foreach( $categories as $category ) {
			$data[ $category['id'] ] = [
				'id' => $category['id'],
				'title' => $category['title'],
				'interests' => $this->get_interest_categories_interests( $list_id, $category['id'] )
			];
		}

		Cache::set_transient( $cache_key, $data, 0.15 );

		return $data;
	}


	/**
	 * protected method due to not being cached, use $this->get_list_interest_categories()
	 *
	 * @param $list_id
	 * @param $category_id
	 * @return array
	 */
	protected function get_interest_categories_interests( $list_id, $category_id ) {
		$data = [];

		$request = $this->request( 'GET', "/lists/$list_id/interest-categories/$category_id/interests", [
			'count' => 100,
		]);

		if ( ! $request->is_successful() ) {
			return [];
		}

		$body = $request->get_body();

		$interests = isset( $body['interests'] ) ? (array) $body['interests'] : [];

		foreach( $interests as $interest ) {
			$data[ $interest['id'] ] = $interest['name'];
		}

		return $data;
	}


	/**
	 * @param $email
	 * @param $list_id
	 * @return bool
	 */
	function is_contact( $email, $list_id ) {

		$cache_key = "mailchimp_is_contact_$list_id";

		// check memory cache
		if ( Temporary_Data::exists( $cache_key, $email ) ) {
			return Temporary_Data::get( $cache_key, $email );
		}

		$subscriber_hash = md5( $email );
		$subscribed = false;

		// will return 404 if subscriber doesn't exists, so don't log errors for this request
		$request = $this->request( 'GET', "/lists/$list_id/members/$subscriber_hash", [], false );

		if ( $request->is_failed() ) {
			return false; // bail and don't cache
		}

		if ( $request->is_http_success_code() ) {
			$subscribed = true;
		}

		Temporary_Data::set( $cache_key, $email, $subscribed );

		return $subscribed;
	}


	/**
	 * $interests should be an array with the interest ID as the key and
	 * true or false as the value, depending on whether adding or removing the group
	 *
	 * @param string $email
	 * @param string $list_id
	 * @param array $interests
	 * @return Remote_Request
	 */
	function update_contact_interest_groups( $email, $list_id, $interests ) {

		$subscriber_hash = md5( $email );

		$args = [
			'email_address' => $email,
			'status_if_new' => 'subscribed',
			'interests' => $interests
		];

		return Integrations::mailchimp()->request( 'PUT', "/lists/$list_id/members/$subscriber_hash", $args );
	}


	function clear_cache_data() {
		Cache::delete_transient( 'mailchimp_lists' );
	}

}
