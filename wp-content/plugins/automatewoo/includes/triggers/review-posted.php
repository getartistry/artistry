<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Review_Posted
 */
class Trigger_Review_Posted extends Trigger {

	public $supplied_data_items = [ 'review', 'customer', 'product' ];


	function load_admin_details() {
		$this->title = __( 'New Review Posted', 'automatewoo' );
		$this->group = __( 'Reviews', 'automatewoo' );
		$this->description = __( 'This trigger does not fire until the review has been approved.', 'automatewoo' );
	}


	function register_hooks() {
		add_action( 'automatewoo/review/posted', [ $this, 'catch_hooks' ] );
	}


	/**
	 * @param Review $review
	 */
	function catch_hooks( $review ) {
		$this->maybe_run([
			'customer' => Customer_Factory::get_by_review( $review ),
			'product' => wc_get_product( $review->get_product_id() ),
			'review' => $review
		]);
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$customer = $workflow->data_layer()->get_customer();
		$product = $workflow->data_layer()->get_product();
		$review = $workflow->data_layer()->get_review();

		if ( ! $customer || ! $product || ! $review ) {
			return false;
		}

		// only run once for each comment and workflow
		// just in case the comment is approved more than once
		$log_query = new Log_Query();
		$log_query->where( 'workflow_id', $workflow->get_id() );
		$log_query->where_meta( '_data_layer_review', $review->get_id() );

		if ( $log_query->has_results() ) {
			return false;
		}

		return true;
	}

}
