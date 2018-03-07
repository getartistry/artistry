<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Active_Campaign_Update_Contact_Field
 */
class Action_Active_Campaign_Update_Contact_Field extends Action_Active_Campaign_Abstract {


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Update Contact Custom Field', 'automatewoo' );
	}


	function load_fields() {

		$field_options = [];

		foreach ( Integrations::activecampaign()->get_contact_custom_fields() as $field ) {
			$field_options[ $field->id ] = $field->title;
		}

		$field = ( new Fields\Select() )
			->set_name( 'field' )
			->set_title( __( 'Field', 'automatewoo' ) )
			->set_options( $field_options )
			->set_required();

		$value = ( new Fields\Text() )
			->set_name( 'value' )
			->set_title( __( 'Value', 'automatewoo' ) )
			->set_variable_validation();

		$this->add_contact_email_field();
		$this->add_field( $field );
		$this->add_field( $value );
	}


	function run() {

		$email = Clean::email( $this->get_option( 'email', true ) );
		$field_id = Clean::string( $this->get_option( 'field' ) );
		$value = Clean::string( $this->get_option( 'value', true ) );

		$api = Integrations::activecampaign();

		if ( ! $api->is_contact( $email ) ) {
			$this->workflow->log_action_error( $this, __( 'Failed because contact did not exist.', 'automatewoo' ) );
			return;
		}

		$contact = [
			'email' => $email,
			"field[$field_id,0]" => $value
		];

		$api->request( 'contact/sync', $contact );
	}

}
