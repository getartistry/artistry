<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/***
 * @class Trigger_Order_Note_Added
 * @since 2.2
 */
class Trigger_Order_Note_Added extends Trigger {

	public $supplied_data_items = [ 'order', 'order_note', 'customer' ];

	/** @var bool - hack required to access this prop when note is added */
	public $_is_customer_note = false;


	function load_admin_details() {
		$this->title = __( 'Order Note Added', 'automatewoo');
		$this->description = __( 'Fires when any note is added to an order, can include both private notes and notes to the customer. These notes appear on the right of the order edit screen.', 'automatewoo' );
		$this->group = __( 'Orders', 'automatewoo' );
	}


	function load_fields() {

		$contains = new Fields\Text();
		$contains->set_name('note_contains');
		$contains->set_title( __( 'Note contains text', 'automatewoo'  ) );
		$contains->set_description( __( 'Only trigger this workflow if the order note contains the certain text. This field is optional.', 'automatewoo'  ) );

		$type = new Fields\Order_Note_Type();
		$type->set_placeholder( __( '[All]', 'automatewoo'  ) );

		$this->add_field( $type );
		$this->add_field( $contains );
	}


	function register_hooks() {
		add_filter( 'woocommerce_new_order_note_data', [ $this, 'catch_order_note_filter' ], 20, 2 );
		add_action( 'wp_insert_comment', [ $this, 'catch_comment_create' ], 20, 2 );
	}


	/**
	 * @param array $data
	 * @param array $args
	 * @return array
	 */
	function catch_order_note_filter( $data, $args ) {
		$this->_is_customer_note = $args[ 'is_customer_note' ];
		return $data;
	}


	/**
	 * @param $comment_id
	 * @param $comment \WP_Comment
	 */
	function catch_comment_create( $comment_id, $comment ) {

		if ( $comment->comment_type !== 'order_note' ) {
			return;
		}

		if ( ! $order = wc_get_order( $comment->comment_post_ID ) ) {
			return;
		}

		$order_note = new Order_Note( $comment->comment_ID, $comment->comment_content, Compat\Order::get_id( $order ) );

		// must manually set prop because meta field is added after the comment is inserted
		$order_note->is_customer_note = $this->_is_customer_note;

		$this->maybe_run([
			'customer' => Customer_Factory::get_by_order( $order ),
			'order' => $order,
			'order_note' => $order_note
		]);
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$order = $workflow->data_layer()->get_order();
		$order_note = $workflow->data_layer()->get_order_note();

		if ( ! $order || ! $order_note ) {
			return false;
		}

		$note_type = Clean::string( $workflow->get_trigger_option( 'note_type' ) );
		$note_contains = Clean::string( $workflow->get_trigger_option( 'note_contains' ) );

		if ( $note_type ) {
			if ( $order_note->get_type() != $note_type ) {
				return false;
			}
		}

		if ( $note_contains ) {
			if ( ! stristr( $order_note->content, $note_contains ) )
				return false;
		}

		return true;
	}

}
