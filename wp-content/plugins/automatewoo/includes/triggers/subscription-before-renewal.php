<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Subscription_Before_Renewal
 * @since 2.6.2
 */
class Trigger_Subscription_Before_Renewal extends Trigger_Abstract_Subscriptions {


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Subscription Before Renewal', 'automatewoo' );
		$this->description = __( 'This trigger checks for upcoming subscription renewals once every 24 hours. The exact trigger time will vary.', 'automatewoo' );
	}


	/**
	 * Add options to the trigger
	 */
	function load_fields() {

		$days_before_renewal = ( new Fields\Number() )
			->set_name( 'days_before_renewal' )
			->set_title( __( 'Days before renewal', 'automatewoo' ) )
			->set_required();

		$this->add_field($days_before_renewal);
		$this->add_field_subscription_products();
	}


	/**
	 * Check for renewing subscriptions one each day
	 */
	function register_hooks() {
		// use strict worker so its blocked from ever firing twice in a day
		add_action( 'automatewoo_daily_worker_strict', [ $this, 'run_daily_check' ], 20, 1 );
	}


	/**
	 * Route hooks through here
	 */
	function run_daily_check() {

		if ( ! $this->has_workflows() )
			return;

		/** @var Background_Processes\Subscription_Before_Renewal $process */
		$process = Background_Processes::get('subscription_before_renewal');

		foreach ( $this->get_workflows() as $workflow ) {

			if ( ! $days_before_renewal = absint( $workflow->get_trigger_option('days_before_renewal') ) ) {
				continue;
			}

			$date = new \DateTime();
			$date->modify( "+$days_before_renewal days" );

			foreach ( $this->get_subscriptions_by_next_payment_day( $date ) as $subscription_id ) {
				$process->push_to_queue([
					'subscription_id' => $subscription_id,
					'workflow_id' => $workflow->get_id()
				]);
			}

		}

		$process->start();
	}


	/**
	 * Return an array of subscription ids that renew on a specific date
	 *
	 * @param $date \DateTime
	 * @return array
	 */
	function get_subscriptions_by_next_payment_day( $date ) {

		$day_start = clone $date;
		$day_end = clone $date;
		$day_start->setTime(0,0,0);
		$day_end->setTime(23,59,59);

		$query = new \WP_Query([
			'post_type' => 'shop_subscription',
			'post_status' => 'wc-active',
			'fields' => 'ids',
			'posts_per_page' => -1,
			'no_found_rows' => true,
			'meta_query' => [
				[
					'key' => '_schedule_next_payment',
					'compare' => '>',
					'value' => $day_start->format( Format::MYSQL )
				],
				[
					'key' => '_schedule_next_payment',
					'compare' => '<',
					'value' => $day_end->format( Format::MYSQL )
				]
			]
		]);

		return $query->posts;
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$subscription = $workflow->data_layer()->get_subscription();

		if ( ! $subscription ) {
			return false;
		}

		if ( ! $this->validate_subscription_products_field( $workflow ) ) {
			return false;
		}

		return true;
	}


	/**
	 * @param $workflow
	 * @return bool
	 */
	function validate_before_queued_event( $workflow ) {

		if ( ! parent::validate_before_queued_event( $workflow ) ) {
			return false;
		}

		$subscription = $workflow->data_layer()->get_subscription();

		if ( ! $subscription ) {
			return false;
		}

		// only trigger for active subscriptions
		if ( ! $subscription->has_status('active') ) {
			return false;
		}

		return true;
	}

}
