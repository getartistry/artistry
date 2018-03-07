<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Active_Campaign_Remove_Tag
 * @since 2.0.0
 */
class Action_Active_Campaign_Remove_Tag extends Action_Active_Campaign_Abstract {


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Remove Tags From Contact', 'automatewoo' );
	}


	function load_fields() {
		$this->add_contact_email_field();
		$this->add_tags_field()->set_required();
	}


	function run() {

		$email = Clean::email( $this->get_option( 'email', true ) );
		$tags = Clean::string( $this->get_option( 'tag',  true ) );

		if ( empty( $tags ) ) return;

		$data = [
			'email' => $email,
			'tags' => $this->parse_tags_field( $tags )
		];

		Integrations::activecampaign()->request( 'contact/tag/remove', $data );
	}

}
