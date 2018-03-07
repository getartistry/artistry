<?php

class USIN_Module{

	protected $prefix = 'usin_module_';
	protected $config = array();
	protected $options = array();

	public $id;
	public $license = null;
	
	
	protected function __construct($id, $config){
		$this->id = $id;
		$this->init($config);
	}

	/**
	 * Find a module by ID.
	 *
	 * @param string $id the ID of the module
	 * @return USIN_Module object 
	 */
	public static function get($id){
		$config = USIN_Module_Default_Options::get_by_id($id);

		if(!empty($config)){
			return new USIN_Module($id, $config);
		}
	}

	
	/**
	 * Initializes the module
	 *
	 * @param array $config config opions
	 */
	protected function init($config){
		$this->config = $config;
		$this->options = $this->get_saved_options();

		//setup the license
		if($this->requires_own_license()){
			$license_options = isset($this->options['license']) ? $this->options['license'] : array();
			$this->license = new USIN_License($license_options);
		}

	}

	public function reload(){
		$this->init($this->config);
	}

	/**
	 * Converts the module object to array that can be used for storing or
	 * passed to JavaScript.
	 *
	 * @return array array presentation of the module
	 */
	public function to_array(){
		return array_merge(
			$this->config,
			array(
				'options' => $this->get_options_array(),
				'active' => $this->is_active(),
				'has_options' => $this->has_options()
			));
	}

	/**
	 * Retrieves the license key of a module. If the module uses a license from another module,
	 * this other module's license is returned.
	 *
	 * @return string the license key or null if it is not set
	 */
	public function get_license_key(){
		if(isset($this->config['uses_module_license'])){
			//this module uses license from another module
			$dep_module = USIN_Module::get($this->config['uses_module_license']);
			return $dep_module->get_license_key();
		}else{
			return $this->license->key;
		}
	}

	/**
	 * Checks if the module is active.
	 *
	 * @return boolean
	 */
	public function is_active(){
		if(isset($this->options['active'])){
			return $this->options['active'];
		}elseif(isset($this->config['active'])){
			return $this->config['active'];
		}
		return false;
	}

	/**
	 * Activates a module.
	 */
	public function activate(){
		$this->options['active'] = true;
		return $this->save_options();
	}

	/**
	 * Deactivates a module.
	 *
	 * @return boolean
	 */
	public function deactivate(){
		if($this->allows_deactivate()){
			$this->options['active'] = false;
			return $this->save_options();
		}
		return false;
	}

	/**
	 * Saves the module options.
	 *
	 * @return void
	 */
	public function save_options(){
		$options = $this->get_options_array(true);
		return update_option( $this->prefix.$this->id, $options );
	}


	/**
	 * Retrieves the options of the module in an array format, containing
	 * the license if the module requires its own license.
	 *
	 * @param boolean $save_format sets whether to return the array in a format for storing
	 * in the database
	 * @return array the module options
	 */
	public function get_options_array($save_format = false){
		if($this->requires_own_license()){
			$this->options['license'] = $this->license->to_array($save_format);
		}
		return $this->options;
	}

	
	/**
	 * Checks if the module has any input options.
	 *
	 * @return boolean
	 */
	protected function has_options(){
		return $this->requires_own_license() || $this->has_option_fields();
	}

	/**
	 * Checks if the module requires license in order to be activated.
	 *
	 * @return boolean
	 */
	public function requires_license(){
		return ( isset($this->config['requires_license']) && $this->config['requires_license'] === true );
	}

	/**
	 * Checks if the module requires its own license and not a license from another module
	 * in order to be activated.
	 *
	 * @return boolean
	 */
	protected function requires_own_license(){
		return $this->requires_license() && !isset($this->config['uses_module_license']);
	}

	/**
	 * Checls whether the module has any input options that are not license options.
	 *
	 * @return boolean
	 */
	public function has_option_fields(){
		return isset($this->config['option_fields']);
	}

	/**
	 * Retrieves the module's saved options.
	 *
	 * @return array
	 */
	protected function get_saved_options(){
		return get_option($this->prefix.$this->id, array());
	}

	/**
	 * Checks whether the module allows to be deactivated.
	 *
	 * @return boolean
	 */
	public function allows_deactivate(){
		if(isset($this->config['allow_deactivate'])){
			return $this->config['allow_deactivate'];
		}
		return true;
	}



}

?>