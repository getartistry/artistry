<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Order_Update_Customer_Shipping_Note
 */
class Action_Order_Update_Customer_Shipping_Note extends Action {

	public $required_data_items = [ 'order' ];


	function load_admin_details() {
		$this->title = __( 'Add / Update Customer Provided Note', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @return mixed
	 */
	function load_fields() {

		$note = ( new Fields\Text_Area() )
			->set_name( 'note' )
			->set_title( __( 'Note', 'automatewoo' ) )
			->set_variable_validation()
			->set_rows( 2 );

		$append_checkbox = ( new Fields\Checkbox() )
			->set_name( 'append' )
			->set_title( __( 'Append to existing note?', 'automatewoo' ) )
			->set_description( __( 'If checked and there is an existing customer shipping note for this order the note on this action will added to the field after a pipe (|). If unchecked any existing note will be replaced.', 'automatewoo' ) )
			->set_default_to_checked();

		$this->add_field( $note );
		$this->add_field( $append_checkbox );
	}


	function run() {

		if ( ! $order = $this->workflow->data_layer()->get_order() )
			return;

		$note = Clean::string( $this->get_option( 'note', true ) );
		$append = absint( $this->get_option( 'append' ) );

		if ( $append ) {
			$existing_note = Compat\Order::get_customer_note( $order );
			$new_note = $existing_note ? $existing_note . ' | ' . $note : $note;
		}
		else {
			$new_note = $note;
		}

		Compat\Order::set_customer_note( $order, $new_note );
	}

}
