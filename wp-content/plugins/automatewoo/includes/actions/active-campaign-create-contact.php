<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Active_Campaign_Create_Contact
 * @since 2.0
 */
class Action_Active_Campaign_Create_Contact extends Action_Active_Campaign_Abstract {


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Create / Update Contact', 'automatewoo' );
		$this->description = __( 'This trigger can be used to create or update contacts in ActiveCampaign. If an existing contact is found by email then an update will occur otherwise a new contact will be created. When updating a contact any fields left blank will not be updated.', 'automatewoo' );
	}


	function load_fields() {

		$list_select = ( new Fields\Select() )
			->set_title( __( 'Add to list', 'automatewoo' ) )
			->set_name( 'list' )
			->set_options( Integrations::activecampaign()->get_lists() )
			->set_description( __( 'Leave blank to add a contact without assigning them to any lists.', 'automatewoo' ) );

		$this->add_contact_email_field();
		$this->add_contact_fields();
		$this->add_field( $list_select );
		$this->add_tags_field()
			->set_title( __( 'Add tags', 'automatewoo' ) );
	}


	function run() {

		$email = Clean::email( $this->get_option( 'email', true ) );
		$first_name = Clean::string( $this->get_option( 'first_name', true ) );
		$last_name = Clean::string( $this->get_option( 'last_name', true ) );
		$phone = Clean::string( $this->get_option( 'phone', true ) );
		$company = Clean::string( $this->get_option( 'company', true ) );
		$list_id = Clean::string( $this->get_option( 'list' ) );
		$tags = Clean::string( $this->get_option( 'tag', true ) );

		$contact = [
			'email' => $email,
		];

		if ( $first_name ) $contact['first_name'] = $first_name;
		if ( $last_name ) $contact['last_name'] = $last_name;
		if ( $phone ) $contact['phone'] = $phone;
		if ( $company ) $contact['orgname'] = $company;
		if ( $tags ) $contact['tags'] = $tags;

		if ( $list_id ) {
			$contact[ "p[$list_id]" ] = $list_id;
			$contact[ "status[$list_id]" ] = 1;
		}

		$ac = Integrations::activecampaign();

		$ac->request( 'contact/sync', $contact );
		$ac->clear_contact_transients( $email );
	}

}
