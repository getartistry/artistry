<?php


class USIN_Notice{
	
	public $kind = 'info';
	public $content = null;
	public $id = 0;
	public $dismissed_period = 0;
	const DISMISSED_KEY = 'usin-notice-dismissed-';
	const NONCE_KEY = 'usin-notice-nonce';
	
	
	public function __construct($kind, $content, $id, $dismissed_period){
		$this->kind = $kind;
		$this->content = $content;
		$this->id = $id;
		$this->dismissed_period = $dismissed_period;
		
		$this->init();
	}
	
	
	protected function init(){
		if($this->should_display()){
			add_action( 'admin_notices', array($this, 'print_notice') );
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );
		}
	}
	
	protected function should_display(){
		return get_transient(self::dismissed_key($this->id)) === false;
	}
	
	public function print_notice(){
		$nonce = wp_create_nonce( self::NONCE_KEY );
		
		echo sprintf('<div class="notice is-dismissible usin-notice %s" data-notice_id="%s"
			data-dismiss_period="%d" data-nonce="%s"><p>%s</p></div>',
			$this->get_notice_class(), $this->id, $this->dismissed_period, $nonce, $this->content);
	}
	
	protected function get_notice_class(){
		$types = array(
			'success' => 'notice-success',
			'info' => 'notice-warning',
			'alert' => 'notice-error' 
		);
		return isset($types[$this->kind]) ? $types[$this->kind] : 'notice-info';
	}
	
	/**
	 * Enqueue the script to mark the notices as dismissed.
	 */
	public function enqueue_scripts(){
		wp_enqueue_script('usin_notice', 
			plugins_url('js/notice.js', USIN_PLUGIN_FILE), 
			array('jquery'), 
			USIN_VERSION);
	}
	
	
	// STATIC CLASS METHDOS

	/**
	 * Create helper method that creates an instance. Use this static method for
	 * better code readability.
	 */
	public static function create($kind, $content, $id, $dismissed_period = MONTH_IN_SECONDS){
		return new USIN_Notice($kind, $content, $id, $dismissed_period);
	}
	
	public static function mark_as_dismissed(){
		$required_params = array('nonce', 'notice_id', 'dismiss_period');
		foreach($required_params as $p){
			if(!isset($_GET[$p])){
				exit("Missing required parameter $p");
			}
		}

		if (!wp_verify_nonce( $_GET['nonce'], self::NONCE_KEY )) {
			exit("Failed to verify nonce");
		}

		set_transient(self::dismissed_key($_GET['notice_id']), true, intval($_GET['dismiss_period']));

		exit;
	}
	
	protected static function dismissed_key($id){
		return self::DISMISSED_KEY.$id;
	}
	
}

add_action( 'wp_ajax_usin_mark_notice_as_dismissed', array('USIN_Notice', 'mark_as_dismissed') );
