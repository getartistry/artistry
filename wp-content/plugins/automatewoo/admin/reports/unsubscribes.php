<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Report_Unsubscribes
 */
class Report_Unsubscribes extends Admin_List_Table {

	public $name = 'unsubscribes';

	
	function __construct() {
		parent::__construct([
			'singular' => __( 'Unsubscribe', 'automatewoo' ),
			'plural' => __( 'Unsubscribes', 'automatewoo' ),
			'ajax' => false
		]);
	}


	/**
	 * @param $unsubscribe Unsubscribe
	 * @return string
	 */
	function column_cb( $unsubscribe ) {
		return '<input type="checkbox" name="unsubscribe_ids[]" value="' . absint( $unsubscribe->get_id() ) . '" />';
	}


	/**
	 * @param Customer $customer
	 * @param mixed $column_name
	 * @return string
	 */
	function column_default( $customer, $column_name ) {

		switch( $column_name ) {

			case 'email':
				return Format::customer( $customer );
				break;

			case 'time':
				return $this->format_date( $customer->get_date_unsubscribed() );
				break;
		}
	}


	function get_columns() {
		$columns = [
			'cb' => '<input type="checkbox" />',
			'email' => __( 'Customer', 'automatewoo' ),
			'time' => __( 'Date', 'automatewoo' ),
		];

		return $columns;
	}


	/**
	 * Retrieve the bulk actions
	 */
	function get_bulk_actions() {
		$actions = [
			'bulk_delete' => __( 'Delete', 'automatewoo' ),
		];

		return $actions;
	}


	function prepare_items() {

		$this->_column_headers = [ $this->get_columns(), [], $this->get_sortable_columns() ];
		$current_page = absint( $this->get_pagenum() );
		$per_page = $this->get_items_per_page( 'automatewoo_unsubscribes_per_page' );

		$this->get_items( $current_page, $per_page );

		$this->set_pagination_args([
			'total_items' => $this->max_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $this->max_items / $per_page )
		]);
	}


	/**
	 * Get Products matching stock criteria
	 */
	function get_items( $current_page, $per_page ) {
		$query = new Customer_Query();
		$query->where( 'unsubscribed', true );
		$query->set_ordering( 'unsubscribed_date' );
		$query->set_calc_found_rows( true );
		$query->set_limit( $per_page );
		$query->set_offset( ($current_page - 1 ) * $per_page );
		$results = $query->get_results();

		$this->items = $results;
		$this->max_items = $query->found_rows;
	}

}
