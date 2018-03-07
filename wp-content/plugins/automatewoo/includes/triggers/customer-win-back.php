<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Guest customers are currently not supported
 *
 * @class Trigger_Customer_Win_Back
 */
class Trigger_Customer_Win_Back extends Trigger {

	public $supplied_data_items = [ 'customer', 'order' ];


	function load_admin_details() {
		$this->title = __( 'Customer Win Back', 'automatewoo' );
		$this->description = __( "This trigger fires for customers based on their last purchase date. Please note that it will not start running immediately because it is processed daily in the background. The 'order based' variables, rules and actions used by this trigger refer to the customer's last successful order.", 'automatewoo' );
		$this->group = __( 'Customers', 'automatewoo' );
	}


	function load_fields() {

		$period = ( new Fields\Number() )
			->set_name( 'days_since_last_purchase' )
			->set_title( __( 'Minimum days since last purchase', 'automatewoo' ) )
			->set_description( __( "Defines the minimum number of days to wait after a customer's last purchase.", 'automatewoo' ) )
			->set_min(1)
			->set_required();

		$period_max = ( new Fields\Number() )
			->set_name( 'days_since_last_purchase_max' )
			->set_title( __( 'Maximum days since last purchase (optional)', 'automatewoo' ) )
			->set_description( __( "Defines the maximum number of days after the customer's last purchase that this trigger will fire. It is a good idea to set this field to avoid sending emails to excessively old customers when the workflow is first created.", 'automatewoo' ) );

		$repeat = ( new Fields\Checkbox() )
			->set_name( 'enable_repeats' )
			->set_title( __( 'Enable repeats', 'automatewoo' ) )
			->set_description( __( 'If checked this trigger will repeatedly fire after the minimum last purchase date passes and the customer has not made a purchase. E.g. if the minimum is set to 30 days the trigger will fire 30 days after the customers last purchase and every 30 days from then until the maximum is reached or the customer makes another purchase. If unchecked the trigger will not repeat until the customer makes a new purchase.', 'automatewoo' ) );

		$this->add_field( $period );
		$this->add_field( $period_max );
		$this->add_field( $repeat );
	}


	function register_hooks() {
		add_action( 'automatewoo_daily_worker', [ $this, 'run_daily_check' ] );
	}


	/**
	 * This trigger does not use $this->maybe_run() so we don't have to loop through every single user when processing
	 */
	function run_daily_check() {

		if ( ! $this->has_workflows() )
			return;

		if ( ! $workflows = $this->get_workflows() ) {
			return;
		}

		/** @var Background_Processes\Customer_Win_Back $process */
		$process = Background_Processes::get('customer_win_back');

		foreach ( $workflows as $workflow ) {

			foreach ( $this->get_customers_matching_last_purchase_range( $workflow ) as $customer ) {

				$process->push_to_queue([
					'customer_id' => $customer->get_id(),
					'workflow_id' => $workflow->get_id()
				]);
			}
		}

		$process->start();
	}


	/**
	 * Fetch users by date using the last order meta field
	 * @param $workflow
	 * @return Customer[]
	 */
	function get_customers_matching_last_purchase_range( $workflow ) {

		if ( ! $min_date = $this->get_min_last_order_date( $workflow ) ) {
			return [];
		}

		$query = new Customer_Query();
		$query->where( 'last_purchased', $min_date, '<' );

		if ( $max_date = $this->get_max_last_order_date( $workflow ) ) {
			$query->where( 'last_purchased', $max_date, '>' );
		}

		return $query->get_results();
	}


	/**
	 * @param Workflow $workflow
	 * @return \DateTime|bool
	 */
	function get_min_last_order_date( $workflow ) {

		if ( ! $days = absint( $workflow->get_trigger_option( 'days_since_last_purchase' ) ) ) {
			return false;
		}

		$date = new \DateTime();
		$date->modify( "-$days days" );

		return $date;
	}


	/**
	 * @param Workflow $workflow
	 * @return \DateTime|bool
	 */
	function get_max_last_order_date( $workflow ) {

		if ( ! $days = absint( $workflow->get_trigger_option( 'days_since_last_purchase_max' ) ) ) {
			return false;
		}

		$date = new \DateTime();
		$date->modify( "-$days days" );

		return $date;
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$customer = $workflow->data_layer()->get_customer();
		$most_recent_order = $workflow->data_layer()->get_order();
		$enable_repeats = absint( $workflow->get_trigger_option( 'enable_repeats' ) );

		if ( ! $customer || ! $most_recent_order ) {
			return false;
		}

		// exclude customers with active subscriptions
		// these customers are still active but their last purchase date might suggest they are inactive
		// TODO in the future the end date of the customers last subscription should be factored in to this logic
		if ( Integrations::subscriptions_enabled() && $customer->is_registered() ) {
			if ( wcs_user_has_subscription( $customer->get_user_id(), '', 'active' ) ) {
				return false;
			}
		}

		// for accuracy, we use the actual order date instead of Customer::get_date_last_purchased()
		$last_purchase_date = Compat\Order::get_date_created( $most_recent_order, true );
		$min_last_order_date = $this->get_min_last_order_date( $workflow );

		if ( ! $min_last_order_date || ! $last_purchase_date ) {
			return false;
		}

		$last_purchase_date = new \DateTime( $last_purchase_date );

		// update the stored last purchase date
		$customer->set_date_last_purchased( $last_purchase_date );
		$customer->save();

		// check that the user has not made a purchase since the start of the delay period
		if ( $last_purchase_date->getTimestamp() > $min_last_order_date->getTimestamp() ) {
			return false;
		}

		// if repeats are enabled the wait period should start at the last time the workflow was run or queued
		// if repeats are disabled the date range should start at the last order date
		$wait_period = $enable_repeats ? $min_last_order_date : $last_purchase_date;


		if ( $workflow->get_timing_type() !== 'immediately' ) {
			// check workflow has not been added to the queue already

			$query = ( new Queue_Query() )
				->where( 'workflow_id', $workflow->get_translation_ids() )
				->where( 'created', $wait_period, '>' )
				->where_meta( 'data_item_user', $customer->get_user_id() );

			$query->where_meta[] = [
				[
					'key' => 'data_item_customer',
					'value' => $customer->get_id()
				]
			];

			if ( $customer->is_registered() ) {
				$query->where_meta[] = [
					'key' => 'data_item_user',
					'value' => $customer->get_user_id()
				];
			}

			if ( $query->has_results() ) {
				return false;
			}
		}

		// check the workflow has not run already
		$query = ( new Log_Query() )
			->where( 'workflow_id', $workflow->get_translation_ids() )
			->where( 'date', $wait_period, '>' );

		$query->where_meta[] = [
			[
				'key' => '_data_layer_customer',
				'value' => $customer->get_id()
			]
		];

		if ( $customer->is_registered() ) {
			$query->where_meta[] = [
				'key' => 'user_id',
				'value' => $customer->get_user_id()
			];
		}

		if ( $query->has_results() )
			return false;
		
		return true;
	}


	/**
	 * @param Workflow $workflow
	 * @return bool
	 */
	function validate_before_queued_event( $workflow ) {

		if ( ! parent::validate_before_queued_event( $workflow ) ) {
			return false;
		}

		$customer = $workflow->data_layer()->get_customer();

		if ( ! $customer ) {
			return false;
		}

		$min_last_order_date = $this->get_min_last_order_date( $workflow );
		$last_purchase_date = $customer->get_date_last_purchased();

		if ( ! $min_last_order_date || ! $last_purchase_date ) {
			return false;
		}

		// check that the user has not made a purchase while the workflow was queued
		if ( $last_purchase_date->getTimestamp() > $min_last_order_date->getTimestamp() ) {
			return false;
		}

		return true;
	}

}
