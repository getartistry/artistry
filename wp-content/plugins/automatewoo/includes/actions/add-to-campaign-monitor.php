<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Add_To_Campaign_Monitor
 * @deprecated February 2018
 */
class Action_Add_To_Campaign_Monitor extends Action_Campaign_Monitor_Abstract {

	public $required_data_items = [ 'customer' ];


	function load_admin_details() {
		$this->title = __( 'Campaign Monitor Add Customer to List [DEPRECATED]', 'automatewoo' );
		$this->group = __( 'DEPRECATED', 'automatewoo' );
		$this->description = $this->get_deprecation_warning() . ' ' . __( 'Instead please enable the Campaign Monitor integration in settings and then use the new <b>Campaign Monitor - Add Subscriber To List</b> action.', 'automatewoo' );
	}


	function load_fields() {

		$api_key = new Fields\Text();
		$api_key->set_name( 'api_key' );
		$api_key->set_title( __( 'API key', 'automatewoo' ) );
		$api_key->set_required( true );
		$api_key->set_description( __( 'You can get your API key from the Account Settings page when logged into your Campaign Monitor account.', 'automatewoo' ) );

		$list_id = new Fields\Text();
		$list_id->set_name( 'list_id' );
		$list_id->set_title( __( 'List ID', 'automatewoo' ) );
		$list_id->set_required(true);
		$list_id->set_description( __( 'You find the List ID of a list by heading into any list in your account and clicking the \'change name/type\' link below your list name.', 'automatewoo' ) );

		$this->add_field($api_key);
		$this->add_field($list_id);
		$this->add_field( $this->get_resubscribe_field() );
	}


	function run() {

		$customer = $this->workflow->data_layer()->get_customer();
		$api_key = Clean::string( $this->get_option('api_key') );
		$list_id = Clean::string( $this->get_option('list_id') );

		if ( ! $api_key || ! $list_id || ! $customer ) {
			return;
		}

		$campaign_monitor = new Integration_Campaign_Monitor( $api_key );

		$data = [
			'EmailAddress' => $customer->get_email(),
			'Name' => $customer->get_full_name(),
			'Resubscribe' => (bool) $this->get_option('resubscribe')
		];

		$campaign_monitor->request( 'POST', "/subscribers/$list_id.json", $data );
	}

}
