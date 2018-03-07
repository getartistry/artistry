<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Queue_Query
 * @since 2.1.0
 */
class Queue_Query extends Query_Custom_Table {

	/** @var string */
	public $table_id = 'queue';

	/** @var string  */
	protected $model = 'AutomateWoo\Queued_Event';

	/** @var string  */
	public $meta_table_id = 'queue-meta';


	/**
	 * @return Queued_Event[]
	 */
	function get_results() {
		return parent::get_results();
	}
}