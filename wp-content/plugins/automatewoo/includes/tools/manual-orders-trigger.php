<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Tool_Manual_Orders_Trigger
 * @since 2.5
 */
class Tool_Manual_Orders_Trigger extends Tool {

	public $id = 'manual_orders_trigger';


	function __construct() {

		$this->title = __( 'Manual Orders Trigger', 'automatewoo' );
		$this->description = __( 'Manually trigger a workflow for any orders that match a date range. For example if you create a workflows using the Order Completed trigger and want to have that workflows apply to orders that are already completed.', 'automatewoo' );

		$this->additional_description = sprintf(
			__( 'If you are processing a large amount of orders they will be processed in the background at the rate of %d every %s minutes.', 'automatewoo' ),
			Tools::get_batch_size(),
			round( Tools::get_batch_delay() / 60 )
		);
	}


	/**
	 * @return array
	 */
	function get_form_fields() {

		$fields = [];

		$fields[] = ( new Fields\Workflow() )
			->set_name_base('args')
			->set_required()
			->add_query_arg( 'post_status', 'publish' )
			->add_query_arg( 'meta_query', [[
				'key' => 'trigger_name',
				'value' => [
					'order_placed',
					'order_status_changes',

					'order_cancelled',
					'order_completed',
					'order_on_hold',
					'order_pending',
					'order_processing',
					'order_refunded',

					'users_order_count_reaches',
					'user_purchases_from_taxonomy_term',
					'users_total_spend',

					'order_payment_received_each_line_item',
					'order_placed_each_line_item',
					'order_status_changes_each_line_item'
				]
				]]
			);

		$fields[] = ( new Fields\Date() )
			->set_name( 'date_from' )
			->set_title(__( 'Order Created Date - Range From', 'automatewoo' ))
			->set_name_base('args')
			->set_required();

		$fields[] = ( new Fields\Date() )
			->set_name( 'date_to' )
			->set_title( __( 'Order Created Date - Range To', 'automatewoo' ) )
			->set_name_base( 'args' )
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
			return new \WP_Error( 2, __( 'The selected workflow is not currently active.', 'automatewoo') );
		}

		$orders = $this->get_orders( $args['date_from'], $args['date_to'], $workflow );

		if ( empty( $orders ) ) {
			return new \WP_Error( 3, __( 'No orders match that date range.', 'automatewoo') );
		}

		return true;
	}


	/**
	 * @param $date_from
	 * @param $date_to
	 * @param $workflow Workflow
	 * @return array
	 */
	function get_orders( $date_from, $date_to, $workflow ) {

		$trigger = $workflow->get_trigger();

		if ( ! $trigger )
			return [];

		$query_args = [
			'post_type' => 'shop_order',
			'fields' => 'ids',
			'posts_per_page' => -1,
			'post_status' => array_keys( wc_get_order_statuses() ),
			'date_query' => [
				[
					'after' => $date_from,
					'before' => $date_to,
					'inclusive' => true
				]
			]
		];

		// if triggers is status based, pre filter the orders by status
		$status_based_triggers = [
			'order_cancelled',
			'order_completed',
			'order_on_hold',
			'order_pending',
			'order_processing',
			'order_refunded'
		];

		if ( in_array( $trigger->get_name(), $status_based_triggers ) ) {
			$query_args[ 'post_status' ] = 'wc-' . $trigger->_target_status;
		}

		$query = new \WP_Query( $query_args );

		return $query->posts;
	}


	/**
	 * @param $args
	 * @return bool|\WP_Error
	 */
	function process( $args ) {

		$args = $this->sanitize_args( $args );

		$workflow = AW()->get_workflow( $args['workflow'] );
		$orders = $this->get_orders( $args['date_from'], $args['date_to'], $workflow );

		Tools::new_background_process( $this->get_id(), [
			'workflow' => $workflow->get_id(),
			'order_ids' => $orders
		]);

		return true;
	}


	/**
	 * Do validation in the validate_process() method not here
	 *
	 * @param $args
	 */
	function display_confirmation_screen( $args ) {

		$args = $this->sanitize_args( $args );

		$workflow = AW()->get_workflow( $args[ 'workflow' ] );
		$orders = $this->get_orders( $args['date_from'], $args['date_to'], $workflow );

		$number_to_preview = 25;

		echo '<p>' . sprintf(
				__('Are you sure you want to manually trigger the <strong>%s</strong> workflow for '
					.'<strong>%s</strong> orders? This can not be undone.', 'automatewoo'),
				$workflow->title, count($orders) ) . '</p>';

		echo '<p>' . __( '<strong>Please note:</strong> This list only indicates the orders that match your selected date period. '
				. "These orders have yet to be validated against the selected workflow.", 'automatewoo' ) . '</p>';

		echo '<p>';

		foreach ( $orders as $i => $order_id ) {

			if ( $i == $number_to_preview )
				break;

			$order = wc_get_order( $order_id );

			echo '#<a href="'. get_edit_post_link( $order_id ).'">' . $order_id . '</a> for ' . $order->get_formatted_billing_full_name();
			echo '<br>';
		}

		if ( count( $orders ) > $number_to_preview ) {
			echo '+ ' . ( count( $orders ) - $number_to_preview ) . ' more orders...';
		}

		echo '</p>';

	}


	/**
	 * @param $args
	 * @param $batch_size
	 * @return bool|array
	 */
	function background_process_batch( $args, $batch_size ) {

		$args = $this->sanitize_args( $args );

		$workflow = AW()->get_workflow( $args[ 'workflow' ] );

		if ( ! $workflow->exists || ! $workflow->is_active() )
			return false;

		$order_ids = $args[ 'order_ids' ];

		$orders_in_batch = array_slice( $order_ids, 0, $batch_size );
		$remaining_orders = array_slice( $order_ids, $batch_size );

		/** @var $trigger Trigger_Abstract_Order_Base */
		if ( ! $trigger = $workflow->get_trigger() ) {
			return false;
		}

		foreach ( $orders_in_batch as $order ) {

			$order = wc_get_order( $order );
			$user = AW()->order_helper->prepare_user_data_item( $order );

			// property sets whether the trigger fires per order or per order line item
			if ( empty( $trigger->is_run_for_each_line_item ) ) {
				$workflow->maybe_run([
					'order' => $order,
					'user' => $user
				]);
			}
			else {
				foreach ( $order->get_items() as $order_item_id => $order_item ) {
					$workflow->maybe_run([
						'order' => $order,
						'order_item' => AW()->order_helper->prepare_order_item( $order_item_id, $order_item ),
						'user' => $user,
						'product' => Compat\Order::get_product_from_item( $order, $order_item )
					]);
				}
			}
		}

		if ( ! empty( $remaining_orders ) ) {
			$args['order_ids'] = $remaining_orders;
			return $args;
		}

		return false;
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

		if ( isset( $args['order_ids'] ) ) {
			$args['order_ids'] = Clean::ids( $args['order_ids'] );
		}

		return $args;
	}

}

return new Tool_Manual_Orders_Trigger();
