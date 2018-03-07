<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Customer_Update_Meta
 */
class Action_Customer_Update_Meta extends Action {

	public $required_data_items = [ 'customer' ];


	function load_admin_details() {
		$this->title = __( 'Add / Update Customer Meta', 'automatewoo' );
		$this->group = __( 'Customers', 'automatewoo' );
		$this->description = __( 'If the customer has an account, the user meta table is used. If the customer is a guest, the guest meta table is used.', 'automatewoo' );
	}


	function load_fields() {

		$meta_key = ( new Fields\Text() )
			->set_name( 'meta_key' )
			->set_title( __( 'Meta key', 'automatewoo' ) )
			->set_required()
			->set_variable_validation();

		$meta_value = ( new Fields\Text() )
			->set_name( 'meta_value' )
			->set_title( __( 'Meta value', 'automatewoo' ) )
			->set_variable_validation();

		$this->add_field( $meta_key );
		$this->add_field( $meta_value );
	}


	function run() {

		if ( ! $customer = $this->workflow->data_layer()->get_customer() ) {
			return;
		}

		$meta_key = Clean::string( $this->get_option('meta_key', true ) );
		$meta_value = Clean::string( $this->get_option('meta_value', true ) );

		// Make sure there is a meta key but a value can be blank
		if ( $meta_key ) {
			$customer->update_meta( $meta_key, $meta_value );
		}
	}

}
