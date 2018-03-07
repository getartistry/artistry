<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Reports_Tab_Conversions_List
 */
class Reports_Tab_Conversions_List extends \AW_Admin_Reports_Tab_Abstract {

	function __construct() {
		$this->id = 'conversions-list';
		$this->name = __( 'Conversions List', 'automatewoo' );
	}


	/**
	 * @return object
	 */
	function get_report_class() {
		include_once AW()->admin_path( '/reports/conversions-list.php' );
		return new Report_Conversions_List();
	}


	/**
	 * @param $action
	 */
	function handle_actions( $action ) {

		switch ( $action ) {

			case 'bulk_unmark_conversion':

				$this->controller->verify_nonce_action();

				$ids = Clean::ids( aw_request( 'order_ids' ) );

				if ( empty( $ids ) ) {
					$this->controller->add_error( __( 'Please select some queued events to bulk edit.', 'automatewoo') );
					return;
				}

				foreach ( $ids as $order_id ) {
					if ( $order = wc_get_order( $order_id ) ) {
						Compat\Order::delete_meta( $order, '_aw_conversion' );
						Compat\Order::delete_meta( $order, '_aw_conversion_log' );
					}
				}

				$this->controller->add_message( __( 'Bulk edit completed.', 'automatewoo') );

				break;
		}
	}

}

return new Reports_Tab_Conversions_List();
