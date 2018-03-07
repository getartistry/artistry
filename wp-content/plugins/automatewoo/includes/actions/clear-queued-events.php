<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *	@class Action_Clear_Queued_Events
 * @since 2.2
 */
class Action_Clear_Queued_Events extends Action {


	function load_admin_details() {
		$this->title = __( 'Clear Queued Events', 'automatewoo' );
		$this->group = __( 'AutomateWoo', 'automatewoo' );
		$this->description = __( "Clears a customer's currently queued events for selected workflows.", 'automatewoo' );
	}


	function load_fields() {

		$workflows = new Fields\Workflow();
		$workflows->set_required();
		$workflows->set_title( __( 'Workflows', 'automatewoo' ) );
		$workflows->set_multiple();

		$user = new Fields\Text();
		$user->set_name('email');
		$user->set_title( __( 'Customer email', 'automatewoo' ) );
		$user->set_variable_validation();
		$user->set_required();

		$this->add_field($workflows);
		$this->add_field($user);
	}


	function run() {

		$email = Clean::email( $this->get_option( 'email', true ) );
		$workflows = Clean::ids( $this->get_option('workflow') );

		if ( empty( $workflows ) || ! $email ) {
			return;
		}

		if ( ! $customer = Customer_Factory::get_by_email( $email ) ) {
			return;
		}

		// create OR query for possible matching data from customer, user or guest
		$meta_query = [
			[
				'key' => 'data_item_customer',
				'value' => $customer->get_id()
			]
		];

		if ( $customer->is_registered() ) {
			$meta_query[] = [
				'key' => 'data_item_user',
				'value' => $customer->get_user_id()
			];
		}
		else {
			$meta_query[] = [
				'key' => 'data_item_guest',
				'value' => $customer->get_guest_id()
			];
		}

		$query = new Queue_Query();
		$query->where( 'workflow_id', $workflows );
		$query->where_meta[] = $meta_query;

		$results = $query->get_results();

		foreach ( $results as $result ) {
			$result->delete();
		}
	}

}