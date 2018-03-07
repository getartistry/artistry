<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Guest_Leaves_Review
 * @since 2.1.12
 */
class Trigger_Guest_Leaves_Review extends Trigger {

	public $supplied_data_items = [ 'guest', 'product', 'comment' ];


	function load_admin_details() {
		$this->title = __( 'Guest Leaves a Product Review [DEPRECATED]', 'automatewoo');
		$this->group = __( 'DEPRECATED', 'automatewoo' );
		$this->description = $this->get_deprecation_warning() . ' ' . __( 'Please use the New Review Posted trigger.', 'automatewoo' );
	}


	function load_fields(){}


	function register_hooks() {
		add_action( 'transition_comment_status', [ $this, 'catch_comment_approval' ], 20, 3 );
		add_action( 'comment_post', [ $this, 'catch_new_comments' ], 20, 2 ); // happens after the guest has been stored
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
	 * @param $comment \WP_Comment
	 */
	function catch_comment_approval( $new_status, $old_status, $comment ) {
		if ( $new_status !== 'approved' ) return;
		$this->catch_hooks( $comment );
	}


	/**
	 * @param \WP_Comment $comment
	 */
	function catch_hooks( $comment ) {

		if ( $comment->user_id )
			return;

		// Make sure the comment is on a product
		if ( 'product' === get_post_type( $comment->comment_post_ID ) ) {

			$guest = new Guest();
			$guest->get_by( 'email', strtolower( $comment->comment_author_email ) );

			$product = wc_get_product( $comment->comment_post_ID );

			$this->maybe_run([
				'guest' => $guest,
				'product' => $product,
				'comment' => $comment
			]);
		}
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {
		$product = $workflow->data_layer()->get_product();
		$comment = $workflow->data_layer()->get_comment();

		if ( ! $product || ! $comment ) {
			return false;
		}

		// only run once for each comment and workflow
		// just in case the comment is approved more than once
		$log_query = ( new Log_Query() )
			->where( 'workflow_id', $workflow->get_id() )
			->where( 'comment_id', $comment->comment_ID );

		if ( $log_query->has_results() ) {
			return false;
		}

		return true;
	}

}
