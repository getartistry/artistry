<?php

/**
 * Class for Linkedin messages
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/providers
 */

class Wsi_Linkedin extends Wsi_Providers {

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
		$this->name = 'linkedin';
		$this->connect();
	}

	/**
	 * Free version don't let you choose friends
	 * Display collector for provider
	 */
	public function collector() {
		global $wsi_plugin;
		$opts = $wsi_plugin->get_opts();

		$template = wsi_locate_template('popup/collector/share.php');
		wsi_get_template('popup/collector.php', array(
				'template' => $template,
				'provider' => $this->name,
			)
		);
		$this->provider->logout();
	}
}