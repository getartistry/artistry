<?php

/**
 * Includes the assets loading and script printing functionality for the Modules
 * page.
 */
class USIN_Module_Assets{

	protected $base_file = USIN_PLUGIN_FILE;
	protected $version = USIN_VERSION;
	protected $page_slug;
	protected $module_options;
	protected $page;

	/**
	 * @param string $page_slug      the slug of the Modules page
	 * @param USIN_Module_Options $module_options the module options object
	 * @param USIN_Module_Page $page           the module page object
	 */
	public function __construct($page_slug, $module_options, $page){
		$this->page_slug = $page_slug;
		$this->module_options = $module_options;
		$this->page = $page;
	}

	/**
	 * Registers all of the required action hooks.
	 */
	public function init(){
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_assets') );
		add_action( 'admin_print_scripts', array($this, 'print_inline') );
	}

	/**
	 * Checks whether the current page is the Modules page.
	 * @return boolean true if it is the Modules page and false otherwise
	 */
	protected function is_modules_page(){
		global $current_screen;
		return strpos( $current_screen->base, $this->page_slug ) !== false;
	}

	/**
	 * Loads the required assets on the Modules page/
	 */
	public function enqueue_assets(){
		if($this->is_modules_page()){
			//enqueue JavaScript files
			
			wp_enqueue_script('usin_angular', 
				plugins_url('js/lib/angular/angular.min.js', $this->base_file), 
				array(), 
				$this->version);

			wp_enqueue_script('usin_helpers', 
				plugins_url('js/helpers.js', $this->base_file), 
				array(), 
				$this->version);

			wp_enqueue_script('usin_modules', 
				plugins_url('js/modules.min.js', $this->base_file), 
				array('usin_angular', 'usin_helpers'), 
				$this->version);
				
			wp_enqueue_script('usin_templates', 
				plugins_url('views/modules/templates.js', $this->base_file), 
				array('usin_modules'), 
				$this->version);

			//enqueue CSS files
			wp_enqueue_style( 'usin_main_css', plugins_url('css/style.css', $this->base_file ), array(), $this->version );
		}

	}

	/**
	 * Prints the inline code on the Modules page.
	 */
	public function print_inline(){
		if($this->is_modules_page()){
			$this->init_js();
		}
	}

	/**
	 * Prints the initializing JavaScript code on the Modules page.
	 */
	protected function init_js(){
		$options = array(
			'viewsURL' => 'views/modules',
			'ajaxURL' => admin_url( 'admin-ajax.php' ),
			'modules' => $this->module_options->get_module_options(),
			'nonce' => $this->page->ajax_nonce
		);

		$strings = array(
			'activeModules' => __('Active Modules', 'usin'),
			'inactiveModules' => __('Inactive Modules', 'usin'),
			'settings' => __('Settings', 'usin'),
			'activateModule' => __('Activate Module', 'usin'),
			'deactivateModule' => __('Deactivate Module', 'usin'),
			'freeTrial' => __('Try for free', 'usin'),
			'buy' => __('Buy now', 'usin'),
			'enterLicense' => __('Enter a license key', 'usin'),
			'licenseKey' => __('License key', 'usin'),
			'addLicense' => __('Add license', 'usin'),
			'removeLicense' => __('Remove', 'usin'),
			'refresh' => __('Refresh', 'usin'),
			'licenseActivated' => __('License activated', 'usin'),
			'licenseDeactivated' => __('License deactivated', 'usin'),
			'error' => __('Error', 'usin'),
			'errorRequest' => __('HTTP request error', 'usin'),
			'noActiveModules' => __('No active modules', 'usin'),
			'noInactiveModules' => __('No inactive modules', 'usin'),
			'noModuleLicense' => __('This module requires a license key to be set in the "%s" section', 'usin'),
			'beta' => __('Beta', 'usin')
		);

		$options['strings'] = $strings;
		$options = apply_filters('usin_user_module_options', $options);

		$output = '<script type="text/javascript">var USIN = '.json_encode($options).';</script>';

		echo $output;

	}

}