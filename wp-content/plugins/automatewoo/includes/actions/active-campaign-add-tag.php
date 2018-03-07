<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Active_Campaign_Add_Tag
 * @since 2.0
 */
class Action_Active_Campaign_Add_Tag extends Action_Active_Campaign_Abstract {


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Add Tags To Contact', 'automatewoo' );
	}


	function load_fields() {

		$create_user = ( new Fields\Checkbox() )
			->set_name( 'create_missing_contact' )
			->set_title( __( "Create contact if missing", 'automatewoo' ) )
			->set_description( __( "The below fields will be used only if the contact needs to be created.", 'automatewoo' ) );

		$this->add_contact_email_field();
		$this->add_tags_field()->set_required();
		$this->add_field( $create_user );
		$this->add_contact_fields();
	}


	function run() {

		$email = Clean::email( $this->get_option( 'email', true ) );
		$tags = Clean::string( $this->get_option( 'tag',  true ) );
		$create_missing_contact = absint( $this->get_option( 'create_missing_contact' ) );

		if ( empty( $tags ) ) return;

		$api = Integrations::activecampaign();

		if ( $api->is_contact( $email ) ) {

			$data = [
				'email' => $email,
				'tags' => $this->parse_tags_field( $tags )
			];

			$api->request( 'contact/tag/add', $data );
		}
		else {

			if ( $create_missing_contact ) {

				$first_name = Clean::string( $this->get_option( 'first_name', true ) );
				$last_name = Clean::string( $this->get_option( 'last_name', true ) );
				$phone = Clean::string( $this->get_option( 'phone', true ) );
				$company = Clean::string( $this->get_option( 'company', true ) );

				$contact = [
					'email' => $email,
					'tags' => $tags
				];

				if ( $first_name ) $contact['first_name'] = $first_name;
				if ( $last_name ) $contact['last_name'] = $last_name;
				if ( $phone ) $contact['phone'] = $phone;
				if ( $company ) $contact['orgname'] = $company;

				$api->request( 'contact/sync', $contact );

			}
		}

		$api->clear_contact_transients( $email );

	}
}
