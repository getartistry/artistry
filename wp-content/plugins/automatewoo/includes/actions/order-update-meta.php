<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Order_Update_Meta
 */
class Action_Order_Update_Meta extends Action {

	public $required_data_items = [ 'order' ];


	function load_admin_details() {
		$this->title = __( 'Add / Update Order Meta', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	function load_fields() {
		$meta_key = ( new Fields\Text() )
			->set_name('meta_key')
			->set_title(__('Meta key', 'automatewoo'))
			->set_variable_validation()
			->set_required();

		$meta_value = ( new Fields\Text() )
			->set_name( 'meta_value' )
			->set_title( __('Meta value', 'automatewoo') )
			->set_variable_validation();

		$this->add_field($meta_key);
		$this->add_field($meta_value);
	}


	function run() {

		if ( ! $order = $this->workflow->data_layer()->get_order() ) {
			return;
		}

		$meta_key = Clean::string( $this->get_option( 'meta_key', true ) );
		$meta_value = Clean::string( $this->get_option( 'meta_value', true ) );

		// Make sure there is a meta key but a value is not required
		if ( $meta_key ) {
			Compat\Order::update_meta( $order, $meta_key, $meta_value );
		}

	}

}
