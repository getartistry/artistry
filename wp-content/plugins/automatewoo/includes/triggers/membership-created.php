<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Membership_Created
 * @since 2.9
 */
class Trigger_Membership_Created extends Trigger_Abstract_Memberships {

	public $_membership_created_via_admin;


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Membership Created', 'automatewoo' );
	}


	function load_fields() {
		$plans_field = $this->get_field_membership_plans();
		$this->add_field( $plans_field );
	}


	function register_hooks() {

		if ( is_admin() ) {
			add_action( 'transition_post_status', [ $this, 'transition_post_status' ], 50, 3 );
		}

		add_action( 'wc_memberships_user_membership_created', [ $this, 'membership_created' ], 100, 2 );
	}


	/**
	 * @param $new_status
	 * @param $old_status
	 * @param \WP_Post $post
	 */
	function transition_post_status( $new_status, $old_status, $post ) {
		if ( $old_status === 'auto-draft' && $post->post_type === 'wc_user_membership' ) {
			// don't trigger now as post transition happens before data is saved
			$this->_membership_created_via_admin = $post->ID;
			add_action( 'wc_memberships_user_membership_saved', [ $this, 'membership_created_via_admin' ], 100, 2 );
		}
	}


	/**
	 * @param \WC_Memberships_Membership_Plan $plan
	 * @param $args
	 */
	function membership_created_via_admin( $plan, $args ) {
		// check the created membership is a match
		if ( $this->_membership_created_via_admin == $args['user_membership_id'] ) {
			$this->membership_created( $plan, $args );
		}
	}


	/**
	 * @param \WC_Memberships_Membership_Plan $plan
	 * @param array $args [
	 *     @type int $user_id
	 *     @type int $user_membership_id
	 *     @type bool $is_update
	 * ]
	 */
	function membership_created( $plan, $args ) {

		$membership_id = $args['user_membership_id'];
		$membership = wc_memberships_get_user_membership( $membership_id );

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

		// options
		$plans = Clean::recursive( $workflow->get_trigger_option( 'membership_plans' ) );

		if ( ! empty( $plans ) ) {
			if ( ! in_array( $membership->get_plan_id(), $plans ) ) {
				return false;
			}
		}

		return true;
	}


}
