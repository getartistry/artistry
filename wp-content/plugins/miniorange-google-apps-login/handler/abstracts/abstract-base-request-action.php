<?php

/**
 * Class Base_Request_action
 * This abstract class aims to handle all the request data.
 */
abstract class Base_Request_action{

	/**
	 * Base_Request_action constructor.
	 */
	function __construct() {
		add_action('init',array($this,'handle_request_data'));
	}

	/**
	 * This function will be the first to get request data,
	 * This function will be used to process the request data before actual business logic.
	 * @return mixed
	 */
	abstract function handle_request_data();

	/**
	 * This function will be used to route the request data for business logic.
	 * @return mixed
	 */
	abstract function route_request_data($getData);


	/**
	 * This fuction will be used to do the validation on request data.
	 * @return mixed
	 */
	abstract function validate_request_data($getData);


}