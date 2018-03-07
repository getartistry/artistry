<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Abstract_Order_Status_Base
 */
abstract class Trigger_Abstract_Order_Status_Base extends Trigger_Abstract_Order_Base {

	/** @var string|false */
	public $_target_status = false;
	

	function load_fields() {
		$this->add_field_validate_queued_order_status();
	}


	function register_hooks() {
		add_action( $this->get_hook_order_status_changed(), [ $this, 'status_changed' ], 10, 3 );
		// add special hook for orders that are created as pending and never paid
		add_action( 'automatewoo_order_pending', [ $this, 'order_pending' ] );
	}


	/**
	 * @param $order_id
	 */
	function order_pending( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! $order || ! $order->has_status( 'pending' ) ) {
			return; // ensure order is still pending
		}

		$this->status_changed( $order_id, '', 'pending' );
	}


	/**
	 * @param int $order_id
	 * @param string $old_status
	 * @param string $new_status
	 */
	function status_changed( $order_id, $old_status, $new_status ) {

		if ( ! $new_status ) {
			return; // new status is required
		}

		// target status is used for status specific triggers
		if ( $this->_target_status ) {
			if ( $new_status !== $this->_target_status ) {
				return;
			}
		}

		// use temp data to store the real status changes since the status of order may have already changed if using async
		Temporary_Data::set( 'order_new_status', $order_id, $new_status );

		if ( $old_status ) {
			Temporary_Data::set( 'order_old_status', $order_id, $old_status );
		}

		if ( $this->is_run_for_each_line_item ) {
			$this->trigger_for_each_order_item( $order_id );
		}
		else {
			$this->trigger_for_order( $order_id );
		}
	}


	/**
	 * @param Workflow $workflow
	 * @return bool
	 */
	function validate_before_queued_event( $workflow ) {

		$order = $workflow->data_layer()->get_order();

		if ( ! $order ) {
			return false;
		}

		if ( $this->_target_status ) {
			if ( $workflow->get_trigger_option('validate_order_status_before_queued_run') ) {
				if ( $order->get_status() != $this->_target_status )
					return false;
			}
		}

		return true;
	}

}
