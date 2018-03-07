<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Guest
 *
 * @property string $id
 * @property string $tracking_key
 * @property string $created
 * @property string $last_active
 */
class Guest extends Model {

	/** @var string */
	public $table_id = 'guests';

	/** @var string  */
	public $object_type = 'guest';

	/** @var string  */
	public $meta_table_id = 'guest-meta';

	/** @var \WC_Order */
	private $most_recent_order;

	/** @var string */
	private $formatted_billing_address;


	/**
	 * @param $id
	 */
	function __construct( $id = false ) {
		if ( $id ) $this->get_by( 'id', $id );
	}


	/**
	 * @param string $email
	 */
	function set_email( $email ) {
		$this->set_prop( 'email', Clean::email( $email ) );
	}


	/**
	 * @return string
	 */
	function get_email() {
		return Clean::email( $this->get_prop( 'email' ) );
	}


	/**
	 * @param string $key
	 */
	function set_key( $key ) {
		$this->set_prop( 'tracking_key', Clean::string( $key ) );
	}


	/**
	 * @return string
	 */
	function get_key() {
		return Clean::string( $this->get_prop( 'tracking_key' ) );
	}


	/**
	 * @param \DateTime $date
	 */
	function set_date_created( $date ) {
		$this->set_date_column( 'created', $date );
	}


	/**
	 * @return bool|\DateTime
	 */
	function get_date_created() {
		return $this->get_date_column( 'created' );
	}


	/**
	 * @param \DateTime $date
	 */
	function set_date_last_active( $date ) {
		$this->set_date_column( 'last_active', $date );
	}


	/**
	 * @return bool|\DateTime
	 */
	function get_date_last_active() {
		return $this->get_date_column( 'last_active' );
	}


	/**
	 * @param string $language
	 */
	function set_language( $language ) {
		$this->set_prop( 'language', Clean::string( $language ) );
	}


	/**
	 * @return string
	 */
	function get_language() {
		return Clean::string( $this->get_prop( 'language' ) );
	}


	/**
	 * @param string $ip
	 */
	function set_ip( $ip ) {
		$this->set_prop( 'ip', Clean::string( $ip ) );
	}


	/**
	 * @return string
	 */
	function get_ip() {
		return Clean::string( $this->get_prop( 'ip' ) );
	}


	/**
	 * @return string
	 */
	function get_full_name() {
		return sprintf( _x( '%1$s %2$s', 'full name', 'automatewoo' ), $this->get_first_name(), $this->get_last_name() );
	}


	/**
	 * @return string
	 */
	function get_first_name() {
		return $this->get_checkout_field( 'billing_first_name' );
	}


	/**
	 * @return string
	 */
	function get_last_name() {
		return $this->get_checkout_field( 'billing_last_name' );
	}


	/**
	 * @return string
	 */
	function get_phone() {
		return $this->get_checkout_field( 'billing_phone' );
	}


	/**
	 * @return string
	 */
	function get_country() {
		return $this->get_checkout_field( 'billing_country' );
	}


	/**
	 * @return string
	 */
	function get_company() {
		return $this->get_checkout_field( 'billing_company' );
	}


	/**
	 * @return string
	 */
	function get_address_1() {
		return $this->get_checkout_field( 'billing_address_1' );
	}


	/**
	 * @return string
	 */
	function get_address_2() {
		return $this->get_checkout_field( 'billing_address_2' );
	}


	/**
	 * @return string
	 */
	function get_city() {
		return $this->get_checkout_field( 'billing_city' );
	}


	/**
	 * @return string
	 */
	function get_state() {
		return $this->get_checkout_field( 'billing_state' );
	}


	/**
	 * @return string
	 */
	function get_postcode() {
		return $this->get_checkout_field( 'billing_postcode' );
	}


	/**
	 * Update guest ip and active date
	 */
	function do_check_in() {
		$this->set_ip( \WC_Geolocation::get_ip_address() );
		$this->set_date_last_active( new \DateTime() );
		$this->save();

		$this->update_meta( 'user_agent', aw_get_user_agent() );
	}


	/**
	 * Retrieve a valid checkout field if one is stored in meta or get from an order belonging to the guest
	 * @param $field
	 * @return string
	 */
	function get_checkout_field( $field ) {

		if ( ! PreSubmit::is_checkout_capture_field( $field ) ) {
			return false;
		}

		$value = $this->get_meta( $field );

		if ( $value !== false ) {
			return $value;
		}

		return $this->get_checkout_field_from_order( $field );
	}


	/**
	 * @param $field
	 * @return mixed
	 */
	function get_checkout_field_from_order( $field ) {

		if ( ! $order = $this->get_most_recent_order() ) {
			return false;
		}

		$value = '';

		switch ( $field ) {
			case 'billing_first_name':
				$value = Compat\Order::get_billing_first_name( $order );
				break;
			case 'billing_last_name':
				$value = Compat\Order::get_billing_last_name( $order );
				break;
			case 'billing_company':
				$value = Compat\Order::get_billing_company( $order );
				break;
			case 'billing_country':
				$value = Compat\Order::get_billing_country( $order );
				break;
			case 'billing_phone':
				$value = Compat\Order::get_billing_phone( $order );
				break;
			case 'billing_address_1':
				$value = Compat\Order::get_billing_address_1( $order );
				break;
			case 'billing_address_2':
				$value = Compat\Order::get_billing_address_2( $order );
				break;
			case 'billing_city':
				$value = Compat\Order::get_billing_city( $order );
				break;
			case 'billing_state':
				$value = Compat\Order::get_billing_state( $order );
				break;
			case 'billing_postcode':
				$value = Compat\Order::get_billing_postcode( $order );
				break;
		}

		// set the field in guest meta even if the field is an empty string
		// so we can tell if a field is blank on the order or just missing from guest meta
		$this->update_meta( $field, $value );

		return $value;
	}


	/**
	 * @return \WC_Order
	 */
	function get_most_recent_order() {
		if ( ! isset( $this->most_recent_order ) ) {

			$orders = wc_get_orders([
				'type' => 'shop_order',
				'customer' => $this->get_email(),
				'limit' => 1
			]);

			$this->most_recent_order = $orders ? current( $orders ) : false;
		}

		return $this->most_recent_order;
	}


	/**
	 * @return Cart|false
	 */
	function get_cart() {
		return Cart_Factory::get_by_guest_id( $this->get_id() );
	}


	/**
	 * A guest is locked when they have placed an order or when a workflow runs for them
	 * After this their email address can't be changed
	 * @return bool
	 */
	function is_locked() {

		if ( $this->get_meta( 'is_locked' ) ) {
			return true; // lock is essentially cached once it becomes true
		}

		if ( $this->should_be_locked() ) {
			$this->update_meta( 'is_locked', true );
			return true;
		}

		return false;
	}


	/**
	 * @return bool
	 */
	function should_be_locked() {

		// has placed one order
		if ( $this->get_most_recent_order() ) {
			return true;
		}

		$customer = Customer_Factory::get_by_guest_id( $this->get_id(), false );

		// if customer exists and has at least one workflow
		if ( $customer ) {
			$query = new Log_Query();
			$query->where_meta('_data_layer_customer', $customer->get_id() );
			if ( $query->has_results() ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * @param $is_locked
	 */
	function set_is_locked( $is_locked ) {
		$this->update_meta( 'is_locked', (bool) $is_locked );
	}


	/**
	 * @return string
	 */
	public function get_formatted_billing_address() {
		if ( ! $this->formatted_billing_address ) {

			$address = [
				'first_name' => $this->get_first_name(),
				'last_name' => $this->get_last_name(),
				'company' => $this->get_company(),
				'address_1' => $this->get_address_1(),
				'address_2' => $this->get_address_2(),
				'city' => $this->get_city(),
				'state' => $this->get_state(),
				'postcode' => $this->get_postcode(),
				'country' => $this->get_country()
			];

			$this->formatted_billing_address = WC()->countries->get_formatted_address( $address );
		}

		return $this->formatted_billing_address;
	}


	function delete_cart() {
		if ( $this->exists && $cart = $this->get_cart() ) {
			$cart->delete();
		}
	}


	function delete() {
		$this->delete_cart();
		parent::delete();
	}

}
