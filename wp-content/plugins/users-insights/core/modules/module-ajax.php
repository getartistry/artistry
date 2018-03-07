<?php

/**
 * Includes the AJAX functionality for the Modules page
 */
class USIN_Module_Ajax extends USIN_Ajax{

	protected $user_capability;
	protected $module_options;
	protected $nonce_key;

	/**
	 * @param USIN_Module_Options $module_options  the module options object
	 * @param string $user_capability the required user capability to access the modules page
	 * @param string $nonce_key       the nonce key for the security checks
	 */
	public function __construct($module_options, $user_capability, $nonce_key){
		$this->module_options = $module_options;
		$this->user_capability = $user_capability;
		$this->nonce_key = $nonce_key;
	}

	/**
	 * Registers the required actions hooks.
	 */
	public function add_actions(){
		add_action('wp_ajax_usin_add_license', array($this, 'add_license'));
		add_action('wp_ajax_usin_deactivate_license', array($this, 'deactivate_license'));
		add_action('wp_ajax_usin_activate_module', array($this, 'activate_module'));
		add_action('wp_ajax_usin_deactivate_module', array($this, 'deactivate_module'));
		add_action('wp_ajax_usin_refresh_license_status', array($this, 'refresh_license_status'));
	}
	
	/**
	 * Handler for the Add & Activate License functionality.
	 */
	public function add_license(){
		$this->verify_request($this->user_capability);
		$this->validate_required_post_params(array('license_key', 'module_id'));

		$license_key = $_POST['license_key'];
		$module_id = $_POST['module_id'];

		$res = $this->module_options->activate_license($license_key, $module_id);

		$this->respond($res);
	}

	/**
	 * Handler for the Deactivate & Remove License functionality.
	 */
	public function deactivate_license(){
		$this->verify_request($this->user_capability);
		$this->validate_required_post_params(array('license_key', 'module_id'));

		$license_key = $_POST['license_key'];
		$module_id = $_POST['module_id'];

		$res = $this->module_options->deactivate_license($license_key, $module_id);

		$this->respond($res);
	}
	
	public function refresh_license_status(){
		$this->verify_request($this->user_capability);
		$this->validate_required_post_params(array('module_id'));
		
		$module_id = $_POST['module_id'];

		$res = $this->module_options->refresh_license_status($module_id);
		$this->respond($res);
	}

	/**
	 * Activates a module.
	 */
	public function activate_module(){
		$this->verify_request($this->user_capability);
		$this->validate_required_post_params(array('module_id'));

		$this->module_options->activate_module($_POST['module_id']);
		
		$this->respond_success();
	}


	/**
	 * Deactivates a module.
	 */
	public function deactivate_module(){
		$this->verify_request($this->user_capability);
		$this->validate_required_post_params(array('module_id'));

		$this->module_options->deactivate_module($_POST['module_id']);
		
		$this->respond_success();
	}


}