<?php

class USIN_List_Page{
	
	protected $capability;
	protected $options;
	protected $nonce_key = 'usin_user_list';
	protected $assets;
	protected $ajax;
	protected $export;

	public $slug;
	public $title;
	public $ajax_nonce;
	public $menu_position = '71.9839'; //Users

	public function __construct($title, $slug, $options){
		$this->title = $title;
		$this->slug = $slug;
		$this->options = $options;
		$this->capability = USIN_Capabilities::LIST_USERS;
	}

	public function init(){
		add_action ( 'admin_menu', array($this, 'add_menu_page') );
		add_action ( 'admin_init', array($this, 'create_nonce') );

		$this->assets = new USIN_List_Assets($this->options, $this);
		$this->assets->init();

		$this->ajax = new USIN_List_Ajax($this->options, $this->capability, $this->nonce_key);
		$this->ajax->add_actions();

		$this->export = new USIN_List_Export($this->options, $this->nonce_key);
	}

	public function add_menu_page(){
		$icon_url = plugins_url('images/usin-wp-menu-logo.png', USIN_PLUGIN_FILE );

		add_menu_page( $this->title, $this->title, $this->capability, 
			$this->slug, array($this, 'print_page_markup'), $icon_url, $this->menu_position );
	}

	public function create_nonce(){
		$this->ajax_nonce = wp_create_nonce($this->nonce_key);
	}

	public function print_page_markup(){
		?>
		<div ng-app="usinApp" class="usin">
			<div class="usin-header-wrap">
				<div class="usin-header">
					<div class="usin-logo-wrap"></div>
					<h2 class="usin-main-title"><?php echo $this->title; ?></h2>
					<div class="clear"></div>
				</div>
				<div ng-view></div>
			</div>
		</div>
		<?php
	}



}