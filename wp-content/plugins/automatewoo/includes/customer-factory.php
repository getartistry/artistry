<?php

namespace AutomateWoo;

/**
 * @class Customer_Factory
 * @since 3.0.0
 */
class Customer_Factory extends Factory {

	static $model = 'AutomateWoo\Customer';


	/**
	 * @param int $customer_id
	 * @return Customer|bool
	 */
	static function get( $customer_id ) {
		return parent::get( $customer_id );
	}


	/**
	 * @param int $user_id
	 * @param bool $create - create the customer record if it doesn't exist
	 * @return Customer|bool
	 */
	static function get_by_user_id( $user_id, $create = true ) {

		if ( ! $user_id ) return false;

		$user_id = Clean::id( $user_id );

		if ( Cache::exists( $user_id, 'customer_user_id' ) ) {
			return static::get( Cache::get( $user_id, 'customer_user_id' ) );
		}

		$customer = new Customer();
		$customer->get_by( 'user_id', $user_id );

		if ( $customer->exists ) {
			return $customer;
		}

		if ( $create ) {
			// attempt to create the customer
			$customer = static::create_from_user( $user_id );

			if ( $customer ) {
				return $customer;
			}
		}

		return false;
	}


	/**
	 * @param int $guest_id
	 * @param bool $create - create the customer record if it doesn't exist
	 * @return Customer|bool
	 */
	static function get_by_guest_id( $guest_id, $create = true ) {

		if ( ! $guest_id ) return false;

		$guest_id = Clean::id( $guest_id );

		if ( Cache::exists( $guest_id, 'customer_guest_id' ) ) {
			return static::get( Cache::get( $guest_id, 'customer_guest_id' ) );
		}

		$customer = new Customer();
		$customer->get_by( 'guest_id', $guest_id );

		if ( $customer->exists ) {
			return $customer;
		}

		if ( $create ) {
			// attempt to create the customer
			$customer = static::create_from_guest( $guest_id );

			if ( $customer ) {
				return $customer;
			}
		}

		return false;
	}


	/**
	 * @param string $email
	 * @param bool $create - create the customer record if it doesn't exist
	 * @return Customer|bool
	 */
	static function get_by_email( $email, $create = true ) {

		if ( ! is_email( $email ) ) return false;

		$email = Clean::email( $email );

		// check for matching user
		if ( $user = get_user_by( 'email', $email ) ) {
			return static::get_by_user_id( $user->ID, $create );
		}

		// check for matching guest
		if ( $guest = Guest_Factory::get_by_email( $email ) ) {
			return static::get_by_guest_id( $guest->get_id(), $create );
		}

		if ( $create ) {
			// create guest for new customer
			$guest = Guest_Factory::create( $email );
			return static::get_by_guest_id( $guest->get_id() );
		}

		return false;
	}


	/**
	 * @param int $key
	 * @return Customer|bool
	 */
	static function get_by_key( $key ) {

		if ( ! $key ) return false;

		$key = Clean::string( $key );

		if ( Cache::exists( $key, 'customer_key' ) ) {
			return static::get( Cache::get( $key, 'customer_key' ) );
		}

		$customer = new Customer();
		$customer->get_by( 'id_key', $key );

		if ( $customer->exists ) {
			return $customer;
		}

		return false;
	}


	/**
	 * @param \WP_User|Order_Guest $user
	 * @param bool $create
	 * @return Customer|bool
	 */
	static function get_by_user_data_item( $user, $create = true ) {
		if ( is_a( $user, 'WP_User' ) ) {
			return static::get_by_user_id( $user->ID, $create );
		}
		elseif ( is_a( $user, 'AutomateWoo\Order_Guest' ) ) {
			return static::get_by_email( $user->user_email, $create );
		}
		return false;
	}


	/**
	 * @param \WC_Order $order
	 * @param bool $create
	 * @return Customer|bool
	 */
	static function get_by_order( $order, $create = true ) {
		if ( ! $order ) {
			return false;
		}

		if ( $order->get_user_id() ) {
			return static::get_by_user_id( $order->get_user_id(), $create );
		}
		else {
			return static::get_by_email( Compat\Order::get_billing_email( $order ), $create );
		}
	}


	/**
	 * @param Review $review
	 * @param bool $create
	 * @return Customer|bool
	 */
	static function get_by_review( $review, $create = true ) {
		if ( ! $review ) {
			return false;
		}

		if ( $review->get_user_id() ) {
			return static::get_by_user_id( $review->get_user_id(), $create );
		}
		else {
			return static::get_by_email( $review->get_email(), $create );
		}
	}


	/**
	 * @param int $user_id
	 * @return Customer|bool
	 */
	static function create_from_user( $user_id ) {

		$user = get_userdata( $user_id );

		if ( ! $user ) {
			return false;
		}

		$customer = new Customer();
		$customer->set_user_id( $user_id );
		$customer->set_key( static::generate_unique_customer_key() );
		$customer->save();

		update_user_meta( $user_id, '_automatewoo_customer_id', $customer->get_id() );

		return $customer;
	}


	/**
	 * @param int $guest_id
	 * @return Customer|bool
	 */
	static function create_from_guest( $guest_id ) {

		$guest = Guest_Factory::get( $guest_id );

		if ( ! $guest ) {
			return false;
		}

		$customer = new Customer();
		$customer->set_guest_id( $guest_id );
		$customer->set_key( static::generate_unique_customer_key() );
		$customer->save();

		return $customer;
	}


	/**
	 * @return string
	 */
	static function generate_unique_customer_key() {

		$key = aw_generate_key(20, false );

		$query = new Customer_Query();
		$query->where('id_key', $key );

		if ( $query->has_results() ) {
			return static::generate_unique_customer_key();
		}

		return $key;
	}


	/**
	 * @param Customer $customer
	 */
	static function update_cache( $customer ) {
		parent::update_cache( $customer );

		if ( $customer->get_user_id() ) {
			Cache::set( $customer->get_user_id(), $customer->get_id(), 'customer_user_id' );
		}

		if ( $customer->get_guest_id() ) {
			Cache::set( $customer->get_guest_id(), $customer->get_id(), 'customer_guest_id' );
		}

		Cache::set( $customer->get_key(), $customer->get_id(), 'customer_key' );
	}


	/**
	 * @param Customer $customer
	 */
	static function clean_cache( $customer ) {
		parent::clean_cache( $customer );

		static::clear_cached_prop( $customer, 'user_id', 'customer_user_id' );
		static::clear_cached_prop( $customer, 'guest_id', 'customer_guest_id' );
		static::clear_cached_prop( $customer, 'id_key', 'customer_key' );
	}

}
