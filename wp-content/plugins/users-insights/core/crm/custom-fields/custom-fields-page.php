<?php

/**
 * Includes the main initialization functionality for the Custom Fields page.
 */
class USIN_Custom_Fields_Page{
	
	protected $capability;
	protected $nonce_key = 'usin_custom_fields';
	protected $assets;
	protected $ajax;
	protected $parent_slug;

	public $slug = 'usin_custom_fields';
	public $title;
	public $ajax_nonce;

	/**
	 * @param string $parent_slug    the slug of the parent menu item
	 * @param string $capability     the user capability required to access this page
	 */
	public function __construct($parent_slug){
		$this->title = __('Custom Fields', 'usin');
		$this->parent_slug = $parent_slug;
		$this->capability = USIN_Capabilities::MANAGE_CUSTOM_FIELDS;
	}

	/**
	 * Main initialization functionality, registers the required action hooks.
	 */
	public function init(){
		add_action ( 'admin_menu', array($this, 'add_menu_page'), 15 );
		add_action ( 'admin_init', array($this, 'create_nonce') );

		$this->assets = new USIN_Custom_Fields_Assets($this->slug, $this);
		$this->assets->init();
		// 
		$this->ajax = new USIN_Custom_Fields_Ajax($this->capability, $this->nonce_key);
		$this->ajax->add_actions();
	}

	/**
	 * Adds the page as a menu item.
	 */
	public function add_menu_page(){

		add_submenu_page( $this->parent_slug, $this->title, $this->title, 
			$this->capability, $this->slug, array($this, 'print_page_markup') );
	}

	/**
	 * Creates a nonce for the AJAX requests on this page.
	 */
	public function create_nonce(){
		$this->ajax_nonce = wp_create_nonce($this->nonce_key);
	}

	/**
	 * Prints the main page markup.
	 */
	public function print_page_markup(){
		?>
		<div class="usin-header-wrap">
			<div class="usin-header">
				<div class="usin-logo-wrap"></div>
				<div class="clear"></div>
			</div>
		</div>
		<div ng-app="usinCustomFieldsApp" class="usin">
			<div class="usin-main"></div>
		</div>
		<?php
	}



}