<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Wishlist_Reminder
 */
class Trigger_Wishlist_Reminder extends Trigger {

	public $supplied_data_items = [ 'user', 'wishlist' ];

	public $allow_queueing = false;


	function load_admin_details() {
		$this->title = sprintf( __( 'Wishlist Reminder (%s)', 'automatewoo'), Wishlists::get_integration_title() );
		$this->group = __( 'Wishlists', 'automatewoo' );
		$this->description = __( "Setting the 'Reminder Interval' field to 30 will mean this trigger will fire every 30 days for any users that have items in their wishlist. This trigger is checked daily. Please note this doesn't work for guests because their wishlist data only exists in their session data.", 'automatewoo');
	}


	/**
	 * Add options to the trigger
	 */
	function load_fields() {

		$period = new Fields\Number();
		$period->set_name('interval');
		$period->set_title( __( 'Reminder interval (days)', 'automatewoo' ) );
		$period->set_description( __( 'E.g. Reminder any customers with items in a Wishlist every 30 days.', 'automatewoo'  ) );

		$once_only = new Fields\Checkbox();
		$once_only->set_name('once_only');
		$once_only->set_title( __( 'Once per customer', 'automatewoo' ));
		$once_only->set_description( __( 'If checked the trigger will fire only once for each customer for each wishlist they create. Most customers only use the one wishlist so use with caution. Setting a high Reminder interval may be a better plan.', 'automatewoo'  ) );

		$this->add_field( $period );
		$this->add_field( $once_only );
	}


	/**
	 * When should this trigger run?
	 */
	function register_hooks() {
		add_action( 'automatewoo_daily_worker', [ $this, 'catch_hooks' ] );
	}


	/**
	 * Route hooks through here
	 */
	function catch_hooks() {

		// As this query is going to be memory intensive lets make sure we have a workflow using this trigger
		if ( ! $this->has_workflows() )
			return;

		$integration = Wishlists::get_integration();

		if ( $integration == 'woothemes' ) {
			$query = new \WP_Query([
				'post_type' => 'wishlist',
				'posts_per_page' => -1
			]);
			$wishlists = $query->posts;
		}
		elseif( $integration == 'yith') {
			$wishlists = YITH_WCWL()->get_wishlists([
				'user_id' => false,
				'show_empty' => false
			]);
		}
		else {
			return;
		}


		if ( is_array( $wishlists ) ) {

			foreach( $wishlists as $wishlist ) {

				$normalized_wishlist = Wishlists::get_normalized_wishlist( $wishlist );

				$user = get_user_by( 'id', $normalized_wishlist->get_user_id() );

				if ( $user ) {
					$this->maybe_run([
						'user' => $user,
						'wishlist' => $normalized_wishlist
					]);
				}
			}
		}

	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$user = $workflow->data_layer()->get_user();
		$wishlist = $workflow->data_layer()->get_wishlist();

		if ( ! $user || ! $wishlist ) {
			return false;
		}

		// Only do this once for each user for each workflow and each wishlist
		if ( $workflow->get_trigger_option('once_only') ) {

			$log_query = ( new Log_Query() )
				->where( 'workflow_id', $workflow->get_id() )
				->where( 'wishlist_id', $wishlist->get_id() )
				->where( 'user_id', $user->ID );

			if ( $log_query->has_results() ) {
				return false;
			}
		}


		$interval = absint( $workflow->get_trigger_option('interval') );

		if ( ! $interval ) {
			return false;
		}

		$last_interval_date = new \DateTime();
		$last_interval_date->modify( "-$interval days" );


		// Now check our logs for the last run
		$log_query = ( new Log_Query() )
			->where( 'workflow_id', $workflow->get_id() )
			->where( 'date', $last_interval_date, '>' )
			->where( 'wishlist_id', $wishlist->get_id() )
			->where( 'user_id', $user->ID );

		if ( $log_query->has_results() ) {
			return false;
		}

		return true;
	}

}

