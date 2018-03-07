<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Only allows a single product choice as the text variable system only supports single data items
 *
 * @class Trigger_User_Purchases_Specific_Product
 */
class Trigger_User_Purchases_Specific_Product extends Trigger_Abstract_Order_Status_Base {


	function init() {
		$this->supplied_data_items[] = 'product';
		$this->supplied_data_items[] = 'order_item';
	}


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Order Includes a Specific Product [DEPRECATED]', 'automatewoo');
		$this->group = __( 'DEPRECATED', 'automatewoo' );
		$this->description = $this->get_deprecation_warning() . ' ' . __( 'Instead please use one of the order status triggers in combination with the Order Items rule.', 'automatewoo' );
	}


	function load_fields() {

		$product = new Fields\Product();
		$product->allow_variations = true;
		$product->set_description( __( 'Only trigger when a certain product is purchased.', 'automatewoo'  ) );
		$product->set_required();

		$order_status = new Fields\Order_Status( false );
		$order_status->set_required();
		$order_status->set_default('wc-completed');

		$this->add_field( $product );
		$this->add_field( $order_status );

		parent::load_fields();
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$order = $workflow->data_layer()->get_order();

		if ( ! $order ) {
			return false;
		}

		$status_option = Clean::string( $workflow->get_trigger_option('order_status') );
		$new_status = Temporary_Data::get( 'order_new_status', Compat\Order::get_id( $order ) );

		if ( ! $this->validate_status_field( $status_option, $new_status ) ) {
			return false;
		}

		$target_product = wc_get_product( absint( $workflow->get_trigger_option('product') ) );

		if ( ! $target_product )
			return false;

		$target_is_variation = Compat\Product::is_variation( $target_product );
		$target_product_id = Compat\Product::get_id( $target_product );

		foreach ( $order->get_items() as $item_id => $item ) {

			/** @var $item \WC_Order_Item_Product|array */
			if ( $target_is_variation ) {
				$match = Compat\Order_Item::get_variation_id( $item ) == $target_product_id;
			}
			else {
				$match = Compat\Order_Item::get_product_id( $item ) == $target_product_id;
			}

			if ( $match ) {
				// Add data to workflow layer
				$workflow->set_data_item( 'product', $target_product );
				$workflow->set_data_item( 'order_item', AW()->order_helper->prepare_order_item( $item_id, $item ) );

				return true;
			}
		}

		return false;
	}


	/**
	 * Ensures 'to' status has not changed while sitting in queue
	 *
	 * @param Workflow $workflow
	 * @return bool
	 */
	function validate_before_queued_event( $workflow ) {

		$order = $workflow->data_layer()->get_order();

		if ( ! $order )
			return false;

		// Option to validate order status
		if ( $workflow->get_trigger_option('validate_order_status_before_queued_run') ) {
			if ( ! $this->validate_status_field( $workflow->get_trigger_option('order_status'), $order->get_status() ) )
				return false;
		}

		return true;
	}


}
