<?php

class USIN_Ajax{

	protected $user_capability;
	protected $nonce_key;

	protected function get_nonce(){
		return $_REQUEST['nonce'];
	}
	
	/**
	 * Checks whether the request is valid. Verifies the noce and if capability
	 * is set, checks whether the current user has this capability
	 *
	 * @param boolean $check_nonce whether to check the nonce or not
	 * @param string $capability (optional) when set, it will check whether the current user
	 * has this capability
	 * @return boolean
	 */
	private function is_request_valid($check_nonce = false, $capability = null){
		if($capability === null){
			$capability = $this->user_capability;
		}
		
		if(!current_user_can($capability)){
			return new WP_Error('usin_not_allowed',  __('You are not allowed to perform this action', 'usin'));
		}
		if($check_nonce && !wp_verify_nonce( $this->get_nonce(), $this->nonce_key )){
			return new WP_Error('usin_not_allowed',  __('Nonce did not verify', 'usin'));
		}
		return true;
	}
	
	/**
	 * Converts a JSON representation of an array to an actual array
	 *
	 * @param string $key they key under which the array is stored in the request
	 * @return array
	 */
	protected function get_request_array($key){
		$arr = null;
		if(isset($_REQUEST[$key])){
			$conv_arr = json_decode(stripcslashes($_REQUEST[$key]));
			if(!empty($conv_arr)){
				$arr = $conv_arr;
			}
		}
		return $arr;
	}
	
	/**
	 * Converts array values that contain numbers as strings to integers
	 *
	 * @param array $arr the array of values
	 * @return array
	 */
	protected function array_values_to_integer($arr){
		if(empty($arr)){
			return array();
		}
		
		$new_arr = array();
		foreach ($arr as $key => $value) {
			$new_arr[$key] = intval($value);
		}
		
		return $new_arr;
	}
	
	
	/**
	 * Checks whether the required $_POST params exist. If they don't it
	 * responds with an error and stops the execution
	 *
	 * @param array $required_params the required param keys
	 * @return void
	 */
	protected function validate_required_post_params($required_params){
		foreach ($required_params as $param ) {
			if(empty($_POST[$param])){
				$this->respond_error( __('Missing required param: ', 'usin').$param);
			}
		}
		
	}

	/**
	 * Verifies the current request. If the request is not valid, it responds
	 * with an error and stops the execution
	 *
	 * @param string $capability (optional) when set, it will check whether the current user
	 * has this capability
	 * @return boolean true if the request is valid
	 */
	protected function verify_request($capability = null){
		$valid = $this->is_request_valid(true, $capability);
		if(is_wp_error($valid)){
			$this->respond_error( $valid->get_error_message() );
		}
		
		return true;
	}
	
	/**
	 * Responds with an error to the request. Stops the execution.
	 *
	 * @param string $message (optional) The error message to respond with
	 * @return void
	 */
	protected function respond_error($message = 'Failed to execute your request'){
		status_header(400);
		wp_send_json(array('error' => $message));
	}
	
	/**
	 * Responds with an error to the success. Stops the execution.
	 *
	 * @param mixed $data (optional) Data to return in the response. If not set,
	 * a success:true response will be returned
	 * @return void
	 */
	protected function respond_success($data = array()){
		$res = (empty($data) && !is_array($data)) || $data === true ? array('success' => true) : $data;
		wp_send_json($res);
	}
	
	/**
	 * Responds to the request - it could be a success or error response
	 *
	 * @param mixed $res this defines whether the response is successful depending on its value:
	 * - WP_Error - responds with an error, setting the message of the error in the response
	 * - false - responds with an error
	 * - everythng else - responds with success, setting the value of $res in the response
	 * @return void
	 */
	protected function respond($res){
		if(is_wp_error($res)){
			$this->respond_error($res->get_error_message());
		}elseif($res === false){
			$this->respond_error();
		}else{
			$this->respond_success($res);
		}
	}

}