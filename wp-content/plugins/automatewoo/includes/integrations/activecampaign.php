<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Integration_ActiveCampaign
 * @since 2.6.1
 */
class Integration_ActiveCampaign extends Integration {

	/** @var string */
	public $integration_id = 'activecampaign';

	/** @var string */
	private $api_key;

	/** @var string */
	private $api_url;

	/** @var \ActiveCampaign */
	private $sdk;

	/** @var int */
	public $request_count = 1;


	/**
	 * @param $api_url
	 * @param $api_key
	 */
	function __construct( $api_url, $api_key ) {
		$this->api_url = $api_url;
		$this->api_key = $api_key;
	}


	/**
	 * @return array
	 */
	function get_lists() {

		if ( $cache = Cache::get_transient( 'ac_lists' ) ) {
			return $cache;
		}

		if ( ! $sdk = $this->get_sdk() )
			return [];

		$lists = $sdk->api( 'list/list', [ 'ids' => 'all' ] );
		$clean_lists = [];

		foreach ( $lists as $list ) {
			if ( is_object($list) ) {
				$clean_lists[$list->id] = $list->name;
			}
		}

		Cache::set_transient( 'ac_lists', $clean_lists, 0.15 );

		return $clean_lists;
	}



	/**
	 * @param $email
	 * @return bool
	 */
	function is_contact( $email ) {

		$cache_key = 'aw_ac_is_contact_' . md5( $email );

		if ( $cache = get_transient( $cache_key ) ) {
			return $cache === 'yes';
		}

		$contact = $this->request( "contact/view?email=$email" );

		$is_contact = $contact->success;

		set_transient( $cache_key, $is_contact ? 'yes': 'no', MINUTE_IN_SECONDS * 5 );

		return $is_contact;
	}


	/**
	 * @param $email
	 */
	function clear_contact_transients( $email ) {
		delete_transient( 'aw_ac_is_contact_' . md5( $email ) );
	}


	/**
	 * @return array
	 */
	function get_contact_custom_fields() {

		if ( $cache = Cache::get_transient( 'ac_contact_fields' ) ) {
			return $cache;
		}

		if ( ! $sdk = $this->get_sdk() ) {
			return [];
		}

		$response = $sdk->api( 'list/field/view?ids=all' );
		$fields = [];

		foreach ( $response as $item ) {
			if ( is_object($item) ) {
				$fields[ $item->id ] = $item;
			}
		}

		Cache::set_transient( 'ac_contact_fields', $fields, 0.15 );

		return $fields;
	}




	/**
	 * @param $path
	 * @param $data
	 * @return \ActiveCampaign|false
	 */
	function request( $path, $data = [] ) {

		if ( ! $this->get_sdk() )
			return false;

		$this->request_count++;

		// avoid overloading the api
		if ( $this->request_count % 4 == 0 ) {
			sleep(2);
		}

		return $this->get_sdk()->api( $path, $data );
	}


	/**
	 * @return \ActiveCampaign
	 */
	private function get_sdk() {

		if ( ! isset( $this->sdk ) ) {

			if ( ! class_exists( '\ActiveCampaign' ) ) {
				require_once AW()->lib_path( '/activecampaign-api-php/includes/ActiveCampaign.class.php' );
			}

			if ( $this->api_url && $this->api_key ) {
				$this->sdk = new \ActiveCampaign( $this->api_url, $this->api_key );
			}
			else {
				$this->sdk = false;
			}
		}

		return $this->sdk;
	}


	function clear_cache_data() {
		Cache::delete_transient( 'ac_lists' );
		Cache::delete_transient( 'ac_contact_fields' );
	}

}
