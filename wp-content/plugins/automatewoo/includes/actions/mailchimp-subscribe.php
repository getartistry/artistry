<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_MailChimp_Subscribe
 */
class Action_MailChimp_Subscribe extends Action_MailChimp_Abstract {

	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Add Contact To List', 'automatewoo' );
	}


	function load_fields() {

		$email = ( new Fields\Text() )
			->set_name( 'email' )
			->set_title( __( 'Contact email', 'automatewoo' ) )
			->set_description( __( 'You can use variables such as {{ customer.email }} here. If blank {{ customer.email }} will be used.', 'automatewoo' ) )
			//->set_required()
			->set_variable_validation();

		$first_name = ( new Fields\Text() )
			->set_name( 'first_name' )
			->set_title( __( 'First name', 'automatewoo' ) )
			->set_description( __( 'This field is optional.', 'automatewoo' ) )
			->set_variable_validation();

		$last_name = ( new Fields\Text() )
			->set_name( 'last_name' )
			->set_title( __( 'Last name', 'automatewoo' ) )
			->set_description( __( 'This field is optional.', 'automatewoo' ) )
			->set_variable_validation();

		$double_optin = ( new Fields\Checkbox() )
			->set_name('double_optin')
			->set_title( __( 'Double optin', 'automatewoo' ) )
			->set_description( __( 'Users will receive an email asking them to confirm their subscription.', 'automatewoo' ) );

		$this->add_list_field();
		$this->add_field( $email );
		$this->add_field( $double_optin );
		$this->add_field( $first_name );
		$this->add_field( $last_name );
	}


	function run() {

		$list_id = Clean::string( $this->get_option('list') );
		$email = Clean::email( $this->get_option( 'email', true ) );
		$first_name = Clean::string( $this->get_option( 'first_name', true ) );
		$last_name = Clean::string( $this->get_option( 'last_name', true ) );
		$customer = $this->workflow->data_layer()->get_customer();

		if ( ! $list_id ) {
			return;
		}

		if ( ! $email && $customer ) {
			// use to customer.email if blank
			$email = $customer->get_email();
		}

		$args = [];
		$subscriber_hash = md5( $email );

		$args['email_address'] = $email;
		$args['status'] = $this->get_option('double_optin') ? 'pending' : 'subscribed';

		if ( $first_name || $last_name ) {
			$args['merge_fields'] = [
				'FNAME' => $first_name,
				'LNAME' => $last_name
			];
		}

		Integrations::mailchimp()->request( 'PUT', "/lists/$list_id/members/$subscriber_hash", $args );
	}

}
