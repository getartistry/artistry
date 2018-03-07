<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Campaign_Monitor_Add_Subscriber
 */
class Action_Campaign_Monitor_Add_Subscriber extends Action_Campaign_Monitor_Abstract {


	function load_admin_details() {
		$this->title = __( 'Add Subscriber to List', 'automatewoo' );
		parent::load_admin_details();
	}


	function load_fields() {
		$this->add_field( $this->get_subscriber_email_field() );
		$this->add_field( $this->get_subscriber_name_field() );
		$this->add_field( $this->get_list_field() );
		$this->add_field( $this->get_resubscribe_field() );
	}


	function run() {
		$email = Clean::email( $this->get_option( 'email', true ) );
		$name = Clean::string( $this->get_option( 'name', true ) );
		$list = Clean::string( $this->get_option( 'list' ) );
		$resubscribe = (bool) $this->get_option( 'resubscribe' );

		if ( ! $email || ! $list ) {
			return;
		}

		$api = Integrations::campaign_monitor();

		$data = [
			'EmailAddress' => $email,
			'Name' => $name,
			'Resubscribe' => $resubscribe,
		];

		$api->request( 'POST', "/subscribers/$list.json", $data );
	}

}
