<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * This trigger hooks in the the order completed action but will only fire once when a users total spend reaches a certain amount.
 *
 * @class Trigger_Customer_Total_Spend_Reaches
 */
class Trigger_Customer_Total_Spend_Reaches extends Trigger {

	public $supplied_data_items = [ 'customer', 'order' ];


	function load_admin_details() {
		$this->title = __( 'Customer Total Spend Reaches', 'automatewoo' );
		$this->description = __( "This trigger checks the customer's total spend each time an order is completed.", 'automatewoo');
		$this->group = __( 'Customers', 'automatewoo' );
	}


	function load_fields() {
		$total_spend = ( new Fields\Number() )
			->set_name( 'total_spend' )
			->set_title( __( 'Total spend', 'automatewoo' ) )
			->set_description( __( 'Do not add a currency symbol.', 'automatewoo'  ) )
			->set_required();

		$this->add_field( $total_spend );
	}


	/**
	 * Must run after customer totals have been updated
	 */
	function register_hooks() {
		add_action( $this->get_hook_order_status_changed(), [ $this, 'catch_hooks' ], 10, 3 );
	}


	/**
	 * @param $order_id
	 * @param $old_status
	 * @param $new_status
	 */
	function catch_hooks( $order_id, $old_status, $new_status ) {

		if ( $new_status !== 'completed' )
			return;

		$order = wc_get_order( $order_id );

		$this->maybe_run([
			'order' => $order,
			'customer' => Customer_Factory::get_by_order( $order )
		]);
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$customer = $workflow->data_layer()->get_customer();
		$order = $workflow->data_layer()->get_order();

		if ( ! $customer || ! $order ) {
			return false;
		}

		if ( ! $total_spend = floatval( $workflow->get_trigger_option('total_spend') ) )
			return false;

		if ( $customer->get_total_spent() < $total_spend ) {
			return false;
		}

		// Only do this once for each user (for each workflow)
		if ( $workflow->get_run_count_for_customer( $customer ) !== 0 ) {
			return false;
		}

		return true;
	}

}
