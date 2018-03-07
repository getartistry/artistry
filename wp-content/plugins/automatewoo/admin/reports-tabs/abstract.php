<?php
/**
 * @class 		AW_Admin_Reports_Tab_Abstract
 * @package		AutomateWoo/Admin
 */

abstract class AW_Admin_Reports_Tab_Abstract {

	/** @var string */
	public $id;

	/** @var string */
	public $name;

	/** @var AutomateWoo\Admin\Controllers\Reports */
	public $controller;


	/**
	 * @return object
	 */
	abstract function get_report_class();

	/**
	 * @return string
	 */
	function get_id() {
		return $this->id;
	}


	/**
	 * @return string
	 */
	function get_url() {
		return admin_url( 'admin.php?page=automatewoo-reports&tab=' . $this->get_id() );
	}


	/**
	 * @return string|false
	 */
	function output_before_report() {
		return false;
	}


	/**
	 * @param $action
	 */
	function handle_actions( $action ) {}



	/**
	 *
	 */
	function output() {
		if ( ! $class = $this->get_report_class() )
			return false;

		$class->nonce_action = $this->controller->get_nonce_action();

		$class->output_report();
	}

}
