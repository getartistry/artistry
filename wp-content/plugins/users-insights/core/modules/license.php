<?php

class USIN_License{

	public $key = null;
	public $status = null;
	public $activated = false;
	public $expires = null;
	public $renewal_url = null;
	public $renewal_message = null;

	protected $save_keys = array('key', 'status', 'activated', 'expires', 'renewal_url', 'renewal_message');

	const STATUS_VALID = 'valid';
	const STATUS_INACTIVE = 'inactive';
	const STATUS_EXPIRED = 'expired';
	
	/**
	 * License constructor
	 *
	 * @param array $options the license options
	 */
	public function __construct($options){
		
		foreach($this->save_keys as $key){
			if(isset($options[$key])){
				$this->$key = $options[$key];
			}
		}

		$this->set_status();
		
	}

	/**
	 * Set the default status. The status field is a new field, so we'll
	 * set a default status if it is not set.
	 *
	 * @return void
	 */
	protected function set_status(){
		if($this->key && $this->status == self::STATUS_VALID && $this->get_days_to_expiry() <= 0){
			//license was valid, now it has expired
			$this->status = self::STATUS_EXPIRED;
		}elseif($this->key && !$this->status){
			//set default status to valid if no status is set (status is a new field)
			$this->status = self::STATUS_VALID;
		}elseif(!$this->key && !$this->status){
			//set a default invalid status when no key and no status are set
			$this->status = self::STATUS_INACTIVE;
		}
	}

	/**
	 * Checks whether the license is valid
	 *
	 * @return boolean
	 */
	public function is_valid(){
		return $this->status == self::STATUS_VALID;
	}

	/**
	 * Checks whether the license is expired
	 *
	 * @return boolean
	 */
	public function is_expired(){
		return $this->status == self::STATUS_EXPIRED;
	}

	/**
	 * Checks whether the license is going to expire within the next 30 days
	 *
	 * @return boolean
	 */
	public function is_about_to_expire(){
		if($this->is_valid()){
			$days_to_expiry = $this->get_days_to_expiry();
			if($days_to_expiry > 0 && $days_to_expiry <= 30){
				return true;
			}
		}
		return false;
	}


	/**
	 * Converts the license object to array that can be used for storing or
	 * in JavaScript.
	 *
	 * @param boolean $save_format sets whether to be prepared for storing in database or not
	 * @return array the license options as an array
	 */
	public function to_array($save_format = false){
		$arr = array();
		foreach($this->save_keys as $key){
			$arr[$key] = $this->$key;
		}

		if(!$save_format){
			$arr['status_text'] = $this->get_status_text();
		}

		return $arr;
	}

	/**
	 * Sets the license as active
	 *
	 * @param string $key the license key
	 * @param string $expires expiry date
	 * @return void
	 */
	public function activate($key, $expires){
		$this->activated = true;
		$this->key = $key;
		$this->expires = $expires;
		$this->status = self::STATUS_VALID;
	}

	/**
	 * Sets the license as inactive
	 *
	 * @return void
	 */
	public function deactivate(){
		$this->activated = false;
		$this->key = '';
		$this->expires = null;
		$this->status = self::STATUS_INACTIVE;
	}

	/**
	 * Returns a human friendly status message
	 *
	 * @return string
	 */
	protected function get_status_text(){
		if($this->is_valid()){
			$days_to_expiry = $this->get_days_to_expiry();
			return $days_to_expiry <= 1 ? __('License expires today', 'usin') :
				sprintf( __('License active - expires in %d days', 'usin'), floor($days_to_expiry));
		}else{
			return sprintf(__('License %s', 'usin'), $this->status);
		}
	}

	/**
	 * Finds the number of days until the license expiry
	 *
	 * @return int
	 */
	protected function get_days_to_expiry(){
		$diff = intval(mysql2date('U', $this->expires)) - time();
		return $diff/(60*60*24);
	}


}