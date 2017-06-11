<?php

/**
 * Class for Yahoo messages
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/providers
 */

class Wsi_Yahoo extends Wsi_Providers {

	/*
	 * Provider name
	 */
	public $name;
	protected $hybridauth;
	protected $provider;

	/**
	 * Class constructor
	 */
	function __construct( ) {
		$this->name = 'yahoo';
		$this->connect();
	}

}