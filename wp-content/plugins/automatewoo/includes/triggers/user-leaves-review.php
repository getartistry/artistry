<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/***
 * @class Trigger_User_Leaves_Review
 */
class Trigger_User_Leaves_Review extends Trigger {

	public $supplied_data_items = [ 'comment', 'user', 'product' ];


	function load_admin_details() {
		$this->title = __( 'User Leaves a Product Review [DEPRECATED]', 'automatewoo' );
		$this->group = __( 'DEPRECATED', 'automatewoo' );
		$this->description = $this->get_deprecation_warning() . ' ' . __( 'Please use the New Review Posted trigger.', 'automatewoo' );
	}


	function register_hooks() {
		add_action( 'transition_comment_status', [ $this, 'catch_comment_approval' ], 20, 3 );
		add_action( 'comment_post', [ $this, 'catch_new_comments' ], 10, 2 );
	}


	/**
	 * Catch any comments approved on creation
	 *
	 * @param $comment_ID
	 * @param $approved
	 */
	function catch_new_comments( $comment_ID, $approved ) {

		if ( $approved != 1 )
			return;

		$comment = get_comment( $comment_ID );

		$this->catch_hooks( $comment );
	}


	/**
	 * Catch any comments that were approved after creation
	 *
	 * @param $new_status string
	 * @param $old_status string
	 * @param $comment object
	 */
	function catch_comment_approval( $new_status, $old_status, $comment ) {

		if ( $new_status !== 'approved' )
			return;

		$this->catch_hooks( $comment );
	}


	/**
	 * @param $comment object
	 */
	function catch_hooks( $comment ) {

		if ( ! $comment->user_id )
			return;

		// Make sure the comment is on a product
		if ( 'product' === get_post_type( $comment->comment_post_ID ) ) {
			$user = get_user_by( 'id', $comment->user_id );
			$product = wc_get_product( $comment->comment_post_ID );

			$this->maybe_run([
				'user' => $user,
				'product' => $product,
				'comment' => $comment
			]);
		}
	}


	/**
	 * @param $workflow Workflow
	 *
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$user = $workflow->data_layer()->get_user();
		$product = $workflow->data_layer()->get_product();
		$comment = $workflow->data_layer()->get_comment();

		if ( ! $user || ! $product || ! $comment )
			return false;

		// only run once for each comment and workflow
		// just in case the comment is approved more than once
		$log_query = new Log_Query();
		$log_query->where( 'workflow_id', $workflow->get_id() );
		$log_query->where( 'comment_id', $comment->comment_ID );

		if ( $log_query->has_results() ) {
			return false;
		}

		return true;
	}

}
