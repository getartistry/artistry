<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Order_Add_Note
 * @since 3.5
 */
class Action_Order_Add_Note extends Action {

	public $required_data_items = [ 'order' ];


	function load_admin_details() {
		$this->title = __( 'Add Order Note', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	function load_fields() {
		$type = new Fields\Order_Note_Type();
		$type->set_required();

		$note = new Fields\Text_Area();
		$note->set_name('note' );
		$note->set_title( __( 'Note', 'automatewoo' ) );
		$note->set_variable_validation();
		$note->set_required();

		$this->add_field( $type );
		$this->add_field( $note );
	}


	function run() {

		$note_type = Clean::string( $this->get_option( 'note_type' ) );
		$note = Clean::textarea( $this->get_option( 'note', true ) );
		$order = $this->workflow->data_layer()->get_order();

		if ( ! $note || ! $note_type || ! $order ) {
			return;
		}

		$order->add_order_note( $note, $note_type === 'customer', false );
	}
}