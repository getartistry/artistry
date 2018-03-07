<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Customer_Remove_Tags
 */
class Action_Customer_Remove_Tags extends Action_Customer_Add_Tags {

	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Remove Tags From Customer', 'automatewoo' );
	}


	function run() {

		if ( ! $customer = $this->workflow->data_layer()->get_customer() ) {
			return;
		}

		$tags = Clean::recursive( $this->get_option( 'user_tags' ) );

		if ( ! $customer->is_registered() || empty( $tags ) ) {
			return;
		}

		wp_remove_object_terms( $customer->get_user_id(), $tags, 'user_tag' );
	}

}
