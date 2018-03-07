<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Memberships_Change_Plan
 * @since 2.8
 */
class Action_Memberships_Change_Plan extends Action_Memberships_Abstract {

	public $required_data_items = [ 'user' ];


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( "Create / Change Membership Plan For User", 'automatewoo' );
		$this->description = __( "Changes the plan of a user's active membership. If no active membership exists a new membership can optionally be created.", 'automatewoo' );
	}


	function load_fields() {

		$plans = Memberships_Helper::get_membership_plans();

		$from_plan = new Fields\Select();
		$from_plan->set_options( $plans );
		$from_plan->set_name( 'from_plan' );
		$from_plan->set_title( __( 'Existing plan', 'automatewoo' ) );
		$from_plan->set_placeholder( __( '[None]', 'automatewoo' ) );
		$from_plan->set_description( __( 'Leave this blank to only create new memberships.', 'automatewoo' ) );

		$to_plan = new Fields\Select();
		$to_plan->set_options( $plans );
		$to_plan->set_name( 'to_plan' );
		$to_plan->set_title( __( 'New plan', 'automatewoo' ) );
		$to_plan->set_required();

		$allow_create = new Fields\Checkbox();
		$allow_create->set_name( 'allow_create' );
		$allow_create->set_title( __( 'Allow membership creation?', 'automatewoo' ) );
		$allow_create->set_description( __( 'If checked a new membership will be created for the user if they are not a member of the existing plan.', 'automatewoo' ) );

		$this->add_field( $from_plan );
		$this->add_field( $to_plan );
		$this->add_field( $allow_create );

	}


	function run() {

		$user = $this->workflow->data_layer()->get_user();
		$existing_plan_id = absint( $this->get_option( 'from_plan' ) );
		$new_plan_id = absint( $this->get_option( 'to_plan' ) );
		$allow_create = Clean::string( $this->get_option( 'allow_create' ) );
		$new_plan = $new_plan_id ? wc_memberships_get_membership_plan( $new_plan_id ) : false;

		if ( ! $user instanceof \WP_User || ! $new_plan ) {
			return;
		}

		// check if user already has a membership with the new plan
		$check_for_membership = wc_memberships_get_user_membership( $user->ID, $new_plan );

		if ( $check_for_membership ) {
			$this->workflow->log_action_note( $this, __( 'Plan could not be changed or created because the user already has a membership using the new plan.', 'automatewoo' ) );
			return;
		}

		$membership = $existing_plan_id ? wc_memberships_get_user_membership( $user->ID, $existing_plan_id ) : false;

		if ( $membership ) {
			$this->change_membership_plan( $membership,  $new_plan );
		}
		elseif ( $allow_create ) {
			// if no existing plan and allow create is checked, create a new plan for the user
			$this->create_membership( $user, $new_plan );
		}

	}


	/**
	 * @param \WP_User $user
	 * @param \WC_Memberships_Membership_Plan $new_plan
	 */
	function create_membership( $user, $new_plan ) {

		$membership = wc_memberships_create_user_membership([
			'user_id' => $user->ID,
			'plan_id' => $new_plan->get_id(),
		]);

		if ( is_wp_error( $membership ) ) {
			$this->workflow->log_action_error( $this, $membership->get_error_message() );
		}
		else {

			$membership->add_note(
				sprintf(
					__( 'Membership created by AutomateWoo workflow #%s', 'automatewoo' ),
					$this->workflow->get_id()
				)
			);

			$this->workflow->log_action_note( $this, sprintf(
				__( 'Membership #%s successfully created.', 'automatewoo' ),
				$membership->get_id()
			));
		}

	}


	/**
	 * @param \WC_Memberships_User_Membership $membership
	 * @param \WC_Memberships_Membership_Plan $new_plan
	 */
	function change_membership_plan( $membership, $new_plan ) {

		$membership->add_note(
			sprintf(
				__( 'Membership plan changed from %s to %s by AutomateWoo workflow:#%s', 'automatewoo' ),
				$membership->get_plan()->get_name(),
				$new_plan->get_name(),
				$this->workflow->get_id()
			)
		);

		wp_update_post([
			'ID' => $membership->get_id(),
			'post_parent' => $new_plan->get_id()
		]);
	}
}
