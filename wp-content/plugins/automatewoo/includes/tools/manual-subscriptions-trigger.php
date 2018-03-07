<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Tool_Manual_Subscriptions_Trigger
 * @since 2.4.5
 */
class Tool_Manual_Subscriptions_Trigger extends Tool {

	public $id = 'manual_subscriptions_trigger';


	function __construct() {

		$this->title = __( 'Manual Subscriptions Trigger', 'automatewoo' );
		$this->description = __( 'Manually trigger a workflow for any subscriptions that match a date range. USE WITH EXTREME CAUTION. '
			. 'For example this tool could be useful if you created a workflow using the <strong>Subscription Payment Complete</strong> trigger to queue a reminder '
			. 'email three days before the subscription renewal. You could use this tool to <strong>manually trigger</strong> and create the reminders the on all existing subscriptions which would '
			. 'normally not be queued until the next renewal. '
			, 'automatewoo' );

		$this->additional_description = sprintf(
			__( 'If you are processing a large amount of subscriptions they will be processed in the background at the rate of %d every %s minutes.', 'automatewoo' ),
			Tools::get_batch_size(),
			round( Tools::get_batch_delay() / 60 )
		);
	}


	/**
	 *
	 */
	function get_form_fields() {

		$fields = [];

		$fields[] = ( new Fields\Workflow() )
			->set_name_base('args')
			->add_query_arg( 'post_status', 'publish' )
			->set_required()
			->add_query_arg( 'meta_query', [[
				'key' => 'trigger_name',
				'value' => [
					'subscription_payment_complete',
					'subscription_status_changed'
				]
				]]);

		$fields[] = ( new Fields\Date() )
			->set_name('date_from')
			->set_title(__( 'Subscription Start Date - Range From','automatewoo' ))
			->set_name_base('args')
			->set_required();

		$fields[] = ( new Fields\Date() )
			->set_name('date_to')
			->set_title(__( 'Subscription Start Date - Range To','automatewoo' ))
			->set_name_base('args')
			->set_required();

		return $fields;
	}


	/**
	 * @param $args
	 * @return bool|\WP_Error
	 */
	function validate_process( $args ) {

		$args = $this->sanitize_args( $args );

		if ( empty( $args['workflow'] ) || empty( $args['date_from'] ) || empty( $args['date_to'] ) ) {
			return new \WP_Error( 1, __('Missing a required field.', 'automatewoo') );
		}

		$workflow = AW()->get_workflow( $args['workflow'] );

		if ( ! $workflow || ! $workflow->is_active() ) {
			return new \WP_Error( 2, __('The selected workflow is not currently active.', 'automatewoo') );
		}

		$subscriptions = $this->get_subscriptions( $args['date_from'], $args['date_to'] );

		if ( empty( $subscriptions ) ) {
			return new \WP_Error( 3, __( 'No subscriptions match that date range.', 'automatewoo') );
		}

		return true;
	}


	/**
	 * @param $date_from
	 * @param $date_to
	 * @return array
	 */
	function get_subscriptions( $date_from, $date_to ) {

		$query = new \WP_Query([
			'post_type' => 'shop_subscription',
			'post_status' => 'any',
			'fields' => 'ids',
			'posts_per_page' => -1,
			'date_query' => [
				[
					'after' => $date_from,
					'before' => $date_to,
					'inclusive' => true
				]
			]
		]);

		return $query->posts;
	}


	/**
	 * @param $args
	 * @return bool|\WP_Error
	 */
	function process( $args ) {

		$args = $this->sanitize_args( $args );

		$workflow = AW()->get_workflow( $args['workflow'] );
		$subscriptions = $this->get_subscriptions( $args['date_from'], $args['date_to'] );

		Tools::new_background_process( $this->get_id(), [
			'workflow' => $workflow->get_id(),
			'subscription_ids' => $subscriptions
		]);

		return true;
	}


	/**
	 * Do validation in the init_process() method not here
	 *
	 * @param $args
	 */
	function display_confirmation_screen( $args ) {

		$args = $this->sanitize_args( $args );

		$workflow = AW()->get_workflow( $args['workflow'] );
		$subscriptions = $this->get_subscriptions( $args['date_from'], $args['date_to'] );

		$number_to_preview = 25;

		echo '<p>' . sprintf(
				__('Are you sure you want to manually trigger the <strong>%s</strong> workflow for '
					.'<strong>%s</strong> subscriptions? This can not be undone.', 'automatewoo'),
				$workflow->title, count($subscriptions) ) . '</p>';

		echo '<p>' . __( '<strong>Please note:</strong> This list only indicates the subscriptions that match your selected date period. '
				. "These subscriptions have yet to be validated against the selected workflow.", 'automatewoo' ) . '</p>';

		echo '<p>';

		foreach ( $subscriptions as $i => $subscription_id ) {

			if ( $i == $number_to_preview )
				break;

			$subscription = wcs_get_subscription( $subscription_id );
			$subscription_id = Compat\Subscription::get_id( $subscription );

			echo '#<a href="'. get_edit_post_link( $subscription_id ).'">' . $subscription_id . '</a> for ' . $subscription->get_formatted_billing_full_name();
			echo '<br>';
		}

		if ( count( $subscriptions ) > $number_to_preview ) {
			echo '+ ' . ( count( $subscriptions ) - $number_to_preview ) . ' more subscriptions...';
		}

		echo '</p>';


	}


	/**
	 * @param array $args
	 * @param $batch_size
	 * @return bool|array
	 */
	function background_process_batch( $args, $batch_size ) {

		$args = $this->sanitize_args( $args );

		$workflow = AW()->get_workflow( $args['workflow'] );

		if ( ! $workflow || ! $workflow->is_active() )
			return false;

		$subscription_ids = $args[ 'subscription_ids' ];

		$subscriptions_in_batch = array_slice( $subscription_ids, 0, $batch_size );
		$remaining_subscriptions = array_slice( $subscription_ids, $batch_size );

		foreach ( $subscriptions_in_batch as $subscription_id ) {
			$subscription = wcs_get_subscription( $subscription_id );

			$workflow->maybe_run([
				'subscription' => $subscription,
				'user' => $subscription->get_user()
			]);
		}

		if ( ! empty( $remaining_subscriptions ) ) {
			$args['subscription_ids'] = $remaining_subscriptions;
			return $args;
		}
	}


	/**
	 * @param array $args
	 * @return array
	 */
	function sanitize_args( $args ) {

		if ( isset( $args['workflow'] ) ) {
			$args['workflow'] = absint( $args[ 'workflow' ] );
		}

		if ( isset( $args['date_from'] ) ) {
			$args['date_from'] = Clean::string( $args['date_from'] );
		}

		if ( isset( $args['date_to'] ) ) {
			$args['date_to'] = Clean::string( $args['date_to'] );
		}

		if ( isset( $args['subscription_ids'] ) ) {
			$args['subscription_ids'] = Clean::ids( $args['subscription_ids'] );
		}

		return $args;
	}

}

return new Tool_Manual_Subscriptions_Trigger();