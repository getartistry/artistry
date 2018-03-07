<?php

/**
 * Includes the main Options functionality for the Modules page.
 */
class USIN_Module_Options{
	
	protected static $instance;

	protected function __construct(){}

	/**
	 * This is a singleton class, returns the instance of the class.
	 * @return USIN_Module_Options the instance
	 */
	public static function get_instance(){
		if(! self::$instance ){
			self::$instance = new USIN_Module_Options();
			self::$instance->init();
		}
		return self::$instance;
	}

	protected function init(){
		add_action('current_screen', array($this, 'check_license_status'));
	}


	/**
	 * Loads the Module Options and sets them to the modules property.
	 */
	public function get_module_options(){
		$module_options = array();
		$module_ids = wp_list_pluck(USIN_Module_Default_Options::get(), 'id');
		
		foreach ($module_ids as $module_id) {
			$module = USIN_Module::get($module_id);

			$module_options[] = $module->to_array();
		}
		return $module_options;
	}

	/**
	 * Activates a module.
	 * @param  string $module_id the ID of the module to activate
	 */
	public function activate_module($module_id){
		$module = USIN_Module::get($module_id);
		if($module){
			$module->activate();
			do_action('usin_module_activated', $module_id);
		}
	}

	/**
	 * Deactivates a module.
	 * @param  string $module_id the ID of the module to deactivate
	 */
	public function deactivate_module($module_id){
		$module = USIN_Module::get($module_id);
		if($module){
			$module->deactivate();
			do_action('usin_module_deactivated', $module_id);
		}
	}

	/**
	 * Retrieves the license key for a module.
	 * @param  string $module_id the module ID
	 * @return string            the lincense key
	 */
	public function get_license($module_id){
		$module = USIN_Module::get($module_id);;
		return $module->get_license_key();
	}


	/**
	 * Activates a license, sets the activation details in the module options.
	 * @param string $module_id   the module ID
	 * @param string $license_key the license key
	 * @return the module options array on success and WP_Error on failure
	 */
	public function activate_license($license_key, $module_id){
		$module = USIN_Module::get($module_id);

		$res = USIN_Remote_License::activate($license_key, $module_id);
		
		if(is_wp_error($res)){
			return $res;
		}

		if($res->success === true && $res->license === USIN_License::STATUS_VALID){
			$module->license->activate($license_key, $res->expires);
			$module->save_options();
			
			$this->refresh_geolocation_status($module->license);

			return $module->get_options_array();
		}

		return $this->license_error($res);
			
	}

	/**
	 * Sends a request to check the license status and updates it in the database
	 *
	 * @param string $module_id the module ID
	 * @return the module options array on success and WP_Error on failure
	 */
	public function refresh_license_status($module_id){
		$module = USIN_Module::get($module_id);

		$license_key = $module->get_license_key();

		$res = USIN_Remote_License::load_status($license_key, $module_id);
		if(is_wp_error($res)){
			return $res;
		}

		if( $res->success === true ){
			$module->license->status = $res->license;
			$module->license->expires = $res->expires;

			$module->license->renewal_url = isset($res->renewal_url) ? $res->renewal_url : null;
			$module->license->renewal_message = isset($res->renewal_message) ? $res->renewal_message : null;
			
			$module->save_options();
			$this->refresh_geolocation_status($module->license);
			
			return $module->get_options_array();
		}

		return $this->license_error($res);
		
	}

	protected function refresh_geolocation_status($license){
		if(!$this->is_module_active('geolocation')){
			return;
		}

		if(USIN_Geolocation_Status::is_paused() && $license->is_valid()){
			USIN_Geolocation_Status::resume();
		}elseif(!USIN_Geolocation_Status::is_paused() && !$license->is_valid()){
			USIN_Geolocation_Status::pause();
		}

	}

	/**
	 * Deactivates a license, removes the activation details in the module options.
	 * @param string $module_id   the module ID
	 * @param string $license_key the license key
	 * @return array the module options
	 * @return the module options array on success and WP_Error on failure
	 */
	 public function deactivate_license($license_key, $module_id){
		$module = USIN_Module::get($module_id);

		$res = USIN_Remote_License::deactivate($license_key, $module_id);

		if(is_wp_error($res)){
			return $res;
		}

		if( ($res->success === true && $res->license === 'deactivated') ||
			(!$res->success && $res->license == 'failed' && empty($res->item_name)) ){  //the license doesn't exist anymore, just remove it from the options
			
			$module->license->deactivate();
			$module->save_options();
			return $module->get_options_array();

		}

		return $this->license_error($res);
	}

	public function check_license_status(){
		if ((defined('DOING_AJAX') && DOING_AJAX) || !is_admin()){
			return;
		}

		$transient_key = 'usin_license_checked';
		$module = USIN_Module::get('globallicense');
		$license_key = $module->get_license_key();
		
		if(!empty($license_key) && usin_is_a_users_insights_page()){

			if(get_transient($transient_key) === false){
				//refresh the license status every 24 hours
				$this->refresh_license_status($module->id);
				$module->reload();
				set_transient( $transient_key, true, DAY_IN_SECONDS );
			}
			
			if(current_user_can(USIN_Capabilities::MANAGE_OPTIONS)){
				//show a license expired notification
				if($module->license->is_expired()){
					$this->show_license_expiry_notice($module->license, true);
				}elseif($module->license->is_about_to_expire()){
					$this->show_license_expiry_notice($module->license, false);
				}
			}
		}
	}

	protected function show_license_expiry_notice($license, $expired){

		if($expired){
			$message = 'Your Users Insights license has expired. ';
			$notice_type = 'alert';
			$notice_id = 'license_expired';	
			$dismiss_period = MONTH_IN_SECONDS;
		}else{
			//the license is about to expire
			$message = 'Your Users Insights license is about to expire. ';
			$notice_type = 'info';
			$notice_id = 'license_will_expire';
			$dismiss_period = 2 * MONTH_IN_SECONDS; //once dismissed don't show again until it actually expires
		}

		$renew_license = 'Renew your license';
		if($license->renewal_url){
			$renew_license = sprintf('<a href="%s">%s</a>', esc_url($license->renewal_url), $renew_license);
		}
		$message .= sprintf('%s to keep receiving updates and access to the Geolocation API.', $renew_license);

		if($license->renewal_message){
			$message .= '<br/>'.wp_kses_data($license->renewal_message);
		}
		
		USIN_Notice::create($notice_type, $message, $notice_id, $dismiss_period);
		
	}


	protected function license_error($res){
		$error = isset($res->error_msg) ? $res->error_msg : __('Invalid license', 'usin');
		return new WP_Error('invalid_license', $error);
	}

	/**
	 * Checks whether a module is activated.
	 * @param  string  $module_id the module ID
	 * @return boolean            true if the module is activated and false otherwise
	 */
	public function is_module_active($module_id){
		$module = USIN_Module::get($module_id);;
		return $module->is_active();
	}

}