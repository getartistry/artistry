<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Only allows a single category choice as the text variable system only supports single data items

 * @class Trigger_User_Purchases_From_Category
 */
class Trigger_User_Purchases_From_Category extends Trigger_Abstract_Order_Status_Base {

	public $is_run_for_each_line_item = true;


	function init() {
		$this->supplied_data_items[] = 'category';
	}


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Order Includes Product From Category [DEPRECATED]', 'automatewoo' );
		$this->group = __( 'DEPRECATED', 'automatewoo' );
		$this->description = $this->get_deprecation_warning() . ' ' . __( 'Instead please use one of the order status triggers in combination with the Order Item Categories rule.', 'automatewoo' );
	}


	function load_fields() {

		$category = new Fields\Category();
		$category->set_description( __( 'Only trigger when the a product is purchased from a certain category.', 'automatewoo'  ) );
		$category->set_required();

		$order_status = new Fields\Order_Status( false );
		$order_status->set_required();
		$order_status->set_default( 'wc-completed' );

		$this->add_field( $category );
		$this->add_field( $order_status );
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$order = $workflow->data_layer()->get_order();
		$product = $workflow->data_layer()->get_product();

		if ( ! $order || ! $product ) {
			return false;
		}

		$status_option = Clean::string( $workflow->get_trigger_option('order_status') );
		$new_status = Temporary_Data::get( 'order_new_status', Compat\Order::get_id( $order ) );

		if ( ! $this->validate_status_field( $status_option, $new_status ) ) {
			return false;
		}

		if ( ! $expected_category_id = absint( $workflow->get_trigger_option('category') ) )
			return false;

		$product_id = Compat\Product::is_variation( $product ) ? Compat\Product::get_parent_id( $product ) : Compat\Product::get_id( $product );
		$categories = wp_get_object_terms( $product_id, 'product_cat', [ 'fields' => 'ids' ] );

		if ( ! $categories ) {
			return false;
		}

		foreach ( $categories as $category_id ) {
			if ( $category_id == $expected_category_id ) {
				$workflow->set_data_item( 'category', get_category( $category_id ) );
				return true;
			}
		}

		return false;
	}

}
