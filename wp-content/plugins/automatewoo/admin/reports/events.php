<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Report_Events
 */
class Report_Events extends Admin_List_Table {

	public $name = 'events';

	protected $default_param_orderby = 'date_scheduled';

	protected $default_param_order = 'ASC';


	function __construct() {
		parent::__construct([
			'singular' => __( 'Event', 'automatewoo' ),
			'plural' => __( 'Events', 'automatewoo' ),
			'ajax' => false
		]);
	}


	/**
	 * @param $event Event
	 * @return string
	 */
	function column_cb( $event ) {
		return '<input type="checkbox" name="event_ids[]" value="' . absint( $event->get_id() ) . '" />';
	}


	/**
	 * @param Event $event
	 * @param mixed $column_name
	 * @return string
	 */
	function column_default( $event, $column_name ) {

		switch( $column_name ) {
			case 'id':
				echo '#' . $event->get_id();
				break;

			case 'hook':
				return $event->get_hook();
				break;

			case 'status':
				return $event->get_status();
				break;

			case 'args':
				return '<code>[ ' . implode( ', ', $event->get_args() ) . ' ]</code>';
				break;

			case 'date_scheduled':
				return $this->format_date( $event->get_date_scheduled() );
				break;

		}
	}


	function get_columns() {
		$columns = [
//			'cb' => '<input type="checkbox" />',
			'id'  => __( 'Event', 'automatewoo' ),
			'status'  => __( 'Status', 'automatewoo' ),
			'hook'  => __( 'Hook', 'automatewoo' ),
			'args'  => __( 'Arguments', 'automatewoo' ),
			'date_scheduled' => __( 'Scheduled date', 'automatewoo' )
		];

		return $columns;
	}


	/**
	 * @return array
	 */
	protected function get_sortable_columns() {
		return [
			'date_scheduled' => [ 'date_scheduled', true ]
		];
	}


	function prepare_items() {

		$this->_column_headers = [ $this->get_columns(), [], $this->get_sortable_columns() ];
		$current_page = absint( $this->get_pagenum() );
		$per_page = 100;

		$this->get_items( $current_page, $per_page );

		$this->set_pagination_args([
			'total_items' => $this->max_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $this->max_items / $per_page )
		]);
	}


	/**
	 * @param $current_page
	 * @param $per_page
	 */
	function get_items( $current_page, $per_page ) {

		$query = new Event_Query();
		$query->set_calc_found_rows( true );
		$query->set_limit( $per_page );
		$query->set_offset( ($current_page - 1 ) * $per_page );
		$query->set_ordering( $this->get_param_orderby(), $this->get_param_order() );

		$this->items = $query->get_results();
		$this->max_items = $query->found_rows;
	}


	/**
	 * Retrieve the bulk actions
	 */
	function get_bulk_actions() {
		$actions = [
//			'bulk_delete' => __( 'Delete', 'automatewoo' ),
		];

		return $actions;
	}


}
