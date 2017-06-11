<?php

/**
 * Class for Mail messages
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/providers
 */

class Wsi_Mail extends Wsi_Providers{

	/*
	 * Provider name
	 */
	public $name;

	/**
	 * Class constructor
	 */
	function __construct( ) {
		$this->name = 'mail';
	}

	/**
	 * Display collector for provider
	 */
	public function collector() {
		$template = wsi_locate_template('popup/collector/mail.php');
		wsi_get_template('popup/collector.php', array( 'template' => $template));
	}



}