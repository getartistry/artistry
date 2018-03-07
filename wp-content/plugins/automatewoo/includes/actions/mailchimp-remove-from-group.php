<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_MailChimp_Remove_From_Group
 * @since 3.4.0
 */
class Action_MailChimp_Remove_From_Group extends Action_MailChimp_Add_To_Group {

	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Remove Contact From Group', 'automatewoo' );
	}


	function load_fields() {
		parent::load_fields();
		$this->remove_field( 'allow_add_to_list' );
	}


	function run() {

		$list_id = Clean::string( $this->get_option('list') );
		$email = Clean::email( $this->get_option( 'email', true ) );
		$interests = Clean::recursive( $this->get_option( 'groups' ) );

		if ( ! $list_id || ! $interests || ! $email ) {
			return;
		}

		if ( ! Integrations::mailchimp()->is_contact( $email, $list_id ) ) {
			return; // can't remove groups if no contact
		}

		$group_updates = [];

		foreach( $interests as $interest_id ) {
			$group_updates[ $interest_id ] = false;
		}

		Integrations::mailchimp()->update_contact_interest_groups( $email, $list_id, $group_updates );
	}

}
