<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Subscription_Before_End
 */
class Trigger_Subscription_Before_End extends Trigger_Subscription_Before_Renewal {


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Subscription Before End', 'automatewoo' );
		$this->description = __( 'This trigger checks for subscriptions that are due to expire/end once every 24 hours. The exact trigger time will vary.', 'automatewoo' );
	}


	/**
	 * Add options to the trigger
	 */
	function load_fields() {

		$days_before = ( new Fields\Number() )
			->set_name( 'days_before' )
			->set_title( __( 'Days before end', 'automatewoo' ) )
			->set_required();

		$this->add_field( $days_before );
		$this->add_field_subscription_products();
	}


	/**
	 * Route hooks through here
	 */
	function run_daily_check() {

		if ( ! $this->has_workflows() )
			return;

		/** @var Background_Processes\Subscription_Before_End $process */
		$process = Background_Processes::get('subscription_before_end');

		foreach ( $this->get_workflows() as $workflow ) {

			if ( ! $days_before = absint( $workflow->get_trigger_option('days_before') ) ) {
				continue;
			}

			$date = new \DateTime();
			$date->modify( "+$days_before days" );

			foreach ( $this->get_subscriptions_by_end_day( $date ) as $subscription_id ) {
				$process->push_to_queue([
					'subscription_id' => $subscription_id,
					'workflow_id' => $workflow->get_id()
				]);
			}

		}

		$process->start();
	}


	/**
	 * @param $date \DateTime
	 * @return array
	 */
	function get_subscriptions_by_end_day( $date ) {

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
					'key' => '_schedule_end',
					'compare' => '>',
					'value' => $day_start->format( Format::MYSQL )
				],
				[
					'key' => '_schedule_end',
					'compare' => '<',
					'value' => $day_end->format( Format::MYSQL )
				]
			]
		]);

		return $query->posts;
	}

}
