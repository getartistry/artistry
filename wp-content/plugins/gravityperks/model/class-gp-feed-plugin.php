<?php

GFForms::include_feed_addon_framework();

abstract class GP_Feed_Plugin extends GFFeedAddOn {

	public static $perk_class;

	public $perk;

	/**
	 * Get an instance of the class. Should be overridden using the following sample code.
	 *
	 * if( self::$instance == null ) {
	 *     self::$instance = isset ( self::$perk ) ? new self ( new self::$perk ) : new self();
	 * }
	 *
	 * return self::$instance;
	 */
	public static function get_instance() {
		_doing_it_wrong( __METHOD__, 'This function must be extended. Clay said so.', null );
	}

	public static function includes() { }

	public function __construct( $perk = null ) {

		parent::__construct();

		if( ! $this->perk ) {
			$this->perk = $perk ? $perk : new GP_Perk( $this->_path, $this );
		}

	}

	public function init() {

		parent::init();

		$this->perk->init();

	}

	public function log( $message, $is_error = false ) {
		if( $is_error ) {
			$this->log_error( $message );
		} else {
			$this->log_debug( $message );
		}
	}

}