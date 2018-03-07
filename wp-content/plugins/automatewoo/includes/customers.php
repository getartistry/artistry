<?php

namespace AutomateWoo;

/**
 * Customer management class
 *
 * @class Customers
 * @since 3.0.0
 */
class Customers {


	static function init() {
		add_action( 'automatewoo/object/delete', [ __CLASS__, 'delete_customer_on_guest_delete' ] );
		add_action( 'delete_user', [ __CLASS__, 'delete_customer_on_user_delete' ] );
		add_action( 'automatewoo/session_tracker/convert_guest', [ __CLASS__, 'update_customer_when_guest_registers' ], 10, 2 );
		add_action( 'automatewoo_updated_async', [ __CLASS__, 'setup_registered_customers' ] );
		add_action( 'automatewoo_after_setup_registered_customers', [ __CLASS__, 'maybe_setup_guest_customers' ] );
		add_action( 'automatewoo_four_hourly_worker', [ __CLASS__, 'maybe_setup_guest_customers' ] ); // fallback
	}


	/**
	 * @param Model|Guest $object
	 */
	static function delete_customer_on_guest_delete( $object ) {
		if ( $object->object_type !== 'guest' ) {
			return;
		}

		if ( $customer = Customer_Factory::get_by_guest_id( $object->get_id(), false ) ) {
			$customer->delete();
		}
	}


	/**
	 * @param int $user_id
	 */
	static function delete_customer_on_user_delete( $user_id ) {
		if ( ! $user_id ) {
			return;
		}

		if ( $customer = Customer_Factory::get_by_user_id( $user_id, false ) ) {
			$customer->delete();
		}
	}


	/**
	 * Convert guest customer to registered user customer
	 * Importantly, this fires before the guest deletion check
	 *
	 * @param Guest $guest
	 * @param \WP_User $user
	 */
	static function update_customer_when_guest_registers( $guest, $user ) {

		$guest_customer = Customer_Factory::get_by_guest_id( $guest->get_id(), false );

		if ( ! $guest_customer ) {
			return; // nothing to convert
		}

		$user_customer = Customer_Factory::get_by_user_id( $user->ID, false );

		if ( $user_customer ) {
			return; // user already exists, guest will just be deleted
		}

		// update the guest customer record
		$guest_customer->set_guest_id(0 );
		$guest_customer->set_user_id( $user->ID );
		$guest_customer->save();
	}


	/**
	 * Dispatches background process to create customer records for all registered users
	 */
	static function setup_registered_customers() {

		/** @var Background_Processes\Setup_Registered_Customers $process */
		$process = Background_Processes::get('setup_registered_customers');

		if ( $process->has_queued_items() ) {
			$process->maybe_schedule_health_check();
			return; // already running
		}

		$users = get_users([
			'fields' => 'ids',
			'meta_query' => [
				[
					'key' => '_automatewoo_customer_id',
					'compare' => 'NOT EXISTS'
				]
			]
		]);

		if ( $users ) {
			$process->data( $users )->start();
		}
		else {
			do_action( 'automatewoo_after_setup_registered_customers' );
		}
	}


	/**
	 * Dispatches background process to setup guest customers
	 * Goes through every guest order and creates a customer for it
	 */
	static function maybe_setup_guest_customers() {

		if ( get_option( '_automatewoo_setup_guest_customers_complete' ) ) {
			return;
		}

		/** @var Background_Processes\Setup_Guest_Customers $process */
		$process = Background_Processes::get('setup_guest_customers');

		if ( $process->has_queued_items() ) {
			$process->maybe_schedule_health_check();
			return; // already running
		}

		// guest orders
		$orders = wc_get_orders([
			'type' => 'shop_order',
			'limit' => -1,
			'status' => [ 'completed', 'processing' ],
			'customer_id' => 0,
			'return' => 'ids'
		]);


		if ( $orders ) {
			$process->data( $orders )->start();
		}
		else {
			update_option( '_automatewoo_setup_guest_customers_complete', true, false );
		}

	}

}
