<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_MailChimp_Unsubscribe
 */
class Action_MailChimp_Unsubscribe extends Action_MailChimp_Abstract {

	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Remove Contact From List', 'automatewoo' );
	}


	function load_fields() {

		$email = ( new Fields\Text() )
			->set_name( 'email' )
			->set_title( __( 'Contact email', 'automatewoo' ) )
			->set_description( __( 'You can use variables such as {{ customer.email }} here. If blank {{ customer.email }} will be used.', 'automatewoo' ) )
			->set_variable_validation()
			->set_required();

		$unsubscribe_only = new Fields\Checkbox();
		$unsubscribe_only->set_name('unsubscribe_only');
		$unsubscribe_only->set_title( __( 'Unsubscribe only', 'automatewoo' ) );
		$unsubscribe_only->set_description( __( 'If checked the user will be unsubscribed instead of deleted.', 'automatewoo' ) );

		$this->add_list_field();
		$this->add_field( $email );
		$this->add_field( $unsubscribe_only );
	}


	function run() {

		$list_id = Clean::string( $this->get_option('list') );
		$email = Clean::email( $this->get_option( 'email', true ) );

		if ( ! $list_id )
			return;

		// fallback to customer.email
		if ( ! $email && $customer = $this->workflow->data_layer()->get_customer() ) {
			$email = $customer->get_email();
		}

		$subscriber = md5( $email );

		if ( $this->get_option('unsubscribe_only') ) {
			Integrations::mailchimp()->request( 'PATCH', "/lists/$list_id/members/$subscriber", [
				'status' => 'unsubscribed',
			]);
		}
		else {
			Integrations::mailchimp()->request( 'DELETE', "/lists/$list_id/members/$subscriber" );
		}
	}

}
