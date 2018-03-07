<?php

/**
 * Includes the AJAX functionality for the Custom User Meta Fields.
 */
class USIN_Custom_Fields_Ajax extends USIN_Ajax{

	protected $user_capability;
	protected $nonce_key;

	/**
	 * @param string $user_capability the required user capability to apply modifications
	 * @param string $nonce_key       the nonce key for the security checks
	 */
	public function __construct($user_capability, $nonce_key){
		$this->user_capability = $user_capability;
		$this->nonce_key = $nonce_key;
	}

	/**
	 * Registers the required actions hooks.
	 */
	public function add_actions(){
		add_action('wp_ajax_usin_add_field', array($this, 'add_field'));
		add_action('wp_ajax_usin_delete_field', array($this, 'delete_field'));
		add_action('wp_ajax_usin_update_field', array($this, 'update_field'));
	}
	
	/**
	 * Receives a request to register a custom user meta field and calls
	 * the required functions to do this if the request parameters are valid.
	 */
	public function add_field(){
		$this->verify_request($this->user_capability);

		$res = USIN_Custom_Fields_Options::add_field($_POST['field_name'], $_POST['field_key'], $_POST['field_type']);
		if(!is_wp_error($res)){
			$res = USIN_Custom_Fields_Options::get_saved_fields();
		}
		
		$this->respond($res);
	}
	
	/**
	 * Receives a request to delete a custom user meta field and calls
	 * the required functions to do this if the request parameters are valid.
	 */
	public function delete_field(){
		$this->verify_request($this->user_capability);
		$this->validate_required_post_params(array('field_key'));
		
		$success = USIN_Custom_Fields_Options::delete_field($_POST['field_key']);
		if($success){
			$this->respond_success(USIN_Custom_Fields_Options::get_saved_fields());
		}
		
		$this->respond_error();
	}
	
	/**
	 * Receives a request to update a custom user meta field and calls
	 * the required functions to do this if the request parameters are valid.
	 */
	public function update_field(){
		$this->verify_request($this->user_capability);
		
		$res = USIN_Custom_Fields_Options::update_field($_POST['field_name'], $_POST['field_key'], $_POST['field_type']);
		if(!is_wp_error($res)){
			$res = USIN_Custom_Fields_Options::get_saved_fields();
		}
		
		$this->respond($res);
	}


}