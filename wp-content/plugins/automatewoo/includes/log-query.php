<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Log_Query
 */
class Log_Query extends Query_Custom_Table {

	/** @var string */
	public $table_id = 'logs';

	/** @var string  */
	public $meta_table_id = 'log-meta';

	/** @var string */
	public $model = 'AutomateWoo\Log';

	/**
	 * @return Log[]
	 */
	function get_results() {
		return parent::get_results();
	}

}
