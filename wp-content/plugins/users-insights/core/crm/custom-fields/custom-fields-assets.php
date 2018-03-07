<?php

/**
 * Includes the assets loading and script printing functionality for the Custom Fields
 * page.
 */
class USIN_Custom_Fields_Assets{

	protected $base_file = USIN_PLUGIN_FILE;
	protected $version = USIN_VERSION;
	protected $page_slug;
	protected $page;

	/**
	 * @param string $page_slug      the slug of the Custom Fields page
	 * @param USIN_Module_Page $page           the module page object
	 */
	public function __construct($page_slug, $page){
		$this->page_slug = $page_slug;
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
	 * Checks whether the current page is the Custom Fields page.
	 * @return boolean true if it is the Custom Fields page and false otherwise
	 */
	protected function is_custom_fields_page(){
		global $current_screen;
		return strpos( $current_screen->base, $this->page_slug ) !== false;
	}

	/**
	 * Loads the required assets on the Custom Fields page/
	 */
	public function enqueue_assets(){
		if($this->is_custom_fields_page()){
			//enqueue JavaScript files
			
			wp_enqueue_script('usin_angular', 
				plugins_url('js/lib/angular/angular.min.js', $this->base_file), 
				array(), 
				$this->version);

			wp_enqueue_script('usin_helpers', 
				plugins_url('js/helpers.js', $this->base_file), 
				array('usin_angular'), 
				$this->version);

			wp_enqueue_script('usin_custom_fields', 
				plugins_url('js/custom-fields.min.js', $this->base_file), 
				array('usin_angular', 'usin_helpers'), 
				$this->version);

			//enqueue CSS files
			wp_enqueue_style( 'usin_main_css', plugins_url('css/style.css', $this->base_file ), array(), $this->version );
		}

	}

	/**
	 * Prints the inline code on the Custom Fields page.
	 */
	public function print_inline(){
		if($this->is_custom_fields_page()){
			$this->init_js();
		}
	}

	/**
	 * Prints the initializing JavaScript code on the Custom Fields page.
	 */
	protected function init_js(){
		$options = array(
			'viewsURL' => plugins_url('views/custom-fields', $this->base_file),
			'ajaxURL' => admin_url( 'admin-ajax.php' ),
			'fields' => USIN_Custom_Fields_Options::get_saved_fields(),
			'fieldTypes' => USIN_Custom_Fields_Options::$field_types,
			'nonce' => $this->page->ajax_nonce,
			'customTemplates' => array()
		);

		$strings = array(
			'addField' => __('Add Field', 'usin'),
			'fieldName' => __('Field Name', 'usin'),
			'fieldKey' => __('Field Key', 'usin'),
			'fieldType' => __('Field Type', 'usin'),
			'fields' => __('Fields', 'usin'),
			'fieldUpdateError' => __( 'Error updating fields', 'usin' ),
			'areYouSure' => __('Are you sure?', 'usin'),
			'actions' => __('Actions', 'usin'),
			'edit' => __('Edit', 'usin'),
			'update' => __('Update', 'usin'),
			'delete' => __('Delete', 'usin'),
			'keyMessage' => __('Tip: If you would like to use existing custom user meta fields from the
			WordPress users meta table, please make sure to insert the existing meta key into the "Field Key"
			field. ', 'usin')
			
		);

		$options['strings'] = $strings;

		$options = apply_filters('usin_cf_options', $options);

		$output = '<script type="text/javascript">var USIN = '.json_encode($options).';</script>';

		echo $output;

	}

}