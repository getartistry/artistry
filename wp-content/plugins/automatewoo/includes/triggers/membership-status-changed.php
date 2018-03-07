<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Membership_Status_Changed
 * @since 2.8.3
 */
class Trigger_Membership_Status_Changed extends Trigger_Abstract_Memberships {


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Membership Status Changed', 'automatewoo' );
	}


	function load_fields() {

		$statuses = Memberships_Helper::get_membership_statuses();

		$plans_field = $this->get_field_membership_plans();

		$placeholder = __( 'Leave blank for any status', 'automatewoo'  );

		$from = ( new Fields\Select() )
			->set_title( __( 'Status changes from', 'automatewoo'  ) )
			->set_name( 'membership_status_from' )
			->set_options( $statuses )
			->set_placeholder( $placeholder )
			->set_multiple();

		$to = ( new Fields\Select() )
			->set_title( __( 'Status changes to', 'automatewoo'  ) )
			->set_name( 'membership_status_to' )
			->set_options( $statuses )
			->set_placeholder( $placeholder )
			->set_multiple();

		$this->add_field( $plans_field );
		$this->add_field( $from );
		$this->add_field( $to );
	}



	function register_hooks() {
		add_action( 'wc_memberships_user_membership_status_changed', [ $this, 'catch_hooks' ], 30, 3 );
	}


	/**
	 * @param \WC_Memberships_User_Membership $membership The membership
	 * @param string $old_status Old status, without the wcm- prefix
	 * @param string $new_status New status, without the wcm- prefix
	 */
	function catch_hooks( $membership, $old_status, $new_status ) {

		Temporary_Data::set( 'membership_old_status', $membership->get_id(), $old_status );

		$this->maybe_run([
			'membership' => $membership,
			'user' => Memberships_Helper::get_user_data( $membership )
		]);
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$membership = $workflow->data_layer()->get_membership();

		if ( ! $membership )
			return false;

		$status_from = Clean::recursive( $workflow->get_trigger_option( 'membership_status_from' ) );
		$status_to = Clean::recursive( $workflow->get_trigger_option( 'membership_status_to' ) );
		$plans = Clean::recursive( $workflow->get_trigger_option( 'membership_plans' ) );
		$old_status = Temporary_Data::get( 'membership_old_status', $membership->get_id() );

		if ( ! $this->validate_status_field( $status_from, $old_status ) ) {
			return false;
		}

		if ( ! $this->validate_status_field( $status_to, $membership->get_status() ) ) {
			return false;
		}

		if ( ! empty( $plans ) ) {
			if ( ! in_array( $membership->get_plan_id(), $plans ) ) {
				return false;
			}
		}

		return true;
	}



	/**
	 * Ensures 'to' status has not changed while sitting in queue
	 *
	 * @param $workflow
	 * @return bool
	 */
	function validate_before_queued_event( $workflow ) {

		// check parent
		if ( ! parent::validate_before_queued_event( $workflow ) )
			return false;

		$membership = $workflow->data_layer()->get_membership();

		if ( ! $membership )
			return false;

		$status_to = Clean::recursive( $workflow->get_trigger_option( 'membership_status_to' ) );

		if ( ! $this->validate_status_field( $status_to, $membership->get_status() ) )
			return false;

		return true;
	}

}
