<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Abstract_Subscriptions
 * @since 2.1
 */
abstract class Trigger_Abstract_Subscriptions extends Trigger {

	/** @var bool - trigger can run per subscription or per line item */
	public $is_run_for_each_line_item = false;


	function __construct() {

		if ( $this->is_run_for_each_line_item ) {
			$this->supplied_data_items = [ 'customer', 'subscription', 'product' ];
		}
		else {
			$this->supplied_data_items = [ 'customer', 'subscription' ];
		}

		parent::__construct();
	}


	function load_admin_details() {
		$this->group = __( 'Subscriptions', 'automatewoo' );
	}


	/**
	 * @param $subscription
	 * @return \WC_Subscription|false
	 */
	function get_subscription( $subscription ) {

		if ( is_object( $subscription ) && is_a( $subscription, 'WC_Subscription' ) ) {
			return $subscription;
		}
		elseif ( is_numeric( $subscription ) ) {
			return wcs_get_subscription( $subscription );
		}
		return false;
	}


	/**
	 * @param int|\WC_Subscription $subscription
	 */
	function trigger_for_subscription( $subscription ) {

		if ( ! $subscription = $this->get_subscription( $subscription ) ) {
			return;
		}

		$this->maybe_run([
			'subscription' => $subscription,
			'customer' => Customer_Factory::get_by_user_id( $subscription->get_user_id() )
		]);
	}


	/**
	 * @param int|\WC_Subscription $subscription
	 */
	function trigger_for_each_subscription_line_item( $subscription ) {

		if ( ! $subscription = $this->get_subscription( $subscription ) ) {
			return;
		}

		$customer = Customer_Factory::get_by_user_id( $subscription->get_user_id() );

		foreach ( $subscription->get_items() as $order_item_id => $order_item ) {
			$this->maybe_run([
				'subscription' => $subscription,
				'customer' => $customer,
				'product' => Compat\Subscription::get_product_from_item( $subscription, $order_item )
			]);
		}
	}


	function add_field_subscription_products() {
		$product = new Fields\Subscription_Products();
		$product->set_description( __( 'Select which subscription products to trigger this workflow for. Leave blank to apply this trigger to all subscription products.', 'automatewoo'  ) );
		$this->add_field($product);
	}


	function add_field_active_only() {
		$field = new Fields\Checkbox();
		$field->set_name('active_only');
		$field->set_title( __( 'Active subscriptions only', 'automatewoo'  ) );
		$field->set_description( __( 'This can be used for workflows that are not run immediately as it will check that the related subscription is still active just before running.', 'automatewoo'  ) );
		$field->default_to_checked = true;
		$this->add_field( $field );
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	protected function validate_subscription_products_field( $workflow ) {

		$subscription = $workflow->data_layer()->get_subscription();
		$subscription_products = Clean::recursive( $workflow->get_trigger_option( 'subscription_products' ) );

		// blank field == all
		if ( empty( $subscription_products ) )
			return true;

		$included_product_ids = [];

		foreach ( $subscription->get_items() as $item ) {
			$included_product_ids[] = Compat\Order_Item::get_product_id( $item );
			$included_product_ids[] = Compat\Order_Item::get_variation_id( $item );
		}

		if ( array_intersect( $included_product_ids, $subscription_products ) == false )
			return false;

		return true;
	}


	/**
	 * @param Workflow $workflow
	 * @return bool
	 */
	protected function validate_subscription_active_only_field( $workflow ) {
		$subscription = $workflow->data_layer()->get_subscription();
		if ( $workflow->get_trigger_option('active_only') ) {
			if ( ! $subscription->has_status('active') ) {
				return false;
			}
		}
		return true;
	}

}
