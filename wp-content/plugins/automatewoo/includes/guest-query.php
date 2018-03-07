<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Guest_Query
 * @since 2.0
 */
class Guest_Query extends Query_Custom_Table {

	/** @var string */
	public $table_id = 'guests';

	/** @var string  */
	public $meta_table_id = 'guest-meta';

	/** @var string  */
	protected $model = 'AutomateWoo\Guest';


	/**
	 * @return Guest[]
	 */
	function get_results() {
		return parent::get_results();
	}

}
