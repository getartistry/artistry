<?php

namespace AutomateWoo\Admin\Controllers;

use AutomateWoo\Admin;
use AutomateWoo\Clean;
use AutomateWoo\Customer;
use AutomateWoo\Customer_Factory;
use AutomateWoo\Report_Unsubscribes;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Unsubscribes
 */
class Unsubscribes extends Base {


	function handle() {

		$action = $this->get_current_action();

		switch ( $action ) {

			case 'bulk_delete':
				$this->action_bulk_edit( str_replace( 'bulk_', '', $action ) );
				$this->output_list_table();
				break;

			default:
				$this->output_list_table();
				break;
		}
	}


	private function output_list_table() {

		include_once AW()->admin_path( '/reports/unsubscribes.php' );

		$table = new Report_Unsubscribes();
		$table->prepare_items();
		$table->nonce_action = $this->get_nonce_action();

		$sidebar_content = '<p>' . sprintf(
			__( 'All emails sent from AutomateWoo workflows automatically include an unsubscribe link in the email footer. This link allows any recipient, both user or guest, to unsubscribe. <%s>Read more&hellip;<%s>', 'automatewoo' ),
			'a href="' . Admin::get_docs_link('unsubscribes', 'unsubscribes-list' ) . '" target="_blank"',
			'/a'
		) . '</p>';

		$this->output_view( 'page-table-with-sidebar', [
			'table' => $table,
			'sidebar_content' => $sidebar_content
		]);
	}


	/**
	 * @param $action
	 */
	private function action_bulk_edit( $action ) {

		$this->verify_nonce_action();

		$ids = Clean::ids( aw_request( 'unsubscribe_ids' ) );

		if ( empty( $ids ) ) {
			$this->add_error( __( 'Please select some customers to resubscribe.', 'automatewoo') );
			return;
		}

		foreach ( $ids as $id ) {
			if ( ! $customer = Customer_Factory::get( $id ) ) {
				continue;
			}

			switch ( $action ) {
				case 'delete':
					$customer->set_is_unsubscribed( false );
					$customer->save();
					break;
			}
		}

		$this->add_message( __( 'Bulk edit completed.', 'automatewoo' ) );
	}
}

return new Unsubscribes();