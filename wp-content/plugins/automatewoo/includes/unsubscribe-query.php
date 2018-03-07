<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @deprecated must keep this for db migrations
 *
 * @class Unsubscribe_Query
 */
class Unsubscribe_Query extends Query_Custom_Table {

	/** @var string */
	public $table_id = 'unsubscribes';

	protected $model = 'AutomateWoo\Unsubscribe';


	/**
	 * @return Unsubscribe[]
	 */
	function get_results() {
		return parent::get_results();
	}

}
