<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Cart_Query
 * @since 2.0
 */
class Cart_Query extends Query_Custom_Table {

	/** @var string */
	public $table_id = 'carts';

	protected $model = 'AutomateWoo\Cart';


	/**
	 * @return Cart[]
	 */
	function get_results() {
		return parent::get_results();
	}

}
