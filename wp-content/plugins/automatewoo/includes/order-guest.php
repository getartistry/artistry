<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Imitates WP_User object but ID is always 0
 * This object should be used as a data-type 'user' and can be queued with an order
 *
 * @class Order_Guest
 * @since 2.1.0
 * @deprecated since 3.0, use Customer instead
 */
class Order_Guest {

	/** @var int */
	public $ID = 0;

	/** @var string */
	public $user_email;

	/** @var string */
	public $first_name;

	/** @var string */
	public $last_name;

	/** @var string */
	public $billing_phone;

	/** @var string */
	public $billing_country;

	/** @var string */
	public $billing_postcode;

	/** @var string */
	public $billing_state;

	/** @var string */
	public $billing_city;

	/** @var string */
	public $shipping_country;

	/** @var string */
	public $shipping_state;

	/** @var string */
	public $shipping_city;

	/** @var string */
	public $shipping_postcode;

	/** @var \WC_Order */
	public $order;

	/** @var array  */
	public $roles = [ 'guest' ];


	/**
	 * @param $order \WC_Order|bool
	 */
	function __construct( $order = false ) {
		if ( $order ) {

			$this->order = $order;

			$this->user_email = Compat\Order::get_billing_email( $order );
			$this->first_name = Compat\Order::get_billing_first_name( $order );
			$this->last_name = Compat\Order::get_billing_last_name( $order );
			$this->billing_phone = Compat\Order::get_billing_phone( $order );

			$this->billing_country = Compat\Order::get_billing_country( $order );
			$this->billing_city = Compat\Order::get_billing_city( $order );
			$this->billing_state = Compat\Order::get_billing_state( $order );
			$this->billing_postcode = Compat\Order::get_billing_postcode( $order );

			$this->shipping_country = Compat\Order::get_shipping_country( $order );
			$this->shipping_city = Compat\Order::get_shipping_city( $order );
			$this->shipping_state = Compat\Order::get_shipping_state( $order );
			$this->shipping_postcode = Compat\Order::get_shipping_postcode( $order );
		}
	}
}
